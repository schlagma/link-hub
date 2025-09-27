<div x-data="{ profileDropdown: false }" class="relative ml-1">
    <button type="button" aria-haspopup="menu" aria-expanded="false" data-headlessui-state=""
        class="-m-1.5 flex items-center py-1.5 pl-2.5 pr-1.5 text-zinc-300 hover:text-zinc-100 cursor-pointer rounded"
        :class="{ 'bg-zinc-700' : profileDropdown }"
        @click="profileDropdown = true">
        <span class="sr-only">Open user menu</span>
        <span class="flex items-center">
            <span aria-hidden="true">@svg('mdi-account-circle', 'size-6')</span>
            <span x-show="profileDropdown" aria-hidden="true">@svg('mdi-chevron-up', 'ml-2 h-5 w-5 text-zinc-300')</span>
            <span x-show="!profileDropdown" aria-hidden="true">@svg('mdi-chevron-down', 'ml-2 h-5 w-5 text-zinc-300')</span>
        </span>
    </button>
    <div class="absolute -right-1.5 z-10 mt-4 w-64 origin-top-right divide-y divide-zinc-200 dark:divide-zinc-700 rounded-md bg-white dark:bg-zinc-800 ring-1 shadow-lg ring-black/5 dark:ring-zinc-700 focus:outline-hidden"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="menu-button"
        tabindex="-1"
        x-show="profileDropdown"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        @click.outside="profileDropdown = false"
    >

        @auth
        <div class="px-4 py-3" role="none">
            <p class="truncate text-zinc-800 dark:text-white font-bold" role="none">{{ Auth::user()->name }}</p>
            @can('admin', Auth::user())
            <p class="text-sm text-zinc-500 dark:text-zinc-400" role="none">{{ __('roles.admin') }}</p>
            @elsecan('election-commission', Auth::user())
            <p class="text-sm text-zinc-500 dark:text-zinc-400" role="none">{{ __('roles.electionCommission') }}</p>
            @else
            <p class="text-sm text-zinc-500 dark:text-zinc-400" role="none">{{ __('roles.user') }}</p>
            @endcan
        </div>
        @endauth
        <div class="p-1" role="none">
            @guest
            <a href="/auth/login" class="group flex w-full items-center px-3 py-2 text-zinc-800 dark:text-white cursor-pointer hover:bg-zinc-200 dark:hover:bg-zinc-700 focus:bg-zinc-200 dark:focus:bg-zinc-700 border-b-0! rounded" role="menuitem" tabindex="-1" id="menu-item-3">
                <span aria-hidden="true">@svg('mdi-login', 'mr-3 size-5 text-zinc-500')</span>
                {{ __('common.login') }}
            </a>
            @endguest
            @auth
            <a href="/auth/logout" class="group flex w-full items-center px-3 py-2 text-zinc-800 dark:text-white cursor-pointer hover:bg-zinc-200 dark:hover:bg-zinc-700 focus:bg-zinc-200 dark:focus:bg-zinc-700 border-b-0! rounded" role="menuitem" tabindex="-1" id="menu-item-3">
                <span aria-hidden="true">@svg('mdi-logout', 'mr-3 size-5 text-zinc-500')</span>
                {{ __('common.logout') }}
            </a>
            @endauth
        </div>
    </div>
</div>