<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $userGroups = json_decode(auth()->user()->groups);
        $query = Page::orderBy('title');

        if (!in_array(config('app.group_admin'), $userGroups)) {
            $allPages = Page::select('id', 'groups')->get();
            $allowedPages = [];
            foreach ($userGroups as $group) {
                foreach ($allPages as $page) {
                    // Add pages the user can view to the database query
                    if (in_array($group, json_decode($page->groups))) {
                        array_push($allowedPages, $page->id);
                    }
                }
            }
            $query->whereIn('id', $allowedPages);
        }
        
        $pages = $query->paginate(12);

        return view('livewire.admin.dashboard',[
            'pages' => $pages,
        ]);
    }
}
