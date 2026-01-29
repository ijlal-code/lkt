<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Sp2aController extends Controller
{
    /**
     * Menampilkan daftar SP2A
     * * PENTING: Kita menggunakan latest()->get() untuk mengambil SEMUA data.
     * Ini memastikan setelah SM/SMQA/GM melakukan approve, surat tersebut 
     * TIDAK HILANG dari tabel mereka, melainkan hanya statusnya yang berubah.
     */
    public function index()
    {
        $sp2as = Sp2a::latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    /**
     * Halaman Form Buat Baru
     */
    public function create()
    {
        return view('sp2a.create');
    }

    /**
     * Simpan SP2A Baru (Staff) -> Langsung Kirim ke SM
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'kepada_nama' => 'required|string',
            'dari_nama' => 'required|string',
            'perihal' => 'required|string',
            'dasar_surat' => 'required|string',
            'isi_surat' => 'required',
            'penanda_tangan_nama' => 'required|string',
            'tembusan' => 'nullable|array',
        ]);

        Sp2a::create([
            'tanggal_surat' => $request->tanggal_surat,
            'kepada_nama'   => $request->kepada_nama,
            'kepada_email'  => $request->kepada_email,
            'dari_nama'     => $request->dari_nama,
            'perihal'       => $request->perihal,
            'dasar_surat'   => $request->dasar_surat,
            'isi_surat'     => $request->isi_surat,
            'penanda_tangan_nama' => $request->penanda_tangan_nama,
            
            // Simpan Array Tembusan (filter menghapus input kosong)
            'tembusan' => array_values(array_filter($request->tembusan ?? [])),

            // --- WORKFLOW START ---
            'nomor_sp2a' => null,
            'current_step' => 'sm', 
            'status' => 'Menunggu Approval SM', 
            'catatan_koreksi' => null,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil dibuat. Status: Menunggu Approval SM.');
    }

    /**
     * Halaman Detail (Lihat & Approve)
     */
    public function show($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        return view('sp2a.show', compact('sp2a'));
    }

    /**
     * Halaman Edit (Hanya jika dikembalikan ke Staff)
     */
    public function edit($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        
        // Validasi: Hanya bisa edit jika status dikembalikan ke staff
        if ($sp2a->current_step != 'staff') {
            return back()->with('error', 'Dokumen sedang diproses approval, tidak bisa diedit.');
        }

        return view('sp2a.edit', compact('sp2a'));
    }

    /**
     * Proses Update Revisi (Staff) -> Kirim Ulang ke SM
     */
    public function update(Request $request, $id)
    {
        $sp2a = Sp2a::findOrFail($id);

        if ($sp2a->current_step != 'staff') {
            return back()->with('error', 'Tidak dapat mengubah dokumen yang sedang diproses.');
        }

        $request->validate([
            'tanggal_surat' => 'required|date',
            'kepada_nama' => 'required|string',
            'isi_surat' => 'required',
            'tembusan' => 'nullable|array',
        ]);

        $sp2a->update([
            'tanggal_surat' => $request->tanggal_surat,
            'kepada_nama' => $request->kepada_nama,
            'kepada_email' => $request->kepada_email,
            'dari_nama' => $request->dari_nama,
            'perihal' => $request->perihal,
            'dasar_surat' => $request->dasar_surat,
            'isi_surat' => $request->isi_surat,
            'penanda_tangan_nama' => $request->penanda_tangan_nama,
            'tembusan' => array_values(array_filter($request->tembusan ?? [])),

            // RESET WORKFLOW KE AWAL (SM)
            'current_step' => 'sm', 
            'status' => 'Revisi Terkirim (Menunggu Approval SM)',
            'catatan_koreksi' => null, // Hapus catatan lama
            
            // Reset Timestamp Approval sebelumnya
            'approved_sm_at' => null,
            'approved_smqa_at' => null,
            'approved_gm_at' => null,
        ]);

        return redirect()->route('sp2a.show', $id)->with('success', 'Revisi berhasil dikirim kembali ke SM.');
    }

    /**
     * Logic Approval Berjenjang
     * Status dibuat deskriptif agar user paham posisi dokumen.
     * Menggunakan back() agar data tetap terlihat di halaman.
     */
    public function approve($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        $userRole = Auth::user()->role; 

        // 1. TAHAP SM (Manager) -> Ke SM QA
        if ($sp2a->current_step == 'sm' && $userRole == 'sm') {
            $sp2a->update([
                'current_step' => 'smqa',
                // Update Status Deskriptif
                'status' => 'Approved by SM (Menunggu Approval SM QA)',
                'approved_sm_at' => now(),
            ]);
            
            // Return Back: Tetap di halaman index/show, User melihat status berubah.
            return back()->with('success', 'Berhasil disetujui. Dokumen diteruskan ke SM QA.');
        }

        // 2. TAHAP SM QA (Pak Chandra) -> Ke GM
        if ($sp2a->current_step == 'smqa' && $userRole == 'smqa') {
            $sp2a->update([
                'current_step' => 'gm',
                // Update Status Deskriptif
                'status' => 'Approved by SM QA (Menunggu Approval GM)',
                'approved_smqa_at' => now(),
            ]);
            
            return back()->with('success', 'Berhasil disetujui. Dokumen diteruskan ke GM Internal Audit.');
        }

        // 3. TAHAP GM (Final) -> Selesai & Generate Nomor
        if ($sp2a->current_step == 'gm' && $userRole == 'gm') {
            
            // Generate Nomor Surat Otomatis
            $romawi = $this->getRomawi($sp2a->tanggal_surat->format('n'));
            $tahun = $sp2a->tanggal_surat->format('Y');
            $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT);
            
            $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$romawi}/{$tahun}";

            $sp2a->update([
                'current_step' => 'finished',
                'status' => 'Approved By System (Final)',
                'approved_gm_at' => now(),
                'nomor_sp2a' => $nomorSurat, 
            ]);

            return back()->with('success', "Dokumen Final! Nomor $nomorSurat telah diterbitkan.");
        }

        return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui tahap ini.');
    }

    /**
     * Logic Koreksi (Kembalikan ke Staff)
     */
    public function koreksi(Request $request, $id)
    {
        $request->validate(['catatan' => 'required|string']);
        
        $sp2a = Sp2a::findOrFail($id);
        
        // Kembalikan ke STAFF
        $sp2a->update([
            'current_step' => 'staff',
            'status' => 'Perlu Perbaikan (Dikembalikan oleh ' . Auth::user()->name . ')',
            'catatan_koreksi' => $request->catatan,
        ]);

        return back()->with('warning', 'Dokumen dikembalikan ke Staff untuk perbaikan.');
    }

    /**
     * Hapus SP2A
     */
    public function destroy($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        if ($sp2a->current_step == 'finished') {
            return back()->with('error', 'Tidak bisa menghapus dokumen yang sudah disetujui sistem (Final).');
        }

        $sp2a->delete();
        return back()->with('success', 'Dokumen SP2A berhasil dihapus.');
    }

    /**
     * Helper Romawi
     */
    private function getRomawi($n) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$n] ?? 'I';
    }

    public function history()
    {
        $role = Auth::user()->role;
        $query = Sp2a::query();

        // Filter berdasarkan Role
        if ($role == 'sm') {
            // Tampilkan yang sudah ada tanggal approve SM-nya
            $query->whereNotNull('approved_sm_at');
        } 
        elseif ($role == 'smqa') {
            // Tampilkan yang sudah ada tanggal approve SM QA-nya
            $query->whereNotNull('approved_smqa_at');
        } 
        elseif ($role == 'gm') {
            // Tampilkan yang sudah ada tanggal approve GM-nya
            $query->whereNotNull('approved_gm_at');
        } 
        else {
            // Jika Staff/Admin iseng akses, kosongkan atau tampilkan semua (opsional)
            // Disini kita kosongkan saja
            $query->where('id', 0);
        }

        $riwayat = $query->latest()->get();

        return view('sp2a.history', compact('riwayat'));
    }
}