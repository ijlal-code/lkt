<?php

namespace App\Http\Controllers;

use App\Models\Ltk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan import ini ada

class LtkController extends Controller
{
    /**
     * Menampilkan daftar LKT
     */
    public function index()
    {
        // Mengambil data terbaru
        $ltks = Ltk::latest()->get(); 
        return view('ltk.index', compact('ltks'));
    }

    /**
     * Menampilkan Form Pembuatan LKT
     */
    public function create()
    {
        return view('ltk.create');
    }

    /**
     * Menyimpan Data LKT ke Database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        // Field 'required' wajib diisi user, 'nullable' boleh dikosongkan.
        $validatedData = $request->validate([
            // Header Wajib
            'nomor_lkt' => 'required|unique:ltks,nomor_lkt',
            'tanggal' => 'required|date',
            'kepada' => 'required|string',
            'unit_kerja' => 'required|string',
            
            // Konten Utama Wajib
            'ketidaksesuaian' => 'required|string',
            'akar_penyebab' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
            'target_penyelesaian' => 'required|date',

            // Field Tambahan (Boleh Kosong / Nullable)
            'penerbit_1' => 'nullable|string',
            'penerbit_2' => 'nullable|string',
            'penerbit_3' => 'nullable|string',
            
            'sumber' => 'nullable|string',
            'sumber_lainnya_text' => 'nullable|string',
            
            'lokasi' => 'nullable|string',
            'bukti_objektif' => 'nullable|string',
            'inisial_auditor' => 'nullable|string',

            // Referensi Standar (ISO dll)
            'iso_9001_klausul' => 'nullable|string',
            'iso_14001_klausul' => 'nullable|string',
            'smk3_elemen' => 'nullable|string',
            'iso_45001_klausul' => 'nullable|string',
            'lab_17025_klausul' => 'nullable|string',
            'iso_50001_klausul' => 'nullable|string',
            'smkp_minerba_elemen' => 'nullable|string',
            'iso_37001_elemen' => 'nullable|string',

            // Footer / Verifikasi
            'auditee_nama' => 'nullable|string',
            'verifikasi_tindakan' => 'nullable|string',
            'status' => 'nullable|string',
            'kategori_temuan' => 'nullable|string',
            'tanggal_verifikasi' => 'nullable|date',
            'dilanjutkan_ke_ltk_no' => 'nullable|string',
        ]);

        // 2. Simpan ke Database
        Ltk::create($validatedData);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('ltk.index')->with('success', 'LKT Berhasil dibuat dan disimpan.');
    }

    /**
     * Download PDF LKT
     */
    public function downloadPDF($id)
    {
        // Cari data berdasarkan ID, jika tidak ketemu akan error 404
        $ltk = Ltk::findOrFail($id);

        // Load View PDF (sesuai nama file di resources/views/ltk/pdf.blade.php)
        $pdf = Pdf::loadView('ltk.pdf', compact('ltk'));

        // Set ukuran kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // Download file dengan nama dinamis (contoh: LKT-2026-001.pdf)
        return $pdf->download('LKT-' . $ltk->nomor_lkt . '.pdf');
    }
}