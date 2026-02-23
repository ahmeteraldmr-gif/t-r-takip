<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Müşteriler / Firmalar</h2>
            <div class="flex gap-2 w-full sm:w-auto">
                <form method="GET" action="{{ route('customers.index') }}" class="flex-1 sm:flex-initial">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Ad, telefon, e-posta ara..."
                        class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                </form>
                <a href="{{ route('customers.create') }}" class="btn-primary shrink-0">+ Müşteri Ekle</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-emerald-50 text-emerald-800 rounded-xl shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($customers->isEmpty())
                <div class="card p-10 text-center">
                    <p class="text-gray-500">Henüz müşteri eklenmemiş.</p>
                    <a href="{{ route('customers.create') }}" class="mt-4 btn-primary inline-flex items-center justify-center">
                        + İlk Müşteriyi Ekle
                    </a>
                </div>
            @else
                <div class="space-y-4 sm:space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($customers as $c)
                            <div class="card p-5 flex flex-col gap-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a href="{{ route('customers.show', $c) }}" class="text-base font-semibold text-amber-700 hover:text-amber-800">
                                            {{ $c->name }}
                                        </a>
                                        @if($c->phone || $c->email)
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $c->phone ?? '-' }}
                                                @if($c->phone && $c->email)
                                                    ·
                                                @endif
                                                {{ $c->email ?? '' }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                        {{ $c->trips_count }} sefer
                                    </span>
                                </div>
                                @if($c->address)
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $c->address }}</p>
                                @endif
                                <div class="mt-1 flex flex-wrap gap-2 items-center">
                                    <a href="{{ route('customers.show', $c) }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Detay</a>
                                    <a href="{{ route('customers.edit', $c) }}" class="text-sm text-gray-500 hover:text-gray-800">Düzenle</a>
                                    <form action="{{ route('customers.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Sil</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
