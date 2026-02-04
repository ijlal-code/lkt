<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Sp2a;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



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
        // AMBIL NAMA GM DARI SETTING
        $gmSetting = Setting::where('key', 'gm_name')->first();
        $gmName = $gmSetting ? $gmSetting->value : 'Silakan Atur Nama GM di Menu Setting';

        return view('sp2a.create', compact('gmName'));
    }

    /**
     * Simpan SP2A Baru -> STATUS 'staff' (Draft)
     */
   public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'kepada_nama' => 'required|array', // Validasi Array
            'kepada_nama.*' => 'required|string', // Validasi item di dalam array
            'dari_nama' => 'required|string',
            'perihal' => 'required|string',
            'dasar_surat' => 'required|string',
            'isi_surat' => 'required',
            // penanda_tangan_nama diambil dari hidden input atau query ulang
        ]);

        // Pastikan nama GM diambil data terbaru dari DB (agar aman) atau dari request
        $gmSetting = Setting::where('key', 'gm_name')->first();
        $fixedGmName = $gmSetting ? $gmSetting->value : $request->penanda_tangan_nama;

        Sp2a::create([
            'tanggal_surat' => $request->tanggal_surat,
            'kepada_nama'   => array_values(array_filter($request->kepada_nama ?? [])), // Simpan Array
            // 'kepada_email'  => dihapus,
            'dari_nama'     => $request->dari_nama,
            'perihal'       => $request->perihal,
            'dasar_surat'   => $request->dasar_surat,
            'isi_surat'     => $request->isi_surat,
            'penanda_tangan_nama' => $fixedGmName, // Otomatis
            'tembusan' => array_values(array_filter($request->tembusan ?? [])),
            
            'nomor_sp2a' => null,
            'current_step' => 'staff', 
            'status' => 'Draft (Belum Dikirim)', 
            'catatan_koreksi' => null,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil disimpan.');
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
            'kepada_nama' => 'required|array', // Array
            'isi_surat' => 'required',
        ]);
        
        // Ambil GM Name eksisting (biasanya tidak berubah saat edit, atau mau update otomatis juga boleh)
        // Disini kita biarkan penanda tangan nama sesuai saat dibuat, atau update dari setting:
        $gmSetting = Setting::where('key', 'gm_name')->first();
        $fixedGmName = $gmSetting ? $gmSetting->value : $sp2a->penanda_tangan_nama;

        $sp2a->update([
            'tanggal_surat' => $request->tanggal_surat,
            'kepada_nama' => array_values(array_filter($request->kepada_nama ?? [])),
            // 'kepada_email' => dihapus
            'dari_nama' => $request->dari_nama,
            'perihal' => $request->perihal,
            'dasar_surat' => $request->dasar_surat,
            'isi_surat' => $request->isi_surat,
            'penanda_tangan_nama' => $fixedGmName,
            'tembusan' => array_values(array_filter($request->tembusan ?? [])),

            'current_step' => 'staff',
            'status' => 'Draft (Telah Diupdate)',
            
            'approved_sm_at' => null,
            'approved_smqa_at' => null,
            'approved_gm_at' => null,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft berhasil diperbarui.');
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
        
        $html = view('sp2a.pdf', compact('sp2a'))->render();

        // Mengatur ukuran kertas Custom yang lebih lebar
        // Format F4 Standar: [215, 330]
        // Kita perlebar menjadi: [230, 330] agar tabel lebih leluasa
        $mpdf = new Mpdf([
            'mode' => 'utf-8', 
            'format' => [230, 330], // [Lebar, Tinggi] dalam mm
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        $nomorSurat = $sp2a->nomor_sp2a ? str_replace('/', '-', $sp2a->nomor_sp2a) : 'DRAFT';
        $fileName = 'SP2A_' . $nomorSurat . '.pdf';

        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
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