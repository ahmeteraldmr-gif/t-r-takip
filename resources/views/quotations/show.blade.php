<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $quotation->title }}</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('quotations.edit', $quotation) }}" class="btn-secondary">Düzenle</a>
                <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" class="inline" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-red-600 hover:text-red-700 hover:bg-red-50 border-red-200">Sil</button>
                </form>
                <a href="{{ route('quotations.index') }}" class="btn-secondary">Geri</a>
            </div>
        </div>
    </x-slot>
    <div class="py-6 sm:py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card p-6 space-y-4">
            <div><p class="text-sm text-gray-500">Müşteri</p><p class="font-medium">{{ $quotation->customer?->name ?? '-' }}</p></div>
            <div><p class="text-sm text-gray-500">Tutar</p><p class="font-bold text-xl text-amber-700">{{ number_format($quotation->amount, 2, ',', '.') }} TL</p></div>
            <div><p class="text-sm text-gray-500">Güzergah</p><p class="font-medium">{{ $quotation->origin ?? '-' }} - {{ $quotation->destination ?? '-' }}</p></div>
            <div><p class="text-sm text-gray-500">Durum</p><span class="px-2 py-1 text-sm rounded bg-gray-100">{{ \App\Models\Quotation::STATUS_LABELS[$quotation->status] ?? $quotation->status }}</span></div>
        </div>
    </div>
</x-app-layout>
