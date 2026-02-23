<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Teker Patladı, Motor Arızası vb. Sorun Ekle — {{ $trip->destination }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-900 text-sm">
                <strong>Lastik patlama, teker patlama, motor arızası, kaza</strong> gibi çıkan olayları bu formdan ekleyebilirsiniz. Sorun tipini seçin ve maliyeti girin.
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('incidents.store') }}">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $trip->id }}" />

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="date" :value="__('Tarih')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', now()->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="type" :value="__('Sorun Tipi')" />
                                <select id="type" name="type" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Seçin') }}</option>
                                    <option value="lastik_patlama" {{ old('type') == 'lastik_patlama' ? 'selected' : '' }}>Lastik Patlama</option>
                                    <option value="teker_patlama" {{ old('type') == 'teker_patlama' ? 'selected' : '' }}>Teker Patlama</option>
                                    <option value="motor_arızası" {{ old('type') == 'motor_arızası' ? 'selected' : '' }}>Motor Arızası</option>
                                    <option value="kaza" {{ old('type') == 'kaza' ? 'selected' : '' }}>Kaza</option>
                                    <option value="fren_arızası" {{ old('type') == 'fren_arızası' ? 'selected' : '' }}>Fren Arızası</option>
                                    <option value="diğer" {{ old('type') == 'diğer' ? 'selected' : '' }}>Diğer</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="description" :value="__('Açıklama')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cost" :value="__('Maliyet (TL)')" />
                                <x-text-input id="cost" class="block mt-1 w-full" type="number" name="cost" :value="old('cost')" step="0.01" min="0" />
                                <x-input-error :messages="$errors->get('cost')" class="mt-2" />
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
