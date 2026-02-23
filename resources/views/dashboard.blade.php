<x-app-layout>
    <x-slot name="header">
        {{-- boş --}}
    </x-slot>

    <div class="py-7 sm:py-9">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Karşılama + Tarih Başlığı --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                        Merhaba, <span class="text-amber-600">{{ Auth::user()->name }}</span> 👋
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ now()->locale('tr')->isoFormat('D MMMM YYYY, dddd') }}
                    </p>
                </div>
                <div
                    class="hidden sm:flex items-center gap-2 bg-white border border-gray-200 rounded-2xl px-4 py-2.5 shadow-sm">
                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                    <span class="text-xs font-medium text-gray-600">Sistem Aktif</span>
                </div>
            </div>

            {{-- İstatistik Kartları --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if(Auth::user()->isPatron())
                    <a href="{{ route('trucks.index') }}" class="stat-card group">
                        <div class="shine"></div>
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-200 group-hover:shadow-amber-300 transition-all duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-medium text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100">Toplam</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $truckCount }}</p>
                        <p class="text-sm text-gray-500 font-medium">Kayıtlı Tır</p>
                        <p
                            class="mt-3 text-amber-600 text-xs font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                            Tırları Görüntüle <span>→</span>
                        </p>
                    </a>
                @endif

                <a href="{{ route('trips.index') }}" class="stat-card group">
                    <div class="shine"></div>
                    <div class="flex items-center justify-between mb-3">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-200 group-hover:shadow-blue-300 transition-all duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        @if($activeTripsCount > 0)
                            <span
                                class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100 badge-active">●
                                Devam Ediyor</span>
                        @else
                            <span
                                class="text-xs font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">Pasif</span>
                        @endif
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $activeTripsCount }}</p>
                    <p class="text-sm text-gray-500 font-medium">Devam Eden Sefer</p>
                    <p
                        class="mt-3 text-blue-600 text-xs font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                        Seferleri Gör <span>→</span>
                    </p>
                </a>

                @if(Auth::user()->isPatron())
                    <a href="{{ route('drivers.index') }}" class="stat-card group">
                        <div class="shine"></div>
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-200 group-hover:shadow-violet-300 transition-all duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-medium text-violet-600 bg-violet-50 px-2.5 py-1 rounded-full border border-violet-100">Toplam</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $driverCount }}</p>
                        <p class="text-sm text-gray-500 font-medium">Kayıtlı Şoför</p>
                        <p
                            class="mt-3 text-violet-600 text-xs font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                            Şoförleri Gör <span>→</span>
                        </p>
                    </a>

                    <a href="{{ route('reports.index') }}" class="stat-card group">
                        <div class="shine"></div>
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200 group-hover:shadow-emerald-300 transition-all duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">Bu
                                Ay</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">
                            {{ number_format($totalExpenseThisMonth, 0, ',', '.') }}<span
                                class="text-lg font-semibold text-gray-500 ml-0.5">₺</span>
                        </p>
                        <p class="text-sm text-gray-500 font-medium">Toplam Masraf</p>
                        <p
                            class="mt-3 text-emerald-600 text-xs font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                            Raporları Gör <span>→</span>
                        </p>
                    </a>
                @endif
            </div>

            {{-- Gecikmiş Bakımlar --}}
            @if ($maintenanceOverdue->isNotEmpty())
                <div class="bg-gradient-to-r from-red-50 to-red-50/60 border border-red-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center">
                                <span class="text-base">⚠️</span>
                            </div>
                            <h3 class="text-sm font-bold text-red-900">Gecikmiş Bakımlar</h3>
                            <span
                                class="text-xs font-bold text-white bg-red-500 px-2 py-0.5 rounded-full">{{ $maintenanceOverdue->count() }}</span>
                        </div>
                        <a href="{{ route('maintenances.index') }}"
                            class="text-red-700 text-xs font-semibold hover:underline flex items-center gap-1">Tümünü Gör
                            →</a>
                    </div>
                    <div class="space-y-2">
                        @foreach ($maintenanceOverdue as $m)
                            <div
                                class="flex justify-between items-center py-2.5 px-4 bg-white/60 rounded-xl border border-red-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>
                                    <span class="font-semibold text-red-800 text-sm">{{ $m->truck->plate }}</span>
                                    <span class="text-red-700 text-sm">—
                                        {{ \App\Models\Maintenance::TYPE_LABELS[$m->type] ?? $m->type }}</span>
                                </div>
                                <span
                                    class="text-xs text-red-600 font-semibold bg-red-100 px-2.5 py-1 rounded-full">{{ $m->due_date->format('d.m.Y') }}
                                    geçmiş</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Yaklaşan Bakımlar --}}
            @if ($maintenanceUpcoming->isNotEmpty())
                <div
                    class="bg-gradient-to-r from-amber-50 to-amber-50/60 border border-amber-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
                                <span class="text-base">🔧</span>
                            </div>
                            <h3 class="text-sm font-bold text-amber-900">Yaklaşan Bakımlar</h3>
                            <span
                                class="text-xs font-bold text-white bg-amber-500 px-2 py-0.5 rounded-full">{{ $maintenanceUpcoming->count() }}</span>
                        </div>
                        <a href="{{ route('maintenances.index') }}"
                            class="text-amber-700 text-xs font-semibold hover:underline flex items-center gap-1">Tümünü Gör
                            →</a>
                    </div>
                    <div class="space-y-2">
                        @foreach ($maintenanceUpcoming as $m)
                            <div
                                class="flex justify-between items-center py-2.5 px-4 bg-white/60 rounded-xl border border-amber-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                                    <span class="font-semibold text-gray-800 text-sm">{{ $m->truck->plate }}</span>
                                    <span class="text-gray-700 text-sm">—
                                        {{ \App\Models\Maintenance::TYPE_LABELS[$m->type] ?? $m->type }}</span>
                                </div>
                                <span
                                    class="text-xs text-amber-700 font-semibold bg-amber-100 px-2.5 py-1 rounded-full">{{ $m->due_date->format('d.m.Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Son Seferler --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div
                    class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow shadow-amber-200">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Son Seferler</h3>
                    </div>
                    <a href="{{ route('trips.index') }}"
                        class="text-amber-600 text-sm font-semibold hover:text-amber-700 flex items-center gap-1 hover:gap-2 transition-all duration-150">
                        Tümünü Gör <span>→</span>
                    </a>
                </div>

                @php
                    $statusMap = [
                        'planned' => ['label' => 'Planlandı', 'class' => 'bg-blue-100 text-blue-700 border-blue-200', 'dot' => 'bg-blue-400'],
                        'in_progress' => ['label' => 'Devam Ediyor', 'class' => 'bg-amber-100 text-amber-700 border-amber-200', 'dot' => 'bg-amber-400'],
                        'completed' => ['label' => 'Tamamlandı', 'class' => 'bg-green-100 text-green-700 border-green-200', 'dot' => 'bg-green-400'],
                        'cancelled' => ['label' => 'İptal', 'class' => 'bg-red-100 text-red-700 border-red-200', 'dot' => 'bg-red-400'],
                    ];
                @endphp

                @if ($recentTrips->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm mb-5 font-medium">Henüz sefer eklenmemiş.</p>
                        <a href="{{ route('trips.create') }}" class="btn-primary inline-flex items-center gap-1.5 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            İlk Seferi Ekle
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100"
                                    style="background: linear-gradient(90deg, #f8fafc, #f1f5f9);">
                                    <th
                                        class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Tır</th>
                                    <th
                                        class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Güzergah</th>
                                    <th
                                        class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Tarih</th>
                                    <th
                                        class="px-5 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Durum</th>
                                    <th
                                        class="px-5 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($recentTrips as $trip)
                                    @php $st = $statusMap[$trip->status] ?? ['label' => $trip->status, 'class' => 'bg-gray-100 text-gray-700 border-gray-200', 'dot' => 'bg-gray-400']; @endphp
                                    <tr class="hover:bg-amber-50/40 transition-all duration-150 group">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2.5">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm shrink-0">
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-gray-900 text-sm">{{ $trip->truck->plate }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="font-medium text-gray-800 text-sm block">{{ $trip->route_display }}</span>
                                            @if($trip->truck->driver_display_name)
                                                <span class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ $trip->truck->driver_display_name }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="text-xs font-medium text-gray-500 bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-lg">
                                                {{ $trip->departure_date->format('d.m.Y') }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border {{ $st['class'] }}">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }} {{ $trip->status === 'in_progress' ? 'badge-active' : '' }}"></span>
                                                {{ $st['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                                {{-- Sefere Başla --}}
                                                @if($trip->status === 'planned' || $trip->status === 'cancelled')
                                                    <form action="{{ route('trips.start', $trip) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-xs font-bold rounded-lg shadow-sm shadow-green-200 hover:shadow-green-300 transition-all duration-200">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5"
                                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                            </svg>
                                                            Başlat
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Seferi Bitir --}}
                                                @if($trip->status === 'in_progress')
                                                    <form action="{{ route('trips.end', $trip) }}" method="POST"
                                                        onsubmit="return confirm('Seferi bitirmek istediğinize emin misiniz?');">
                                                        @csrf
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-xs font-bold rounded-lg shadow-sm shadow-red-200 hover:shadow-red-300 transition-all duration-200">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5" d="M9 10h6v4H9z" />
                                                            </svg>
                                                            Bitir
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('trips.show', $trip) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-amber-100 text-gray-600 hover:text-amber-800 text-xs font-semibold rounded-lg transition-all duration-150 group-hover:border-amber-200">
                                                    Detay
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-3.5 border-t border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <p class="text-xs text-gray-400">Son {{ $recentTrips->count() }} sefer gösteriliyor</p>
                        <a href="{{ route('trips.create') }}" class="btn-primary inline-flex items-center gap-1.5 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Yeni Sefer Ekle
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>