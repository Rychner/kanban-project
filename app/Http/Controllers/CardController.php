<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use App\Enums\CardStatus;
use App\Enums\CardPriority;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CardRequest;
use App\Models\Workspace;
use App\Models\Card;

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
        
        if($last_card) return 1;
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
            'order'         => $this->ordering($workspace, $status),
            'priority'      => $request->priority,
        ]);

        flashMessage('Card Information Succesfully Saved', 'success');

        return to_route('workspace.show', [$workspace]);
    }
}
