<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Lastik Ekle - {{ $truck->plate }}</h2></x-slot>
    <div class="py-6 sm:py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-6">
            <form method="POST" action="{{ route('tires.store') }}">
                @csrf
                <input type="hidden" name="truck_id" value="{{ $truck->id }}" />
                <div class="space-y-4">
                    <div>
                        <x-input-label for="position" value="Pozisyon" />
                        <select id="position" name="position" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                            @foreach(\App\Models\Tire::POSITION_LABELS as $v=>$l)
                                <option value="{{ $v }}" {{ old('position')==$v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="change_km" value="Değişim Km" />
                        <x-text-input id="change_km" name="change_km" type="number" min="0" class="block mt-1 w-full" :value="old('change_km')" />
                    </div>
                    <div>
                        <x-input-label for="change_date" value="Değişim Tarihi" />
                        <x-text-input id="change_date" name="change_date" type="date" class="block mt-1 w-full" :value="old('change_date')" />
                    </div>
                    <div>
                        <x-input-label for="brand" value="Marka" />
                        <x-text-input id="brand" name="brand" class="block mt-1 w-full" :value="old('brand')" />
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notlar" />
                        <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex gap-4">
                    <x-primary-button>Kaydet</x-primary-button>
                    <a href="{{ route('trucks.show', $truck) }}" class="btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
