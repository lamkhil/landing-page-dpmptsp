<?php

namespace Database\Seeders\Cms\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Downloads remote images to the `public` disk under `seed/<bucket>/`.
 *
 * - Idempotent: if the target file already exists, returns its path without
 *   re-downloading. Re-running seeders is safe and fast.
 * - Auto-resizes anything wider than 1920px using `convert` (ImageMagick)
 *   so a 25 MB carousel PNG doesn't pollute storage; falls back to GD when
 *   ImageMagick isn't installed, or to a raw copy when neither can decode.
 * - Failure is non-fatal: returns null. Seeders can store null in the
 *   *_path column — Filament admins can replace later.
 *
 * Output paths are relative to the `public` disk (e.g. `seed/hero/1.jpg`),
 * so Storage::url($path) gives the correct /storage/... URL.
 */
class RemoteImageDownloader
{
    public function __construct(
        private int $maxWidth = 1920,
        private int $jpegQuality = 82,
        private int $timeoutSeconds = 30,
    ) {}

    /**
     * Download $url and store as `seed/$bucket/$filename`. Returns the
     * relative path on the `public` disk, or null on failure.
     */
    public function fetch(string $url, string $bucket, string $filename): ?string
    {
        $relative = "seed/{$bucket}/{$filename}";
        $disk = Storage::disk('public');

        if ($disk->exists($relative)) {
            $this->log($relative, 'cached', $disk->size($relative));
            return $relative;
        }

        $t0 = microtime(true);
        try {
            $response = Http::withOptions(['verify' => false])
                ->timeout($this->timeoutSeconds)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 DPMPTSP-Seeder'])
                ->get($url);
        } catch (\Throwable $e) {
            return $this->report($url, "HTTP error: {$e->getMessage()}");
        }

        if (! $response->successful()) {
            return $this->report($url, "HTTP {$response->status()}");
        }

        $bytes = $response->body();
        if (strlen($bytes) === 0) {
            return $this->report($url, 'empty body');
        }

        $disk->makeDirectory("seed/{$bucket}");
        $absolute = $disk->path($relative);

        // SVGs and tiny assets: store raw. Bitmap formats: try to downsize.
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($ext === 'svg' || strlen($bytes) < 100_000) {
            file_put_contents($absolute, $bytes);
            $this->log($relative, 'downloaded', filesize($absolute), microtime(true) - $t0);
            return $relative;
        }

        if (! $this->resizeWithImagick($bytes, $absolute) &&
            ! $this->resizeWithGd($bytes, $absolute)) {
            // Neither tool worked — fall back to raw copy so admins still
            // get a working image (just an oversized one).
            file_put_contents($absolute, $bytes);
            $this->log($relative, 'downloaded (no resize)', filesize($absolute), microtime(true) - $t0);
        } else {
            $this->log(
                $relative,
                sprintf('resized %s → %s', $this->fmtBytes(strlen($bytes)), $this->fmtBytes(filesize($absolute))),
                filesize($absolute),
                microtime(true) - $t0,
            );
        }

        return $relative;
    }

    private function log(string $path, string $action, int $size, ?float $elapsed = null): void
    {
        if (! app()->runningInConsole()) return;
        $timing = $elapsed !== null ? sprintf(' (%.2fs)', $elapsed) : '';
        fwrite(STDERR, sprintf("  [image] %s %s %s%s\n", $action, $this->fmtBytes($size), $path, $timing));
    }

    private function fmtBytes(int $bytes): string
    {
        if ($bytes >= 1_048_576) return sprintf('%.1fMB', $bytes / 1_048_576);
        if ($bytes >= 1024)      return sprintf('%dKB', round($bytes / 1024));
        return $bytes.'B';
    }

    private function resizeWithImagick(string $bytes, string $target): bool
    {
        $convert = trim((string) @shell_exec('command -v convert'));
        if ($convert === '') return false;

        $tmpIn  = tempnam(sys_get_temp_dir(), 'seed_in_');
        file_put_contents($tmpIn, $bytes);

        // `>` modifier means: only shrink if larger; never enlarge.
        $cmd = sprintf(
            '%s %s -resize %dx%d\> -quality %d %s 2>/dev/null',
            escapeshellcmd($convert),
            escapeshellarg($tmpIn),
            $this->maxWidth,
            $this->maxWidth * 2,
            $this->jpegQuality,
            escapeshellarg($target),
        );

        $exit = 0;
        @system($cmd, $exit);
        @unlink($tmpIn);

        return $exit === 0 && file_exists($target) && filesize($target) > 0;
    }

    private function resizeWithGd(string $bytes, string $target): bool
    {
        if (! extension_loaded('gd')) return false;

        $src = @imagecreatefromstring($bytes);
        if ($src === false) return false;

        $srcW = imagesx($src);
        $srcH = imagesy($src);

        if ($srcW <= $this->maxWidth) {
            $dst = $src;
        } else {
            $ratio = $this->maxWidth / $srcW;
            $dst = imagecreatetruecolor($this->maxWidth, (int) ($srcH * $ratio));
            imagecopyresampled($dst, $src, 0, 0, 0, 0, imagesx($dst), imagesy($dst), $srcW, $srcH);
            imagedestroy($src);
        }

        $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        $ok = match ($ext) {
            'png'        => imagepng($dst, $target, 6),
            'jpg','jpeg' => imagejpeg($dst, $target, $this->jpegQuality),
            'gif'        => imagegif($dst, $target),
            'webp'       => imagewebp($dst, $target, $this->jpegQuality),
            default      => imagejpeg($dst, $target, $this->jpegQuality),
        };

        imagedestroy($dst);
        return $ok;
    }

    private function report(string $url, string $reason): ?string
    {
        if (app()->runningInConsole()) {
            fwrite(STDERR, "  [image] skipped {$url} ({$reason})\n");
        }
        return null;
    }
}
