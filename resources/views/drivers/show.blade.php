<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $driver->name }}
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('drivers.edit', $driver) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    {{ __('Düzenle') }}
                </a>
                <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Bu şoförü silmek istediğinize emin misiniz?');">
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
            @if (session('status'))
                <div class="p-4 bg-emerald-50 text-emerald-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- İstatistikler --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Şoför Bilgileri & İstatistikler</h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Ad</p>
                            <p class="font-bold text-lg">{{ $driver->name }}</p>
                        </div>
                        @if($driver->phone)
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Telefon</p>
                            <p class="font-medium">{{ $driver->phone }}</p>
                        </div>
                        @endif
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Sürdüğü Tır Sayısı</p>
                            <p class="font-bold text-amber-700">{{ $driver->trucks->count() }}</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Bu Ay Kaç Sefere Gitti</p>
                            <p class="font-bold text-amber-700">{{ $tripsThisMonth }} sefer</p>
                        </div>
                        <div class="flex items-baseline gap-2 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-500 min-w-[140px]">Toplam Sefer</p>
                            <p class="font-bold">{{ $totalTrips }} sefer</p>
                        </div>
                    </div>
                    @if($tripsLastMonth > 0)
                        <p class="mt-3 text-sm text-gray-500">Geçen ay: {{ $tripsLastMonth }} sefer</p>
                    @endif
                </div>
            </div>

            {{-- Sürdüğü Tırlar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sürdüğü Tırlar</h3>
                    @if ($driver->trucks->isEmpty())
                        <p class="text-gray-500">Bu şoföre henüz tır atanmamış. Tır eklerken veya düzenlerken şoför seçebilirsiniz.</p>
                        <a href="{{ route('trucks.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 text-sm font-medium">Tırlara Git →</a>
                    @else
                        <div class="flex flex-col gap-3">
                            @foreach ($driver->trucks as $truck)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition flex flex-col gap-2">
                                    <div class="flex items-baseline gap-2"><p class="text-xs text-gray-500 min-w-[100px]">Plaka</p><p class="font-bold text-gray-900">{{ $truck->plate }}</p></div>
                                    <div class="flex items-baseline gap-2"><p class="text-xs text-gray-500 min-w-[100px]">Marka / Model</p><p class="text-sm text-gray-600">{{ $truck->brand }} {{ $truck->model }}</p></div>
                                    <div class="flex items-baseline gap-2"><p class="text-xs text-gray-500 min-w-[100px]">Sefer</p><p class="text-xs text-gray-500">{{ $truck->trips->count() }} sefer</p></div>
                                    <a href="{{ route('trucks.show', $truck) }}" class="mt-2 inline-block text-amber-600 text-sm font-medium hover:underline">Tır detayı →</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Son Seferler --}}
            @php
                $recentTrips = \App\Models\Trip::whereHas('truck', fn ($q) => $q->where('driver_id', $driver->id))
                    ->with('truck')
                    ->latest('departure_date')
                    ->limit(10)
                    ->get();
            @endphp
            @if($recentTrips->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Son Seferler</h3>
                    <div class="flex flex-col gap-3">
                        @php $statusLabels = ['planned' => 'Planlandı', 'in_progress' => 'Devam Ediyor', 'completed' => 'Tamamlandı', 'cancelled' => 'İptal']; @endphp
                        @foreach ($recentTrips as $trip)
                        <div class="border border-gray-200 rounded-lg p-4 flex flex-col gap-2">
                            <div class="flex items-baseline gap-2"><p class="text-xs text-gray-500 min-w-[100px]">Tarih</p><p class="text-sm">{{ $trip->departure_date->format('d.m.Y') }}</p></div>
                            <div class="flex items-baseline gap-2"><p class="text-xs text-gray-500 min-w-[100px]">Tır</p><p class="text-sm font-medium">{{ $trip->truck->plate }}</p></div>
                            <div class="flex items-baseline gap-2"><p class="text-xs text-gray-500 min-w-[100px]">Güzergah</p><p class="text-sm text-amber-700">{{ $trip->route_display }}</p></div>
                            <div class="flex items-baseline gap-2"><p class="text-xs text-gray-500 min-w-[100px]">Durum</p><span class="px-2 py-1 text-xs rounded-full bg-gray-100">{{ $statusLabels[$trip->status] ?? $trip->status }}</span></div>
                            <a href="{{ route('trips.show', $trip) }}" class="text-indigo-600 text-sm">Detay</a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
