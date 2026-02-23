<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuotationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Quotation::where('user_id', $request->user()->id)->with('customer');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                    ->orWhere('origin', 'like', "%{$q}%")
                    ->orWhere('destination', 'like', "%{$q}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }
        $quotations = $query->latest()->paginate(15)->withQueryString();
        return view('quotations.index', compact('quotations'));
    }

    public function create(Request $request): View
    {
        $customers = $request->user()->customers()->orderBy('name')->get();
        $customer = $request->has('customer') ? Customer::where('user_id', $request->user()->id)->find($request->customer) : null;
        return view('quotations.create', compact('customers', 'customer'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'origin' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'cargo_type' => ['nullable', 'string', 'max:50'],
            'load_weight' => ['nullable', 'numeric', 'min:0'],
            'valid_until' => ['nullable', 'date'],
            'status' => ['required', 'in:taslak,gonderildi,onaylandi,reddedildi'],
            'notes' => ['nullable', 'string'],
        ]);
        $validated['user_id'] = $request->user()->id;
        if (!empty($validated['customer_id'])) {
            $c = Customer::findOrFail($validated['customer_id']);
            if ($c->user_id !== $request->user()->id) {
                abort(403);
            }
        }
        Quotation::create($validated);
        return redirect()->route('quotations.index')->with('status', 'Teklif oluşturuldu.');
    }

    public function show(Quotation $quotation): View
    {
        if ($quotation->user_id !== request()->user()->id) {
            abort(403);
        }
        $quotation->load('customer');
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation): View
    {
        if ($quotation->user_id !== request()->user()->id) abort(403);
        $customers = request()->user()->customers()->orderBy('name')->get();
        return view('quotations.edit', compact('quotation', 'customers'));
    }

    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        if ($quotation->user_id !== $request->user()->id) abort(403);
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'origin' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'cargo_type' => ['nullable', 'string', 'max:50'],
            'load_weight' => ['nullable', 'numeric', 'min:0'],
            'valid_until' => ['nullable', 'date'],
            'status' => ['required', 'in:taslak,gonderildi,onaylandi,reddedildi'],
            'notes' => ['nullable', 'string'],
        ]);
        if (!empty($validated['customer_id']) && Customer::findOrFail($validated['customer_id'])->user_id !== $request->user()->id) {
            abort(403);
        }
        $quotation->update($validated);
        return redirect()->route('quotations.index')->with('status', 'Teklif güncellendi.');
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        if ($quotation->user_id !== request()->user()->id) abort(403);
        $quotation->delete();
        return redirect()->route('quotations.index')->with('status', 'Teklif silindi.');
    }
}
