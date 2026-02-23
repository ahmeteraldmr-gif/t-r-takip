<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Müşteri Ekle</h2>
    </x-slot>
    <div class="py-6 sm:py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-6">
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-input-label for="name" value="Firma / Ad" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" value="{{ old('name') }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="phone" value="Telefon" />
                        <x-text-input id="phone" name="phone" class="block mt-1 w-full" value="{{ old('phone') }}" />
                    </div>
                    <div>
                        <x-input-label for="email" value="E-posta" />
                        <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" value="{{ old('email') }}" />
                    </div>
                    <div>
                        <x-input-label for="address" value="Adres" />
                        <textarea id="address" name="address" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notlar" />
                        <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex gap-4">
                    <x-primary-button>Kaydet</x-primary-button>
                    <a href="{{ route('customers.index') }}" class="btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
