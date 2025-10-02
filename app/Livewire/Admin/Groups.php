<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Groups extends Component
{
    public $groups;

    public function render()
    {
        $groups = DB::table('groups')->get();
        $this->groups = $groups;
        return view('livewire.admin.groups');
    }

    public function createGroup()
    {
        Group::create([
            'name' => '',
            'identifier' => '',
        ]);
    }

    public function updateGroup($key)
    {
        Group::updateOrCreate(
            [
                'id' => $this->groups[$key]->id,
            ],
            [
                'name' => $this->groups[$key]->name,
                'identifier' => $this->groups[$key]->identifier,
            ]
        );

        Flux::toast(variant: 'success', text: __('admin.groupUpdated'));
    }

    public function deleteGroup($key)
    {
        Group::destroy($key);
    }
}
