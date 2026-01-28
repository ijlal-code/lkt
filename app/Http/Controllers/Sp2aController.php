<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Sp2aController extends Controller
{
    /**
     * Menampilkan daftar SP2A (Halaman Admin)
     */
    public function index()
    {
        // Mengambil data urut dari yang terbaru
        $sp2as = Sp2a::latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    /**
     * Menampilkan Form Buat SP2A
     * Mengirim data User berdasarkan Role untuk Dropdown
     */
    public function create()
    {
        // Ambil list user berdasarkan role agar Admin mudah memilih
        $audities = User::where('role', 'auditi')->get();
        $auditors = User::where('role', 'auditor')->get();
        $k3s      = User::where('role', 'k3')->get();
        $staffs   = User::where('role', 'staff')->get();
        $atasans  = User::where('role', 'atasan_staff')->get();

        return view('sp2a.create', compact('audities', 'auditors', 'k3s', 'staffs', 'atasans'));
    }

    /**
     * Menyimpan Draft SP2A ke Database
     */
    public function store(Request $request)
{
    // 1. Tambahkan Validasi
    $request->validate([
        'tanggal_surat' => 'required|date',
        'kepada_user_id' => 'required|exists:users,id',
        'dari_nama' => 'required|string',
        'perihal' => 'required|string',
        'dasar_surat' => 'required|string', // <--- TAMBAHKAN INI
        'isi_surat' => 'required',
        'penanda_tangan_nama' => 'required|string',
    ]);

    $auditi = User::findOrFail($request->kepada_user_id);

    Sp2a::create([
        'tanggal_surat' => $request->tanggal_surat,
        'kepada_nama'   => $auditi->name,
        'kepada_email'  => $auditi->email,
        'dari_nama'     => $request->dari_nama,
        'perihal'       => $request->perihal,
        
        'dasar_surat'   => $request->dasar_surat, // <--- TAMBAHKAN BARIS INI (PENTING)
        
        'isi_surat'     => $request->isi_surat,
        'penanda_tangan_nama' => $request->penanda_tangan_nama,
        
        'email_auditor' => $request->email_auditor,
        'email_k3'      => $request->email_k3,
        'email_staff'   => $request->email_staff,
        'email_atasan'  => $request->email_atasan,

        'status' => 'Draft',
    ]);

    return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil dibuat.');
}

    /**
     * Proses SP2A (Generate Nomor & Ubah Status ke Menunggu K3)
     * Dijalankan oleh Admin
     */
    public function process($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        // Cek apakah sudah diproses sebelumnya
        if ($sp2a->status != 'Draft') {
            return back()->with('error', 'Dokumen ini sudah diproses sebelumnya.');
        }

        // 1. Generate Nomor Surat Otomatis
        // Format: SP2A/001/INTERNAL/I/2026
        $bulanRomawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT); 
        
        $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$bulanRomawi}/{$tahun}";

        // 2. Update Status menjadi "Menunggu Approval K3"
        // Note: Kita BELUM membubuhkan 'Approved By System' disini. Itu tugas K3.
        $sp2a->update([
            'nomor_sp2a' => $nomorSurat,
            'status' => 'Menunggu Approval K3',
        ]);

        return back()->with('success', "Berhasil diproses ($nomorSurat). Status sekarang: Menunggu Approval K3.");
    }

    /**
     * Hapus SP2A (Hanya jika masih Draft)
     */
    public function destroy($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        if ($sp2a->status == 'Approved By System') {
            return back()->with('error', 'Tidak bisa menghapus dokumen yang sudah disetujui sistem.');
        }

        $sp2a->delete();
        return back()->with('success', 'Dokumen SP2A berhasil dihapus.');
    }

    /**
     * Helper: Konversi Angka Bulan ke Romawi
     */
    private function getRomawi($n) {
        $map = [
            1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 
            7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'
        ];
        return $map[$n];
    }
}