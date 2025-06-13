<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use App\Enums\CardStatus;
use App\Enums\CardPriority;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardSingleResource;
use App\Models\Workspace;
use App\Models\Card;
use App\Models\User;

class CardController extends Controller
{
    public function create(Workspace $workspace): Response
    {   
        return inertia(component: 'Card/Create', props: [
            'page_settings' => [
                'title'     => 'Create Card',
                'subtitle'  => 'Fill out this form to add new card',
                'method'    => 'POST',
                'action'    => route('card.store', $workspace),
            ],
            'status'        => request()->status ?? 'To Do',
            'statuses'      => CardStatus::options(),
            'priority'      => request()->priority ?? CardPriority::UNKNOWN->value,
            'priorities'    => CardPriority::options(),
            'workspace'     => fn() => $workspace->only('slug'),
        ]);
    }

    public function ordering(Workspace $workspace, string $status): int
    {
        $last_card = Card::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', $status)
            ->orderByDesc('order', 'desc')
            ->first();
        
        if(!$last_card) return 1;
        return $last_card->order + 1;
    }

    public function store(Workspace $workspace, CardRequest $request): RedirectResponse
    {                
        $card = $request->user()->cards()->create([
            'workspace_id'  => $workspace->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'deadline'      => $request->deadline,
            'status'        => $status = $request->status,
            'priority'      => $request->priority,
            'order'         => $this->ordering($workspace, $status),
        ]);

        $card->members()->create([
            'user_id'   => $request->user()->id,
            'role'      => $card->user_id == $request->user()->id ? 'Project Manager' : 'Member',
        ]);

        flashMessage('Card Information Succesfully Saved', 'success');
        return to_route('card.edit', [$workspace, $card]);
    }

    public function show(Workspace $workspace, Card $card): Response
    {
        return inertia('Card/Show', [
            'card'          => fn() => new CardSingleResource($card->load(['members','tasks','user','attachments'])),
            'page_settings' => [
                'title'     => 'Detail Card',
                'subtitle'  => 'You can see Card Information here.',
            ],
        ]);
    }

    public function edit(Workspace $workspace, Card $card)
    {
        return inertia('Card/Edit', [
            'card'          => fn() => new CardSingleResource($card->load(['members','tasks','user','attachments'])),
            'page_settings' => [
                'title'     => 'Edit Card',
                'subtitle'  => 'Fill out this form to Edit Card.',
                'method'    => 'PUT',
                'action'    => route('card.update', [$workspace, $card]),
            ],
            'statuses'      => CardStatus::options(),
            'priorities'    => CardPriority::options(),
            'workspace'     => fn() => $workspace->only('slug'), 
        ]);
    }

    public function update(Workspace $workspace, Card $card, CardRequest $request): RedirectResponse
    {
        $last_status = $card->status->value;

        $card->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'deadline'      => $request->deadline,
            'status'        => $status = $request->status,
            'priority'      => $request->priority,
            'order'         => $this->ordering($workspace, $status),
        ]);

        $this->adjustOrdering($workspace, $last_status);

        flashMessage('Successfully Updated Card Information');

        return to_route('workspace.show', [$workspace]);
    }
    
    public function adjustOrdering(Workspace $workspace, string $status)
    {
        $order = 1;

        return Card::where('workspace_id', $workspace->id)
            ->where('status', $status)
            ->orderBy('order')
            ->get()
            ->each(function($card) use(&$order){
                $card->order = $order;
                $card->save();
                $order++;
            });
    }

    public function destroy(Workspace $workspace, Card $card): RedirectResponse
    {
        $last_status = $card->status->value;

        $card->delete();

        $this->adjustOrdering($workspace, $last_status);

        flashMessage('Successfully Delete Card Information');

        return to_route('workspace.show', [$workspace]);
    }

    public function reorder (Workspace $workspace, Card $card, Request $request): RedirectResponse
    {
        if($request->cardActive['type'] == $request->cardOver['type'])
        {
            $active = Card::find($request->cardActive['data']);
            $over = Card::find($request->cardOver['data']);

            if($active->status->value == $over->status->value)
            {
                $temp_order = $active->order;
                $active->order = $over->order;
                $over->order = $temp_order;

                $active->save();
                $over->save();
            }
            else {
                $last_status_active = $active->status->value;
                $active->status = $over->status->value;
                $active->save();

                $this->adjustOrdering($workspace, $last_status_active);
                $this->adjustOrdering($workspace, $active->status->value);
            }
        }
        else {
            $active = Card::find($request->cardActive['data']);
            $last_status_active = $ative->status->value;

            $active->status = $request->cardOver['data'];
            $active->order = $this->ordering($workspace, $request->cardOver['data']);
            $active->save();

            $this->adjustOrdering($workspace, $last_status_active);
        }

        flashMessage('Card Successfully Moved');
        return to_route('workspace.show', $workspace);
    }
}
