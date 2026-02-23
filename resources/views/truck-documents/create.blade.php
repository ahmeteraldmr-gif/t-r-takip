<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Belge Ekle - {{ $truck->plate }}</h2></x-slot>
    <div class="py-6 sm:py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-6">
            <form method="POST" action="{{ route('truck-documents.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="truck_id" value="{{ $truck->id }}" />
                <div class="space-y-4">
                    <div>
                        <x-input-label for="type" value="Belge Tipi" />
                        <select id="type" name="type" required class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                            @foreach(\App\Models\TruckDocument::TYPE_LABELS as $v=>$l)
                                <option value="{{ $v }}" {{ old('type')==$v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="file" value="Dosya (PDF, JPG, PNG - max 5MB)" />
                        <input id="file" name="file" type="file" accept=".pdf,.jpg,.jpeg,.png" required class="block mt-1 w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700" />
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="expiry_date" value="Son Kullanma Tarihi" />
                        <x-text-input id="expiry_date" name="expiry_date" type="date" class="block mt-1 w-full" :value="old('expiry_date')" />
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notlar" />
                        <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex gap-4">
                    <x-primary-button>Yükle</x-primary-button>
                    <a href="{{ route('trucks.show', $truck) }}" class="btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
