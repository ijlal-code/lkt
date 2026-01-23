<?php

namespace App\Http\Controllers;

use App\Models\Ltk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LtkController extends Controller
{
    // Halaman List Data
    public function index()
    {
        $ltks = Ltk::latest()->get();
        return view('ltk.index', compact('ltks'));
    }

    // Halaman Form Tambah
    public function create()
    {
        return view('ltk.create');
    }

    // Simpan Data
    public function store(Request $request)
    {
        // Validasi sederhana (tambahkan sesuai kebutuhan)
        $request->validate([
            'LTK_No' => 'required',
            'Kepada' => 'required',
        ]);

        // Checkbox handling: jika tidak dicentang, form tidak mengirim value, jadi kita set default 0
        $data = $request->all();
        $checkboxes = [
            'Ref_ISO_9001', 'Ref_ISO_14001', 'Ref_SMK3', 'Ref_ISO_45001', 
            'Ref_LAB_17025', 'Ref_ISO_50001', 'Ref_SMKP_Minerba', 'Ref_ISO_37001'
        ];
        
        foreach ($checkboxes as $box) {
            $data[$box] = $request->has($box) ? 1 : 0;
        }

        Ltk::create($data);

        return redirect()->route('ltk.index')->with('success', 'Laporan LTK berhasil dibuat!');
    }

    // Download PDF
    public function downloadPdf($id)
    {
        $ltk = Ltk::findOrFail($id);
        
        $pdf = Pdf::loadView('ltk.pdf', compact('ltk'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('LTK-' . $ltk->LTK_No . '.pdf');
    }
}