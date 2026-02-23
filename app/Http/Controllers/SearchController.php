<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Truck;
use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim($request->get('q', ''));
        $trips = collect();
        $trucks = collect();
        $customers = collect();
        $quotations = collect();

        if (strlen($q) >= 2) {
            $userId = $request->user()->id;

            $trips = Trip::forUser($request->user())
                ->where(function ($qb) use ($q) {
                    $qb->where('destination', 'like', "%{$q}%")
                        ->orWhere('origin', 'like', "%{$q}%")
                        ->orWhere('receiver_name', 'like', "%{$q}%")
                        ->orWhere('notes', 'like', "%{$q}%")
                        ->orWhereHas('truck', fn ($t) => $t->where('plate', 'like', "%{$q}%"));
                })
                ->with('truck')
                ->latest('departure_date')
                ->take(10)
                ->get();

            $trucks = Truck::forUser($request->user())
                ->where(function ($qb) use ($q) {
                    $qb->where('plate', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%")
                        ->orWhere('model', 'like', "%{$q}%");
                })
                ->take(10)
                ->get();

            $customers = $request->user()->isPatron() ? Customer::where('user_id', $userId)
                ->where(function ($qb) use ($q) {
                    $qb->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                })
                ->take(10)
                ->get() : collect();

            $quotations = $request->user()->isPatron() ? Quotation::where('user_id', $userId)
                ->where(function ($qb) use ($q) {
                    $qb->where('title', 'like', "%{$q}%")
                        ->orWhere('origin', 'like', "%{$q}%")
                        ->orWhere('destination', 'like', "%{$q}%")
                        ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$q}%"));
                })
                ->with('customer')
                ->take(10)
                ->get() : collect();
        }

        return view('search.index', compact('q', 'trips', 'trucks', 'customers', 'quotations'));
    }
}
