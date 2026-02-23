<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripStop;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TripStopController extends Controller
{
    public function create(Request $request): View
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip);

        return view('trip-stops.create', compact('trip'));
    }

    public function store(Request $request): RedirectResponse
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip_id);

        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'location' => ['required', 'string', 'max:255'],
            'stopped_at' => ['required', 'date'],
            'left_at' => ['nullable', 'date', 'after_or_equal:stopped_at'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        TripStop::create($validated);

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Duraklama eklendi.');
    }

    public function destroy(TripStop $tripStop): RedirectResponse
    {
        $this->authorize('update', $tripStop->trip);

        $trip = $tripStop->trip;
        $tripStop->delete();

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Duraklama silindi.');
    }
}
