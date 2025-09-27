<div class="p-6 sm:px-8 pb-0! overflow-y-auto h-full flex flex-col">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <flux:heading size="xl">{{ __('admin.addPage') }}</flux:heading>
        </div>
    </div>
    <div class="mt-6 mb-12">
        <div class="grid md:grid-cols-[2fr_1fr_6rem] gap-6">
            <flux:field class="col-span-full">
                <flux:label>{{ __('admin.title') }}</flux:label>
                <flux:input wire:model="title" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.description') }}</flux:label>
                <flux:editor
                    toolbar="bold italic underline strike | bullet ordered | subscript superscript"
                    wire:model="description"
                />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.groups') }}</flux:label>
                <flux:pillbox multiple searchable wire:model="groups">
                    @foreach($allGroups as $group)
                        <flux:pillbox.option value="{{ $group->identifier }}">{{ $group->name }}</flux:pillbox.option>
                    @endforeach
                </flux:pillbox>
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.isPublic') }}</flux:label>
                <flux:switch wire:model="isPublic" />
            </flux:field>
        </div>
    </div>
    <div class="mt-auto py-6 -mx-8 px-8 flex items-center justify-end gap-x-4 border-t border-zinc-200 dark:border-zinc-900 bg-zinc-100 dark:bg-zinc-800">
        <flux:button icon="ban" wire:navigate href="{{ route('admin.dashboard') }}">
            {{ __('common.cancel') }}
        </flux:button>
        <flux:button variant="primary" icon="save" wire:click="save">
            {{ __('common.save') }}
        </flux:button>
    </div>
</div>