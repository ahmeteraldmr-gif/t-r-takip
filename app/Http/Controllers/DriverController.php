<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DriverController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Driver::class);

        $query = $request->user()->drivers();
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($qry) use ($search) {
                $qry->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        $drivers = $query->withCount('trucks')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('drivers.index', compact('drivers'));
    }

    public function create(): View
    {
        $this->authorize('create', Driver::class);
        return view('drivers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Driver::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->drivers()->create($validated);

        return redirect()->route('drivers.index')
            ->with('status', 'Şoför başarıyla eklendi.');
    }

    public function show(Driver $driver): View
    {
        $this->authorize('view', $driver);

        $driver->load(['trucks.trips']);

        $tripsThisMonth = Trip::whereHas('truck', fn ($q) => $q->where('driver_id', $driver->id))
            ->whereMonth('departure_date', now()->month)
            ->whereYear('departure_date', now()->year)
            ->count();

        $tripsLastMonth = Trip::whereHas('truck', fn ($q) => $q->where('driver_id', $driver->id))
            ->whereMonth('departure_date', now()->subMonth()->month)
            ->whereYear('departure_date', now()->subMonth()->year)
            ->count();

        $totalTrips = Trip::whereHas('truck', fn ($q) => $q->where('driver_id', $driver->id))->count();

        return view('drivers.show', compact('driver', 'tripsThisMonth', 'tripsLastMonth', 'totalTrips'));
    }

    public function edit(Driver $driver): View
    {
        $this->authorize('update', $driver);
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $this->authorize('update', $driver);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $driver->update($validated);

        return redirect()->route('drivers.show', $driver)
            ->with('status', 'Şoför başarıyla güncellendi.');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        $this->authorize('delete', $driver);

        $driver->trucks()->update(['driver_id' => null]);
        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('status', 'Şoför başarıyla silindi.');
    }
}
