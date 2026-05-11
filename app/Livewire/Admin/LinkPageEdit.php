<?php

namespace App\Livewire\Admin;

use App\Models\Link;
use App\Models\Page;
use Flux\Flux;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.admin')]
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

        return redirect()->back();
    }

    public function removeItem($id)
    {
        // Get the item
        $item = Link::where('id', $id)->first();

        // Delete the item
        $item->delete();

        // Get all siblings of this item
        $siblings = Link::where('page', $this->pageID)
            ->orderBy('order')
            ->get();

        // Update the order
        foreach ($siblings as $index => $s) {
            $s->update([
                'order' => $index,
            ]);
        }

        return redirect()->back();
    }

    public function sortItems($id, $position)
    {
        $movedItem = Link::where('id', $id)->first();
        if (!$movedItem) return;

        $oldPosition = $movedItem->order;
        $newPosition = $position;

        // Get all items
        $allItems = Link::where('page', $this->pageID)
            ->orderBy('order')
            ->get();

        if ($oldPosition < $newPosition) {
            // Moving down - shift items between oldPosition+1 and newPosition down by 1
            foreach ($allItems as $item) {
                if ($item->id !== $id && $item->order > $oldPosition && $item->order <= $newPosition) {
                    $item->update(['order' => $item->order - 1]);
                }
            }
        } elseif ($oldPosition > $newPosition) {
            // Moving up - shift items between newPosition and oldPosition-1 up by 1
            foreach ($allItems as $item) {
                if ($item->id !== $id && $item->order >= $newPosition && $item->order < $oldPosition) {
                    $item->update(['order' => $item->order + 1]);
                }
            }
        }

        // Set moved item to new position
        $movedItem->update(['order' => $newPosition]);
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

        Flux::toast(variant: 'success', text: __('admin.itemUpdated'));
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
        
        Flux::toast(variant: 'success', text: __('admin.pageUpdated'));
    }

    public function deletePage()
    {
        Link::where('page', $this->pageID)->delete();
        Page::destroy($this->pageID);
        Flux::toast(variant: 'success', text: __('admin.pageDeleted'));
        $this->redirect('/admin', navigate: true);
    }
}
