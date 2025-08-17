<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::all();
        return Inertia::render('Public/Home', [
            'lapangans' => $lapangans
        ]);
    }

    public function jadwal(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        $lapangans = Lapangan::with(['bookings' => function($q) use ($tanggal) {
            $q->where('tanggal', $tanggal);
        }])->get();

        return Inertia::render('Public/Jadwal', [
            'lapangans' => $lapangans,
            'tanggal' => $tanggal
        ]);
    }
}

