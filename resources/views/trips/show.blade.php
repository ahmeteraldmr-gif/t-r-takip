<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight break-words">
                {{ $trip->truck->plate }} — {{ $trip->route_display }}
            </h2>
            <p class="text-sm text-gray-500 -mt-2">{{ $trip->departure_date->format('d.m.Y') }}</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('fuel-expenses.create', ['trip' => $trip->id]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Benzin
                </a>
                <a href="{{ route('other-expenses.create', ['trip' => $trip->id]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Masraf
                </a>
                <a href="{{ route('incidents.create', ['trip' => $trip->id]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Sorun
                </a>
                <a href="{{ route('trip-stops.create', ['trip' => $trip->id]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Duraklama
                </a>
                @if($trip->status === 'planned' || $trip->status === 'cancelled')
                    <form action="{{ route('trips.start', $trip) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Sefere Başla
                        </button>
                    </form>
                @endif
                @if($trip->status === 'in_progress')
                    <form action="{{ route('trips.end', $trip) }}" method="POST" class="inline" onsubmit="return confirm('Seferi bitirmek istediğinize emin misiniz?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                            Seferi Bitir
                        </button>
                    </form>
                @endif
                <a href="{{ route('trips.edit', $trip) }}" class="btn-secondary text-sm {{ $trip->status === 'in_progress' ? 'ring-2 ring-amber-400' : '' }}">
                    {{ $trip->status === 'in_progress' ? 'Düzenle (Sefer Aktif)' : 'Düzenle' }}
                </a>
                <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="inline" onsubmit="return confirm('Bu seferi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-sm text-red-600 hover:text-red-700 hover:bg-red-50 border-red-200">Sil</button>
                </form>
                <a href="{{ route('trucks.show', $trip->truck) }}" class="btn-secondary text-sm">
                    ← Geri
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 text-emerald-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Sefer sayacı / süre (devam ediyor veya bitti) --}}
            @if($trip->status === 'in_progress' && $trip->started_at)
                <div class="bg-amber-50 border-2 border-amber-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-amber-900 mb-2">Sefer Sayacı (Açık Süre)</h3>
                    <p class="text-3xl font-bold text-amber-700 font-mono" id="trip-counter">—</p>
                    <p class="text-sm text-amber-800 mt-1">Sefere başlama: {{ $trip->started_at->format('d.m.Y H:i') }}</p>
                    <script>
                        (function() {
                            var start = new Date('{{ $trip->started_at->format('c') }}');
                            function update() {
                                var now = new Date();
                                var sec = Math.floor((now - start) / 1000);
                                var d = Math.floor(sec / 86400);
                                var h = Math.floor((sec % 86400) / 3600);
                                var m = Math.floor((sec % 3600) / 60);
                                var s = sec % 60;
                                var parts = [];
                                if (d > 0) parts.push(d + ' gün');
                                parts.push(String(h).padStart(2,'0') + ' saat');
                                parts.push(String(m).padStart(2,'0') + ' dk');
                                parts.push(String(s).padStart(2,'0') + ' sn');
                                document.getElementById('trip-counter').textContent = parts.join(' ');
                            }
                            update();
                            setInterval(update, 1000);
                        })();
                    </script>
                </div>
            @endif
            @if($trip->status === 'completed' && $trip->started_at && $trip->ended_at)
                <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-green-900 mb-2">Sefer Süresi (Ne Kadar Açık Kaldı)</h3>
                    <p class="text-2xl font-bold text-green-800">{{ $trip->duration_display }}</p>
                    <p class="text-sm text-green-700 mt-1">Başlangıç: {{ $trip->started_at->format('d.m.Y H:i') }} — Bitiş: {{ $trip->ended_at->format('d.m.Y H:i') }}</p>
                </div>
            @endif

            {{-- Bu seferde: Kaç lt aldı, masraf, sorunlar (kullanıcı girer) --}}
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bu Seferde: Kaç Lt Aldı, Masraf, Sorunlar</h3>
                <div class="flex flex-col gap-4">
                    <div class="bg-white rounded-lg p-4 border border-slate-100 flex flex-col gap-2">
                        <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-sm text-gray-500 min-w-[120px]">Benzin (Lt)</p><p class="text-lg font-bold text-gray-900">{{ $trip->total_liters > 0 ? number_format($trip->total_liters, 1) . ' L' : '-' }}</p></div>
                        <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-sm text-gray-500 min-w-[120px]">Kaç L kullandı</p><p class="text-sm text-gray-600">{{ $trip->total_liters_used > 0 ? number_format($trip->total_liters_used, 1) . ' L' : '-' }}</p></div>
                        <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-sm text-gray-500 min-w-[120px]">Ne kadar kaldı</p><p class="text-sm text-gray-600">{{ $trip->total_liters > 0 ? number_format(max(0, $trip->total_liters - $trip->total_liters_used), 1) . ' L' : '-' }}</p></div>
                        <div class="flex items-baseline gap-2 py-1"><p class="text-sm text-gray-500 min-w-[120px]">Toplam (TL)</p><p class="text-sm font-medium">{{ number_format($trip->total_fuel_expense, 2, ',', '.') }} TL</p></div>
                        <a href="{{ route('fuel-expenses.create', ['trip' => $trip->id]) }}" class="text-xs text-gray-600 hover:underline mt-1 inline-block">+ Benzin ekle</a>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-slate-100 flex flex-col gap-2">
                        <div class="flex items-baseline gap-2 py-1"><p class="text-sm text-gray-500 min-w-[120px]">Diğer Masraflar</p><p class="text-xl font-bold text-amber-700">{{ number_format($trip->total_other_expense, 2, ',', '.') }} TL</p></div>
                        <a href="{{ route('other-expenses.create', ['trip' => $trip->id]) }}" class="text-xs text-amber-600 hover:underline mt-1 inline-block">+ Masraf ekle</a>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-slate-100 flex flex-col gap-2">
                        <div class="flex items-baseline gap-2 py-1"><p class="text-sm text-gray-500 min-w-[120px]">Sorunlar</p>
                        @php
                            $incidentLabels = ['lastik_patlama' => 'Lastik Patlama', 'teker_patlama' => 'Teker Patlama', 'motor_arızası' => 'Motor Arızası', 'kaza' => 'Kaza', 'fren_arızası' => 'Fren Arızası', 'diğer' => 'Diğer'];
                            $byType = $trip->incidents_by_type;
                        @endphp
                        @if(empty($byType))
                            <p class="text-gray-500 text-sm">Sorun yok</p>
                        @else
                            @foreach($byType as $type => $count)
                                <p class="text-sm text-red-700">• {{ $incidentLabels[$type] ?? $type }} ({{ $count }})</p>
                            @endforeach
                        @endif
                        </div>
                        <div class="flex items-baseline gap-2 py-1"><p class="text-sm text-gray-500 min-w-[120px]">Toplam Maliyet</p><p class="text-sm font-bold">{{ number_format($trip->total_incident_cost, 2, ',', '.') }} TL</p></div>
                        <a href="{{ route('incidents.create', ['trip' => $trip->id]) }}" class="text-xs text-red-600 hover:underline mt-1 inline-block">+ Sorun ekle</a>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-slate-100 flex flex-col gap-2">
                        <div class="flex items-baseline gap-2 py-1"><p class="text-sm text-gray-500 min-w-[120px]">Toplam Sefer Masrafı</p><p class="text-xl font-bold text-gray-900">{{ number_format($trip->total_expense, 2, ',', '.') }} TL</p></div>
                    </div>
                </div>
            </div>

            {{-- Duraklamalar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-blue-200">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Duraklama Yerleri</h3>
                        <a href="{{ route('trip-stops.create', ['trip' => $trip->id]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 text-white font-medium rounded-lg hover:bg-gray-800 transition shadow-sm shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Duraklama Ekle (Durdur)
                        </a>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Tır durduğunda duraklama yeri ekleyin. İstediğiniz zaman "Duraklama Ekle" ile kaydedebilirsiniz.</p>
                    @if ($trip->tripStops->isEmpty())
                        <p class="text-gray-500 text-sm">Henüz duraklama kaydı yok.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($trip->tripStops->sortBy('stopped_at') as $stop)
                                <div class="flex flex-wrap items-start justify-between gap-2 border border-gray-200 rounded-lg p-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $stop->location }}</p>
                                        <p class="text-sm text-gray-500">Durdu: {{ $stop->stopped_at->format('d.m.Y H:i') }}</p>
                                        @if($stop->left_at)
                                            <p class="text-sm text-gray-500">Yola çıktı: {{ $stop->left_at->format('d.m.Y H:i') }}</p>
                                        @else
                                            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded">Hâlâ duruyor</span>
                                        @endif
                                        @if($stop->notes)
                                            <p class="text-sm text-gray-600 mt-1">{{ $stop->notes }}</p>
                                        @endif
                                    </div>
                                    <form action="{{ route('trip-stops.destroy', $stop) }}" method="POST" class="inline" onsubmit="return confirm('Bu duraklamayı silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 text-sm hover:underline">Sil</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Özet --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sefer Özeti</h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Çıkış Tarihi</p>
                            <p class="font-medium">{{ $trip->departure_date->format('d.m.Y') }}</p>
                        </div>
                        @if($trip->truck->driver_display_name)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Şoför</p>
                            <p class="font-medium">{{ $trip->truck->driver_display_name }}</p>
                        </div>
                        @endif
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Nereden → Nereye</p>
                            <p class="font-medium">{{ $trip->route_display }}</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Kaç Gün Kaldı</p>
                            <p class="font-medium">{{ $trip->days_stayed ?? '-' }}{{ $trip->days_stayed ? ' gün' : '' }}</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Durum</p>
                            @php
                                $statusLabels = [
                                    'planned' => 'Planlandı',
                                    'in_progress' => 'Devam Ediyor',
                                    'completed' => 'Tamamlandı',
                                    'cancelled' => 'İptal',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($trip->status === 'completed') bg-green-100 text-green-800
                                @elseif($trip->status === 'in_progress') bg-gray-100 text-gray-800
                                @elseif($trip->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $statusLabels[$trip->status] ?? $trip->status }}
                            </span>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Komisyon</p>
                            <p class="font-medium">{{ number_format($trip->commission_amount, 2, ',', '.') }} TL</p>
                        </div>
                        @if($trip->customer)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Müşteri</p>
                            <p class="font-medium"><a href="{{ route('customers.show', $trip->customer) }}" class="text-indigo-600 hover:underline">{{ $trip->customer->name }}</a></p>
                        </div>
                        @endif
                        @if($trip->revenue_amount && $trip->revenue_amount > 0)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Gelir / Kar</p>
                            <p class="font-medium">{{ number_format($trip->revenue_amount, 2, ',', '.') }} TL @if($trip->profit !== null) ({{ $trip->profit >= 0 ? 'Kar: ' : 'Zarar: ' }}{{ number_format($trip->profit, 2, ',', '.') }} TL) @endif</p>
                            <p class="text-xs text-gray-500">{{ \App\Models\Trip::PAYMENT_STATUS_LABELS[$trip->payment_status ?? 'bekliyor'] ?? $trip->payment_status }}</p>
                        </div>
                        @endif
                        @if($trip->fuel_consumption_per_100_km)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Yakıt Tüketimi</p>
                            <p class="font-medium">{{ $trip->fuel_consumption_per_100_km }} L/100 km</p>
                        </div>
                        @endif
                        @if($trip->start_km || $trip->end_km || $trip->total_km)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Km</p>
                            <p class="font-medium">{{ $trip->start_km ?? '-' }} → {{ $trip->end_km ?? '-' }}{{ $trip->total_km ? ' (' . number_format($trip->total_km) . ' km)' : '' }}</p>
                        </div>
                        @endif
                        @if($trip->cargo_type || $trip->load_weight || $trip->receiver_name)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Yük / Yükleme</p>
                            <p class="font-medium">{{ \App\Models\Trip::CARGO_TYPE_LABELS[$trip->cargo_type] ?? $trip->cargo_type ?? '-' }}{{ $trip->load_weight ? ' · ' . number_format($trip->load_weight, 1) . ' ton' : '' }}</p>
                            @if($trip->receiver_name)<p class="text-sm text-gray-600">{{ $trip->receiver_name }}</p>@endif
                            @if($trip->loading_date || $trip->unloading_date)<p class="text-xs text-gray-500">Yükleme: {{ $trip->loading_date?->format('d.m.Y') ?? '-' }} / Boşaltma: {{ $trip->unloading_date?->format('d.m.Y') ?? '-' }}</p>@endif
                        </div>
                        @endif
                    </div>
                    @if ($trip->notes)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Notlar</p>
                            <p class="text-gray-900">{{ $trip->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Benzin Masrafları --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Benzin Masrafları</h3>
                        <a href="{{ route('fuel-expenses.create', ['trip' => $trip->id]) }}"
                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">+ Ekle</a>
                    </div>
                    @if ($trip->fuelExpenses->isEmpty())
                        <p class="text-gray-500 text-sm">Henüz benzin kaydı yok.</p>
                    @else
                        <div class="flex flex-col gap-3">
                            @foreach ($trip->fuelExpenses as $fe)
                                <div class="border border-gray-200 rounded-lg p-4 bg-white flex flex-col gap-2">
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Tarih</p><p class="font-medium">{{ $fe->date->format('d.m.Y') }}</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Kaç Lt aldı</p><p class="text-gray-900 font-medium">{{ number_format($fe->liters, 2) }} L</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Litre Fiyatı / Toplam</p><p class="text-gray-900">{{ number_format($fe->price_per_liter, 2) }} TL — {{ number_format($fe->total_amount, 2) }} TL</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Kaç L kullandı / Ne kadar kaldı</p><p class="text-gray-900">{{ $fe->liters_used ? number_format($fe->liters_used, 2) . ' L' : '-' }} / {{ ($fe->liters_used !== null && $fe->liters_used !== '') ? number_format(max(0, $fe->liters - $fe->liters_used), 2) . ' L' : '-' }}</p></div>
                                        <div class="pt-2 flex gap-2">
                                            <a href="{{ route('fuel-expenses.edit', $fe) }}" class="text-indigo-600 text-sm">Düzenle</a>
                                            <form action="{{ route('fuel-expenses.destroy', $fe) }}" method="POST" class="inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm font-medium">Toplam: {{ number_format($trip->total_fuel_expense, 2, ',', '.') }} TL</p>
                    @endif
                </div>
            </div>

            {{-- Diğer Masraflar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Diğer Masraflar</h3>
                        <a href="{{ route('other-expenses.create', ['trip' => $trip->id]) }}"
                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">+ Ekle</a>
                    </div>
                    @if ($trip->otherExpenses->isEmpty())
                        <p class="text-gray-500 text-sm">Henüz diğer masraf kaydı yok.</p>
                    @else
                        <div class="flex flex-col gap-3">
                            @php $expenseCategories = ['yemek' => 'Yemek', 'otel' => 'Otel', 'yol_geçiş' => 'Yol Geçiş', 'otopark' => 'Otopark', 'diğer' => 'Diğer']; @endphp
                            @foreach ($trip->otherExpenses as $oe)
                                <div class="border border-gray-200 rounded-lg p-4 bg-white flex flex-col gap-2">
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Tarih</p><p class="font-medium">{{ $oe->date->format('d.m.Y') }}</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Kategori</p><p class="text-gray-900">{{ $expenseCategories[$oe->category] ?? $oe->category }}</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Açıklama</p><p class="text-gray-900 text-sm">{{ $oe->description ?? '-' }}</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Tutar</p><p class="font-bold text-gray-900">{{ number_format($oe->amount, 2) }} TL</p></div>
                                        <div class="pt-2 flex gap-2">
                                            <a href="{{ route('other-expenses.edit', $oe) }}" class="text-indigo-600 text-sm">Düzenle</a>
                                            <form action="{{ route('other-expenses.destroy', $oe) }}" method="POST" class="inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm font-medium">Toplam: {{ number_format($trip->total_other_expense, 2, ',', '.') }} TL</p>
                    @endif
                </div>
            </div>

            {{-- Sorunlar: Teker patladı, motor arızası vb. BURAYA EKLEYİN --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-200">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Çıkan Olaylar — Lastik Patlama, Motor Arızası vb.</h3>
                            <p class="text-sm text-gray-600 mt-1">Lastik/teker patlama, motor arızası, kaza gibi çıkan olayları <strong>bu bölüme</strong> ekleyin.</p>
                        </div>
                        <a href="{{ route('incidents.create', ['trip' => $trip->id]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition shadow-sm shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Sorun Ekle
                        </a>
                    </div>
                    @if ($trip->incidents->isEmpty())
                        <p class="text-gray-500 text-sm py-2">Henüz sorun kaydı yok. Teker patlama, motor arızası vb. sorunları yukarıdaki <strong>“Sorun Ekle”</strong> butonuna tıklayarak ekleyebilirsiniz.</p>
                    @else
                        <div class="flex flex-col gap-3">
                            @php $incidentTypes = ['lastik_patlama' => 'Lastik Patlama', 'teker_patlama' => 'Teker Patlama', 'motor_arızası' => 'Motor Arızası', 'kaza' => 'Kaza', 'fren_arızası' => 'Fren Arızası', 'diğer' => 'Diğer']; @endphp
                            @foreach ($trip->incidents as $inc)
                                <div class="border border-gray-200 rounded-lg p-4 bg-white flex flex-col gap-2">
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Tarih</p><p class="font-medium">{{ $inc->date->format('d.m.Y') }}</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Tip</p><p class="text-gray-900">{{ $incidentTypes[$inc->type] ?? $inc->type }}</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Açıklama</p><p class="text-gray-900 text-sm">{{ $inc->description ?? '-' }}</p></div>
                                    <div class="flex items-baseline gap-2 py-1 border-b border-gray-50"><p class="text-xs text-gray-500 uppercase min-w-[140px]">Maliyet</p><p class="font-bold text-gray-900">{{ $inc->cost ? number_format($inc->cost, 2) . ' TL' : '-' }}</p></div>
                                        <div class="pt-2 flex gap-2">
                                            <a href="{{ route('incidents.edit', $inc) }}" class="text-indigo-600 text-sm">Düzenle</a>
                                            <form action="{{ route('incidents.destroy', $inc) }}" method="POST" class="inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm font-medium">Toplam Maliyet: {{ number_format($trip->total_incident_cost, 2, ',', '.') }} TL</p>
                    @endif
                </div>
            </div>

            {{-- Toplam Masraf Özeti --}}
            <div class="card border-2 border-amber-200 bg-amber-50/50">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Toplam Sefer Masrafı</h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-baseline gap-2 py-2 border-b border-amber-100">
                            <p class="text-xs text-gray-500 uppercase min-w-[140px]">Benzin</p>
                            <p class="font-bold text-lg text-gray-900">{{ number_format($trip->total_fuel_expense, 2, ',', '.') }} TL</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-amber-100">
                            <p class="text-xs text-gray-500 uppercase min-w-[140px]">Diğer masraflar</p>
                            <p class="font-bold text-lg text-gray-900">{{ number_format($trip->total_other_expense, 2, ',', '.') }} TL</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-amber-100">
                            <p class="text-xs text-gray-500 uppercase min-w-[140px]">Olay maliyetleri</p>
                            <p class="font-bold text-lg text-gray-900">{{ number_format($trip->total_incident_cost, 2, ',', '.') }} TL</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-amber-100">
                            <p class="text-xs text-gray-500 uppercase min-w-[140px]">Komisyon</p>
                            <p class="font-bold text-lg text-gray-900">{{ number_format($trip->commission_amount ?? 0, 2, ',', '.') }} TL</p>
                        </div>
                    </div>
                    <div class="mt-4 p-5 bg-amber-100 rounded-lg">
                        <p class="text-xs text-amber-800 uppercase font-medium">TOPLAM</p>
                        <p class="text-2xl font-bold text-amber-900">{{ number_format($trip->total_expense, 2, ',', '.') }} TL</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
