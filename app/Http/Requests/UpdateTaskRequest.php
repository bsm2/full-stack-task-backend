<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,in_progress,done',
            'due_date' => 'sometimes|required|date|date_format:Y-m-d',
            'priority' => 'sometimes|required|in:low,medium,high',
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
