<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Enums\WorkspaceVisibility;
use App\Traits\HasFile;
use App\Http\Requests\WorkspaceRequest;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Member;
use App\Http\Resources\WorkspaceResource;

class WorkspaceController extends Controller
{
    use HasFile;

    public function create(): Response
    {
        return inertia(component: 'Workspace/Create', props: [
            'page_settings' => [
                'title'     => 'Create Workspace',
                'subtitle'  => 'Fill out this form to add new workspace',
                'method'    => 'POST',
                'action'    => route('workspace.store'),
            ],
            'visibilities'  => WorkspaceVisibility::options(),
        ]);
    }

    public function store(WorkspaceRequest $request): RedirectResponse
    {
        $workspace = $request->user()->workspaces()->create([
            'name'          => $name = $request->name,
            'slug'          => str()->slug($name. str()->uuid(10)),
            'cover'         => $this->upload_file($request, 'cover', 'workspace/cover'),
            'logo'          => $this->upload_file($request, 'logo', 'workspace/logo'),
            'visibility'    => $request->visibility,
        ]);

        $workspace->members()->create([
            'user_id'   => $request->user()->id,
            'role'      => $workspace->user_id == $request->user()->id ? 'Project Manager' : 'Member',
        ]);

        flashMessage('Workspace Information Succesfully Saved', 'success');

        return to_route('workspace.show', $workspace);
    }

    public function show(Workspace $workspace): Response
    {
        return inertia(component: 'Workspace/Show', props: [
            'workspace' => fn () => new WorkspaceResource($workspace),
        ]);
    }

    public function edit(Workspace $workspace): Response
    {
        return inertia(component: 'Workspace/Setting', props: [
            'workspace'     => fn () => new WorkspaceResource($workspace->load('members')),
            'page_settings' => [
                'title'     => 'Edit Workspace',
                'subtitle'  => 'Fill out this form to Edit Workspace',
                'method'    => 'PUT',
                'action'    =>  route('workspace.update', $workspace),
            ],
            'visibilities'    => WorkspaceVisibility::options(),
        ]);
    }

    public function update(Workspace $workspace, WorkspaceRequest $request): RedirectResponse
    {
        $workspace->update([
            'name'          => $name = $request->name,
            'slug'          => str()->slug($name. str()->uuid(10)),
            'cover'         => $request->hasFile('cover') ? $this->upload_file($request, 'cover', 'workspace/cover') : $workspace->cover,
            'logo'          => $request->hasFile('logo') ? $this->upload_file($request, 'logo', 'workspace/logo') : $workspace->logo,
            'visibility'    => $request->visibility,
        ]);

        flashMessage('Succesfully Update Workspace', 'success');

        return to_route('workspace.show', $workspace);
    }

    public function member_store(Workspace $workspace, Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required','email','string',
            ],
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if(!$user)
        {
            flashMessage('Unregistered User', 'error');
            return back();
        }

        if($workspace->members()->where('user_id', $user->id)->exists())
        {
            flashMessage('User is already a Member of this workspace', 'error');
            return back();
        }

        $workspace->members()->create([
            'user_id'   => $user->id,
            'role'      => 'Member',
        ]);

        flashMessage('Member Succesfully Invited');
        return back();
    }

    public function member_destroy(Workspace $workspace, Member $member): RedirectResponse
    {
        $member->delete();

        flashMessage('Member Succesfully Deleted');
        return back();
    }
}
