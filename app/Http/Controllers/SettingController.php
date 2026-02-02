<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data GM, jika tidak ada buat default
        $gm = Setting::firstOrCreate(
            ['key' => 'gm_name'],
            ['value' => '']
        );
        return view('settings.index', compact('gm'));
    }

    public function update(Request $request)
    {
        $request->validate(['gm_name' => 'required|string']);

        Setting::where('key', 'gm_name')->update(['value' => $request->gm_name]);

        return back()->with('success', 'Nama GM Internal Audit berhasil diperbarui.');
    }
}