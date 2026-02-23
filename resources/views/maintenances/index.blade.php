<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bakım Hatırlatmaları
            </h2>
            <a href="{{ route('maintenances.create') }}" class="btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Bakım Hatırlatması Ekle
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 text-emerald-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Gecikmiş Bakımlar --}}
            @if ($overdue->isNotEmpty())
                <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-red-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        Gecikmiş Bakımlar
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($overdue as $m)
                            <div class="bg-white rounded-lg p-4 border border-red-200">
                                <p class="font-bold text-red-800">{{ $m->truck->plate }}</p>
                                <p class="text-sm">{{ \App\Models\Maintenance::TYPE_LABELS[$m->type] ?? $m->type }}</p>
                                <p class="text-xs text-red-600">Vade: {{ $m->due_date->format('d.m.Y') }} (geçmiş)</p>
                                <div class="mt-2 flex gap-2">
                                    <form action="{{ route('maintenances.mark-done', $m) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="last_done_date" value="{{ now()->format('Y-m-d') }}">
                                        <button type="submit" class="text-green-600 text-sm font-medium hover:underline">Yapıldı</button>
                                    </form>
                                    <a href="{{ route('maintenances.edit', $m) }}" class="text-indigo-600 text-sm">Düzenle</a>
                                    <form action="{{ route('maintenances.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 text-sm">Sil</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Yaklaşan Bakımlar --}}
            @if ($upcoming->isNotEmpty())
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-amber-900 mb-4">Yaklaşan Bakımlar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($upcoming as $m)
                            <div class="bg-white rounded-lg p-4 border border-amber-100">
                                <p class="font-bold text-gray-900">{{ $m->truck->plate }}</p>
                                <p class="text-sm">{{ \App\Models\Maintenance::TYPE_LABELS[$m->type] ?? $m->type }}</p>
                                <p class="text-xs text-amber-700">Vade: {{ $m->due_date->format('d.m.Y') }}</p>
                                @php $days = now()->diffInDays($m->due_date, false); @endphp
                                @if ($days <= 7 && $days >= 0)
                                    <span class="text-xs bg-amber-200 text-amber-900 px-2 py-0.5 rounded mt-1 inline-block">{{ $days }} gün kaldı</span>
                                @endif
                                <div class="mt-2 flex gap-2">
                                    <form action="{{ route('maintenances.mark-done', $m) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="last_done_date" value="{{ now()->format('Y-m-d') }}">
                                        <button type="submit" class="text-green-600 text-sm font-medium hover:underline">Yapıldı</button>
                                    </form>
                                    <a href="{{ route('maintenances.edit', $m) }}" class="text-indigo-600 text-sm">Düzenle</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Tır Bazında Tüm Hatırlatmalar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tır Bazında Bakım Hatırlatmaları</h3>
                    @forelse ($trucks as $truck)
                        @php $pending = $truck->maintenances->whereNull('last_done_date'); @endphp
                        @if ($pending->isNotEmpty())
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-800 mb-2">{{ $truck->plate }} — {{ $truck->brand }} {{ $truck->model }}</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($pending as $m)
                                        <div class="border border-gray-200 rounded-lg p-3 flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium">{{ \App\Models\Maintenance::TYPE_LABELS[$m->type] ?? $m->type }}</p>
                                                <p class="text-xs text-gray-500">Vade: {{ $m->due_date->format('d.m.Y') }}</p>
                                            </div>
                                            <div class="flex gap-2">
                                                <form action="{{ route('maintenances.mark-done', $m) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="last_done_date" value="{{ now()->format('Y-m-d') }}">
                                                    <button type="submit" class="text-green-600 text-xs hover:underline">Yapıldı</button>
                                                </form>
                                                <a href="{{ route('maintenances.edit', $m) }}" class="text-indigo-600 text-xs">Düzenle</a>
                                                <form action="{{ route('maintenances.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 text-xs">Sil</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-gray-500">Henüz tır yok.</p>
                    @endforelse
                    @if ($trucks->isNotEmpty() && $overdue->isEmpty() && $upcoming->isEmpty())
                        <p class="text-gray-500">Henüz bekleyen bakım hatırlatması yok.</p>
                        <a href="{{ route('maintenances.create') }}" class="text-indigo-600 text-sm mt-2 inline-block">+ Bakım hatırlatması ekle</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
