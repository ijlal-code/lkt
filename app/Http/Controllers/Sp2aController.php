<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use App\Models\User; // Pakai Model User, bukan Contact lagi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sp2aNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class Sp2aController extends Controller
{
    public function index()
    {
        $sp2as = Sp2a::latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    public function create()
    {
        // Ambil user berdasarkan role untuk dropdown
        $audities = User::where('role', 'auditi')->get();
        $auditors = User::where('role', 'auditor')->get();
        $k3s      = User::where('role', 'k3')->get();
        $staffs   = User::where('role', 'staff')->get();
        $atasans  = User::where('role', 'atasan_staff')->get();

        return view('sp2a.create', compact('audities', 'auditors', 'k3s', 'staffs', 'atasans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'kepada_user_id' => 'required', // ID User Auditi
            'dari_nama' => 'required',
            'perihal' => 'required',
            'isi_surat' => 'required',
        ]);

        // Ambil data Auditi
        $auditi = User::findOrFail($request->kepada_user_id);

        Sp2a::create([
            'tanggal_surat' => $request->tanggal_surat,
            'kepada_nama'   => $auditi->name,
            'kepada_email'  => $auditi->email,
            'dari_nama'     => $request->dari_nama,
            'perihal'       => $request->perihal,
            'isi_surat'     => $request->isi_surat,
            'penanda_tangan_nama' => $request->penanda_tangan_nama,
            
            // Simpan Email Tembusan (dari dropdown)
            'email_auditor' => $request->email_auditor,
            'email_k3'      => $request->email_k3,
            'email_staff'   => $request->email_staff,
            'email_atasan'  => $request->email_atasan,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil dibuat.');
    }

    /**
     * Ganti "Approve" jadi "Process"
     */
    public function process($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        if ($sp2a->status === 'Approved') {
            return back()->with('error', 'Sudah diproses sebelumnya.');
        }

        // 1. Generate Nomor
        $bulanRomawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT);
        $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$bulanRomawi}/{$tahun}";

        $sp2a->update([
            'nomor_sp2a' => $nomorSurat,
            'status' => 'Approved',
            'approved_at' => now(),
        ]);

        // 2. Generate PDF
        $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));
        $pdfContent = $pdf->output();

        // 3. Kirim Email ke SEMUA Role Terkait
        try {
            // Email Utama (Auditi)
            $mail = Mail::to($sp2a->kepada_email);
            
            // Kumpulkan semua CC
            $ccs = [];
            if ($sp2a->email_auditor) $ccs[] = $sp2a->email_auditor;
            if ($sp2a->email_k3)      $ccs[] = $sp2a->email_k3;
            if ($sp2a->email_staff)   $ccs[] = $sp2a->email_staff;
            if ($sp2a->email_atasan)  $ccs[] = $sp2a->email_atasan;

            if (!empty($ccs)) {
                $mail->cc($ccs);
            }

            $mail->send(new Sp2aNotification($sp2a, $pdfContent));

        } catch (\Exception $e) {
            return back()->with('error', 'Berhasil diproses, tapi gagal kirim email: ' . $e->getMessage());
        }

        return back()->with('success', "Status: PROSES SELESAI. Email terkirim ke Auditi dan Tembusan.");
    }

    private function getRomawi($n) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$n];
    }
}