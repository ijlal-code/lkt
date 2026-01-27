<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use App\Models\Ltk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sp2aNotification; 

class Sp2aController extends Controller
{
    /**
     * Menampilkan Daftar SP2A
     */
    public function index()
    {
        // Ambil data SP2A terbaru beserta data LKT relasinya
        $sp2as = Sp2a::with('ltk')->latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    /**
     * Form Buat SP2A (Berdasarkan ID LKT)
     */
    public function create($ltk_id)
    {
        $ltk = Ltk::findOrFail($ltk_id);
        
        // Cek apakah LKT ini sudah punya SP2A
        if ($ltk->sp2a) {
            return redirect()->route('sp2a.index')->with('error', 'SP2A untuk LKT nomor ' . $ltk->nomor_lkt . ' sudah ada.');
        }

        return view('sp2a.create', compact('ltk'));
    }

    /**
     * Simpan SP2A ke Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'ltk_id' => 'required|exists:ltks,id',
            'tanggal_sp2a' => 'required|date',
            'kepada' => 'required|string',
            'dasar_peringatan' => 'required|string',
            'email_auditee' => 'required|email',
        ]);

        Sp2a::create($request->all());

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil dibuat. Silakan Approve untuk menerbitkan nomor.');
    }

    /**
     * Approval SP2A
     */
    public function approve($id)
    {
        $sp2a = Sp2a::with('ltk')->findOrFail($id);

        if ($sp2a->status === 'Approved') {
            return back()->with('error', 'SP2A sudah disetujui sebelumnya.');
        }

        // Generate Nomor
        $bulanRomawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT); 
        $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$bulanRomawi}/{$tahun}";

        // Update
        $sp2a->update([
            'nomor_sp2a' => $nomorSurat,
            'status' => 'Approved',
            'tanggal_approved' => now(),
        ]);

        // Kirim Email (Pastikan mailer sudah dikonfigurasi)
        if ($sp2a->email_auditee) {
            // Logika kirim email di sini
            // Mail::to($sp2a->email_auditee)...
        }

        return back()->with('success', 'SP2A Disetujui. Nomor: ' . $nomorSurat);
    }

    private function getRomawi($bulan) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 
                7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}