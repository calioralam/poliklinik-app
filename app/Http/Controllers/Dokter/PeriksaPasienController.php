<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksa'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store (Request $request)
    {
        $request->validate([
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obats = json_decode($request->obat_json, true);

        // cek stok obat
        foreach ($obats as $obat) {
            $id_obat = $obat['id'];
            $jumlah = $obat['qty'] ?? 1;
            
            $obat_data = Obat::find($id_obat);
            if (!$obat_data || $obat_data->stok < $jumlah) {
                return redirect()->back()->withErrors([
                    'obat' => "Obat '{$obat_data->nama_obat}' stok tidak cukup!"
                ])->withInput();
            }
        }

        $periksa = Periksa::create([
            'id_daftar' => $request->id_daftar,
            'tgl_periksa' => now(),
            'catatan' => $request->catatan,
            'biaya_periksa' => $request->biaya_periksa + 150000, 
        ]);

        foreach ($obats as $obat) {
            $id_obat = $obat['id'];
            $jumlah = $obat['qty'] ?? 1;
            
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat' => $id_obat,
                'jumlah' => $jumlah
            ]);
            
            $obat_data = Obat::find($id_obat);
            $obat_data->stok -= $jumlah;
            $obat_data->save();
        }

        return redirect()->route('periksa-pasien.index')->with('success', 'Data periksa pasien berhasil disimpan.');
    }
}
