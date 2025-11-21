<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- LEFT SIDE --}}
            <div class="flex items-center">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="/logo.png" class="block h-9 w-auto" alt="App Logo">
                </a>

                {{-- Auth Navigation --}}
                @auth
                <div class="hidden sm:flex sm:space-x-8 sm:ml-10">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium
                       {{ request()->routeIs('dashboard') 
                            ? 'border-indigo-500 text-gray-900 dark:text-gray-200'
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                        Dashboard
                    </a>
                </div>
                @endauth
            </div>

            {{-- RIGHT SIDE (Desktop) --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6 relative">

                @auth
                    {{-- User Dropdown --}}
                    <button id="userMenuButton"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md 
                        text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 
                        focus:outline-none transition">
                        <span>{{ Auth::user()->name??'' }}</span>

                        <svg class="ml-1 h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 
                                  1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="userMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1">

                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 
                                    hover:bg-gray-100 dark:hover:bg-gray-800">
                                Log Out
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Guest Links --}}
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800">Register</a>
                    </div>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <div class="flex items-center sm:hidden">
                <button id="mobileMenuButton"
                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100
                        dark:text-gray-500 dark:hover:text-gray-400 dark:hover:bg-gray-900">

                    <svg id="mobileMenuIconOpen" class="h-6 w-6" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <svg id="mobileMenuIconClose" class="h-6 w-6 hidden" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div id="mobileMenu" class="hidden sm:hidden">

        {{-- Authenticated Links --}}
        @auth
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
               {{ request()->routeIs('dashboard')
                    ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:text-gray-200'
                    : 'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 hover:border-gray-300' }}">
                Dashboard
            </a>
        </div>

        {{-- User Info --}}
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                    {{ Auth::user()->name??'' }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email??'' }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-base text-gray-600 dark:text-gray-300 
                   hover:bg-gray-100 dark:hover:bg-gray-900">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left px-4 py-2 text-base text-gray-600 
                            dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900">
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        @endauth

        {{-- Guest Mobile Menu --}}
        @guest
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('login') }}"
               class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900">
                Register
            </a>
        </div>
        @endguest
    </div>
</nav>


{{-- JS for Dropdown + Mobile --}}
<script>
    // Desktop dropdown
    const userButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');

    if (userButton) {
        userButton.onclick = () => {
            userMenu.classList.toggle('hidden');
        };
    }

    // Mobile menu
    const mobileBtn = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const openIcon = document.getElementById('mobileMenuIconOpen');
    const closeIcon = document.getElementById('mobileMenuIconClose');

    mobileBtn.onclick = () => {
        mobileMenu.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    };
</script>
