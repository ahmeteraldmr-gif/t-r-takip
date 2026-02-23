<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $trucks = Truck::forUser($request->user())
            ->with(['maintenances' => fn ($q) => $q->whereNull('last_done_date')->orderBy('due_date')])
            ->orderBy('plate')
            ->get();

        $upcoming = Maintenance::forUser($request->user())
            ->whereNull('last_done_date')
            ->where('due_date', '>=', now())
            ->with('truck')
            ->orderBy('due_date')
            ->take(10)
            ->get();

        $overdue = Maintenance::forUser($request->user())
            ->whereNull('last_done_date')
            ->where('due_date', '<', now())
            ->with('truck')
            ->orderBy('due_date')
            ->get();

        return view('maintenances.index', compact('trucks', 'upcoming', 'overdue'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $trucks = Truck::forUser($request->user())->orderBy('plate')->get();

        if ($trucks->isEmpty()) {
            return redirect()->route('trucks.index')->with('status', 'Önce en az bir tır eklemeniz gerekiyor.');
        }

        $truck = null;
        if ($request->has('truck')) {
            $truck = Truck::forUser($request->user())->findOrFail($request->truck);
        }

        return view('maintenances.create', compact('trucks', 'truck'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'truck_id' => ['required', 'exists:trucks,id'],
            'type' => ['required', 'string', 'in:yağ_değişimi,fren,muayene,sigorta,kasko,lastik,diğer'],
            'due_date' => ['required', 'date'],
            'last_done_date' => ['nullable', 'date'],
            'last_done_km' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $truck = Truck::findOrFail($validated['truck_id']);
        $this->authorize('update', $truck);

        Maintenance::create($validated);

        return redirect()->route('maintenances.index')
            ->with('status', 'Bakım hatırlatması eklendi.');
    }

    public function edit(Maintenance $maintenance): View
    {
        $this->authorize('update', $maintenance->truck);

        $trucks = Truck::forUser(request()->user())->orderBy('plate')->get();

        return view('maintenances.edit', compact('maintenance', 'trucks'));
    }

    public function update(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $this->authorize('update', $maintenance->truck);

        $validated = $request->validate([
            'truck_id' => ['required', 'exists:trucks,id'],
            'type' => ['required', 'string', 'in:yağ_değişimi,fren,muayene,sigorta,kasko,lastik,diğer'],
            'due_date' => ['required', 'date'],
            'last_done_date' => ['nullable', 'date'],
            'last_done_km' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $truck = Truck::findOrFail($validated['truck_id']);
        $this->authorize('update', $truck);

        $maintenance->update($validated);

        return redirect()->route('maintenances.index')
            ->with('status', 'Bakım hatırlatması güncellendi.');
    }

    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        $this->authorize('update', $maintenance->truck);

        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('status', 'Bakım hatırlatması silindi.');
    }

    public function markDone(Maintenance $maintenance, Request $request): RedirectResponse
    {
        $this->authorize('update', $maintenance->truck);

        $maintenance->update([
            'last_done_date' => $request->input('last_done_date', now()->format('Y-m-d')),
            'last_done_km' => $request->input('last_done_km'),
        ]);

        return redirect()->route('maintenances.index')
            ->with('status', 'Bakım yapıldı olarak işaretlendi.');
    }
}
