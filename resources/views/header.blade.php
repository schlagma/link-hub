<flux:navbar class="flex h-[4rem] shrink-0 items-center gap-x-4 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 px-4 sm:gap-x-6 sm:px-6 lg:px-8 z-10 print:hidden">
    @if(str_contains(Route::getFacadeRoot()->current()->uri(), 'admin'))
        <flux:sidebar.toggle class="lg:hidden" icon="menu" />
    @else
        <a wire:navigate href="/" class="flex h-full items-center border-b-0 sm:w-[18rem] px-6 -ml-6">
            @if(file_exists(public_path('logo.svg')) && file_exists(public_path('logo-small.svg')))
                <img class="h-8 w-auto hidden sm:inline" src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }}">
                <img class="h-8 w-auto sm:hidden" src="{{ asset('logo-small.svg') }}" alt="{{ config('app.name') }}">
            @else
                <span class="text-zinc-800 dark:text-white text-xl font-semibold">{{ config('app.name') }}</span>
            @endif
        </a>
    @endif
    <div class="flex items-center gap-x-4 ml-auto">
        <flux:modal.trigger name="info">
            <flux:button
                variant="ghost"
                icon="info"
                title="{{ __('common.about') }} GISELA &hellip;"
                @click="dialogInfo = true"
            />
        </flux:modal>

        <flux:dropdown>
            @guest
            <flux:profile :chevron="false" />
            @endguest
            @auth
            <flux:profile :chevron="false" avatar:name="{{ auth()->user()->name }}" />
            @endauth

            <flux:navmenu class="w-64">
                @auth
                    <div class="px-2 py-1.5" role="none">
                        <p class="truncate text-zinc-800 dark:text-white font-semibold" role="none">{{ auth()->user()->name }}</p>
                        @can('admin', Auth::user())
                            <p class="text-sm text-zinc-500 dark:text-zinc-400" role="none">{{ __('roles.admin') }}</p>
                        @else
                            <p class="text-sm text-zinc-500 dark:text-zinc-400" role="none">{{ __('roles.user') }}</p>
                        @endcan
                    </div>
                    <flux:navmenu.separator />
                @endauth

                @guest
                    <flux:navmenu.item
                        icon="log-in"
                        href="/auth/login"
                    >
                        {{ __('common.login') }}
                    </flux:navmenu.item>
                @endguest
                @auth
                    <flux:navmenu.item
                        icon="log-out"
                        href="/auth/logout"
                    >
                        {{ __('common.logout') }}
                    </flux:navmenu.item>
                @endauth
            </flux:navmenu>
        </flux:dropdown>
    </div>
</flux:navbar>