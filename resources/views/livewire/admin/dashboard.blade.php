<div class="p-6 sm:px-8 overflow-y-auto h-full space-y-8">
    @can('admin', Auth::user())
        <div class="flex gap-2 flex-col sm:flex-row justify-end">
            <flux:button variant="primary" icon="plus" wire:navigate href="{{ route('admin.link-page-create') }}" type="button" class="btn-primary">
                {{ __('admin.addPage') }}
            </flux:button>
        </div>
    @endcan
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($pages as $page)
            <flux:button
                wire:navigate
                href="{{ route('admin.link-page-edit', ['id' => $page->id]) }}"
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