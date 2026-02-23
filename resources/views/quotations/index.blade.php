<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fiyat Teklifleri</h2>
            <div class="flex gap-2 w-full sm:w-auto">
                <form method="GET" action="{{ route('quotations.index') }}" class="flex-1 sm:flex-initial">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Ara..."
                        class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                </form>
                <a href="{{ route('quotations.create') }}" class="btn-primary shrink-0">+ Teklif Ekle</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-emerald-50 text-emerald-800 rounded-lg">{{ session('status') }}</div>
            @endif

            @if ($quotations->isEmpty())
                <div class="card p-12 text-center">
                    <p class="text-gray-500">Henüz teklif yok.</p>
                    <a href="{{ route('quotations.create') }}" class="mt-4 btn-primary inline-block">+ İlk Teklifi Ekle</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($quotations as $q)
                        <div class="card p-5">
                            <p class="font-bold text-gray-900">{{ $q->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $q->customer?->name ?? '-' }}</p>
                            <p class="text-sm">{{ $q->origin ?? '-' }} → {{ $q->destination ?? '-' }}</p>
                            <p class="font-bold text-gray-700 mt-2">{{ number_format($q->amount, 2, ',', '.') }} TL</p>
                            <span class="inline-block mt-2 px-2 py-0.5 text-xs rounded
                                @if($q->status==='onaylandi') bg-green-100 text-green-800
                                @elseif($q->status==='reddedildi') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ \App\Models\Quotation::STATUS_LABELS[$q->status] ?? $q->status }}
                            </span>
                            <div class="mt-3 flex flex-wrap gap-2 items-center">
                                <a href="{{ route('quotations.show', $q) }}" class="text-gray-600 text-sm hover:text-gray-900">Detay</a>
                                <a href="{{ route('quotations.edit', $q) }}" class="text-gray-600 text-sm hover:text-gray-900">Düzenle</a>
                                <form action="{{ route('quotations.destroy', $q) }}" method="POST" class="inline" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-sm hover:text-red-800">Sil</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $quotations->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
