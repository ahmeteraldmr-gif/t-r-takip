<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Benzin Ekle') }} - {{ $trip->destination }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('fuel-expenses.store') }}">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $trip->id }}" />

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="date" :value="__('Tarih')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', now()->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="liters" :value="__('Kaç Lt aldı')" />
                                <x-text-input id="liters" class="block mt-1 w-full" type="number" name="liters" :value="old('liters')" step="0.01" min="0" required />
                                <x-input-error :messages="$errors->get('liters')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="price_per_liter" :value="__('Litre fiyatı (TL)')" />
                                <x-text-input id="price_per_liter" class="block mt-1 w-full" type="number" name="price_per_liter" :value="old('price_per_liter')" step="0.01" min="0" required />
                                <x-input-error :messages="$errors->get('price_per_liter')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="liters_used" :value="__('Kaç L kullandı')" />
                                <x-text-input id="liters_used" class="block mt-1 w-full" type="number" name="liters_used" :value="old('liters_used')" step="0.01" min="0" placeholder="Boş bırakırsanız kalan hesaplanmaz" />
                                <p class="mt-1 text-xs text-gray-500">Girerseniz "Ne kadar kaldı" otomatik hesaplanır</p>
                                <x-input-error :messages="$errors->get('liters_used')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="notes" :value="__('Notlar')" />
                                <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <x-primary-button>{{ __('Kaydet') }}</x-primary-button>
                            <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
