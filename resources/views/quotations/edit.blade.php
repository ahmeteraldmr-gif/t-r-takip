<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Teklif Düzenle</h2></x-slot>
    <div class="py-6 sm:py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-6">
            <form method="POST" action="{{ route('quotations.update', $quotation) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <x-input-label for="customer_id" value="Müşteri" />
                        <select id="customer_id" name="customer_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">— Seçin —</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id', $quotation->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="title" value="Başlık" />
                        <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title', $quotation->title)" required />
                    </div>
                    <div>
                        <x-input-label for="amount" value="Tutar (TL)" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="old('amount', $quotation->amount)" required />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="origin" value="Nereden" />
                            <x-text-input id="origin" name="origin" class="block mt-1 w-full" :value="old('origin', $quotation->origin)" />
                        </div>
                        <div>
                            <x-input-label for="destination" value="Nereye" />
                            <x-text-input id="destination" name="destination" class="block mt-1 w-full" :value="old('destination', $quotation->destination)" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="cargo_type" value="Yük Tipi" />
                            <select id="cargo_type" name="cargo_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">— Seçin —</option>
                                @foreach(\App\Models\Trip::CARGO_TYPE_LABELS as $v=>$l)
                                    <option value="{{ $v }}" {{ old('cargo_type', $quotation->cargo_type)==$v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="load_weight" value="Yük Ağırlığı (ton)" />
                            <x-text-input id="load_weight" name="load_weight" type="number" step="0.01" class="block mt-1 w-full" :value="old('load_weight', $quotation->load_weight)" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="valid_until" value="Geçerlilik Tarihi" />
                        <x-text-input id="valid_until" name="valid_until" type="date" class="block mt-1 w-full" :value="old('valid_until', $quotation->valid_until?->format('Y-m-d'))" />
                    </div>
                    <div>
                        <x-input-label for="status" value="Durum" />
                        <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                            @foreach(\App\Models\Quotation::STATUS_LABELS as $v=>$l)
                                <option value="{{ $v }}" {{ old('status', $quotation->status)==$v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notlar" />
                        <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $quotation->notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-4 items-center">
                    <x-primary-button>Güncelle</x-primary-button>
                    <a href="{{ route('quotations.index') }}" class="btn-secondary">İptal</a>
                    <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" class="inline" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Teklifi Sil</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
