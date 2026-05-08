<?php

namespace App\Domain\Complaint\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Complaint extends Model
{
    use LogsActivity;
    use SoftDeletes;

    public const STATUS_OPEN        = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED    = 'resolved';
    public const STATUS_REJECTED    = 'rejected';

    protected $fillable = [
        'ticket_no', 'full_name', 'email', 'phone', 'channel', 'subject',
        'body', 'attachment_path', 'status', 'handled_by', 'response',
        'responded_at', 'ip_address',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public static function booted(): void
    {
        static::creating(function (self $complaint) {
            if (! $complaint->ticket_no) {
                $complaint->ticket_no = self::generateTicketNo();
            }
        });
    }

    public static function generateTicketNo(): string
    {
        return 'DP-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'handled_by', 'responded_at'])
            ->logOnlyDirty();
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeOpen(Builder $q): Builder
    {
        return $q->whereIn('status', [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }
}
