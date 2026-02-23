<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Duraklama Ekle — {{ $trip->route_display }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 text-sm">
                Tır durduğunda buradan duraklama yeri ekleyin. Yer adı, ne zaman durduğu ve (isteğe bağlı) ne zaman yola çıktığını girebilirsiniz.
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('trip-stops.store') }}">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $trip->id }}" />

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="location" :value="__('Duraklama Yeri')" />
                                <input type="text" id="location" name="location" list="iller-list" autocomplete="off"
                                    value="{{ old('location') }}" placeholder="Şehir veya yer adı yazın"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <datalist id="iller-list">
                                    @foreach(config('provinces') as $il)
                                        <option value="{{ $il }}"></option>
                                    @endforeach
                                </datalist>
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="stopped_at" :value="__('Ne Zaman Durdu')" />
                                <x-text-input id="stopped_at" class="block mt-1 w-full" type="datetime-local" name="stopped_at" :value="old('stopped_at', now()->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('stopped_at')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="left_at" :value="__('Ne Zaman Yola Çıktı (opsiyonel)')" />
                                <x-text-input id="left_at" class="block mt-1 w-full" type="datetime-local" name="left_at" :value="old('left_at')" />
                                <p class="mt-1 text-xs text-gray-500">Boş bırakırsanız "hâlâ duruyor" sayılır</p>
                                <x-input-error :messages="$errors->get('left_at')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="notes" :value="__('Not')" />
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
