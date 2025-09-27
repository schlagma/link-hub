<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

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

        Toaster::success('admin.groupUpdated');
    }

    public function deleteGroup($key)
    {
        Group::destroy($key);
    }
}
