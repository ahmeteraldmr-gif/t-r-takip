<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">📊 Raporlar</h2>
            <a href="{{ route('reports.export', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                CSV İndir
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Tarih Filtresi --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="text-sm font-medium text-gray-600">Hızlı Filtre:</span>
                    <a href="{{ route('reports.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition
                           {{ request('start_date') == now()->startOfMonth()->format('Y-m-d') ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Bu Ay
                    </a>
                    <a href="{{ route('reports.index', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition
                           {{ request('start_date') == now()->subMonth()->startOfMonth()->format('Y-m-d') ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Geçen Ay
                    </a>
                    <a href="{{ route('reports.index') }}"
                        class="px-3 py-1.5 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                        Tümü
                    </a>
                </div>
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Başlangıç</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Bitiş</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition">
                        Filtrele
                    </button>
                    <a href="{{ route('reports.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Temizle
                    </a>
                </form>
                @if(request()->hasAny(['start_date', 'end_date']))
                    <p class="mt-2 text-xs text-amber-600 font-medium">
                        📅
                        {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d.m.Y') : '...' }}
                        – {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d.m.Y') : '...' }}
                        tarihleri arası gösteriliyor
                    </p>
                @endif
            </div>

            {{-- Özet Kartları --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Toplam Gelir</p>
                    <p class="text-lg font-bold text-green-700">{{ number_format($totalRevenue ?? 0, 2, ',', '.') }} TL
                    </p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Toplam Masraf</p>
                    <p class="text-lg font-bold text-red-600">{{ number_format($grandTotal, 2, ',', '.') }} TL</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Bekleyen Tahsilat</p>
                    <p class="text-lg font-bold text-amber-700">{{ number_format($pendingRevenue ?? 0, 2, ',', '.') }}
                        TL</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Toplam Km</p>
                    <p class="text-lg font-bold text-blue-700">
                        {{ number_format($trips->sum(fn($t) => $t->total_km ?? 0), 0, ',', '.') }} km</p>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Yakıt</p>
                    <p class="text-base font-semibold text-gray-800">{{ number_format($totalFuel, 2, ',', '.') }} TL</p>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Diğer Masraf</p>
                    <p class="text-base font-semibold text-gray-800">{{ number_format($totalOther, 2, ',', '.') }} TL
                    </p>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Olay Maliyeti</p>
                    <p class="text-base font-semibold text-gray-800">{{ number_format($totalIncident, 2, ',', '.') }} TL
                    </p>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Komisyon</p>
                    <p class="text-base font-semibold text-gray-800">{{ number_format($totalCommission, 2, ',', '.') }}
                        TL</p>
                </div>
            </div>

            {{-- Sekmeli Bölüm --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden"
                x-data="{ tab: 'monthly' }">

                {{-- Sekme Başlıkları --}}
                <div class="flex border-b border-gray-200 overflow-x-auto">
                    <button @click="tab='monthly'"
                        :class="tab==='monthly' ? 'border-b-2 border-amber-500 text-amber-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                        class="px-5 py-3 text-sm whitespace-nowrap transition">
                        Aylık Rapor
                    </button>
                    <button @click="tab='trips'"
                        :class="tab==='trips' ? 'border-b-2 border-amber-500 text-amber-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                        class="px-5 py-3 text-sm whitespace-nowrap transition">
                        Sefer Listesi
                    </button>
                    <button @click="tab='fuel'"
                        :class="tab==='fuel' ? 'border-b-2 border-amber-500 text-amber-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                        class="px-5 py-3 text-sm whitespace-nowrap transition">
                        Yakıt Dökümü
                    </button>
                    @if(!empty($driverPerformance) && count($driverPerformance) > 0)
                        <button @click="tab='drivers'"
                            :class="tab==='drivers' ? 'border-b-2 border-amber-500 text-amber-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="px-5 py-3 text-sm whitespace-nowrap transition">
                            Şoförler
                        </button>
                    @endif
                    <button @click="tab='truckfuel'"
                        :class="tab==='truckfuel' ? 'border-b-2 border-amber-500 text-amber-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                        class="px-5 py-3 text-sm whitespace-nowrap transition">
                        ⛽ Tır Yakıtı
                    </button>
                    @if(!empty($truckComparison) && count($truckComparison) > 0)
                        <button @click="tab='trucks'"
                            :class="tab==='trucks' ? 'border-b-2 border-amber-500 text-amber-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="px-5 py-3 text-sm whitespace-nowrap transition">
                            Tır Karşılaştırma
                        </button>
                    @endif
                    @if(isset($chartData) && $chartData->isNotEmpty())
                        <button @click="tab='chart'"
                            :class="tab==='chart' ? 'border-b-2 border-amber-500 text-amber-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="px-5 py-3 text-sm whitespace-nowrap transition">
                            Grafik
                        </button>
                    @endif
                </div>

                {{-- Aylık Rapor --}}
                <div x-show="tab==='monthly'" class="overflow-x-auto">
                    @if($monthlyData->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-10">Aylık veri bulunamadı.</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ay</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Sefer
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Km</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Yakıt
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Diğer
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Olay</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Komisyon
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-amber-600 uppercase">Toplam
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($monthlyData as $data)
                                    <tr class="hover:bg-amber-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $data['label'] }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $data['trip_count'] }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($data['total_km'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($data['fuel'], 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($data['other'], 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($data['incident'], 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($data['commission'], 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-amber-700">
                                            {{ number_format($data['total'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Sefer Listesi --}}
                <div x-show="tab==='trips'" class="overflow-x-auto" style="display:none">
                    @if($trips->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-10">Sefer bulunamadı.</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tır</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Güzergah
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Km</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Yakıt
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Diğer
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Olay</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Komisyon
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-amber-600 uppercase">Toplam
                                    </th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($trips as $trip)
                                    <tr class="hover:bg-amber-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $trip->truck->plate }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $trip->destination }}</td>
                                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                            {{ $trip->departure_date->format('d.m.Y') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ $trip->total_km ? number_format($trip->total_km, 0, ',', '.') : '-' }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($trip->total_fuel_expense, 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($trip->total_other_expense, 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($trip->total_incident_cost, 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($trip->commission_amount ?? 0, 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-amber-700">
                                            {{ number_format($trip->total_expense, 2, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('trips.show', $trip) }}"
                                                class="text-amber-600 hover:text-amber-700 text-xs font-medium">Detay →</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Yakıt Dökümü --}}
                <div x-show="tab==='fuel'" class="overflow-x-auto" style="display:none">
                    @if($fuelSummary->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-10">Yakıt kaydı bulunamadı.</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sefer /
                                        Tır</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Litre
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Lt Fiyatı
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-amber-600 uppercase">Toplam
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($fuelSummary->take(50) as $fs)
                                    <tr class="hover:bg-amber-50 transition">
                                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $fs['date']->format('d.m.Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">{{ $fs['trip'] }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($fs['liters'], 2) }} L
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($fs['price_per_liter'], 2) }} TL</td>
                                        <td class="px-4 py-3 text-right font-bold text-amber-700">
                                            {{ number_format($fs['total'], 2, ',', '.') }} TL</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-amber-50 border-t-2 border-amber-200">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-sm font-semibold text-amber-800">
                                        Toplam ({{ $fuelSummary->count() }} kayıt)
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-amber-800">
                                        {{ number_format($totalFuel, 2, ',', '.') }} TL</td>
                                </tr>
                            </tfoot>
                        </table>
                        @if($fuelSummary->count() > 50)
                            <p class="px-4 py-2 text-xs text-gray-400">İlk 50 kayıt gösteriliyor. Tamamı için CSV indirin.</p>
                        @endif
                    @endif
                </div>

                {{-- Şoför Performansı --}}
                @if(!empty($driverPerformance) && count($driverPerformance) > 0)
                    <div x-show="tab==='drivers'" class="overflow-x-auto" style="display:none">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Şoför /
                                        Tır</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Sefer
                                        Sayısı</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Toplam Km
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ort. Süre
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($driverPerformance as $name => $d)
                                    <tr class="hover:bg-amber-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $name }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $d['trips'] }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($d['km'], 0, ',', '.') }} km</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ $d['trips'] > 0 ? number_format($d['duration_days'] / $d['trips'], 1) . ' gün' : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Tır Karşılaştırma --}}
                @if(!empty($truckComparison) && count($truckComparison) > 0)
                    <div x-show="tab==='trucks'" class="overflow-x-auto" style="display:none">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Plaka</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Sefer
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Toplam
                                        Masraf</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Km</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-amber-600 uppercase">TL/Km
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($truckComparison as $t)
                                    <tr class="hover:bg-amber-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $t['plate'] }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $t['trips'] }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($t['expense'], 2, ',', '.') }} TL</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($t['km'], 0, ',', '.') }} km</td>
                                        <td class="px-4 py-3 text-right font-bold text-amber-700">
                                            {{ $t['per_km'] > 0 ? number_format($t['per_km'], 2, ',', '.') . ' TL' : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Tır Bazlı Yakıt --}}
                <div x-show="tab==='truckfuel'" class="overflow-x-auto" style="display:none">
                    @php
                        $truckFuelData = $trips->groupBy('truck_id')->map(function($grp) {
                            $truck = $grp->first()->truck;
                            return [
                                'plate'   => $truck->plate,
                                'trips'   => $grp->count(),
                                'liters'  => $grp->sum(fn($t) => $t->total_liters),
                                'fuel_tl' => $grp->sum(fn($t) => $t->total_fuel_expense),
                                'km'      => $grp->sum(fn($t) => $t->total_km ?? 0),
                            ];
                        })->sortByDesc('fuel_tl')->values();
                    @endphp
                    @if($truckFuelData->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-10">Yakıt verisi bulunamadı.</p>
                    @else
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tır Plakası</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Sefer Sayısı</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Toplam Km</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Toplam Litre</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Lt/100km</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-amber-600 uppercase">Yakıt Toplam (TL)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($truckFuelData as $tf)
                            <tr class="hover:bg-amber-50 transition">
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $tf['plate'] }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ $tf['trips'] }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ $tf['km'] > 0 ? number_format($tf['km'], 0, ',', '.') . ' km' : '-' }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ $tf['liters'] > 0 ? number_format($tf['liters'], 0, ',', '.') . ' L' : '-' }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">
                                    @if($tf['km'] > 0 && $tf['liters'] > 0)
                                        {{ number_format(($tf['liters'] / $tf['km']) * 100, 1, ',', '.') }} L
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-amber-700">{{ number_format($tf['fuel_tl'], 2, ',', '.') }} TL</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-amber-50 border-t-2 border-amber-200">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-sm font-semibold text-amber-800">Genel Toplam</td>
                                <td class="px-4 py-3 text-right text-sm font-bold text-amber-800">{{ number_format($truckFuelData->sum('liters'), 0, ',', '.') }} L</td>
                                <td class="px-4 py-3"></td>
                                <td class="px-4 py-3 text-right text-sm font-bold text-amber-800">{{ number_format($totalFuel, 2, ',', '.') }} TL</td>
                            </tr>
                        </tfoot>
                    </table>
                    @endif
                </div>

                {{-- Grafik --}}
                @if(isset($chartData) && $chartData->isNotEmpty())
                    <div x-show="tab==='chart'" class="p-6" style="display:none">
                        <canvas id="reportChart" height="120"></canvas>
                    </div>
                    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
                    <script>
                        (function () {
                            function initChart() {
                                var ctx = document.getElementById('reportChart');
                                if (!ctx || !window.Chart) return;
                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: {!! json_encode($chartData->pluck('label')) !!},
                                        datasets: [
                                            {
                                                label: 'Masraf (TL)',
                                                data: {!! json_encode($chartData->pluck('total')) !!},
                                                backgroundColor: 'rgba(245,158,11,0.7)',
                                                borderRadius: 4
                                            },
                                            {
                                                label: 'Km',
                                                data: {!! json_encode($chartData->pluck('km')) !!},
                                                backgroundColor: 'rgba(156,163,175,0.6)',
                                                borderRadius: 4
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        animation: { duration: 400 },
                                        scales: { y: { beginAtZero: true } },
                                        plugins: { legend: { position: 'top' } }
                                    }
                                });
                            }
                            if (document.readyState === 'loading') {
                                document.addEventListener('DOMContentLoaded', function () { setTimeout(initChart, 200); });
                            } else {
                                setTimeout(initChart, 200);
                            }
                        })();
                    </script>
                @endif

            </div>{{-- /sekme --}}

        </div>
    </div>
</x-app-layout>