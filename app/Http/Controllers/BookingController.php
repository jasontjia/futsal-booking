<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BookingController extends Controller
{
    // ================= USER =================
    public function index()
    {
        $bookings = Booking::with('lapangan')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Booking/Index', [
            'bookings' => $bookings
        ]);
    }

    public function verifikasi(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
        ]);

        $booking->status = $request->status;
        $booking->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Status booking berhasil diperbarui.');
    }


    public function create()
    {
        $lapangans = Lapangan::all();
        return Inertia::render('Booking/Create', [
            'lapangans' => $lapangans
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $request->lapangan_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'menunggu_pembayaran',
        ]);

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat');
    }

    public function edit(Booking $booking)
    {
        $lapangans = Lapangan::all();
        return Inertia::render('Booking/Edit', [
            'booking' => $booking->load('lapangan'),
            'lapangans' => $lapangans
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $booking->lapangan_id = $request->lapangan_id;
        $booking->tanggal = $request->tanggal;
        $booking->jam_mulai = $request->jam_mulai;
        $booking->jam_selesai = $request->jam_selesai;

        // Handle upload bukti pembayaran ke disk 'public'
        if ($request->hasFile('bukti_pembayaran')) {
            if ($booking->bukti_pembayaran && Storage::disk('public')->exists($booking->bukti_pembayaran)) {
                Storage::disk('public')->delete($booking->bukti_pembayaran);
            }
            $booking->bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti', 'public');
            $booking->status = 'menunggu_verifikasi';
        }

        $booking->save();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil diupdate');
    }

    public function destroy(Booking $booking)
    {
        // Pastikan user hanya bisa menghapus booking miliknya sendiri
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Hapus file bukti pembayaran dari storage jika ada
        if ($booking->bukti_pembayaran && Storage::disk('public')->exists($booking->bukti_pembayaran)) {
            Storage::disk('public')->delete($booking->bukti_pembayaran);
        }

        // Hapus booking dari database
        $booking->delete();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus');
    }
     public function adminDashboard()
    {
        // Cek user admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $bookings = Booking::with(['user', 'lapangan'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Index', [
            'bookings' => $bookings
        ]);
    }

    public function uploadBukti(Request $request, Booking $booking)
{
    $request->validate([
        'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // Hapus file lama jika ada
    if ($booking->bukti_pembayaran && Storage::disk('public')->exists($booking->bukti_pembayaran)) {
        Storage::disk('public')->delete($booking->bukti_pembayaran);
    }

    // Simpan file baru
    $booking->bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti', 'public');
    $booking->status = 'menunggu_verifikasi';
    $booking->save();

    return redirect()->route('booking.index')->with('success', 'Bukti pembayaran berhasil diunggah');
}

}
