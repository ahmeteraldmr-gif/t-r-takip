<?php

namespace App\Http\Controllers;

use App\Models\FuelExpense;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FuelExpenseController extends Controller
{
    public function create(Request $request): View
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip);

        return view('fuel-expenses.create', compact('trip'));
    }

    public function store(Request $request): RedirectResponse
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip_id);

        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'date' => ['required', 'date'],
            'liters' => ['required', 'numeric', 'min:0'],
            'price_per_liter' => ['required', 'numeric', 'min:0'],
            'liters_used' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['total_amount'] = $validated['liters'] * $validated['price_per_liter'];

        FuelExpense::create($validated);

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Benzin kaydı eklendi.');
    }

    public function edit(FuelExpense $fuelExpense): View
    {
        $this->authorize('update', $fuelExpense);

        return view('fuel-expenses.edit', compact('fuelExpense'));
    }

    public function update(Request $request, FuelExpense $fuelExpense): RedirectResponse
    {
        $this->authorize('update', $fuelExpense);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'liters' => ['required', 'numeric', 'min:0'],
            'price_per_liter' => ['required', 'numeric', 'min:0'],
            'liters_used' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['total_amount'] = $validated['liters'] * $validated['price_per_liter'];

        $fuelExpense->update($validated);

        return redirect()->route('trips.show', $fuelExpense->trip)
            ->with('status', 'Benzin kaydı güncellendi.');
    }

    public function destroy(FuelExpense $fuelExpense): RedirectResponse
    {
        $this->authorize('delete', $fuelExpense);

        $trip = $fuelExpense->trip;
        $fuelExpense->delete();

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Benzin kaydı silindi.');
    }
}
