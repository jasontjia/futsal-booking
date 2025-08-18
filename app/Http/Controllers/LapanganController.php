<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

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
            'harga_per_jam' => 'required|numeric|min:0',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10000',
        ]);

        // Normalize harga_per_jam
        $validated['harga_per_jam'] = (float) str_replace(',', '.', $validated['harga_per_jam']);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('lapangans', 'public');
        }

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
            'lapangan' => [
                'id' => $lapangan->id,
                'nama' => $lapangan->nama ?? '',
                'harga_per_jam' => (string) $lapangan->harga_per_jam ?? '',
                'jam_buka' => $lapangan->jam_buka ?? '08:00',
                'jam_tutup' => $lapangan->jam_tutup ?? '22:00',
                'foto' => $lapangan->foto ?? null,
            ]
        ]);
    }

    /**
     * Mengupdate data lapangan.
     */
   public function update(Request $request, $id)
{
    $lapangan = Lapangan::findOrFail($id);

    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'harga_per_jam' => 'required|numeric|min:0',
        'jam_buka' => 'required',
        'jam_tutup' => 'required',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:10000',
        'remove_foto' => 'nullable|boolean',
    ]);

    $validated['harga_per_jam'] = (float) str_replace(',', '.', $validated['harga_per_jam']);

    // Hapus foto lama kalau ada flag remove
    if ($request->remove_foto && $lapangan->foto && Storage::disk('public')->exists($lapangan->foto)) {
        Storage::disk('public')->delete($lapangan->foto);
        $lapangan->foto = null;
    }

    // Simpan file baru jika ada
    if ($request->hasFile('foto')) {
        if ($lapangan->foto && Storage::disk('public')->exists($lapangan->foto)) {
            Storage::disk('public')->delete($lapangan->foto);
        }
        $lapangan->foto = $request->file('foto')->store('lapangans', 'public');
    }

    $lapangan->update([
        'nama' => $validated['nama'],
        'harga_per_jam' => $validated['harga_per_jam'],
        'jam_buka' => $validated['jam_buka'],
        'jam_tutup' => $validated['jam_tutup'],
        'foto' => $lapangan->foto,
    ]);

    return redirect()->route('lapangan.index')->with('success', 'Lapangan berhasil diupdate!');
}


    /**
     * Menghapus data lapangan.
     */
    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        if ($lapangan->foto && Storage::disk('public')->exists($lapangan->foto)) {
            Storage::disk('public')->delete($lapangan->foto);
        }

        $lapangan->delete();

        return redirect()->route('lapangan.index')
                         ->with('success', 'Lapangan berhasil dihapus.');
    }
}
