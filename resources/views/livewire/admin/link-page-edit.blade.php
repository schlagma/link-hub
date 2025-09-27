<div class="p-6 sm:px-8 overflow-y-auto h-full flex flex-col">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <flux:heading size="xl">{{ __('admin.editPage') }}</flux:heading>
        </div>
        <div class="flex gap-2 mt-4 xl:ml-16 xl:mt-0 flex flex-col sm:flex-row whitespace-nowrap">
            <flux:button variant="primary" icon="eye" wire:navigate href="{{ route('public.link-page', ['id' => $pageID]) }}">
                {{ __('admin.viewPage') }}
            </flux:button>
            @can('admin')
            <flux:modal.trigger name="delete">
                <flux:button icon="trash-2">
                    {{ __('admin.deletePage') }}
                </flux:button>
            </flux:modal.trigger>
            @endcan
        </div>
    </div>
    <div class="mt-6">
        <div class="grid md:grid-cols-[2fr_1fr_6rem] gap-6">
            <flux:field class="col-span-full">
                <flux:label>{{ __('admin.title') }}</flux:label>
                <flux:input wire:model="title" wire:change="updatePage" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.description') }}</flux:label>
                <flux:editor
                    toolbar="bold italic underline strike | bullet ordered | subscript superscript"
                    wire:model="description"
                    wire:change="updatePage"
                />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.groups') }}</flux:label>
                <flux:pillbox multiple searchable wire:model="groups" wire:change="updatePage">
                    @foreach($allGroups as $group)
                        <flux:pillbox.option value="{{ $group->identifier }}">{{ $group->name }}</flux:pillbox.option>
                    @endforeach
                </flux:pillbox>
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.isPublic') }}</flux:label>
                <flux:switch wire:model="isPublic" wire:change="updatePage" />
            </flux:field>
        </div>
    </div>
    <flux:table class="mt-6">
        <flux:table.columns>
            @if(count($links) > 0)
                <flux:table.column></flux:table.column>
                <flux:table.column>{{ __('admin.groupTitle') }}</flux:table.column>
                <flux:table.column>{{ __('admin.title') }}</flux:table.column>
                <flux:table.column>{{ __('admin.description') }}</flux:table.column>
                <flux:table.column>{{ __('admin.link') }}</flux:table.column>
                <flux:table.column>{{ __('admin.symbol') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            @endif
        </flux:table.columns>
        <flux:table.rows x-sort="$wire.sortItems($item, $position + 1)">
            @foreach($links as $key => $link)
                <flux:table.row x-sort:item="{{ $link->id }}" wire:key="{{ $key }}">
                    <flux:table.cell>
                        <flux:button icon="grip" x-sort:handle class="cursor-grab active:cursor-grabbing" title="{{ __('admin.moveItem') }}" />
                    </flux:table.cell>
                    <flux:table.cell class="h-[3.75rem] flex justify-center items-center">
                        <flux:switch wire:model="links.{{ $key }}.group" wire:change="updateItem({{ $key }})" />
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:input type="text" wire:model="links.{{ $key }}.title" wire:change="updateItem({{ $key }})" />
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:input type="text" wire:model="links.{{ $key }}.description" wire:change="updateItem({{ $key }})" />
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:input type="text" wire:model="links.{{ $key }}.link" wire:change="updateItem({{ $key }})" :disabled="$link->group" />
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:input type="text" wire:model="links.{{ $key }}.symbol" wire:change="updateItem({{ $key }})" :disabled="$link->group" />
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button icon="trash-2" wire:click="removeItem({{ $link->id }})" class="btn-neutral" title="{{ __('admin.removeItem') }}" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <div class="mt-4 flex justify-end">
        <flux:button variant="primary" icon="plus" wire:click="addItem">
            {{ __('admin.addItem') }}
        </flux:button>
    </div>
    
    <flux:modal name="delete" class="md:w-96">
        <div class="space-y-8">
            <div>
                <flux:heading size="lg">{{ __('admin.deletePage') }}</flux:heading>
                <flux:text class="mt-2">{{ __('admin.deletePageModalText') }}</flux:text>
            </div>
            <div class="flex justify-end">
                <flux:button variant="primary" icon="trash-2" wire:click="deletePage">{{ __('admin.deletePage') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>