<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\FuelExpense;
use App\Models\Maintenance;
use App\Models\OtherExpense;
use App\Models\Quotation;
use App\Models\Trip;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $truckIds = $user->accessibleTruckIds();
        $truckCount = $user->isPatron() ? $user->trucks()->count() : $truckIds->count();
        $driverCount = $user->isPatron() ? $user->drivers()->count() : 0;

        $activeTripsCount = Trip::forUser($user)->where('status', 'in_progress')->count();

        $recentTrips = Trip::forUser($user)
            ->with('truck')
            ->latest('departure_date')
            ->take(5)
            ->get();

        // ── Bu ay masraf ──────────────────────────────────────────
        $thisMonthTrips = Trip::forUser($user)
            ->whereMonth('departure_date', now()->month)
            ->whereYear('departure_date', now()->year)
            ->with(['fuelExpenses', 'otherExpenses', 'incidents'])
            ->get();

        $totalExpenseThisMonth = $thisMonthTrips->sum(fn($t) => $t->total_expense);
        $totalRevenueThisMonth = $thisMonthTrips->sum(fn($t) => (float) ($t->revenue_amount ?? 0));
        $completedTripsThisMonth = $thisMonthTrips->where('status', 'completed')->count();
        $totalTripsThisMonth = $thisMonthTrips->count();

        // ── Bakımlar ─────────────────────────────────────────────
        $maintenanceOverdue = Maintenance::forUser($user)
            ->whereNull('last_done_date')
            ->where('due_date', '<', now())
            ->with('truck')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $maintenanceUpcoming = Maintenance::forUser($user)
            ->whereNull('last_done_date')
            ->where('due_date', '>=', now())
            ->with('truck')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // ── Son 6 ay grafik verisi ────────────────────────────────
        $monthlyChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $trips = Trip::forUser($user)
                ->whereMonth('departure_date', $month)
                ->whereYear('departure_date', $year)
                ->with(['fuelExpenses', 'otherExpenses', 'incidents'])
                ->get();

            $monthlyChart[] = [
                'label' => $date->locale('tr')->isoFormat('MMM'),
                'expense' => round($trips->sum(fn($t) => $t->total_expense)),
                'revenue' => round($trips->sum(fn($t) => (float) ($t->revenue_amount ?? 0))),
                'trips' => $trips->count(),
            ];
        }

        // ── Tır durumu ───────────────────────────────────────────
        $truckStatusList = collect();
        if ($user->isPatron()) {
            $truckStatusList = Truck::forUser($user)
                ->with(['trips' => fn($q) => $q->where('status', 'in_progress')->with('truck')])
                ->get()
                ->map(function ($truck) {
                    $activeTrip = $truck->trips->first();
                    return [
                        'plate' => $truck->plate,
                        'brand' => $truck->brand,
                        'model' => $truck->model,
                        'status' => $truck->status,
                        'driver' => $truck->driver_display_name,
                        'active_trip' => $activeTrip,
                    ];
                });
        }

        // ── Son teklifler ─────────────────────────────────────────
        $recentQuotations = collect();
        if ($user->isPatron()) {
            $recentQuotations = Quotation::where('user_id', $user->id)
                ->with('customer')
                ->latest()
                ->take(4)
                ->get();
            $pendingQuotationsCount = Quotation::where('user_id', $user->id)
                ->where('status', 'gonderildi')
                ->count();
        } else {
            $pendingQuotationsCount = 0;
        }

        // ── Şoför aktivitesi ─────────────────────────────────────
        $driverActivity = collect();
        if ($user->isPatron()) {
            $driverActivity = Driver::where('user_id', $user->id)
                ->with(['truck' => fn($q) => $q->with(['trips' => fn($q2) => $q2->where('status', 'in_progress')])])
                ->get()
                ->map(function ($driver) {
                    $activeTrip = $driver->truck?->trips->first();
                    return [
                        'name' => $driver->name,
                        'phone' => $driver->phone ?? null,
                        'truck_plate' => $driver->truck?->plate,
                        'active_trip' => $activeTrip,
                        'on_trip' => (bool) $activeTrip,
                    ];
                });
        }

        return view('dashboard', compact(
            'truckCount',
            'driverCount',
            'activeTripsCount',
            'recentTrips',
            'totalExpenseThisMonth',
            'totalRevenueThisMonth',
            'completedTripsThisMonth',
            'totalTripsThisMonth',
            'maintenanceOverdue',
            'maintenanceUpcoming',
            'monthlyChart',
            'truckStatusList',
            'recentQuotations',
            'pendingQuotationsCount',
            'driverActivity'
        ));
    }
}
