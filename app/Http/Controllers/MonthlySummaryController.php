<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonthlySummaryController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Trip::forUser($user)
            ->with(['fuelExpenses', 'otherExpenses', 'incidents']);

        if ($request->filled('start_date')) {
            $query->whereDate('departure_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('departure_date', '<=', $request->end_date);
        }

        $trips = $query->latest('departure_date')->get();

        $sefer = $trips->count();
        $km = $trips->sum(fn ($t) => $t->total_km ?? 0);
        $masraf = $trips->sum(fn ($t) => $t->total_expense);
        $gelir = $trips->sum(fn ($t) => (float) ($t->revenue_amount ?? 0));
        $kar = $gelir - $masraf;

        return view('monthly-summary.index', compact('sefer', 'km', 'masraf', 'gelir', 'kar'));
    }
}
