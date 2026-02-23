<nav x-data="{ open: false }" class="nav-safe"
    style="background: linear-gradient(90deg, #0f172a 0%, #1e293b 70%, #1c1917 100%); box-shadow: 0 4px 24px rgba(0,0,0,0.18);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tek satır: Logo + menü linkleri + arama + kullanıcı -->
        <div class="flex justify-between items-center min-h-[4.5rem] py-2 gap-2">
            <div class="flex items-center flex-nowrap overflow-x-auto min-w-0 gap-x-1 sm:gap-x-1">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2 mr-4">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-2.5 rounded-xl px-2 py-1.5 hover:bg-white/10 transition-all duration-200 group">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:shadow-amber-500/50 transition-all duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                        <span class="font-bold text-base text-white hidden sm:inline tracking-wide">Tır Takip</span>
                    </a>
                </div>

                <!-- Desktop nav links -->
                <div class="hidden sm:flex sm:flex-nowrap sm:items-center sm:gap-x-0.5 sm:-my-px">
                    @php
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Dashboard', 'pattern' => 'dashboard', 'always' => true],
                        ];
                        if (Auth::user()->isPatron()) {
                            $navItems[] = ['route' => 'trucks.index', 'label' => 'Tırlar & Şoförler', 'pattern' => 'trucks.*|drivers.*', 'always' => false];
                        }
                        $navItems[] = ['route' => 'trips.index', 'label' => 'Seferler', 'pattern' => 'trips.*', 'always' => true];
                        if (Auth::user()->isPatron()) {
                            $navItems[] = ['route' => 'maintenances.index', 'label' => 'Bakım', 'pattern' => 'maintenances.*', 'always' => false];
                            $navItems[] = ['route' => 'reports.index', 'label' => 'Raporlar', 'pattern' => 'reports.*', 'always' => false];
                            if (Auth::user()->isAdmin()) {
                                $navItems[] = ['route' => 'monthly-summary.index', 'label' => 'Aylık Özet', 'pattern' => 'monthly-summary.*', 'always' => false];
                            }
                            $navItems[] = ['route' => 'customers.index', 'label' => 'Müşteriler', 'pattern' => 'customers.*', 'always' => false];
                            $navItems[] = ['route' => 'quotations.index', 'label' => 'Teklifler', 'pattern' => 'quotations.*', 'always' => false];
                        }
                        $navItems[] = ['route' => 'search.index', 'label' => 'Ara', 'pattern' => 'search.*', 'always' => true];
                    @endphp

                    @foreach($navItems as $item)
                                    @php
                                        $patterns = explode('|', $item['pattern']);
                                        $isActive = false;
                                        foreach ($patterns as $p) {
                                            if (request()->routeIs($p)) {
                                                $isActive = true;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <a href="{{ route($item['route']) }}" class="relative px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                                                  {{ $isActive
                        ? 'text-amber-400 bg-white/10'
                        : 'text-gray-300 hover:text-white hover:bg-white/8' }}">
                                        {{ $item['label'] }}
                                        @if($isActive)
                                            <span
                                                class="absolute bottom-0 left-1/2 -translate-x-1/2 w-4/5 h-0.5 bg-amber-400 rounded-full"></span>
                                        @endif
                                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Sağ: Arama + Kullanıcı -->
            <div class="flex items-center shrink-0 gap-2">
                <form method="GET" action="{{ route('search.index') }}" class="hidden md:flex">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                        <input type="search" name="q" placeholder="Plaka, firma ara..." value="{{ request('q') }}"
                            class="pl-9 pr-3 py-2 rounded-xl border border-white/10 text-sm w-44 lg:w-52 bg-white/10 text-white placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/30 focus:bg-white/15 transition-all duration-200 outline-none">
                    </div>
                </form>

                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-gray-300 hover:text-white hover:bg-white/12 border border-transparent hover:border-white/15 transition-all duration-200 focus:outline-none">
                                <div
                                    class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xs font-bold shadow">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Mobil hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" type="button"
                        class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition duration-200">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil menü -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/10"
        style="background: #0f172a;">
        <div class="px-3 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Dashboard</a>
            @if(Auth::user()->isPatron())
                <a href="{{ route('trucks.index') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('trucks.*') || request()->routeIs('drivers.*') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Tırlar
                    & Şoförler</a>
            @endif
            <a href="{{ route('trips.index') }}"
                class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('trips.*') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Seferler</a>
            @if(Auth::user()->isPatron())
                <a href="{{ route('maintenances.index') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('maintenances.*') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Bakım</a>
                <a href="{{ route('reports.index') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('reports.*') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Raporlar</a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('monthly-summary.index') }}"
                        class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('monthly-summary.*') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Aylık
                        Özet</a>
                @endif
                <a href="{{ route('customers.index') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('customers.*') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Müşteriler</a>
                <a href="{{ route('quotations.index') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('quotations.*') ? 'text-amber-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} transition-all duration-150">Teklifler</a>
            @endif
        </div>

        <div class="px-4 pt-3 pb-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
            </div>
            <div class="flex gap-3 shrink-0">
                <a href="{{ route('profile.edit') }}"
                    class="text-sm text-gray-400 hover:text-amber-400 transition-colors">Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-sm text-gray-400 hover:text-red-400 transition-colors">Çıkış</button>
                </form>
            </div>
        </div>
    </div>
</nav>