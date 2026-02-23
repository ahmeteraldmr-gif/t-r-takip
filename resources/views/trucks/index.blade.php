<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tırlar & Şoförler') }}
            </h2>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <form method="GET" action="{{ route('trucks.index') }}" class="flex-1 sm:flex-initial min-w-0">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Tır, şoför, plaka, marka ara..."
                        class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                </form>
                <a href="{{ route('drivers.create') }}" class="btn-secondary shrink-0 inline-flex items-center gap-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Yeni Şoför
                </a>
                <a href="{{ route('trucks.create') }}" class="btn-primary shrink-0 inline-flex items-center gap-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Yeni Tır
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 text-emerald-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Şoförler --}}
            <div class="card">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Şoförler
                    </h3>
                    @if ($drivers->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-xl">
                            <p class="text-gray-500">Henüz şoför eklenmemiş.</p>
                            <a href="{{ route('drivers.create') }}" class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-violet-600 text-white rounded-lg text-sm font-medium hover:bg-violet-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                İlk Şoförü Ekle
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col gap-3">
                            @foreach ($drivers as $driver)
                                <div class="border border-gray-100 rounded-xl p-4 hover:shadow-md hover:border-amber-100 transition-all">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 min-w-[100px]">İsim</p>
                                            <p class="font-bold">
                                            <a href="{{ route('drivers.show', $driver) }}" class="text-amber-600 hover:text-amber-700">{{ $driver->name }}</a>
                                        </p>
                                        </div>
                                        @if($driver->phone)
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 min-w-[100px]">Telefon</p>
                                            <p class="text-sm text-gray-600">{{ $driver->phone }}</p>
                                        </div>
                                        @endif
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 min-w-[100px]">Özet</p>
                                            <p class="text-xs text-gray-500">{{ $driver->trucks_count }} tır · Bu ay {{ $driver->tripsCountThisMonth() }} sefer</p>
                                        </div>
                                        <div class="pt-2 flex gap-2 flex-wrap">
                                            <a href="{{ route('drivers.show', $driver) }}" class="text-amber-600 text-sm font-medium">Detay</a>
                                            <a href="{{ route('drivers.edit', $driver) }}" class="text-gray-600 text-sm">Düzenle</a>
                                            <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Bu şoförü silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($drivers->hasPages())
                            <div class="mt-4">{{ $drivers->links() }}</div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Tırlar --}}
            <div class="card">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Tırlar
                    </h3>
                    @if ($trucks->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-xl">
                            <p class="text-gray-500">Henüz tır eklenmemiş.</p>
                            <a href="{{ route('trucks.create') }}" class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-medium hover:bg-amber-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                İlk Tırı Ekle
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col gap-3">
                            @foreach ($trucks as $truck)
                                <div class="border border-gray-100 rounded-xl p-4 hover:shadow-md hover:border-amber-100 transition-all">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 uppercase min-w-[100px]">Plaka</p>
                                            <p class="font-bold text-lg">
                                            <a href="{{ route('trucks.show', $truck) }}" class="text-amber-600 hover:text-amber-700">{{ $truck->plate }}</a>
                                        </p>
                                        </div>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 uppercase min-w-[100px]">Marka / Model</p>
                                            <p class="text-gray-900">{{ $truck->brand }} {{ $truck->model }}</p>
                                        </div>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 uppercase min-w-[100px]">Durum</p>
                                        <span class="inline-block px-2 py-0.5 text-xs rounded-full
                                            @if($truck->status === 'aktif') bg-green-100 text-green-800
                                            @elseif($truck->status === 'bakımda') bg-amber-100 text-amber-800
                                            @elseif($truck->status === 'satıldı') bg-gray-100 text-gray-800
                                            @elseif($truck->status === 'kiralık') bg-blue-100 text-blue-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ \App\Models\Truck::STATUS_LABELS[$truck->status] ?? $truck->status }}
                                        </span>
                                        </div>
                                        @if($truck->driver_display_name)
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 min-w-[100px]">Şoför</p>
                                            <p class="text-gray-700 font-medium text-sm">{{ $truck->driver_display_name }}</p>
                                        </div>
                                        @endif
                                        @php $latestTrip = $truck->trips->first(); @endphp
                                        @if($latestTrip)
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xs text-gray-500 min-w-[100px]">Son Güzergah</p>
                                            <p class="text-sm font-medium text-amber-700">{{ $latestTrip->route_display }}</p>
                                        </div>
                                        @endif
                                        <div class="pt-3 flex gap-2 flex-wrap">
                                            <a href="{{ route('trucks.show', $truck) }}" class="text-amber-600 text-sm font-medium">Görüntüle</a>
                                            <a href="{{ route('trucks.edit', $truck) }}" class="text-gray-600 text-sm">Düzenle</a>
                                            <form action="{{ route('trucks.destroy', $truck) }}" method="POST" class="inline" onsubmit="return confirm('Bu tırı silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $trucks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
