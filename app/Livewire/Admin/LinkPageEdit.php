<?php

namespace App\Livewire\Admin;

use App\Models\Link;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.admin')]
class LinkPageEdit extends Component
{
    #[Locked]
    public $pageID;

    public string $title;
    public string $description;
    public $groups;
    public bool $isPublic;

    public $links;

    public function mount(Request $request)
    {
        $this->pageID = $request->id;
    }

    public function render(Request $request)
    {
        $page = DB::table('pages')->where('id', $this->pageID)->first();

        $userGroups = json_decode(auth()->user()->groups);
        if (!in_array(config('app.group_admin'), $userGroups)) {
            // Check if one of the groups the user belongs to is allowed to edit the page
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

        $this->title = $page->title;
        $this->description = $page->description;
        $this->groups = json_decode($page->groups);
        $this->isPublic = $page->public;

        $links = DB::table('links')->select('id', 'title', 'description', 'link', 'symbol', 'group')
            ->where('page', $this->pageID)
            ->orderBy('order')
            ->get();

        // Replace 0 and 1 from MariaDB with boolean values
        foreach ($links as $link) {
            if ((bool) $link->group) {
                $link->group = true;
            } else {
                $link->group = false;
            }
        }

        $this->links = $links;

        $allGroups = DB::table('groups')->orderBy('name')->get();

        return view('livewire.admin.link-page-edit', [
            'allGroups' => $allGroups,
        ]);
    }

    public function addItem()
    {
        $position = Link::where('page', $this->pageID)->max('order') + 1;
        Link::create([
            'page' => $this->pageID,
            'title' => '',
            'description' => '',
            'link' => '',
            'symbol' => '',
            'group' => false,
            'order' => $position,
        ]);
    }

    public function removeItem($id)
    {
        $link = Link::find($id);
        $position = $link->order;

        DB::transaction(function () use ($link, $position) {
            Link::where('order', '>', $position)->decrement('order');
            $link->delete();
        });
    }

    public function sortItems($id, $newPosition)
    {
        $item = Link::find($id);
        $currentPosition = $item->order;

        if ($newPosition < $currentPosition) {
            $linksToReorder = Link::whereBetween('order', [$newPosition, $currentPosition - 1]);
            $linksToReorder->increment('order');
        } else {
            $linksToReorder = Link::whereBetween('order', [$currentPosition + 1, $newPosition]);
            $linksToReorder->decrement('order');
        }

        $item->update([
            'order' => $newPosition,
        ]);
    }

    public function updateItem($key)
    {
        Link::updateOrCreate(
            [
                'id' => $this->links[$key]->id
            ],
            [
                'title' => $this->links[$key]->title,
                'description' => $this->links[$key]->description,
                'link' => $this->links[$key]->link,
                'symbol' => $this->links[$key]->symbol,
                'group' => $this->links[$key]->group,
            ]
        );

        Toaster::success('admin.itemUpdated');
    }

    public function updatePage()
    {
        //dd(json_encode($this->groups));
        Page::updateOrCreate(
            [
                'id' => $this->pageID,
            ],
            [
                'title' => $this->title,
                'description' => $this->description,
                'groups' => json_encode($this->groups),
                'public' => $this->isPublic,
            ]
        );
        
        Toaster::success('admin.pageUpdated');
    }

    public function deletePage()
    {
        Link::where('page', $this->pageID)->delete();
        Page::destroy($this->pageID);
        Toaster::success('admin.pageDeleted');
        $this->redirect('/admin', navigate: true);
    }
}
