<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TruckController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Truck::class);

        $search = $request->input('q');

        $truckQuery = Truck::forUser($request->user());
        if ($search) {
            $truckQuery->where(function ($qry) use ($search) {
                $qry->where('plate', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhereHas('driver', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }
        $trucks = $truckQuery->with(['trips' => fn ($q) => $q->latest('departure_date')->limit(1)])
            ->latest()->paginate(12, ['*'], 'page')->withQueryString();

        $driverQuery = $request->user()->drivers()->withCount('trucks');
        if ($search) {
            $driverQuery->where(function ($qry) use ($search) {
                $qry->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        $drivers = $driverQuery->latest()->paginate(12, ['*'], 'driver_page')->withQueryString();

        return view('trucks.index', compact('trucks', 'drivers'));
    }

    public function create(): View
    {
        $this->authorize('create', Truck::class);

        $drivers = request()->user()->drivers()->orderBy('name')->get();

        return view('trucks.create', compact('drivers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Truck::class);

        $request->merge(['driver_id' => $request->input('driver_id') ?: null]);

        $validated = $request->validate([
            'plate' => ['required', 'string', 'max:20'],
            'ruhsat_no' => ['required', 'string', 'max:50'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'status' => ['required', 'in:aktif,bakımda,satıldı,kiralık,devre_dışı'],
        ]);

        $request->user()->trucks()->create($validated);

        return redirect()->route('trucks.index')
            ->with('status', 'Tır başarıyla eklendi.');
    }

    public function show(Truck $truck): View
    {
        $this->authorize('view', $truck);

        $truck->load(['trips.incidents', 'documents', 'tires']);

        return view('trucks.show', compact('truck'));
    }

    public function edit(Truck $truck): View
    {
        $this->authorize('update', $truck);

        $drivers = request()->user()->drivers()->orderBy('name')->get();

        return view('trucks.edit', compact('truck', 'drivers'));
    }

    public function update(Request $request, Truck $truck): RedirectResponse
    {
        $this->authorize('update', $truck);

        $request->merge(['driver_id' => $request->input('driver_id') ?: null]);

        $validated = $request->validate([
            'plate' => ['required', 'string', 'max:20'],
            'ruhsat_no' => ['nullable', 'string', 'max:50'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'status' => ['required', 'in:aktif,bakımda,satıldı,kiralık,devre_dışı'],
        ]);

        $truck->update($validated);

        return redirect()->route('trucks.index')
            ->with('status', 'Tır başarıyla güncellendi.');
    }

    public function destroy(Truck $truck): RedirectResponse
    {
        $this->authorize('delete', $truck);

        $truck->delete();

        return redirect()->route('trucks.index')
            ->with('status', 'Tır başarıyla silindi.');
    }
}
