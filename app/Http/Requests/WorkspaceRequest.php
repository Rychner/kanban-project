<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\WorkspaceVisibility;

class WorkspaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'max:255', 'string',
            ],
            'cover' => [
                Rule::when($this->routeIs('workspace.store'), ['required','mimes:png,jpg', 'max:2048']),
                Rule::when($this->routeIs('workspace.update'), ['nullable','mimes:png,jpg', 'max:2048'])
            ],
            'logo' => [
                Rule::when($this->routeIs('workspace.store'), ['required','mimes:png,jpg', 'max:2048']),
                Rule::when($this->routeIs('workspace.update'), ['nullable','mimes:png,jpg', 'max:2048'])
            ],
            'visibility' => [
                'nullable',
                new Enum(WorkspaceVisibility::class),
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'name'          => 'Name',
            'cover'         => 'Cover',
            'logo'          => 'Logo',
            'visibility'    => 'Visibility',
        ];
    }
}
