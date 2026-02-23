<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Müşteri Düzenle: {{ $customer->name }}</h2>
    </x-slot>
    <div class="py-6 sm:py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-6">
            <form method="POST" action="{{ route('customers.update', $customer) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <x-input-label for="name" :value="__('Firma / Ad')" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $customer->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Telefon')" />
                        <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone', $customer->phone)" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('E-posta')" />
                        <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $customer->email)" />
                    </div>
                    <div>
                        <x-input-label for="address" :value="__('Adres')" />
                        <textarea id="address" name="address" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('address', $customer->address) }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="notes" :value="__('Notlar')" />
                        <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $customer->notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-4 items-center">
                    <x-primary-button>Güncelle</x-primary-button>
                    <a href="{{ route('customers.index') }}" class="btn-secondary">İptal</a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Müşteriyi Sil</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
