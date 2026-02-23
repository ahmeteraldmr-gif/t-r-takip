<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Trip;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Trip::forUser($user)
            ->with(['truck', 'truck.driver', 'fuelExpenses', 'otherExpenses', 'incidents']);

        if ($request->filled('start_date')) {
            $query->whereDate('departure_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('departure_date', '<=', $request->end_date);
        }

        $trips = $query->latest('departure_date')->get();

        $totalFuel = 0;
        $totalOther = 0;
        $totalIncident = 0;
        $totalCommission = 0;
        $totalRevenue = 0;
        $pendingRevenue = 0;
        foreach ($trips as $trip) {
            $totalFuel += $trip->total_fuel_expense;
            $totalOther += $trip->total_other_expense;
            $totalIncident += $trip->total_incident_cost;
            $totalCommission += $trip->commission_amount ?? 0;
            $totalRevenue += (float) ($trip->revenue_amount ?? 0);
            if (($trip->payment_status ?? 'bekliyor') !== 'tahsil_edildi') {
                $pendingRevenue += (float) ($trip->revenue_amount ?? 0);
            }
        }
        $grandTotal = $totalFuel + $totalOther + $totalIncident + $totalCommission;

        $monthlyQuery = Trip::forUser($user)
            ->with(['fuelExpenses', 'otherExpenses', 'incidents']);
        if ($request->filled('start_date')) {
            $monthlyQuery->whereDate('departure_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $monthlyQuery->whereDate('departure_date', '<=', $request->end_date);
        }
        $monthlyData = $monthlyQuery->get()
            ->groupBy(fn ($t) => $t->departure_date->format('Y-m'))
            ->map(function ($monthTrips, $month) {
                $fuel = $monthTrips->sum(fn ($t) => $t->total_fuel_expense);
                $other = $monthTrips->sum(fn ($t) => $t->total_other_expense);
                $incident = $monthTrips->sum(fn ($t) => $t->total_incident_cost);
                $commission = $monthTrips->sum(fn ($t) => $t->commission_amount ?? 0);
                $totalKm = $monthTrips->sum(fn ($t) => $t->total_km ?? 0);
                $months = ['01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık'];
                [$y, $m] = explode('-', $month);
                $label = ($months[$m] ?? $m) . ' ' . $y;
                return [
                    'month' => $month,
                    'label' => $label,
                    'trip_count' => $monthTrips->count(),
                    'total_km' => $totalKm,
                    'fuel' => $fuel,
                    'other' => $other,
                    'incident' => $incident,
                    'commission' => $commission,
                    'total' => $fuel + $other + $incident + $commission,
                ];
            })
            ->sortKeysDesc()
            ->take(12);

        $fuelSummary = collect();
        foreach ($trips as $trip) {
            foreach ($trip->fuelExpenses as $fe) {
                $fuelSummary->push([
                    'date' => $fe->date,
                    'trip' => $trip->destination . ' (' . $trip->truck->plate . ')',
                    'liters' => $fe->liters,
                    'price_per_liter' => $fe->price_per_liter,
                    'total' => $fe->total_amount,
                ]);
            }
        }
        $fuelSummary = $fuelSummary->sortByDesc('date')->values();

        $driverPerformance = $this->getDriverPerformance($trips);
        $truckComparison = $this->getTruckComparison($trips, $user);
        $chartData = $monthlyData->map(fn ($d) => [
            'label' => $d['label'],
            'fuel' => $d['fuel'],
            'other' => $d['other'],
            'incident' => $d['incident'],
            'total' => $d['total'],
            'km' => $d['total_km'] ?? 0,
            'trips' => $d['trip_count'],
        ])->values();

        return view('reports.index', compact('trips', 'totalFuel', 'totalOther', 'totalIncident', 'totalCommission', 'totalRevenue', 'pendingRevenue', 'grandTotal', 'monthlyData', 'fuelSummary', 'driverPerformance', 'truckComparison', 'chartData'));
    }

    private function getDriverPerformance($trips): array
    {
        $byDriver = [];
        foreach ($trips as $trip) {
            $name = $trip->truck->driver_display_name ?? $trip->truck->plate . ' (şoförsüz)';
            if (!isset($byDriver[$name])) {
                $byDriver[$name] = ['trips' => 0, 'km' => 0, 'duration_days' => 0];
            }
            $byDriver[$name]['trips']++;
            $byDriver[$name]['km'] += $trip->total_km ?? 0;
            if ($trip->duration_seconds) {
                $byDriver[$name]['duration_days'] += $trip->duration_seconds / 86400;
            }
        }
        return $byDriver;
    }

    private function getTruckComparison($trips, $user): array
    {
        $byTruck = [];
        $trucks = Truck::where('user_id', $user->id)->get();
        foreach ($trucks as $truck) {
            $truckTrips = $trips->where('truck_id', $truck->id);
            $expense = 0;
            $km = 0;
            foreach ($truckTrips as $t) {
                $expense += $t->total_expense;
                $km += $t->total_km ?? 0;
            }
            $byTruck[] = [
                'plate' => $truck->plate,
                'trips' => $truckTrips->count(),
                'expense' => $expense,
                'km' => $km,
                'per_km' => $km > 0 ? round($expense / $km, 2) : 0,
            ];
        }
        usort($byTruck, fn ($a, $b) => $b['expense'] <=> $a['expense']);
        return $byTruck;
    }

    public function export(Request $request)
    {
        $user = $request->user();
        $query = Trip::forUser($user)
            ->with(['truck', 'fuelExpenses', 'otherExpenses', 'incidents']);

        if ($request->filled('start_date')) {
            $query->whereDate('departure_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('departure_date', '<=', $request->end_date);
        }

        $trips = $query->latest('departure_date')->get();
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            $headers = ['Content-Type' => 'text/csv; charset=utf-8', 'Content-Disposition' => 'attachment; filename="rapor-' . date('Y-m-d') . '.csv"'];
            $callback = function () use ($trips) {
                $f = fopen('php://output', 'w');
                fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($f, ['Tır', 'Tarih', 'Nereden', 'Nereye', 'Km', 'Gelir', 'Masraf', 'Kar/Zarar', 'Ödeme Durumu']);
                foreach ($trips as $t) {
                    fputcsv($f, [
                        $t->truck->plate,
                        $t->departure_date->format('d.m.Y'),
                        $t->origin ?? '-',
                        $t->destination,
                        $t->total_km ?? '-',
                        number_format($t->revenue_amount ?? 0, 2, ',', ''),
                        number_format($t->total_expense, 2, ',', ''),
                        $t->profit !== null ? number_format($t->profit, 2, ',', '') : '-',
                        \App\Models\Trip::PAYMENT_STATUS_LABELS[$t->payment_status ?? 'bekliyor'] ?? $t->payment_status,
                    ], ';');
                }
                fclose($f);
            };
            return Response::stream($callback, 200, $headers);
        }

        return redirect()->route('reports.index')->with('status', 'Desteklenmeyen format.');
    }
}
