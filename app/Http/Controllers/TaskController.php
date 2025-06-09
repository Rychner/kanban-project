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

    public function item(Card $card, Task $task, Request $request): RedirectResponse
    {
        $request->validate([
            'item'  => ['required', 'string', 'max:255'],
        ]);

        $task->children()->create([
            'card_id'   => $card->id,
            'user_id'   => $request->user()->id,
            'title'     => $request->item,
        ]);

        flashMessage("Successfully added an item to Task $task->title");
        return back();
    }

    public function completed(Card $card, Task $task): RedirectResponse
    {
        $previous_is_completed = $task->is_completed;

        $task->update([
            'is_completed'  => !$task->is_completed,
        ]);

        $parent = Task::findOrFail($task->parent_id);

        if(Task::where('parent_id', $parent->id)->count() == Task::where('parent_id', $parent->id)->where('is_completed', true)->count())
        {
            $parent->update([
                'is_completed'  => true,
            ]);

            flashMessage('The Task Item is Successfully Completed');           
        } else {
            $parent->update([
                'is_completed'  => false,
            ]);

            flashMessage('The Task Item is Successfully '. ($previous_is_completed ? 'unmarked' : 'marked'));            return back();
        }

        return back();
    }
}
