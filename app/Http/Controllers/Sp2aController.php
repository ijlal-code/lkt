<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; 

class Sp2aController extends Controller
{
    /**
     * Menampilkan daftar semua SP2A (Draft & Proses)
     */
    public function index()
    {
        $sp2as = Sp2a::latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    /**
     * Halaman Riwayat Approval (History)
     * Dengan Filter Bulan & Tahun
     */
    public function history(Request $request)
    {
        $role = Auth::user()->role;
        $query = Sp2a::query();

        // 1. LOGIKA ROLE (Filter data berdasarkan siapa yang login)
        if ($role == 'sm') {
            $query->whereNotNull('approved_sm_at');
        } 
        elseif ($role == 'smqa') {
            $query->whereNotNull('approved_smqa_at');
        } 
        elseif ($role == 'gm') {
            $query->whereNotNull('approved_gm_at');
        } 
        else {
            // Untuk Admin/Staff/User lain melihat yang sudah selesai
            $query->where('status', 'Approved By System');
        }

        // 2. FILTER PENCARIAN BULAN & TAHUN
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_surat', $request->tahun);
        }

        $riwayat = $query->latest()->get();
        
        return view('sp2a.history', compact('riwayat'));
    }

    public function create()
    {
        return view('sp2a.create');
    }

    /**
     * Simpan SP2A Baru -> STATUS 'staff' (Draft)
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
            'tembusan' => array_values(array_filter($request->tembusan ?? [])),

            // Inisialisasi Status
            'nomor_sp2a' => null,
            'current_step' => 'staff', 
            'status' => 'Draft (Belum Dikirim)', 
            'catatan_koreksi' => null,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil disimpan. Silakan klik tombol "Proses" untuk mengirim ke SM.');
    }

    /**
     * Method untuk Memproses (Kirim) Draft/Revisi ke SM
     */
    public function process($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        // Hanya bisa diproses jika posisi dokumen ada di 'staff'
        if ($sp2a->current_step == 'staff') {
            $sp2a->update([
                'current_step' => 'sm',
                'status' => 'Menunggu Approval SM',
                'catatan_koreksi' => null // Hapus catatan koreksi lama jika ada
            ]);
            return back()->with('success', 'Dokumen berhasil dikirim ke Senior Manager (SM).');
        }

        return back()->with('error', 'Dokumen tidak dapat diproses (Status bukan Draft/Staff).');
    }

    public function show($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        return view('sp2a.show', compact('sp2a'));
    }

    /**
     * Edit Dokumen
     */
    public function edit($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        
        // Cek 'staff' (mencakup draft baru & revisi)
        if ($sp2a->current_step != 'staff') {
            return back()->with('error', 'Dokumen sedang diproses di tahap approval, tidak bisa diedit.');
        }
        return view('sp2a.edit', compact('sp2a'));
    }

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

        // LOGIKA UPDATE:
        // Tetap di 'staff' agar user bisa review dulu, baru klik "Proses" manual.
        
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

            'current_step' => 'staff', // Tetap di Staff
            'status' => 'Draft (Telah Diupdate)', // Update status info
            
            // Reset approval history jika ada perubahan konten
            'approved_sm_at' => null,
            'approved_smqa_at' => null,
            'approved_gm_at' => null,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft berhasil diperbarui. Klik tombol "Proses" di menu aksi untuk mengirim.');
    }

    /**
     * Logic Approval Berjenjang (SM -> SMQA -> GM)
     */
    public function approve($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        $userRole = Auth::user()->role; 

        // 1. SM Approve -> Lanjut ke SM QA
        if ($sp2a->current_step == 'sm' && $userRole == 'sm') {
            $sp2a->update([
                'current_step' => 'smqa',
                'status' => 'Disetujui SM (Menunggu SM QA)',
                'approved_sm_at' => now(),
            ]);
            return back()->with('success', 'Dokumen disetujui. Status kini: Menunggu SM QA.');
        }

        // 2. SM QA Approve -> Lanjut ke GM
        if ($sp2a->current_step == 'smqa' && $userRole == 'smqa') {
            $sp2a->update([
                'current_step' => 'gm',
                'status' => 'Disetujui SM QA (Menunggu GM)',
                'approved_smqa_at' => now(),
            ]);
            return back()->with('success', 'Dokumen disetujui. Status kini: Menunggu GM Internal Audit.');
        }

        // 3. GM Approve -> Final (Generate Nomor)
        if ($sp2a->current_step == 'gm' && $userRole == 'gm') {
            
            $romawi = $this->getRomawi($sp2a->tanggal_surat->format('n'));
            $tahun = $sp2a->tanggal_surat->format('Y');
            $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT);
            $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$romawi}/{$tahun}";

            $sp2a->update([
                'current_step' => 'finished',
                'status' => 'Approved By System',
                'approved_gm_at' => now(),
                'nomor_sp2a' => $nomorSurat, 
            ]);

            return back()->with('success', "Dokumen Final! Nomor $nomorSurat telah diterbitkan.");
        }

        return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui tahap ini.');
    }

    /**
     * Logic Koreksi (Kembalikan ke Staff) dengan Info Role
     */
    public function koreksi(Request $request, $id)
    {
        $request->validate(['catatan' => 'required|string']);
        
        $sp2a = Sp2a::findOrFail($id);
        
        // Ambil role, jika kosong ganti jadi 'ATASAN'
        $roleRaw = Auth::user()->role ?? 'ATASAN';
        $rolePengoreksi = strtoupper($roleRaw); 

        $sp2a->update([
            'current_step' => 'staff',
            // Simpan status lengkap ke database
            'status' => 'Perlu Perbaikan (Dikembalikan oleh ' . $rolePengoreksi . ')',
            'catatan_koreksi' => $request->catatan,
        ]);

        return back()->with('warning', 'Dokumen dikembalikan ke Staff untuk perbaikan.');
    }

    /**
     * Fitur Download PDF
     */
    public function downloadPdf($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));
        $pdf->setPaper('A4', 'portrait');
        $fileName = 'SP2A_' . ($sp2a->nomor_sp2a ? str_replace('/', '-', $sp2a->nomor_sp2a) : 'DRAFT') . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Hapus Dokumen
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

    // Helper Romawi
    private function getRomawi($n) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$n] ?? 'I';
    }
}