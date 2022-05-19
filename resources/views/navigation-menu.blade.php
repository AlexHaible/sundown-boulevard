<div class="w-full text-gray-700 bg-white dark-mode:text-gray-200 dark-mode:bg-gray-800">
    <div x-data="{ open: false }"
        class="flex flex-col w-full px-5 mx-auto md:items-center md:justify-between md:flex-row">
        <div class="flex flex-row items-center justify-between p-4">
            <a href="{{ route('index') }}"
                class="inline-flex items-center text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline">
                <x-jet-application-logo class="block w-auto h-8"></x-jet-application-logo>
            </a>
            @env('local')
            <span
                class="inline-flex items-center px-2 py-1 ml-2 text-xs font-semibold tracking-widest text-gray-900 uppercase bg-red-500 rounded-full bg-opacity-60">local</span>
            @else
            <span
                class="inline-flex items-center px-2 py-1 ml-2 text-xs font-semibold tracking-widest text-gray-900 uppercase bg-yellow-500 rounded-full bg-opacity-60">beta</span>
            @endenv
            <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                    <path x-show="!open" fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                    <path x-show="open" fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <nav :class="{'flex': open, 'hidden': !open}" class="flex-col flex-grow hidden w-full pb-4 md:pb-0 md:flex md:justify-end md:flex-row md:w-60">
            <x-jet-nav-link href="{{ route('dashboard') }}">{{ __('Make Booking') }}</x-jet-nav-link>
            <x-jet-nav-link href="{{ route('bookings') }}">{{ __('View Bookings') }}</x-jet-nav-link>
            @auth
            <x-jet-dropdown align="right" width="w-60">
                <x-slot name="trigger" class="text-black">
                        <x-bi-person-circle class="object-cover w-8 h-8 ml-4 cursor-pointer"></x-bi-person-circle>
                </x-slot>

                <x-slot name="content">
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Account') }}
                    </div>

                    <x-jet-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-jet-dropdown-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                        {{ __('API Tokens') }}
                    </x-jet-dropdown-link>
                    <div class="border-t border-gray-100"></div>
                    @endif


                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-jet-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Logout') }}
                        </x-jet-dropdown-link>
                    </form>
                </x-slot>
            </x-jet-dropdown>
            @else
            <x-jet-nav-link href="{{ route('login') }}">{{ __('Sign in') }}</x-jet-nav-link>
            <x-jet-nav-link href="{{ route('register') }}" class="bg-gray-100">
                {{ __('Sign up ') }}
                <x-heroicon-o-chevron-right class="inline w-5" />
            </x-jet-nav-link>
            @endauth
        </nav>
    </div>
</div>
