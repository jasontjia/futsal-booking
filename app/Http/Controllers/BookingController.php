<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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
            'bookings' => $bookings,
            'csrf_token' => csrf_token(),
        ]);
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

        // Cek tabrakan booking
        $isBooked = Booking::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal', $request->tanggal)
            ->where(function($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('jam_mulai', '<=', $request->jam_mulai)
                         ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })
            ->exists();

        if ($isBooked) {
            return back()->withErrors(['jam_mulai' => 'Waktu sudah dipesan'])->withInput();
        }

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

    public function uploadBukti(Request $request, Booking $booking)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti', 'public');

        $booking->update([
            'bukti_pembayaran' => $path,
            'status' => 'menunggu_verifikasi'
        ]);

        return redirect()->route('booking.index')->with('success', 'Bukti pembayaran diupload');
    }

    // ================= ADMIN =================

    public function verifikasi(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak'
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Booking diverifikasi');
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
}
