<?php

namespace App\Http\Controllers;

use App\Models\Tire;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TireController extends Controller
{
    public function create(Request $request): View
    {
        $truck = Truck::where('user_id', $request->user()->id)->findOrFail($request->truck);
        return view('tires.create', compact('truck'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'truck_id' => ['required', 'exists:trucks,id'],
            'position' => ['required', 'string', 'in:on_sol,on_sag,arka_1,arka_2,arka_3,arka_4,yedek,diger'],
            'change_km' => ['nullable', 'integer', 'min:0'],
            'change_date' => ['nullable', 'date'],
            'brand' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);
        $truck = Truck::findOrFail($validated['truck_id']);
        $this->authorize('update', $truck);
        Tire::create($validated);
        return redirect()->route('trucks.show', $truck)->with('status', 'Lastik kaydı eklendi.');
    }

    public function edit(Tire $tire): View
    {
        $this->authorize('update', $tire->truck);
        return view('tires.edit', compact('tire'));
    }

    public function update(Request $request, Tire $tire): RedirectResponse
    {
        $this->authorize('update', $tire->truck);
        $validated = $request->validate([
            'position' => ['required', 'string', 'in:on_sol,on_sag,arka_1,arka_2,arka_3,arka_4,yedek,diger'],
            'change_km' => ['nullable', 'integer', 'min:0'],
            'change_date' => ['nullable', 'date'],
            'brand' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);
        $tire->update($validated);
        return redirect()->route('trucks.show', $tire->truck)->with('status', 'Lastik kaydı güncellendi.');
    }

    public function destroy(Tire $tire): RedirectResponse
    {
        $this->authorize('update', $tire->truck);
        $truck = $tire->truck;
        $tire->delete();
        return redirect()->route('trucks.show', $truck)->with('status', 'Lastik kaydı silindi.');
    }
}
