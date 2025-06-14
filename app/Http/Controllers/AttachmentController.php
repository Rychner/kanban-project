<?php

namespace App\Http\Controllers;

use App\Traits\HasFile;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AttachmentRequest;
use App\Models\Card;
use App\Models\Attachment;

class AttachmentController extends Controller
{
    use HasFile;

    public function store(Card $card, AttachmentRequest $request): RedirectResponse
    {
        $request->user()->attachments()->create([
            'card_id'   => $card->id,
            'file'      => $this->upload_file($request, 'file', 'attachments'),
            'link'      => $request->link,
            'name'      => $request->name,
        ]);
        
        flashMessage('Attachments was Saved Successfully');
        return back();
    }

    public function destroy(Card $card, Attachment $attachment): RedirectResponse
    {
        $this->delete_file($attachment, 'file');
        $attachment->delete();

        flashMessage('Attachments was Successfully Deleted');
        return back();
    }
}
