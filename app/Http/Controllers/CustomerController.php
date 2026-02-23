<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::where('user_id', $request->user()->id);
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }
        $customers = $query->withCount('trips')->orderBy('name')->paginate(20)->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
        $validated['user_id'] = $request->user()->id;
        Customer::create($validated);
        return redirect()->route('customers.index')->with('status', 'Müşteri eklendi.');
    }

    public function show(Customer $customer): View
    {
        $this->authorize('view', $customer);
        $customer->load(['trips' => fn ($q) => $q->with('truck')->latest('departure_date')->take(20)]);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorize('update', $customer);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $this->authorize('update', $customer);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')->with('status', 'Müşteri güncellendi.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);
        $customer->trips()->update(['customer_id' => null]);
        $customer->delete();
        return redirect()->route('customers.index')->with('status', 'Müşteri silindi.');
    }
}
