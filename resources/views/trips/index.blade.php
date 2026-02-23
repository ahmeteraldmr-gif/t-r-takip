<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">🚛 Seferler</h2>
            <div class="flex gap-2 w-full sm:w-auto">
                <form method="GET" action="{{ route('trips.index') }}" class="flex-1 sm:flex-initial">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Plaka veya güzergah ara..."
                        class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                </form>
                <a href="{{ route('trips.create') }}" class="btn-primary shrink-0 inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Sefer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('status'))
                <div
                    class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            @php
                $statusLabels = [
                    'planned' => ['label' => 'Planlandı', 'class' => 'bg-blue-100 text-blue-800'],
                    'in_progress' => ['label' => 'Devam Ediyor', 'class' => 'bg-amber-100 text-amber-800'],
                    'completed' => ['label' => 'Tamamlandı', 'class' => 'bg-green-100 text-green-800'],
                    'cancelled' => ['label' => 'İptal', 'class' => 'bg-red-100 text-red-800'],
                ];
            @endphp

            @if ($trips->isEmpty())
                <div class="bg-white rounded-xl border border-gray-200 text-center py-16">
                    <svg class="mx-auto h-14 w-14 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1m0 0h10l2 1m-12 0l2-1m10 1l-2-1" />
                    </svg>
                    <p class="text-gray-400 mb-4">Henüz sefer eklenmemiş.</p>
                    <a href="{{ route('trips.create') }}" class="btn-primary inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        İlk Seferi Ekle
                    </a>
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tır</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Güzergah
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Durum
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Yakıt (L)
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Km</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-amber-600 uppercase">Toplam
                                        Masraf</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">İşlem
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($trips as $trip)
                                    @php $st = $statusLabels[$trip->status] ?? ['label' => $trip->status, 'class' => 'bg-gray-100 text-gray-700']; @endphp
                                    <tr class="hover:bg-amber-50 transition group">
                                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $trip->truck->plate }}</td>
                                        <td class="px-4 py-3 text-gray-700 max-w-[200px]">
                                            <span class="truncate block"
                                                title="{{ $trip->route_display }}">{{ $trip->route_display }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                            {{ $trip->departure_date->format('d.m.Y') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $st['class'] }}">
                                                {{ $st['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-700">
                                            {{ $trip->total_liters > 0 ? number_format($trip->total_liters, 0) . ' L' : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-700">
                                            {{ $trip->total_km ? number_format($trip->total_km, 0, ',', '.') . ' km' : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-amber-700">
                                            {{ number_format($trip->total_expense, 0, ',', '.') }} TL
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                                {{-- Sefere Başla --}}
                                                @if($trip->status === 'planned' || $trip->status === 'cancelled')
                                                    <form action="{{ route('trips.start', $trip) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 10h6v4H9z" />
                                                            </svg>
                                                            Bitir
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('trips.show', $trip) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-semibold rounded-lg transition">
                                                    Detay
                                                </a>

                                                <form action="{{ route('trips.destroy', $trip) }}" method="POST"
                                                    onsubmit="return confirm('Bu seferi silmek istediğinize emin misiniz?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition"
                                                        title="Sil">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($trips->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100">
                            {{ $trips->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>