<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Şoför Düzenle') }} - {{ $driver->name }}
            </h2>
            <a href="{{ route('trucks.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                {{ __('Geri') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('drivers.update', $driver) }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="name" :value="__('Şoför Adı')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $driver->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="phone" :value="__('Telefon')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $driver->phone)" placeholder="05XX XXX XX XX" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-4 items-center">
                            <x-primary-button>{{ __('Güncelle') }}</x-primary-button>
                            <a href="{{ route('trucks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">İptal</a>
                            <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Bu şoförü silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">{{ __('Şoförü Sil') }}</button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
