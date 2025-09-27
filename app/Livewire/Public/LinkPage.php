<?php

namespace App\Livewire\Public;

use App\Models\Link;
use App\Models\Page;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Livewire\Component;

class LinkPage extends Component
{
    public function render(Request $request)
    {
        // Get page data
        $page = Page::where('id', $request->id)->first();

        // Check if page is public
        if (!$page->public) {
            // Check if user is authenticated
            if (!auth()->guest()) {
                $userGroups = json_decode(auth()->user()->groups);
                // Check if user is admin
                if (!in_array(config('app.group_admin'), $userGroups)) {
                    // Check if one of the groups the user belongs to is allowed to view the page
                    $matchingGroupExists = false;
                    foreach ($userGroups as $group) {
                        if (in_array($group, json_decode($page->groups))) {
                            $matchingGroupExists = true;
                        }
                    }
                    // If no matching group exist throw a 403 Forbidden error
                    if (!$matchingGroupExists) {
                        abort('403');
                    }
                }
            } else {
                // Redirect to login if user is not logged in
                $this->redirect('/auth/login');
            }
        }

        // Get all links on this page
        $links = Link::where('page', $request->id)->orderBy('order')->get();

        return view('livewire.public.link-page', [
            'pageID' => $page->id,
            'title' => $page->title,
            'description' => $page->description,
            'links' => $links,
        ]);
    }
}
