<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Masraf Düzenle') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('other-expenses.update', $otherExpense) }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="date" :value="__('Tarih')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $otherExpense->date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="category" :value="__('Kategori')" />
                                <select id="category" name="category" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="yemek" {{ old('category', $otherExpense->category) == 'yemek' ? 'selected' : '' }}>Yemek</option>
                                    <option value="otel" {{ old('category', $otherExpense->category) == 'otel' ? 'selected' : '' }}>Otel</option>
                                    <option value="yol_geçiş" {{ old('category', $otherExpense->category) == 'yol_geçiş' ? 'selected' : '' }}>Yol Geçiş</option>
                                    <option value="otopark" {{ old('category', $otherExpense->category) == 'otopark' ? 'selected' : '' }}>Otopark</option>
                                    <option value="diğer" {{ old('category', $otherExpense->category) == 'diğer' ? 'selected' : '' }}>Diğer</option>
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="amount" :value="__('Tutar (TL)')" />
                                <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount', $otherExpense->amount)" step="0.01" min="0" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="description" :value="__('Açıklama')" />
                                <textarea id="description" name="description" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $otherExpense->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <x-primary-button>{{ __('Güncelle') }}</x-primary-button>
                            <a href="{{ route('trips.show', $otherExpense->trip) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
