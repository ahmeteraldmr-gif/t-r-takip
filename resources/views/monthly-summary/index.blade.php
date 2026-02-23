<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aylık Özet</h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Hızlı filtre --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('monthly-summary.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ request('start_date') == now()->startOfMonth()->format('Y-m-d') ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Bu Ay
                </a>
                <a href="{{ route('monthly-summary.index', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ request('start_date') == now()->subMonth()->startOfMonth()->format('Y-m-d') ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Geçen Ay
                </a>
                <a href="{{ route('monthly-summary.index') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('start_date') ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Tümü
                </a>
            </div>

            @if (request('start_date'))
                <p class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d.m.Y') }} — {{ \Carbon\Carbon::parse(request('end_date'))->format('d.m.Y') }}
                </p>
            @endif

            {{-- Özet kartlar --}}
            <div class="space-y-3">
                <div class="card p-4 flex justify-between items-center">
                    <span class="text-gray-600">Sefer</span>
                    <span class="font-bold text-lg">{{ $sefer }}</span>
                </div>
                <div class="card p-4 flex justify-between items-center">
                    <span class="text-gray-600">Km</span>
                    <span class="font-bold text-lg">{{ number_format($km, 0) }} km</span>
                </div>
                <div class="card p-4 flex justify-between items-center">
                    <span class="text-gray-600">Toplam Masraf</span>
                    <span class="font-bold text-lg text-red-700">{{ number_format($masraf, 2, ',', '.') }} TL</span>
                </div>
                <div class="card p-4 flex justify-between items-center">
                    <span class="text-gray-600">Toplam Gelir</span>
                    <span class="font-bold text-lg text-green-700">{{ number_format($gelir, 2, ',', '.') }} TL</span>
                </div>
                <div class="card p-4 flex justify-between items-center border-2 {{ $kar >= 0 ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                    <span class="font-medium">Kar / Zarar</span>
                    <span class="font-bold text-xl {{ $kar >= 0 ? 'text-green-800' : 'text-red-800' }}">
                        {{ number_format($kar, 2, ',', '.') }} TL
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
