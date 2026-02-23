<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Şoförler') }}
            </h2>
            <div class="flex gap-2 w-full sm:w-auto">
                <form method="GET" action="{{ route('drivers.index') }}" class="flex-1 sm:flex-initial">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Şoför adı veya telefon ara..."
                        class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                </form>
                <a href="{{ route('drivers.create') }}" class="btn-primary shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Yeni Şoför
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-emerald-50 text-emerald-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="p-6">
                    @if ($drivers->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <p class="mt-4 text-gray-500">{{ __('Henüz şoför eklenmemiş.') }}</p>
                            <a href="{{ route('drivers.create') }}" class="mt-4 btn-primary inline-flex">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                {{ __('İlk Şoförü Ekle') }}
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($drivers as $driver)
                                <div class="card p-5 group">
                                    <div class="space-y-2">
                                        <p class="text-xs text-gray-500 uppercase">Şoför</p>
                                        <p class="font-bold text-lg">
                                            <a href="{{ route('drivers.show', $driver) }}" class="text-amber-600 hover:text-amber-700">{{ $driver->name }}</a>
                                        </p>
                                        @if($driver->phone)
                                            <p class="text-xs text-gray-500">Telefon</p>
                                            <p class="text-gray-700 text-sm">{{ $driver->phone }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 uppercase mt-2">Sürdüğü Tır Sayısı</p>
                                        <p class="font-medium">{{ $driver->trucks_count }} tır</p>
                                        <p class="text-xs text-gray-500 uppercase">Bu Ay Sefer</p>
                                        <p class="font-medium text-amber-700">{{ $driver->tripsCountThisMonth() }} sefer</p>
                                        <div class="pt-3 flex gap-2 flex-wrap">
                                            <a href="{{ route('drivers.show', $driver) }}" class="text-amber-600 text-sm font-medium hover:text-amber-700">Detay</a>
                                            <a href="{{ route('drivers.edit', $driver) }}" class="text-gray-600 text-sm hover:text-gray-800">Düzenle</a>
                                            <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Bu şoförü silmek istediğinize emin misiniz?');">
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
                            {{ $drivers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
