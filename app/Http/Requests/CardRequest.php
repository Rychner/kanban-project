<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\CardStatus;
use App\Enums\CardPriority;

class CardRequest extends FormRequest
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
            'title'         => ['required','max:255','string'],
            'description'   => ['required','string'],
            'deadline'      => ['nullable', 'date'],
            'priority'      => ['required', new Enum(CardPriority::class)],
            'status'        => ['required', new Enum(CardStatus::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'         => 'Title',
            'description'   => 'Description',
            'deadline'      => 'Deadline',
            'status'        => 'Status',
            'priority'      => 'Priority',
        ];
    }
}
