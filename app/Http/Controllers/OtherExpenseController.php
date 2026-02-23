<?php

namespace App\Http\Controllers;

use App\Models\OtherExpense;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OtherExpenseController extends Controller
{
    public function create(Request $request): View
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip);

        return view('other-expenses.create', compact('trip'));
    }

    public function store(Request $request): RedirectResponse
    {
        $trip = Trip::forUser($request->user())
            ->findOrFail($request->trip_id);

        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        OtherExpense::create($validated);

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Masraf eklendi.');
    }

    public function edit(OtherExpense $otherExpense): View
    {
        $this->authorize('update', $otherExpense);

        return view('other-expenses.edit', compact('otherExpense'));
    }

    public function update(Request $request, OtherExpense $otherExpense): RedirectResponse
    {
        $this->authorize('update', $otherExpense);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $otherExpense->update($validated);

        return redirect()->route('trips.show', $otherExpense->trip)
            ->with('status', 'Masraf güncellendi.');
    }

    public function destroy(OtherExpense $otherExpense): RedirectResponse
    {
        $this->authorize('delete', $otherExpense);

        $trip = $otherExpense->trip;
        $otherExpense->delete();

        return redirect()->route('trips.show', $trip)
            ->with('status', 'Masraf silindi.');
    }
}
