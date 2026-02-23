<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Yeni Sefer Ekle') }}
            </h2>
            <a href="{{ route('trips.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                {{ __('Geri') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('trips.store') }}">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="truck_id" :value="__('Tır')" />
                                <select id="truck_id" name="truck_id" required
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Tır seçin') }}</option>
                                    @foreach ($trucks as $t)
                                        <option value="{{ $t->id }}" {{ old('truck_id', $truck?->id) == $t->id ? 'selected' : '' }}>
                                            {{ $t->plate }} - {{ $t->brand }} {{ $t->model }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('truck_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="departure_date" :value="__('Çıkış Tarihi')" />
                                <x-text-input id="departure_date" class="block mt-1 w-full" type="date" name="departure_date" :value="old('departure_date', now()->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('departure_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="origin" :value="__('Nereden')" />
                                <input type="text" id="origin" name="origin" list="iller-list" autocomplete="off"
                                    value="{{ old('origin') }}" placeholder="Yazın veya listeden seçin (opsiyonel)"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <datalist id="iller-list">
                                    @foreach(config('provinces') as $il)
                                        <option value="{{ $il }}"></option>
                                    @endforeach
                                </datalist>
                                <x-input-error :messages="$errors->get('origin')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="destination" :value="__('Nereye')" />
                                <input type="text" id="destination" name="destination" list="iller-list2" autocomplete="off" required
                                    value="{{ old('destination') }}" placeholder="Yazın veya listeden seçin"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <datalist id="iller-list2">
                                    @foreach(config('provinces') as $il)
                                        <option value="{{ $il }}"></option>
                                    @endforeach
                                </datalist>
                                <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="stopovers" :value="__('Duracağı Yerler (opsiyonel)')" />
                                <input type="text" id="stopovers" name="stopovers" list="iller-list3" autocomplete="off"
                                    value="{{ old('stopovers') }}"
                                    placeholder="Yazın veya listeden seçin, virgülle ayırın (boş bırakılabilir)"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <datalist id="iller-list3">
                                    @foreach(config('provinces') as $il)
                                        <option value="{{ $il }}"></option>
                                    @endforeach
                                </datalist>
                                <p class="mt-1 text-xs text-gray-500">Zorunlu değil. Virgülle ayırarak birden fazla il ekleyebilirsiniz.</p>
                                <x-input-error :messages="$errors->get('stopovers')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Durum')" />
                                <select id="status" name="status" required
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="planned" {{ old('status', 'planned') == 'planned' ? 'selected' : '' }}>Planlandı</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>Devam Ediyor</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>İptal</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="customer_id" :value="__('Müşteri (opsiyonel)')" />
                                <select id="customer_id" name="customer_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">— Seçin —</option>
                                    @foreach($customers ?? [] as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="commission_amount" :value="__('Komisyon Ücreti (TL)')" />
                                <x-text-input id="commission_amount" class="block mt-1 w-full" type="number" name="commission_amount" :value="old('commission_amount', 0)" step="0.01" min="0" />
                                <x-input-error :messages="$errors->get('commission_amount')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="revenue_amount" :value="__('Sefer Geliri (TL)')" />
                                    <x-text-input id="revenue_amount" class="block mt-1 w-full" type="number" name="revenue_amount" :value="old('revenue_amount')" step="0.01" min="0" placeholder="Tahsil edilecek tutar" />
                                </div>
                                <div>
                                    <x-input-label for="payment_status" :value="__('Ödeme Durumu')" />
                                    <select id="payment_status" name="payment_status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach(\App\Models\Trip::PAYMENT_STATUS_LABELS as $v => $l)
                                            <option value="{{ $v }}" {{ old('payment_status', 'bekliyor') == $v ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="days_stayed" :value="__('Kaç Gün Kaldı')" />
                                <x-text-input id="days_stayed" class="block mt-1 w-full" type="number" name="days_stayed" :value="old('days_stayed')" min="0" placeholder="Seferde kaç gün kalındı" />
                                <p class="mt-1 text-xs text-gray-500">Seferde toplam kaç gün kalındı (opsiyonel)</p>
                                <x-input-error :messages="$errors->get('days_stayed')" class="mt-2" />
                            </div>

                            <div class="border-t pt-4 mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Km Takibi</p>
                                <div class="mb-3 flex items-center gap-2 flex-wrap">
                                    <button type="button" id="btn-estimate-km" class="text-sm px-3 py-1.5 bg-amber-100 text-amber-800 rounded-lg hover:bg-amber-200">
                                        Km tahmin et
                                    </button>
                                    <span id="estimate-result" class="text-sm text-gray-500"></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="start_km" :value="__('Başlangıç Km')" />
                                        <x-text-input id="start_km" class="block mt-1 w-full" type="number" name="start_km" :value="old('start_km')" min="0" placeholder="Örn: 125000" />
                                        <x-input-error :messages="$errors->get('start_km')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="end_km" :value="__('Bitiş Km')" />
                                        <x-text-input id="end_km" class="block mt-1 w-full" type="number" name="end_km" :value="old('end_km')" min="0" placeholder="Sefer bitince girilir" />
                                        <x-input-error :messages="$errors->get('end_km')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-4 mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Yükleme / Boşaltma</p>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="cargo_type" :value="__('Yük Tipi')" />
                                        <select id="cargo_type" name="cargo_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">— Seçin —</option>
                                            @foreach(\App\Models\Trip::CARGO_TYPE_LABELS as $val => $lbl)
                                                <option value="{{ $val }}" {{ old('cargo_type') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="load_weight" :value="__('Yük Ağırlığı (ton)')" />
                                        <x-text-input id="load_weight" class="block mt-1 w-full" type="number" name="load_weight" :value="old('load_weight')" step="0.01" min="0" placeholder="Örn: 24" />
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="loading_date" :value="__('Yükleme Tarihi')" />
                                            <x-text-input id="loading_date" class="block mt-1 w-full" type="date" name="loading_date" :value="old('loading_date')" />
                                        </div>
                                        <div>
                                            <x-input-label for="unloading_date" :value="__('Boşaltma Tarihi')" />
                                            <x-text-input id="unloading_date" class="block mt-1 w-full" type="date" name="unloading_date" :value="old('unloading_date')" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="receiver_name" :value="__('Alıcı / Firma Adı')" />
                                        <x-text-input id="receiver_name" class="block mt-1 w-full" type="text" name="receiver_name" :value="old('receiver_name')" placeholder="Teslim alan firma" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="notes" :value="__('Notlar')" />
                                <textarea id="notes" name="notes" rows="3"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <x-primary-button>{{ __('Kaydet') }}</x-primary-button>
                            <a href="{{ route('trips.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                {{ __('İptal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('btn-estimate-km')?.addEventListener('click', function() {
            var origin = document.getElementById('origin')?.value?.trim();
            var dest = document.getElementById('destination')?.value?.trim();
            var resultEl = document.getElementById('estimate-result');
            resultEl.textContent = 'Hesaplanıyor...';
            if (!origin || !dest) {
                resultEl.textContent = 'Nereden ve nereye girin.';
                return;
            }
            fetch('{{ route("trips.estimate-km") }}?origin=' + encodeURIComponent(origin) + '&destination=' + encodeURIComponent(dest))
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.km) {
                        resultEl.textContent = 'Tahmini: ~' + data.km + ' km';
                        resultEl.className = 'text-sm font-medium text-amber-700';
                    } else {
                        resultEl.textContent = data.error || 'Hesaplanamadı.';
                        resultEl.className = 'text-sm text-gray-500';
                    }
                })
                .catch(function() {
                    resultEl.textContent = 'Bağlantı hatası.';
                    resultEl.className = 'text-sm text-gray-500';
                });
        });
    </script>
</x-app-layout>
