<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\Sp2aNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class PesanController extends Controller
{
    /**
     * Menampilkan Inbox Pesan
     */
    public function index()
    {
        $emailUser = Auth::user()->email;

        // MODIFIKASI: Jika dokumen sudah 'Approved By System', tampilkan ke SEMUA user.
        // Jika belum, hanya tampilkan ke orang-orang yang terlibat saja.
        $pesan = Sp2a::where('status', '!=', 'Draft')
            ->where(function($q) use ($emailUser) {
                $q->where('status', 'Approved By System') // Semua user bisa lihat jika sudah disetujui
                  ->orWhere('kepada_email', $emailUser)
                  ->orWhere('email_auditor', $emailUser)
                  ->orWhere('email_k3', $emailUser)
                  ->orWhere('email_staff', $emailUser)
                  ->orWhere('email_atasan', $emailUser);
            })
            ->latest()
            ->get();

        return view('pesan.index', compact('pesan'));
    }

    /**
     * Halaman Preview (Menampilkan bingkai/iframe dan tombol Approve)
     */
   public function previewPage($id)
{
    $sp2a = Sp2a::findOrFail($id);
    $userEmail = Auth::user()->email;

    // Izinkan akses jika status sudah Approved OR user terlibat OR admin
    $isInvolved = collect([$sp2a->kepada_email, $sp2a->email_k3, $sp2a->email_auditor, $sp2a->email_staff, $sp2a->email_atasan])
                    ->contains($userEmail);

    if ($sp2a->status !== 'Approved By System' && !$isInvolved && Auth::user()->role !== 'admin') {
        abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
    }

    return view('pesan.preview', compact('sp2a'));
}

    /**
     * Sumber data PDF untuk di dalam iframe
     */
    public function show($id, Request $request)
{
    $sp2a = Sp2a::findOrFail($id);
    
    // Membersihkan nama file agar tidak error di header
    $safeFileName = str_replace(['/', '\\'], '-', $sp2a->nomor_sp2a) . '.pdf';
    
    $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));

    // JIKA TERDAPAT PARAMETER ?download=true
    if ($request->has('download')) {
        // Menggunakan download() untuk memaksa browser mengunduh file
        return $pdf->download($safeFileName);
    }

    // JIKA TIDAK, TAMPILKAN DI DALAM IFRAME (Preview)
    return $pdf->stream($safeFileName);
}

    /**
     * PROSES APPROVAL OLEH K3
     */
    public function approve($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        if (Auth::user()->role != 'k3') {
            return back()->with('error', 'Hanya Role K3 yang bisa melakukan Approval.');
        }

        if ($sp2a->status == 'Approved By System') {
            return back()->with('error', 'Dokumen sudah disetujui sebelumnya.');
        }

        // 1. Update Status
        $sp2a->update([
            'status' => 'Approved By System',
            'approved_at' => now(),
            // Logika: Jika ingin semua orang bisa melihat, kita bisa menandai dokumen ini 
            // sebagai dokumen publik/broadcast di kolom tertentu jika ada, 
            // atau membiarkan logika index yang menanganinya.
        ]);

        // Pengiriman email dihentikan sementara sesuai permintaan
        /* try {
            $allUserEmails = User::pluck('email')->toArray(); 
            if (!empty($allUserEmails)) {
                Mail::bcc($allUserEmails)->send(new Sp2aNotification($sp2a, $pdfContent));
            }
        } catch (\Exception $e) { ... } 
        */

        return redirect()->route('pesan.index')->with('success', 'Dokumen DISETUJUI! Sekarang semua user dapat melihat dokumen ini di kotak masuk mereka.');
    }
}