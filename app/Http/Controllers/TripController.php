<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TripController extends Controller
{
    public function index(Request $request): View
    {
        $query = Trip::forUser($request->user())->with('truck');
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($qry) use ($search) {
                $qry->where('destination', 'like', "%{$search}%")
                    ->orWhere('origin', 'like', "%{$search}%")
                    ->orWhereHas('truck', fn ($t) => $t->where('plate', 'like', "%{$search}%"));
            });
        }
        $trips = $query->with(['truck', 'fuelExpenses', 'otherExpenses', 'incidents'])
            ->latest('departure_date')->paginate(12)->withQueryString();

        return view('trips.index', compact('trips'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $trucks = Truck::forUser($request->user())->orderBy('plate')->get();

        if ($trucks->isEmpty()) {
            return redirect()->route($request->user()->isPatron() ? 'trucks.create' : 'dashboard')
                ->with('status', $request->user()->isPatron() ? 'Önce en az bir tır eklemeniz gerekiyor.' : 'Size atanmış tır bulunmuyor. Patron ile iletişime geçin.');
        }

        $truck = null;
        if ($request->has('truck')) {
            $truck = Truck::forUser($request->user())->findOrFail($request->truck);
        }
        $customers = $request->user()->isPatron() ? $request->user()->customers()->orderBy('name')->get() : collect();

        return view('trips.create', compact('trucks', 'truck', 'customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'truck_id' => ['required', 'exists:trucks,id'],
            'departure_date' => ['required', 'date'],
            'origin' => ['nullable', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'stopovers' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:planned,in_progress,completed,cancelled'],
            'commission_amount' => ['nullable', 'numeric', 'min:0'],
            'revenue_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['nullable', 'string', 'in:bekliyor,tahsil_edildi,kismi'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'days_stayed' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'start_km' => ['nullable', 'integer', 'min:0'],
            'end_km' => ['nullable', 'integer', 'min:0'],
            'cargo_type' => ['nullable', 'string', 'max:50'],
            'load_weight' => ['nullable', 'numeric', 'min:0'],
            'loading_date' => ['nullable', 'date'],
            'unloading_date' => ['nullable', 'date'],
            'receiver_name' => ['nullable', 'string', 'max:255'],
        ]);

        $truck = Truck::findOrFail($validated['truck_id']);
        $this->authorize('view', $truck);

        $validated['commission_amount'] = $validated['commission_amount'] ?? 0;
        $stopStr = isset($validated['stopovers']) ? trim($validated['stopovers']) : '';
        $validated['stopovers'] = $stopStr !== ''
            ? array_values(array_filter(array_map('trim', explode(',', $stopStr))))
            : null;

        Trip::create($validated);

        return redirect()->route('trips.index')
            ->with('status', 'Sefer başarıyla eklendi.');
    }

    public function show(Trip $trip): View
    {
        $this->authorize('view', $trip);

        $trip->load(['fuelExpenses', 'otherExpenses', 'incidents', 'tripStops', 'truck', 'customer']);

        return view('trips.show', compact('trip'));
    }

    public function edit(Trip $trip): View
    {
        $this->authorize('update', $trip);

        $trucks = Truck::forUser(request()->user())->orderBy('plate')->get();
        $customers = request()->user()->isPatron() ? request()->user()->customers()->orderBy('name')->get() : collect();

        return view('trips.edit', compact('trip', 'trucks', 'customers'));
    }

    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'truck_id' => ['required', 'exists:trucks,id'],
            'departure_date' => ['required', 'date'],
            'origin' => ['nullable', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'stopovers' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:planned,in_progress,completed,cancelled'],
            'commission_amount' => ['nullable', 'numeric', 'min:0'],
            'revenue_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['nullable', 'string', 'in:bekliyor,tahsil_edildi,kismi'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'days_stayed' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'start_km' => ['nullable', 'integer', 'min:0'],
            'end_km' => ['nullable', 'integer', 'min:0'],
            'cargo_type' => ['nullable', 'string', 'max:50'],
            'load_weight' => ['nullable', 'numeric', 'min:0'],
            'loading_date' => ['nullable', 'date'],
            'unloading_date' => ['nullable', 'date'],
            'receiver_name' => ['nullable', 'string', 'max:255'],
        ]);

        $truck = Truck::findOrFail($validated['truck_id']);
        $this->authorize('view', $truck);

        $validated['commission_amount'] = $validated['commission_amount'] ?? 0;
        $stopStr = isset($validated['stopovers']) ? trim($validated['stopovers']) : '';
        $validated['stopovers'] = $stopStr !== ''
            ? array_values(array_filter(array_map('trim', explode(',', $stopStr))))
            : null;

        $trip->update($validated);

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Sefer başarıyla güncellendi.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorize('delete', $trip);

        $truckId = $trip->truck_id;
        $trip->delete();

        return redirect()->route('trucks.show', $truckId)
            ->with('status', 'Sefer başarıyla silindi.');
    }

    public function start(Trip $trip): RedirectResponse
    {
        $this->authorize('update', $trip);

        if ($trip->status !== 'planned' && $trip->status !== 'cancelled') {
            return redirect()->route('trips.show', $trip)->with('status', 'Bu sefer zaten başlamış veya bitmiş.');
        }

        $trip->update([
            'status' => 'in_progress',
            'started_at' => $trip->started_at ?? now(),
        ]);

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Sefere başlandı. Sayacı takip edebilirsiniz.');
    }

    public function end(Trip $trip): RedirectResponse
    {
        $this->authorize('update', $trip);

        if ($trip->status !== 'in_progress') {
            return redirect()->route('trips.show', $trip)->with('status', 'Sadece devam eden seferler bitirilebilir.');
        }

        $trip->update([
            'status' => 'completed',
            'ended_at' => now(),
            'started_at' => $trip->started_at ?? now(),
        ]);

        $duration = $trip->duration_display;

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Sefer bitti. Süre: ' . ($duration ?? '-'));
    }
}
