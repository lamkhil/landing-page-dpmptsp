<?php

namespace App\Http\Controllers\Public;

use App\Domain\Complaint\Models\ContactMessage;
use App\Domain\Footer\Services\FooterService;
use App\Domain\Seo\Services\SeoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class KontakController extends Controller
{
    public function __construct(
        private readonly SeoService $seo,
        private readonly FooterService $footer,
    ) {}

    public function index(): View
    {
        return view('pages.kontak.index', [
            'pageTitle' => 'Kontak Kami',
            'seo'       => $this->seo->for('kontak'),
            'settings'  => $this->footer->settings(),
        ]);
    }

    public function lokasi(): View
    {
        return view('pages.kontak.lokasi', [
            'pageTitle' => 'Lokasi Kantor',
            'seo'       => $this->seo->for('kontak'),
            'settings'  => $this->footer->settings(),
        ]);
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        ContactMessage::create($request->validated() + [
            'status'     => ContactMessage::STATUS_NEW,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Pesan terkirim. Kami akan menghubungi Anda secepatnya.');
    }
}
