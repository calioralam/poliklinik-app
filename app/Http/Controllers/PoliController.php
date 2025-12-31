<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use PDO;

class PoliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polis = Poli::all(); // mengambil semua data poli dari database
        return view('admin.polis.index', compact('polis')); // menampilkan view dengan data poli
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.polis.create'); // menampilkan form pembuatan poli baru
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // memvalidasi data inputan
        $validated = $request->validate([
            'nama_poli' => 'required',
            'keterangan' => 'nullable',
        ]);

        // menyimpan data ke database
        Poli::create($validated);

        // redirect ke halaman index dengan pesan sukses
        return redirect()->route('polis.index')
        ->with('success', 'Poli berhasil di tambahkan')
        ->with('type', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $poli = Poli::findOrFail($id); // mencari poli berdasarkan id
        return view('admin.polis.edit', compact('poli')); // menampilkan form edit dengan data poli
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // memvalidasi data input sama seperti store
        $validated = $request->validate([
            'nama_poli' => 'required',
            'keterangan' => 'nullable',
        ]);

        // mencari poli berdasarkan id dan mengupdate datanya
        $poli = Poli::findOrFail($id);
        $poli->update($validated);

        // redirect ke halaman index dengan pesan sukses
        return redirect()->route('polis.index')->with('success', 'Polis berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // mencari data berdasarkan id dan menghapusnya
        $poli = Poli::findOrFail($id);
        $poli->delete();
        return redirect()->route('polis.index')->with('success', 'Poli Berhasil di hapus !');
    }
}
