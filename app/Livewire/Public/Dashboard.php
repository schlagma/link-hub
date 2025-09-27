<?php

namespace App\Livewire\Public;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        // Begin to build a database query for the pages by adding all public pages
        $query = Page::orderBy('title');

        // Check if user is authenticated
        if (!auth()->guest()) {
            $allPages = Page::select('id', 'groups')->get();
            $userGroups = json_decode(auth()->user()->groups);
            // Check if user is admin
            if (!in_array(config('app.group_admin'), $userGroups)) {
                // Add all public pages to the database query
                $query->where('public', true);
                // Check if one of the groups the user belongs to is allowed to view the pages
                foreach ($userGroups as $group) {
                    foreach ($allPages as $page) {
                        // Add pages the user can view to the database query
                        if (in_array($group, json_decode($page->groups))) {
                            $query->orWhere('id', $page->id);
                        }
                    }
                }
            }
        } else {
            // Add all public pages to the database query
            $query->where('public', true);
        }

        // Retrieve all pages that the user is allowed to view from the database ordered by title
        $pages = $query->paginate(12);

        return view('livewire.public.dashboard',[
            'pages' => $pages,
        ]);
    }
}
