<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IncidentController extends Controller
{
    public function create(Request $request): View
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip);

        return view('incidents.create', compact('trip'));
    }

    public function store(Request $request): RedirectResponse
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip_id);

        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        Incident::create($validated);

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Sorun kaydı eklendi.');
    }

    public function edit(Incident $incident): View
    {
        $this->authorize('update', $incident);

        return view('incidents.edit', compact('incident'));
    }

    public function update(Request $request, Incident $incident): RedirectResponse
    {
        $this->authorize('update', $incident);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $incident->update($validated);

        return redirect()->route('trips.show', $incident->trip)
            ->with('status', 'Sorun kaydı güncellendi.');
    }

    public function destroy(Incident $incident): RedirectResponse
    {
        $this->authorize('delete', $incident);

        $trip = $incident->trip;
        $incident->delete();

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Sorun kaydı silindi.');
    }
}
