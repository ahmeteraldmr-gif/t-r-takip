<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $truck->plate }} — {{ $truck->brand }} {{ $truck->model }}
                @if($truck->driver_display_name)
                    <span class="text-amber-700 font-medium">(Şoför: {{ $truck->driver_display_name }})</span>
                @endif
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('trips.create', ['truck' => $truck->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Yeni Sefer Ekle') }}
                </a>
                <a href="{{ route('trucks.edit', $truck) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    {{ __('Düzenle') }}
                </a>
                <form action="{{ route('trucks.destroy', $truck) }}" method="POST" class="inline" onsubmit="return confirm('Bu tırı silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-200 rounded-md font-semibold text-xs text-red-600 uppercase tracking-widest hover:bg-red-50">
                        {{ __('Sil') }}
                    </button>
                </form>
                <a href="{{ route('trucks.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    {{ __('Geri') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Tır özeti --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tır Detayları</h3>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Plaka</p>
                            <p class="font-bold text-lg">{{ $truck->plate }}</p>
                        </div>
                        @if($truck->ruhsat_no)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Ruhsat No</p>
                            <p class="font-medium">{{ $truck->ruhsat_no }}</p>
                        </div>
                        @endif
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Marka / Model</p>
                            <p class="font-medium">{{ $truck->brand }} {{ $truck->model }}</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Şoför (Kim Sürüyor)</p>
                            <p class="font-medium">{{ $truck->driver_display_name ?? '-' }}</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Toplam Sefer</p>
                            <p class="font-medium">{{ $truck->trips->count() }}</p>
                        </div>
                        @if($truck->total_km > 0)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Toplam Km</p>
                            <p class="font-medium">{{ number_format($truck->total_km) }} km</p>
                        </div>
                        @endif
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Tır Durumu</p>
                            <span class="inline-block px-2 py-1 text-sm rounded-full
                                @if($truck->status === 'aktif') bg-green-100 text-green-800
                                @elseif($truck->status === 'bakımda') bg-amber-100 text-amber-800
                                @elseif($truck->status === 'satıldı') bg-gray-100 text-gray-800
                                @elseif($truck->status === 'kiralık') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ \App\Models\Truck::STATUS_LABELS[$truck->status] ?? $truck->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seferler ve güzergahlar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Nerden Nereye — Seferler') }}</h3>
                    @if ($truck->trips->isEmpty())
                        <p class="text-gray-500">{{ __('Bu tır için henüz sefer eklenmemiş.') }}</p>
                        <a href="{{ route('trips.create', ['truck' => $truck->id]) }}"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            {{ __('İlk Seferi Ekle') }}
                        </a>
                    @else
                        <div class="flex flex-col gap-3">
                            @php
                                $statusLabels = ['planned' => 'Planlandı', 'in_progress' => 'Devam Ediyor', 'completed' => 'Tamamlandı', 'cancelled' => 'İptal'];
                            @endphp
                            @foreach ($truck->trips as $trip)
                                <div class="border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 uppercase min-w-[160px]">Çıkış Tarihi</p>
                                            <p class="font-medium text-gray-900">{{ $trip->departure_date->format('d.m.Y') }}</p>
                                        </div>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 uppercase min-w-[160px]">Nerden → Nereye</p>
                                            <p class="font-bold text-amber-700 text-sm leading-tight">{{ $trip->route_display }}</p>
                                        </div>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 uppercase min-w-[160px]">Durum</p>
                                            <span class="px-2 py-1 text-xs rounded-full
                                            @if($trip->status === 'completed') bg-green-100 text-green-800
                                            @elseif($trip->status === 'in_progress') bg-gray-100 text-gray-800
                                            @elseif($trip->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $statusLabels[$trip->status] ?? $trip->status }}
                                        </span>
                                        </div>
                                        <div class="pt-2">
                                            <a href="{{ route('trips.show', $trip) }}" class="text-indigo-600 text-sm font-medium">Detay</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Çıkan Olaylar (Lastik patlama, motor arızası vb.) --}}
            @php
                $allIncidents = $truck->trips->flatMap->incidents->sortByDesc('date');
                $incidentTypeLabels = ['teker_patlama' => 'Teker Patlama', 'lastik_patlama' => 'Lastik Patlama', 'motor_arızası' => 'Motor Arızası', 'kaza' => 'Kaza', 'fren_arızası' => 'Fren Arızası', 'diğer' => 'Diğer'];
            @endphp
            @if($allIncidents->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Çıkan Olaylar (Lastik Patlama, Motor Arızası vb.)</h3>
                    <div class="flex flex-col gap-3">
                        @foreach($allIncidents as $inc)
                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col gap-2">
                                <div class="flex items-baseline gap-2">
                                    <p class="text-xs text-gray-500 min-w-[100px]">Tarih / Sefer</p>
                                    <p class="text-sm text-gray-600">{{ $inc->trip->departure_date->format('d.m.Y') }} — {{ $inc->trip->route_display }}</p>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <p class="text-xs text-gray-500 min-w-[100px]">Olay Türü</p>
                                    <p class="font-medium text-red-700">{{ $incidentTypeLabels[$inc->type] ?? $inc->type }}</p>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <p class="text-xs text-gray-500 min-w-[100px]">Açıklama</p>
                                    <p class="text-sm text-gray-600">{{ $inc->description ?? '-' }}</p>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <p class="text-xs text-gray-500 min-w-[100px]">Maliyet</p>
                                    <p class="text-sm font-medium">{{ $inc->cost ? number_format($inc->cost, 2) . ' TL' : '-' }}</p>
                                </div>
                                <a href="{{ route('trips.show', $inc->trip) }}" class="text-xs text-indigo-600 hover:underline">Sefer detayı →</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Belge ve Lastik --}}
            <div class="flex flex-col gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Belgeler (Muayene, Sigorta, Ruhsat)</h3>
                            <a href="{{ route('truck-documents.create', ['truck' => $truck->id]) }}" class="text-indigo-600 text-sm font-medium">+ Ekle</a>
                        </div>
                        @if($truck->documents->isEmpty())
                            <p class="text-gray-500 text-sm">Henüz belge yok.</p>
                        @else
                            <div class="space-y-2">
                                @foreach($truck->documents as $doc)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                        <div>
                                            <p class="font-medium">{{ \App\Models\TruckDocument::TYPE_LABELS[$doc->type] ?? $doc->type }}</p>
                                            <p class="text-xs text-gray-500">{{ $doc->original_name ?? $doc->path }} {{ $doc->expiry_date ? '· Son: ' . $doc->expiry_date->format('d.m.Y') : '' }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('truck-documents.download', $doc) }}" class="text-indigo-600 text-sm">İndir</a>
                                            <form action="{{ route('truck-documents.destroy', $doc) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Lastik Takibi</h3>
                            <a href="{{ route('tires.create', ['truck' => $truck->id]) }}" class="text-indigo-600 text-sm font-medium">+ Ekle</a>
                        </div>
                        @if($truck->tires->isEmpty())
                            <p class="text-gray-500 text-sm">Henüz lastik kaydı yok.</p>
                        @else
                            <div class="space-y-2">
                                @foreach($truck->tires as $tire)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                        <div>
                                            <p class="font-medium">{{ \App\Models\Tire::POSITION_LABELS[$tire->position] ?? $tire->position }}</p>
                                            <p class="text-xs text-gray-500">{{ $tire->brand ?? '-' }} {{ $tire->change_km ? '· ' . number_format($tire->change_km) . ' km' : '' }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('tires.edit', $tire) }}" class="text-indigo-600 text-sm">Düzenle</a>
                                            <form action="{{ route('tires.destroy', $tire) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
