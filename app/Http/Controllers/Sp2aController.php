<?php

namespace App\Http\Controllers;

use App\Models\Sp2a;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Sp2aController extends Controller
{
    /**
     * Menampilkan daftar SP2A
     */
    public function index()
    {
        // Mengambil data urut dari yang terbaru
        $sp2as = Sp2a::latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    /**
     * Form Buat SP2A (Hanya untuk Staff/Admin)
     */
    public function create()
    {
        // Mengambil data user untuk dropdown tujuan & CC
        $audities = User::where('role', 'auditi')->get();
        $auditors = User::where('role', 'auditor')->get();
        $k3s      = User::where('role', 'k3')->get();
        $staffs   = User::where('role', 'staff')->get();
        $atasans  = User::where('role', 'atasan_staff')->get();

        return view('sp2a.create', compact('audities', 'auditors', 'k3s', 'staffs', 'atasans'));
    }

    /**
     * Simpan SP2A Baru (Staff) -> Masuk ke Tahap SM
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'kepada_user_id' => 'required|exists:users,id',
            'dari_nama' => 'required|string',
            'perihal' => 'required|string',
            'dasar_surat' => 'required|string',
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
            'dasar_surat'   => $request->dasar_surat,
            'isi_surat'     => $request->isi_surat,
            'penanda_tangan_nama' => $request->penanda_tangan_nama,
            
            // Email Tembusan (CC)
            'email_auditor' => $request->email_auditor,
            'email_k3'      => $request->email_k3,
            'email_staff'   => $request->email_staff,
            'email_atasan'  => $request->email_atasan,

            // --- SETTING WORKFLOW AWAL ---
            'nomor_sp2a' => null, // Belum ada nomor
            'current_step' => 'sm', // Langsung kirim ke SM
            'status' => 'Menunggu Approval SM', 
            'catatan_koreksi' => null,
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil dibuat dan dikirim ke Senior Manager (SM).');
    }

    /**
     * Halaman Detail (Show)
     * Digunakan untuk Review Dokumen sebelum Approve/Koreksi
     */
    public function show($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        return view('sp2a.show', compact('sp2a'));
    }

    /**
     * Halaman Edit (Hanya bisa diakses jika status dikembalikan/koreksi ke Staff)
     */
    public function edit($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        // Validasi: Hanya bisa edit jika step == 'staff'
        if ($sp2a->current_step != 'staff') {
            return back()->with('error', 'Dokumen sedang dalam proses approval dan tidak dapat diedit.');
        }

        // Data untuk dropdown (sama seperti create)
        $audities = User::where('role', 'auditi')->get();
        $auditors = User::where('role', 'auditor')->get();
        $k3s      = User::where('role', 'k3')->get();
        $staffs   = User::where('role', 'staff')->get();
        $atasans  = User::where('role', 'atasan_staff')->get();

        // Kita bisa menggunakan view create dengan mengirimkan data $sp2a
        // Pastikan di view create.blade.php Anda menangani pengisian value="" dengan old() atau $sp2a->field
        return view('sp2a.create', compact('sp2a', 'audities', 'auditors', 'k3s', 'staffs', 'atasans'));
    }

    /**
     * Proses Update Revisi oleh Staff
     * Mengembalikan status flow ke SM
     */
    public function update(Request $request, $id)
    {
        $sp2a = Sp2a::findOrFail($id);

        if ($sp2a->current_step != 'staff') {
            return back()->with('error', 'Tidak dapat mengubah dokumen yang sedang diproses.');
        }

        $request->validate([
            'tanggal_surat' => 'required|date',
            'isi_surat' => 'required',
            'perihal' => 'required',
            // Validasi field lain sesuai kebutuhan
        ]);

        // Persiapan Data Update
        $auditi = User::find($request->kepada_user_id); // Jika user diganti
        
        $data = [
            'tanggal_surat' => $request->tanggal_surat,
            'dari_nama' => $request->dari_nama,
            'perihal' => $request->perihal,
            'dasar_surat' => $request->dasar_surat,
            'isi_surat' => $request->isi_surat,
            'penanda_tangan_nama' => $request->penanda_tangan_nama,
            'email_auditor' => $request->email_auditor,
            'email_k3' => $request->email_k3,
            'email_staff' => $request->email_staff,
            'email_atasan' => $request->email_atasan,
            
            // Jika penerima diganti
            'kepada_nama' => $auditi ? $auditi->name : $sp2a->kepada_nama,
            'kepada_email' => $auditi ? $auditi->email : $sp2a->kepada_email,

            // RESET WORKFLOW
            'current_step' => 'sm', // Kembali ke SM
            'status' => 'Revisi Terkirim (Menunggu Approval SM)',
            'catatan_koreksi' => null, // Hapus catatan koreksi lama

            // Reset Timestamp Approval sebelumnya (agar approver harus approve ulang)
            'approved_sm_at' => null,
            'approved_smqa_at' => null,
            'approved_gm_at' => null,
        ];

        $sp2a->update($data);

        return redirect()->route('sp2a.show', $id)->with('success', 'Revisi berhasil dikirim kembali ke SM.');
    }

    /**
     * Logic Approval Berjenjang (SM -> SMQA -> GM)
     */
    public function approve($id)
    {
        $sp2a = Sp2a::findOrFail($id);
        $userRole = Auth::user()->role; 

        // 1. TAHAP SM (Manager) -> Ke SM QA
        if ($sp2a->current_step == 'sm' && $userRole == 'sm') {
            $sp2a->update([
                'current_step' => 'smqa',
                'status' => 'Menunggu Approval SM QA',
                'approved_sm_at' => now(),
            ]);
            return back()->with('success', 'Berhasil disetujui. Dokumen diteruskan ke SM QA.');
        }

        // 2. TAHAP SM QA (Pak Chandra) -> Ke GM
        if ($sp2a->current_step == 'smqa' && $userRole == 'smqa') {
            $sp2a->update([
                'current_step' => 'gm',
                'status' => 'Menunggu Approval GM Internal Audit',
                'approved_smqa_at' => now(),
            ]);
            return back()->with('success', 'Berhasil disetujui. Dokumen diteruskan ke GM Internal Audit.');
        }

        // 3. TAHAP GM (Final) -> Finish & Generate Nomor
        if ($sp2a->current_step == 'gm' && $userRole == 'gm') {
            
            // Generate Nomor Surat Otomatis
            // Format: SP2A/001/INTERNAL/I/2026
            $bulanRomawi = $this->getRomawi($sp2a->tanggal_surat->format('n'));
            $tahun = $sp2a->tanggal_surat->format('Y');
            $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT);
            
            $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$bulanRomawi}/{$tahun}";

            $sp2a->update([
                'current_step' => 'finished',
                'status' => 'Approved By System',
                'approved_gm_at' => now(),
                'nomor_sp2a' => $nomorSurat, // Nomor disematkan di sini
            ]);

            return back()->with('success', "Dokumen Final! Nomor $nomorSurat telah diterbitkan.");
        }

        return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui tahap ini atau dokumen sudah diproses.');
    }

    /**
     * Logic Koreksi (Kembalikan ke Staff)
     * Bisa dilakukan oleh SM, SMQA, atau GM
     */
    public function koreksi(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string'
        ]);
        
        $sp2a = Sp2a::findOrFail($id);
        
        // Siapapun yang melakukan koreksi (SM, SMQA, GM), kembalikan ke STAFF
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
     * Helper: Konversi Angka Bulan ke Romawi
     */
    private function getRomawi($n) {
        $map = [
            1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 
            7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'
        ];
        return $map[$n] ?? 'I';
    }
}