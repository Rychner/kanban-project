<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Enums\WorkspaceVisibility;
use App\Traits\HasFile;
use App\Http\Requests\WorkspaceRequest;

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

    public function store(WorkspaceRequest $request)
    {
        $request->user()->workspaces()->create([
            'name'          => $name = $request->name,
            'slug'          => str()->slug($name. str()->uuid(10)),
            'cover'         => $this->upload_file($request, 'cover', 'workspace/cover'),
            'logo'          => $this->upload_file($request, 'logo', 'workspace/logo'),
            'visibility'    => $request->visibility,
        ]);

        flashMessage('Workspace Information Succesfully Saved', 'success');

        return back();
    }
}
