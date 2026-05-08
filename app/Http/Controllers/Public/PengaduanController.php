<?php

namespace App\Http\Controllers\Public;

use App\Domain\Complaint\Models\Complaint;
use App\Domain\Seo\Services\SeoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function __construct(private readonly SeoService $seo) {}

    public function index(): View
    {
        return view('pages.pengaduan.index', [
            'pageTitle' => 'Pengaduan',
            'seo'       => $this->seo->for('pengaduan'),
        ]);
    }

    public function create(): View
    {
        return view('pages.pengaduan.lapor', [
            'pageTitle' => 'Lapor Pengaduan',
            'seo'       => $this->seo->for('pengaduan'),
        ]);
    }

    public function store(StoreComplaintRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('complaints', 'public');
            unset($data['attachment']);
        }
        $data['channel']    = 'web';
        $data['status']     = Complaint::STATUS_OPEN;
        $data['ip_address'] = $request->ip();

        $complaint = Complaint::create($data);

        return redirect()
            ->route('pengaduan.tracking.show', $complaint->ticket_no)
            ->with('status', "Pengaduan diterima. No. tiket: {$complaint->ticket_no}");
    }

    public function trackingForm(): View
    {
        return view('pages.pengaduan.tracking', [
            'pageTitle' => 'Tracking Pengaduan',
            'seo'       => $this->seo->for('pengaduan'),
            'complaint' => null,
        ]);
    }

    public function trackingShow(string $ticket): View
    {
        $complaint = Complaint::query()->where('ticket_no', $ticket)->first();

        return view('pages.pengaduan.tracking', [
            'pageTitle' => "Tracking #{$ticket}",
            'seo'       => $this->seo->for('pengaduan'),
            'complaint' => $complaint,
            'ticket'    => $ticket,
        ]);
    }

    public function sp4n(): View       { return $this->stub('SP4N LAPOR'); }
    public function wbs(): View        { return $this->stub('Whistleblowing System'); }
    public function konsultasi(): View { return $this->stub('Konsultasi Masyarakat'); }

    private function stub(string $title): View
    {
        return view('pages.placeholder', ['pageTitle' => $title, 'section' => 'Pengaduan']);
    }
}
