<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index() {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request) {
        $request->validate(['nama'=>'required', 'email'=>'required|email|unique:contacts']);
        Contact::create($request->all());
        return back()->with('success', 'Kontak berhasil disimpan.');
    }
    
    public function destroy(Contact $contact) {
        $contact->delete();
        return back()->with('success', 'Kontak dihapus.');
    }
}
