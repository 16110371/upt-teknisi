<?php

namespace App\Http\Controllers;

use App\Models\Request;

class QueueController extends Controller
{
    public function index()
    {
        $requests = Request::with(['category', 'location'])
            ->whereIn('status', ['Pending', 'Proses'])
            ->orderBy('request_date', 'desc')
            ->get();

        return view('public-queue', compact('requests'));
    }
}
