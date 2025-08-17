<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LapanganController extends Controller
{
    /**
     * Menampilkan daftar semua lapangan.
     */
    public function index()
    {
        $lapangans = Lapangan::all();

        return Inertia::render('Lapangan/Index', [
            'lapangans' => $lapangans
        ]);
    }

    /**
     * Menampilkan form tambah lapangan.
     */
    public function create()
    {
        return Inertia::render('Lapangan/Create');
    }

    /**
     * Menyimpan data lapangan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga_per_jam' => 'required|numeric',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        Lapangan::create($validated);

        return redirect()->route('lapangan.index')
                         ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit lapangan.
     */
    public function edit($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        return Inertia::render('Lapangan/Edit', [
            'lapangan' => $lapangan
        ]);
    }

    /**
     * Mengupdate data lapangan.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga_per_jam' => 'required|numeric',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        $lapangan = Lapangan::findOrFail($id);
        $lapangan->update($validated);

        return redirect()->route('lapangan.index')
                         ->with('success', 'Lapangan berhasil diperbarui.');
    }

    /**
     * Menghapus data lapangan.
     */
    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $lapangan->delete();

        return redirect()->route('lapangan.index')
                         ->with('success', 'Lapangan berhasil dihapus.');
    }
}
