<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan library barryvdh/laravel-dompdf sudah terinstall

class Sp2aController extends Controller
{
    /**
     * Menampilkan daftar semua SP2A.
     * Mengambil semua data agar history tetap terlihat di halaman Index.
     */
    public function index()
    {
        $sp2as = Sp2a::latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    /**
     * Halaman Riwayat Approval Khusus (Filtered)
     * Untuk menu "Riwayat Approval" di sidebar.
     */
    public function history()
    {
        $role = Auth::user()->role;
        $query = Sp2a::query();

        // LOGIKA RIWAYAT:
        // Tampilkan dokumen jika user tersebut PERNAH memberikan stempel waktu approval.
        
        if ($role == 'sm') {
            // SM melihat semua yang pernah dia setujui (termasuk yang sudah final di GM)
            $query->whereNotNull('approved_sm_at');
        } 
        elseif ($role == 'smqa') {
            // SM QA melihat semua yang pernah dia setujui
            $query->whereNotNull('approved_smqa_at');
        } 
        elseif ($role == 'gm') {
            // GM melihat semua yang pernah dia setujui
            $query->whereNotNull('approved_gm_at');
        } 
        else {
            // Role lain (Staff/Auditor/dll) biasanya tidak pakai menu ini (kosongkan)
            // Atau bisa dialihkan melihat semua yang Approved By System
            $query->where('status', 'Approved By System');
        }

        $riwayat = $query->latest()->get();
        return view('sp2a.history', compact('riwayat'));
    }

    public function create()
    {
        return view('sp2a.create');
    }

    /**
     * Simpan SP2A Baru
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
            
            // Simpan Array Tembusan (bersihkan input kosong)
            'tembusan' => array_values(array_filter($request->tembusan ?? [])),

            // WORKFLOW START
            'nomor_sp2a' => null,
            'current_step' => 'sm', 
            'status' => 'Menunggu Approval SM', 
            'catatan_koreksi' => null,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil dibuat. Menunggu Approval SM.');
    }

    public function show($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        return view('sp2a.show', compact('sp2a'));
    }

    public function edit($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        if ($sp2a->current_step != 'staff') {
            return back()->with('error', 'Dokumen sedang diproses, tidak bisa diedit.');
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

            // RESET WORKFLOW KE SM
            'current_step' => 'sm', 
            'status' => 'Revisi Terkirim (Menunggu Approval SM)',
            'catatan_koreksi' => null,
            'approved_sm_at' => null,
            'approved_smqa_at' => null,
            'approved_gm_at' => null,
        ]);

        return redirect()->route('sp2a.show', $id)->with('success', 'Revisi berhasil dikirim kembali ke SM.');
    }

    /**
     * Logic Approval dengan STATUS REALTIME DESKRIPTIF
     */
    public function approve($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        $userRole = Auth::user()->role; 

        // 1. SM Approve -> Lanjut ke SM QA
        if ($sp2a->current_step == 'sm' && $userRole == 'sm') {
            $sp2a->update([
                'current_step' => 'smqa',
                'status' => 'Disetujui SM (Menunggu SM QA)', // Status Realtime
                'approved_sm_at' => now(),
            ]);
            return back()->with('success', 'Dokumen disetujui. Status kini: Menunggu SM QA.');
        }

        // 2. SM QA Approve -> Lanjut ke GM
        if ($sp2a->current_step == 'smqa' && $userRole == 'smqa') {
            $sp2a->update([
                'current_step' => 'gm',
                'status' => 'Disetujui SM QA (Menunggu GM)', // Status Realtime
                'approved_smqa_at' => now(),
            ]);
            return back()->with('success', 'Dokumen disetujui. Status kini: Menunggu GM Internal Audit.');
        }

        // 3. GM Approve -> Final (Generate Nomor)
        if ($sp2a->current_step == 'gm' && $userRole == 'gm') {
            
            // Generate Nomor Surat: SP2A/001/INTERNAL/I/2026
            $romawi = $this->getRomawi($sp2a->tanggal_surat->format('n'));
            $tahun = $sp2a->tanggal_surat->format('Y');
            $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT);
            $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$romawi}/{$tahun}";

            $sp2a->update([
                'current_step' => 'finished',
                'status' => 'Selesai (Approved by System)', // Status Final
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
        $sp2a->update([
            'current_step' => 'staff',
            'status' => 'Perlu Perbaikan (Dikembalikan oleh ' . Auth::user()->name . ')',
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
        
        // Load view khusus PDF (buat file resources/views/sp2a/pdf.blade.php yang isinya mirip show.blade.php tapi clean)
        $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));
        
        // Setting kertas A4
        $pdf->setPaper('A4', 'portrait');
        
        // Nama file saat didownload
        $fileName = 'SP2A_' . ($sp2a->nomor_sp2a ? str_replace('/', '-', $sp2a->nomor_sp2a) : 'DRAFT') . '.pdf';
        
        return $pdf->download($fileName);
    }

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