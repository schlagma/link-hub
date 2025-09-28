<div class="p-6 pt-8 sm:px-8 sm:pb-8 overflow-y-auto h-full">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($pages as $page)
            <flux:button
                wire:navigate
                href="{{ route('public.link-page', ['id' => $page->id]) }}"
                class="text-base!"
            >
                {{ $page->title }}
            </flux:button>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $pages->links() }}
    </div>
</div>