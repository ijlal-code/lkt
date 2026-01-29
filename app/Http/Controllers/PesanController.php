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
    $user = Auth::user();
    $emailUser = $user->email;
    $roleUser = $user->role;

    $pesan = Sp2a::where(function($query) use ($emailUser, $roleUser) {
        // 1. Jika dokumen sudah Selesai (Approved GM), semua role terkait bisa lihat
        $query->where('status', 'Approved By System')
              ->where(function($q) use ($emailUser) {
                  $q->where('kepada_email', $emailUser)
                    ->orWhere('email_auditor', $emailUser)
                    ->orWhere('email_k3', $emailUser)
                    ->orWhere('email_staff', $emailUser)
                    ->orWhere('email_atasan', $emailUser);
              });

        // 2. Tampilkan dokumen ke SM/SMQA/GM sesuai tahapannya walaupun belum 'Approved By System'
        $query->orWhere(function($q) use ($roleUser) {
            $q->where('current_step', $roleUser);
        });
        
        // 3. Khusus Staff/Admin bisa melihat dokumen yang sedang dalam proses
        if (in_array($roleUser, ['admin', 'staff'])) {
            $query->orWhere('status', 'Pending Approval');
        }
    })->latest()->get();

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
        $sp2a->update(['current_step' => 'smqa']);
    } 
    elseif ($user->role == 'smqa' && $sp2a->current_step == 'smqa') {
        $sp2a->update(['current_step' => 'gm']);
    } 
    elseif ($user->role == 'gm' && $sp2a->current_step == 'gm') {
        // TAHAP FINAL: Generate Nomor & Aktifkan akses untuk semua role
        $tahun = date('Y');
        $count = Sp2a::whereYear('approved_at', $tahun)->count() + 1;
        $nomorFinal = "SP2A/" . str_pad($count, 3, '0', STR_PAD_LEFT) . "/IA-ST/" . $tahun;

        $sp2a->update([
            'nomor_sp2a' => $nomorFinal,
            'status' => 'Approved By System',
            'current_step' => 'finished',
            'approved_at' => now(),
            'approved_gm_at' => now()
        ]);
        
        return redirect()->route('pesan.index')->with('success', 'Dokumen disetujui GM. Nomor telah terbit dan dokumen telah didistribusikan.');
    }

    return redirect()->route('pesan.index')->with('success', 'Persetujuan berhasil diproses.');
}
}