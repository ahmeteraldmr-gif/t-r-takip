<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $customer->name }}</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary">Düzenle</a>
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-red-600 hover:text-red-700 hover:bg-red-50 border-red-200">Sil</button>
                </form>
                <a href="{{ route('customers.index') }}" class="btn-secondary">← Geri</a>
            </div>
        </div>
    </x-slot>
    <div class="py-6 sm:py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">İletişim Bilgileri</h3>
            <div class="flex flex-col gap-2">
                <div class="flex items-baseline gap-2 py-2 border-b border-gray-100"><p class="text-sm text-gray-500 min-w-[100px]">Telefon</p><p class="font-medium">{{ $customer->phone ?? '-' }}</p></div>
                <div class="flex items-baseline gap-2 py-2 border-b border-gray-100"><p class="text-sm text-gray-500 min-w-[100px]">E-posta</p><p class="font-medium">{{ $customer->email ?? '-' }}</p></div>
                @if($customer->address)<div class="flex items-baseline gap-2 py-2 border-b border-gray-100"><p class="text-sm text-gray-500 min-w-[100px]">Adres</p><p class="font-medium">{{ $customer->address }}</p></div>@endif
                @if($customer->notes)<div class="flex items-baseline gap-2 py-2 border-b border-gray-100"><p class="text-sm text-gray-500 min-w-[100px]">Notlar</p><p class="font-medium">{{ $customer->notes }}</p></div>@endif
            </div>
        </div>
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Seferler ({{ $customer->trips->count() }})</h3>
            @if ($customer->trips->isEmpty())
                <p class="text-gray-500">Bu müşteriye ait sefer yok.</p>
            @else
                <div class="space-y-3">
                    @foreach ($customer->trips as $trip)
                        <div class="flex justify-between items-center border border-gray-200 rounded-lg p-4">
                            <div>
                                <p class="font-medium">{{ $trip->truck->plate }} — {{ $trip->route_display }}</p>
                                <p class="text-sm text-gray-500">{{ $trip->departure_date->format('d.m.Y') }}</p>
                            </div>
                            <a href="{{ route('trips.show', $trip) }}" class="text-indigo-600 text-sm">Detay</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
