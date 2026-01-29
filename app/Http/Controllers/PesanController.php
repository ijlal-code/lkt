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
    public function approve($id, Request $request)
{
    $sp2a = Sp2a::findOrFail($id);
    $user = Auth::user();

    // 1. Logika Tombol Koreksi (Jika ada input koreksi)
    if ($request->has('koreksi')) {
        $sp2a->update([
            'current_step' => 'staff', // Balikkan ke staff
            'status' => 'Ditolak/Perlu Koreksi',
            'catatan_koreksi' => $request->catatan_koreksi
        ]);
        return back()->with('success', 'Koreksi telah dikirim kembali ke Staff.');
    }

    // 2. Logika Approval Berjenjang
    if ($user->role == 'sm' && $sp2a->current_step == 'sm') {
        $sp2a->update(['current_step' => 'smqa', 'approved_sm_at' => now()]);
    } 
    elseif ($user->role == 'smqa' && $sp2a->current_step == 'smqa') {
        $sp2a->update(['current_step' => 'gm', 'approved_smqa_at' => now()]);
    } 
    elseif ($user->role == 'gm' && $sp2a->current_step == 'gm') {
        // TAHAP FINAL: Generate Nomor & Stamp
        $nomorBaru = "SP2A/" . date('Ymd') . "/" . str_pad($sp2a->id, 4, '0', STR_PAD_LEFT);
        
        $sp2a->update([
            'nomor_sp2a' => $nomorBaru,
            'status' => 'Approved By System',
            'current_step' => 'finished',
            'approved_at' => now(),
            'approved_gm_at' => now()
        ]);
        
        // Di sini otomatis terkirim ke role lain karena status sudah 'Approved By System'
        // (Sesuai logika index() yang kita buat sebelumnya)
    }

    return redirect()->route('pesan.index')->with('success', 'Dokumen berhasil diproses ke tahap selanjutnya.');
}
}