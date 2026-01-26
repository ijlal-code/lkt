<?php

namespace App\Http\Controllers;

use App\Models\Ltk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LtkController extends Controller
{
    /* =====================================================
     * LIST DATA
     * ===================================================== */
    public function index()
    {
        $ltks = Ltk::orderBy('created_at', 'desc')->get();
        return view('ltk.index', compact('ltks'));
    }

    /* =====================================================
     * FORM CREATE
     * ===================================================== */
    public function create()
    {
        return view('ltk.create');
    }

    /* =====================================================
     * SIMPAN DATA
     * ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'LTK_No' => 'required',
            'Kepada' => 'required',
        ]);

        $data = $request->all();

        /* ============================
         * CHECKBOX SUMBER TEMUAN
         * ============================ */
        $sumberCheckboxes = [
            'Sumber_Audit_Internal',
            'Sumber_SMST',
            'Sumber_Komplain_Pelanggan',
            'Sumber_Proses_Perbaikan',
            'Sumber_Tinjauan_Manajemen',
            'Sumber_Lainnya',
        ];

        foreach ($sumberCheckboxes as $item) {
            $data[$item] = $request->has($item) ? 1 : 0;
        }

        /* ============================
         * CHECKBOX REFERENSI STANDAR
         * ============================ */
        $referensiCheckboxes = [
            'Ref_ISO_9001',
            'Ref_ISO_14001',
            'Ref_SMK3',
            'Ref_ISO_45001',
            'Ref_LAB_17025',
            'Ref_ISO_50001',
            'Ref_SMKP_Minerba',
            'Ref_ISO_37001',
        ];

        foreach ($referensiCheckboxes as $item) {
            $data[$item] = $request->has($item) ? 1 : 0;
        }

        Ltk::create($data);

        return redirect()
            ->route('ltk.index')
            ->with('success', 'Laporan Temuan Ketidaksesuaian berhasil disimpan');
    }

    /* =====================================================
     * DETAIL DATA
     * ===================================================== */
    public function show($id)
    {
        $ltk = Ltk::findOrFail($id);
        return view('ltk.show', compact('ltk'));
    }

    /* =====================================================
     * FORM EDIT
     * ===================================================== */
    public function edit($id)
    {
        $ltk = Ltk::findOrFail($id);
        return view('ltk.edit', compact('ltk'));
    }

    /* =====================================================
     * UPDATE DATA
     * ===================================================== */
    public function update(Request $request, $id)
    {
        $ltk = Ltk::findOrFail($id);

        $data = $request->all();

        $checkboxes = [
            // Sumber
            'Sumber_Audit_Internal',
            'Sumber_SMST',
            'Sumber_Komplain_Pelanggan',
            'Sumber_Proses_Perbaikan',
            'Sumber_Tinjauan_Manajemen',
            'Sumber_Lainnya',

            // Referensi
            'Ref_ISO_9001',
            'Ref_ISO_14001',
            'Ref_SMK3',
            'Ref_ISO_45001',
            'Ref_LAB_17025',
            'Ref_ISO_50001',
            'Ref_SMKP_Minerba',
            'Ref_ISO_37001',
        ];

        foreach ($checkboxes as $item) {
            $data[$item] = $request->has($item) ? 1 : 0;
        }

        $ltk->update($data);

        return redirect()
            ->route('ltk.index')
            ->with('success', 'Data LTK berhasil diperbarui');
    }

    /* =====================================================
     * HAPUS DATA
     * ===================================================== */
    public function destroy($id)
    {
        $ltk = Ltk::findOrFail($id);
        $ltk->delete();

        return redirect()
            ->route('ltk.index')
            ->with('success', 'Data LTK berhasil dihapus');
    }

    /* =====================================================
     * DOWNLOAD PDF
     * ===================================================== */
    public function downloadPdf($id)
    {
        $ltk = Ltk::findOrFail($id);

        $pdf = Pdf::loadView('ltk.pdf', compact('ltk'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('LTK-' . $ltk->LTK_No . '.pdf');
    }
}
