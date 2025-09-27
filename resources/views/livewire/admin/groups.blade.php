<div class="p-6 sm:px-8 overflow-y-auto h-full flex flex-col">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <flux:heading size="xl">{{ __('admin.groups') }}</flux:heading>
        </div>
    </div>
    <flux:table class="mt-6">
        <flux:table.columns>
            @if(count($groups) > 0)
                <flux:table.column>{{ __('admin.name') }}</flux:table.column>
                <flux:table.column>{{ __('admin.identifier') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            @endif
        </flux:table.columns>
        <flux:table.rows>
            @foreach($groups as $key => $group)
            <flux:table.row wire:key="{{ $key }}">
                <flux:table.cell>
                    <flux:input wire:model="groups.{{ $key }}.name" wire:change="updateGroup({{ $key }})" />
                </flux:table.cell>
                <flux:table.cell>
                    <flux:input wire:model="groups.{{ $key }}.identifier" wire:change="updateGroup({{ $key }})" />
                </flux:table.cell>
                <flux:table.cell class="flex justify-end">
                    <flux:button icon="trash-2" wire:click="deleteGroup({{ $group->id }})" title="{{ __('admin.deleteGroup') }}" />
                </flux:table.cell>
            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:separator />

    <div class="mt-4 flex justify-end">
        <flux:button variant="primary" icon="plus" wire:click="createGroup">
            {{ __('admin.addGroup') }}
        </flux:button>
    </div>
</div>