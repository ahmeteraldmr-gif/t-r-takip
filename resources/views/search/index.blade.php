<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Hızlı Arama</h2>
    </x-slot>
    <div class="py-6 sm:py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('search.index') }}" class="mb-8">
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="search" name="q" value="{{ $q }}" placeholder="Plaka, firma, hedef, güzergah ara..." autofocus
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                <button type="submit" class="btn-primary">Ara</button>
            </div>
        </form>

        @if(strlen($q) < 2)
            <p class="text-gray-500">Aramak için en az 2 karakter girin.</p>
        @else
            <div class="space-y-6">
                @if($trips->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Seferler</h3>
                        <div class="space-y-2">
                            @foreach($trips as $t)
                                <a href="{{ route('trips.show', $t) }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <p class="font-medium">{{ $t->truck->plate }} - {{ $t->route_display }}</p>
                                    <p class="text-sm text-gray-500">{{ $t->departure_date->format('d.m.Y') }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($trucks->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Tırlar</h3>
                        <div class="space-y-2">
                            @foreach($trucks as $t)
                                <a href="{{ route('trucks.show', $t) }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <p class="font-medium">{{ $t->plate }} - {{ $t->brand }} {{ $t->model }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($customers->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Müşteriler</h3>
                        <div class="space-y-2">
                            @foreach($customers as $c)
                                <a href="{{ route('customers.show', $c) }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <p class="font-medium">{{ $c->name }}</p>
                                    @if($c->phone)<p class="text-sm text-gray-500">{{ $c->phone }}</p>@endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($quotations->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Teklifler</h3>
                        <div class="space-y-2">
                            @foreach($quotations as $quotation)
                                <a href="{{ route('quotations.show', $quotation) }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <p class="font-medium">{{ $quotation->title }} - {{ number_format($quotation->amount, 0) }} TL</p>
                                    <p class="text-sm text-gray-500">{{ $quotation->customer?->name ?? '-' }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(strlen($q) >= 2 && $trips->isEmpty() && $trucks->isEmpty() && $customers->isEmpty() && $quotations->isEmpty())
                    <p class="text-gray-500">Sonuç bulunamadı.</p>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
