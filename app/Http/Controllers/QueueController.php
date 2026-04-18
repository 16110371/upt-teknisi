<?php

namespace App\Http\Controllers;

use App\Models\Request;

class QueueController extends Controller
{
    public function index()
    {
        $requests = Request::with(['category', 'location', 'infrastructure', 'technician'])
            ->whereIn('status', ['Pending', 'Dikerjakan', 'Menunggu Part'])
            ->orderByRaw("FIELD(priority, 'Tinggi', 'Sedang', 'Rendah')")
            ->latest()
            ->get();

        return view('public-queue', compact('requests'));
    }
}
