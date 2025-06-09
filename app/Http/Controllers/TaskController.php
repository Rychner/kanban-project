<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Card;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(Card $card, Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->tasks()->create([
            'card_id'   => $card->id,
            'title'     => $request->title,
        ]);

        flashMessage('Tasks was Saved Successfully');
        return back();
    }

    public function destroy(Card $card, Task $task): RedirectResponse
    {        
        $task->delete();

        flashMessage('Tasks was Successfully Deleted');
        return back();
    }
}
