<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pesan = Sp2a::where(function($query) use ($user) {
            // 1. Jika sudah disetujui GM, semua role terkait (CC) bisa melihat
            $query->where('status', 'Approved By System')
                  ->where(function($q) use ($user) {
                      $q->where('kepada_email', $user->email)
                        ->orWhere('email_auditor', $user->email)
                        ->orWhere('email_k3', $user->email)
                        ->orWhere('email_staff', $user->email)
                        ->orWhere('email_atasan', $user->email);
                  });

            // 2. Tampilkan dokumen ke SM/SMQA/GM sesuai antrian tahapannya
            $query->orWhere('current_step', $user->role);
            
            // 3. Admin & Staff bisa melihat dokumen yang masih dalam proses (Pending)
            if (in_array($user->role, ['admin', 'staff'])) {
                $query->orWhere('status', 'Pending Approval')
                      ->orWhere('status', 'Ditolak/Perlu Koreksi');
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
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sp2a.pdf', compact('sp2a'));
        
        if (request()->has('download')) {
            return $pdf->download('SP2A-'.$sp2a->id.'.pdf');
        }
        return $pdf->stream();
    }

    public function approve($id, Request $request)
    {
        $sp2a = Sp2a::findOrFail($id);
        $user = Auth::user();

        // LOGIKA KOREKSI (Ditolak balik ke Staff)
        if ($request->has('koreksi')) {
            $sp2a->update([
                'current_step' => 'staff',
                'status' => 'Ditolak/Perlu Koreksi',
                'catatan_koreksi' => $request->catatan_koreksi
            ]);
            return redirect()->route('pesan.index')->with('success', 'Dokumen dikirim balik ke Staff untuk diperbaiki.');
        }

        // LOGIKA PERSETUJUAN BERJENJANG
        if ($user->role == 'sm' && $sp2a->current_step == 'sm') {
            $sp2a->update(['current_step' => 'smqa', 'approved_sm_at' => now()]);
        } 
        elseif ($user->role == 'smqa' && $sp2a->current_step == 'smqa') {
            $sp2a->update(['current_step' => 'gm', 'approved_smqa_at' => now()]);
        } 
        elseif ($user->role == 'gm' && $sp2a->current_step == 'gm') {
            // TAHAP FINAL: Generate Nomor Otomatis & Distribusi
            $tahun = date('Y');
            $noUrut = Sp2a::whereNotNull('nomor_sp2a')->count() + 1;
            $nomorBaru = "SP2A/" . str_pad($noUrut, 3, '0', STR_PAD_LEFT) . "/IA/" . $tahun;

            $sp2a->update([
                'nomor_sp2a' => $nomorBaru,
                'status' => 'Approved By System',
                'current_step' => 'finished',
                'approved_at' => now(),
                'approved_gm_at' => now()
            ]);
            return redirect()->route('pesan.index')->with('success', 'Dokumen Selesai! Nomor terbit dan dokumen telah dikirim ke semua pihak.');
        }

        return redirect()->route('pesan.index')->with('success', 'Persetujuan berhasil diteruskan ke tahap berikutnya.');
    }
}