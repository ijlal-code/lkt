<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sp2aNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class PesanController extends Controller
{
    /**
     * Menampilkan Daftar Pesan Masuk (SP2A)
     */
    public function index()
    {
        $emailUser = Auth::user()->email;
        $roleUser = Auth::user()->role;

        // Ambil SP2A yang melibatkan user ini (baik sebagai K3, Auditor, atau Auditi)
        // Dan statusnya sudah diproses oleh Admin (bukan Draft lagi)
        $pesan = Sp2a::where('status', '!=', 'Draft')
            ->where(function($q) use ($emailUser) {
                $q->where('email_k3', $emailUser)
                  ->orWhere('email_auditor', $emailUser)
                  ->orWhere('email_staff', $emailUser)
                  ->orWhere('email_atasan', $emailUser)
                  ->orWhere('kepada_email', $emailUser);
            })
            ->latest()
            ->get();

        return view('pesan.index', compact('pesan'));
    }

    /**
     * Buka Detail Pesan & Preview PDF
     */
    public function show($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        
        // Generate PDF Preview (Tanpa Simpan)
        // Kita kirim variabel $is_preview = true agar tombol download tidak muncul di dalam PDF
        $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));
        
        // Render PDF ke browser (Stream)
        return $pdf->stream('Preview_SP2A.pdf');
    }

    /**
     * LOGIKA APPROVAL OLEH K3
     */
    public function approve($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        // Pastikan hanya K3 (atau role berwenang) yang bisa approve
        if (Auth::user()->role != 'k3') {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui dokumen ini.');
        }

        if ($sp2a->status == 'Approved By System') {
            return back()->with('error', 'Dokumen sudah disetujui sebelumnya.');
        }

        // 1. Update Status Database
        $sp2a->update([
            'status' => 'Approved By System', // Status Baru
            'approved_at' => now(),
        ]);

        // 2. Generate PDF FINAL (Dengan Cap "Approved By System")
        $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));
        $pdfContent = $pdf->output();

        // 3. Kirim Email ke SEMUA PIHAK
        try {
            $mail = Mail::to($sp2a->kepada_email); // Ke Auditi

            // CC ke Semua Pihak Terkait
            $ccs = array_filter([
                $sp2a->email_auditor,
                $sp2a->email_k3,
                $sp2a->email_staff,
                $sp2a->email_atasan
            ]);

            if (!empty($ccs)) {
                $mail->cc($ccs);
            }

            $mail->send(new Sp2aNotification($sp2a, $pdfContent));

        } catch (\Exception $e) {
            return back()->with('error', 'Approved, tapi gagal kirim email: ' . $e->getMessage());
        }

        return back()->with('success', 'Dokumen berhasil di-Approve! PDF bertanda tangan sistem telah dikirim ke semua pihak.');
    }
}