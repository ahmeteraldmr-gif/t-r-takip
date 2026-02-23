<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bakım Hatırlatması Düzenle</h2>
            <a href="{{ route('maintenances.index') }}" class="btn-secondary text-sm">← Geri</a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('maintenances.update', $maintenance) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="truck_id" :value="__('Tır')" />
                                <select id="truck_id" name="truck_id" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($trucks as $t)
                                        <option value="{{ $t->id }}" {{ old('truck_id', $maintenance->truck_id) == $t->id ? 'selected' : '' }}>
                                            {{ $t->plate }} — {{ $t->brand }} {{ $t->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="type" :value="__('Bakım Tipi')" />
                                <select id="type" name="type" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach (\App\Models\Maintenance::TYPE_LABELS as $val => $lbl)
                                        <option value="{{ $val }}" {{ old('type', $maintenance->type) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="due_date" :value="__('Vade Tarihi')" />
                                <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', $maintenance->due_date->format('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="last_done_date" :value="__('Son Yapıldığı Tarih')" />
                                <x-text-input id="last_done_date" class="block mt-1 w-full" type="date" name="last_done_date" :value="old('last_done_date', $maintenance->last_done_date?->format('Y-m-d'))" />
                                <p class="mt-1 text-xs text-gray-500">Doldurursanız hatırlatma tamamlanmış sayılır.</p>
                            </div>
                            <div>
                                <x-input-label for="last_done_km" :value="__('Son Yapıldığı Km')" />
                                <x-text-input id="last_done_km" class="block mt-1 w-full" type="number" name="last_done_km" :value="old('last_done_km', $maintenance->last_done_km)" min="0" />
                            </div>
                            <div>
                                <x-input-label for="notes" :value="__('Notlar')" />
                                <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $maintenance->notes) }}</textarea>
                            </div>
                            <div>
                                <x-input-label for="cost" :value="__('Maliyet (TL)')" />
                                <x-text-input id="cost" class="block mt-1 w-full" type="number" name="cost" :value="old('cost', $maintenance->cost)" step="0.01" min="0" />
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <x-primary-button>Güncelle</x-primary-button>
                            <a href="{{ route('maintenances.index') }}" class="btn-secondary">İptal</a>
                            <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Sil</button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
