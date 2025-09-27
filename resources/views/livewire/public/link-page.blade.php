<div class="p-6 sm:px-8 sm:pb-8 overflow-y-auto h-full">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <flux:heading size="xl">{{ $title }}</flux:heading>
        </div>
        @auth
            <div class="flex gap-2 mt-4 xl:ml-16 xl:mt-0 flex flex-col sm:flex-row whitespace-nowrap">
                <flux:button
                    icon="pencil"
                    wire:navigate
                    href="{{ route('admin.link-page-edit', ['id' => $pageID]) }}"
                    title="{{ __('admin.editPage') }}"
                />
            </div>
        @endauth
    </div>
    @if($description)
        <div class="text mt-4 mb-8">
            {!! $description !!}
        </div>
    @endif
    <div class="mt-6 grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($links as $link)
            @if ($link->group)
                <div class="col-span-full mt-4">
                    <h2 class="font-medium text-xl">{{ $link->title }}</h2>
                    @if($link->description)
                        <p class="mt-2">{{ $link->description }}</p>
                    @endif
                </div>
            @else
                <a
                    href="{{ $link->link }}"
                    class="grid grid-cols-[auto_1fr] bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-md shadow-xs transition ease-in-out"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    @if($link->symbol)
                        <span class="flex items-center row-span-2 bg-(--color-accent) px-3 py-2 text-(--color-accent-foreground) border border-black/10 rounded-l-md">
                            <span aria-hidden="true">@svg($link->symbol, 'size-6')</span>
                        </span>
                        <span class="font-semibold text-lg pt-2 pl-4 pr-3 border-t border-r border-zinc-200 dark:border-white/10 rounded-tr-md">{{ $link->title }}</span>
                    @else
                        <span class="row-span-2 border-l border-t border-b border-zinc-200 rounded-l-md w-3"></span>
                        <span class="font-semibold text-lg pt-2 pr-3 border-t border-r border-zinc-200 dark:border-white/10 rounded-tr-md">{{ $link->title }}</span>
                    @endif
                    @if($link->description)
                        @if($link->symbol)
                            <span class="font-normal pt-1 pb-2 pl-4 pr-3 border-b border-r border-zinc-200 dark:border-white/10 rounded-br-md">{{ $link->description }}</span>
                        @else
                            <span class="font-normal pt-1 pb-2 pr-3 border-b border-r border-zinc-200 dark:border-white/10 rounded-br-md">{{ $link->description }}</span>
                        @endif
                    @else
                        <span class="h-[calc(var(--spacing)_*_9_+_2px)] border-b border-r border-zinc-200 dark:border-white/10 rounded-br-md"></span>
                    @endif
                </a>
            @endif
        @endforeach
    </div>
</div>