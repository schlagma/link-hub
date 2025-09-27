<flux:sidebar collapsible="mobile" class="w-[22rem]! p-0! flex flex-col gap-0! h-full grow bg-zinc-100 dark:bg-zinc-800">
    <flux:sidebar.header class="flex h-[4rem] px-6 shrink-0 items-center bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 border-r lg:border-r-0 border-r-zinc-300  dark:border-r-zinc-700 z-10">
        <a wire:navigate href="{{ route('admin.dashboard') }}" class="h-full flex flex-1 items-center justify-start lg:justify-center">
            <span class="text-zinc-800 dark:text-white text-xl font-semibold">{{ config('app.name') }}</span>
        </a>
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav class="grow overflow-y-auto border-r border-zinc-200 dark:border-zinc-700 px-6 py-4">
        @auth
            <flux:sidebar.item
                icon="files"
                wire:navigate
                href="{{ route('admin.dashboard') }}"
            >
                {{ __('admin.pages') }}
            </flux:sidebar.item>
            @can('admin', Auth::user())
                <flux:sidebar.item
                    icon="users"
                    wire:navigate
                    href="{{ route('admin.groups') }}"
                >
                    {{ __('admin.groups') }}
                </flux:sidebar.item>
            @endcan
        @endauth
    </flux:sidebar.nav>
</flux:sidebar>