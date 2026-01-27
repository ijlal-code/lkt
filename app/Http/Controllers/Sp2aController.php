<?php

namespace App\Http\Controllers;

    
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sp2a;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sp2aNotification; // Pastikan Mailable ini sudah ada (seperti contoh sebelumnya)

class Sp2aController extends Controller
{
    public function index()
    {
        $sp2as = Sp2a::latest()->get();
        return view('sp2a.index', compact('sp2as'));
    }

    public function create()
    {
        // Kirim data kontak untuk dropdown
        $contacts = Contact::all();
        return view('sp2a.create', compact('contacts'));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'tanggal_surat' => 'required|date',
            'kepada_id' => 'required', // ID dari dropdown kontak
            'dasar_surat' => 'required',
            'isi_surat' => 'required',
        ]);

        // Ambil detail kontak berdasarkan ID yang dipilih
        $auditee = Contact::findOrFail($request->kepada_id);
        $auditor = $request->cc_id ? Contact::find($request->cc_id) : null;

        Sp2a::create([
            'tanggal_surat' => $request->tanggal_surat,
            'kepada_nama' => $auditee->nama,
            'kepada_email' => $auditee->email,
            'cc_nama' => $auditor ? $auditor->nama : null,
            'cc_email' => $auditor ? $auditor->email : null,
            'dasar_surat' => $request->dasar_surat,
            'isi_surat' => $request->isi_surat,
            'perihal' => $request->perihal ?? 'Surat Peringatan 2A',
        ]);

        return redirect()->route('sp2a.index')->with('success', 'Draft SP2A berhasil dibuat.');
    }



    public function approve($id)
    {
        $sp2a = Sp2a::findOrFail($id);

        if ($sp2a->status === 'Approved') {
            return back()->with('error', 'Sudah diapprove sebelumnya.');
        }

        // 1. Generate Nomor Surat
        $bulanRomawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $noUrut = str_pad($sp2a->id, 3, '0', STR_PAD_LEFT);
        $nomorSurat = "SP2A/{$noUrut}/INTERNAL/{$bulanRomawi}/{$tahun}";

        // 2. Update Database
        $sp2a->update([
            'nomor_sp2a' => $nomorSurat,
            'status' => 'Approved',
            'approved_at' => now(),
        ]);

        // 3. GENERATE PDF (Tanpa disimpan ke file, langsung ke memori)
        // Pastikan Anda sudah install: composer require barryvdh/laravel-dompdf
        $pdf = Pdf::loadView('sp2a.pdf', compact('sp2a'));
        $pdfContent = $pdf->output(); // Ambil data biner PDF

        // 4. KIRIM EMAIL + ATTACHMENT
        try {
            $mail = Mail::to($sp2a->kepada_email);
            
            // CC jika ada
            if ($sp2a->cc_email) {
                $mail->cc($sp2a->cc_email);
            }

            // Kirim Email dengan membawa data PDF
            $mail->send(new Sp2aNotification($sp2a, $pdfContent));

        } catch (\Exception $e) {
            return back()->with('error', 'SP2A Approved, tapi GAGAL kirim email. Cek koneksi internet/SMTP. Error: ' . $e->getMessage());
        }

        return back()->with('success', "Sukses! SP2A diterbitkan ($nomorSurat) dan file PDF telah dikirim ke email.");
    }

    private function getRomawi($n) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 
                7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$n];
    }
}