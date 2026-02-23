<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\TruckDocument;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TruckDocumentController extends Controller
{
    public function create(Request $request): View
    {
        $truck = Truck::where('user_id', $request->user()->id)->findOrFail($request->truck);
        return view('truck-documents.create', compact('truck'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'truck_id' => ['required', 'exists:trucks,id'],
            'type' => ['required', 'string', 'in:muayene,sigorta,kasko,ruhsat,diger'],
            'file' => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
            'expiry_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);
        $truck = Truck::findOrFail($validated['truck_id']);
        $this->authorize('update', $truck);

        $file = $request->file('file');
        $path = $file->store('documents/' . $truck->id, 'public');

        TruckDocument::create([
            'truck_id' => $truck->id,
            'type' => $validated['type'],
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'expiry_date' => $validated['expiry_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('trucks.show', $truck)->with('status', 'Belge eklendi.');
    }

    public function download(TruckDocument $truckDocument): StreamedResponse
    {
        $this->authorize('update', $truckDocument->truck);
        return Storage::disk('public')->download(
            $truckDocument->path,
            $truckDocument->original_name ?? 'document'
        );
    }

    public function destroy(TruckDocument $truckDocument): RedirectResponse
    {
        $this->authorize('update', $truckDocument->truck);
        $truck = $truckDocument->truck;
        Storage::disk('public')->delete($truckDocument->path);
        $truckDocument->delete();
        return redirect()->route('trucks.show', $truck)->with('status', 'Belge silindi.');
    }
}
