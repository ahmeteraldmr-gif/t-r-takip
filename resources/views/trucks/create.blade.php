<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Yeni Tır Ekle') }}
            </h2>
            <a href="{{ route('trucks.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                {{ __('Geri') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('trucks.store') }}">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="plate" :value="__('Tır Plakası')" />
                                <x-text-input id="plate" class="block mt-1 w-full" type="text" name="plate" :value="old('plate')" required autofocus placeholder="34 ABC 123" />
                                <x-input-error :messages="$errors->get('plate')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="ruhsat_no" :value="__('Ruhsat No')" />
                                <x-text-input id="ruhsat_no" class="block mt-1 w-full" type="text" name="ruhsat_no" :value="old('ruhsat_no')" required placeholder="Ruhsat belgesindeki numara" />
                                <x-input-error :messages="$errors->get('ruhsat_no')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="brand" :value="__('Tır Markası')" />
                                <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand')" required placeholder="Örn: Mercedes, MAN, Scania" />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="model" :value="__('Model')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" required />
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="driver_id" :value="__('Şoför (Kim Sürüyor)')" />
                                <select id="driver_id" name="driver_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seçin (opsiyonel)</option>
                                    @foreach($drivers ?? [] as $d)
                                        <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Önce <a href="{{ route('drivers.create') }}" class="text-amber-600 hover:underline">Şoförler</a> bölümünden şoför ekleyin</p>
                                <x-input-error :messages="$errors->get('driver_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="status" :value="__('Tır Durumu')" />
                                <select id="status" name="status" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(\App\Models\Truck::STATUS_LABELS as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', 'aktif') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <x-primary-button>{{ __('Kaydet') }}</x-primary-button>
                            <a href="{{ route('trucks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                {{ __('İptal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
