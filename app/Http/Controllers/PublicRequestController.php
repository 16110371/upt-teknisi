<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request as HttpRequest;

class PublicRequestController extends Controller
{
    public function create()
    {
        return view('public-request', [
            'categories' => Category::all(),
            'locations' => Location::all(),
        ]);
    }

    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'request_date' => 'nullable|date',
            'requester_name' => 'required|string|max:100',
            'requester_contact' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('requests', 'public');
        }

        $validated['status'] = 'Pending';
        $validated['request_date'] = $validated['request_date'] ?? now();

        Request::create($validated);

        return redirect()->route('public-request.create')->with('success', 'Permintaan berhasil dikirim!');
    }
}
