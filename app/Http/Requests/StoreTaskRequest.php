<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:,pending,in_progress,done',
            'due_date' => 'required|date|after:today|date_format:Y-m-d',
            'priority' => 'required|in:low,medium,high',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes(
            'user_id',
            ['nullable', 'integer', 'exists:users,id'],
            fn() => $this->user()?->hasRole('admin')
        );
    }
}