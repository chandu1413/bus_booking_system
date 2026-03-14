<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }

    public function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string|max:10000',
            'status'          => 'required|in:todo,in_progress,in_review,done',
            'priority'        => 'required|in:low,medium,high,urgent',
            'assignee_id'     => 'nullable|exists:users,id',
            'due_date'        => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0|max:9999',
        ];
    }
}