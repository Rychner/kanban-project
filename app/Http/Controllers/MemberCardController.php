<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Card;
use App\Models\User;

class MemberCardController extends Controller
{
    public function member_store(Card $card, Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required','email','string'],
        ]);

        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if(!$user)
        {
            flashMessage('Unregistered User', 'error');
            return back();
        }

        if($card->members()->where('user_id', $user->id)->exists())
        {
            flashMessage('User is already member of this Card', 'error');
            return back();
        }

        $card->members()->create([
            'user_id'   => $user->id,
            'role'      => 'Member',
        ]);

        flashMessage('Member Successfully Invited');
        return back();
    }
}
