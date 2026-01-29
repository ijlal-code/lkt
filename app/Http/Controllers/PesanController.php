<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PesanController extends Controller
{
    /**
     * KOTAK PESAN (INBOX)
     * Logika:
     * - SM, SMQA, GM: Hanya melihat tugas yang HARUS diapprove sekarang. (Yang selesai masuk History).
     * - Role Lain: Melihat dokumen yang SUDAH selesai (Approved By System).
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $isApprover = in_array($role, ['sm', 'smqa', 'gm']);
        
        $pesan = Sp2a::where(function($query) use ($user, $role, $isApprover) {
            
            if ($isApprover) {
                // --- LOGIKA UNTUK APPROVER (SM, SMQA, GM) ---
                // Hanya tampilkan yang SEDANG MENUNGGU giliran mereka.
                // Jika sudah diapprove GM (Final), tidak muncul disini, tapi di 'Riwayat Approval'.
                $query->where('current_step', $role)
                      ->where('status', '!=', 'Approved By System');
            } else {
                // --- LOGIKA UNTUK ROLE LAIN (Auditor, K3, Auditi, Staff, Admin) ---
                // 1. Tampilkan Semua Dokumen yang SUDAH FINAL (Broadcast ke semua)
                $query->where('status', 'Approved By System');

                // 2. Khusus Staff/Admin: Bisa memantau dokumen yang masih proses/ditolak/draft
                if (in_array($role, ['admin', 'staff'])) {
                    $query->orWhere('status', 'LIKE', '%Menunggu%')
                          ->orWhere('status', 'LIKE', '%Ditolak%')
                          ->orWhere('status', 'LIKE', '%Revisi%');
                }
            }
        })->latest()->get();

        return view('pesan.index', compact('pesan'));
    }

    public function previewPage($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        return view('pesan.preview', compact('sp2a'));
    }

    public function show($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));
        
        if (request()->has('download')) {
            // Nama file PDF menggunakan Nomor SP2A jika ada
            $fileName = $sp2a->nomor_sp2a ? str_replace('/', '-', $sp2a->nomor_sp2a) : 'SP2A-'.$sp2a->id;
            return $pdf->download($fileName . '.pdf');
        }
        return $pdf->stream();
    }

    /**
     * Logic Approval
     */
    public function approve($id, Request $request)
    {
        $sp2a = Sp2a::findOrFail($id);
        $user = Auth::user();

        // 1. LOGIKA KOREKSI (Tolak)
        if ($request->has('koreksi')) {
            $sp2a->update([
                'current_step' => 'staff',
                'status' => 'Ditolak/Perlu Koreksi',
                'catatan_koreksi' => $request->catatan_koreksi
            ]);
            return redirect()->route('pesan.index')->with('success', 'Dokumen dikembalikan ke Staff untuk perbaikan.');
        }

        // 2. LOGIKA APPROVAL BERJENJANG
        
        // SM Approve -> Ke SM QA
        if ($user->role == 'sm' && $sp2a->current_step == 'sm') {
            $sp2a->update([
                'current_step' => 'smqa', 
                'status' => 'Disetujui SM (Menunggu SM QA)', 
                'approved_sm_at' => now()
            ]);
        } 
        // SM QA Approve -> Ke GM
        elseif ($user->role == 'smqa' && $sp2a->current_step == 'smqa') {
            $sp2a->update([
                'current_step' => 'gm', 
                'status' => 'Disetujui SM QA (Menunggu GM)', 
                'approved_smqa_at' => now()
            ]);
        } 
        // GM Approve -> Final (BROADCAST SYSTEM)
        elseif ($user->role == 'gm' && $sp2a->current_step == 'gm') {
            $tahun = date('Y');
            // Logika sederhana nomor urut
            $noUrut = Sp2a::whereNotNull('nomor_sp2a')->count() + 1;
            $romawi = $this->getRomawi(date('n'));
            $nomorBaru = "SP2A/" . str_pad($noUrut, 3, '0', STR_PAD_LEFT) . "/INTERNAL/" . $romawi . "/" . $tahun;

            $sp2a->update([
                'nomor_sp2a' => $nomorBaru,
                'status' => 'Approved By System', // Status ini akan memicu muncul di Inbox user lain
                'current_step' => 'finished',
                'approved_at' => now(), // Opsional
                'approved_gm_at' => now()
            ]);

            // Redirect GM ke History, karena tugasnya selesai dan inboxnya akan kosong dari item ini
            return redirect()->route('sp2a.history')->with('success', 'Dokumen Final! Disetujui System & Terkirim ke semua User.');
        }

        return redirect()->route('pesan.index')->with('success', 'Berhasil disetujui. Lanjut ke tahap berikutnya.');
    }

    // Helper Romawi
    private function getRomawi($n) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$n] ?? 'I';
    }
}