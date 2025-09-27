<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.admin')]
class LinkPageCreate extends Component
{
    public string $title = "";
    public string $description = "";
    public array $groups = [];
    public bool $isPublic = false;

    public function render()
    {
        $allGroups = DB::table('groups')->orderBy('name')->get();
        return view('livewire.admin.link-page-create', [
            'allGroups' => $allGroups,
        ]);
    }

    public function save()
    {
        Page::create([
            'title' => $this->title,
            'description' => $this->description,
            'groups' => json_encode($this->groups),
            'public' => $this->isPublic,
        ]);

        Toaster::success('admin.pageAdded');
        $this->redirect('/admin', navigate: true);
    }
}
