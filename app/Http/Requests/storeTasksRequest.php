<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeTasksRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required|date',
            'status' => 'required|in:done,in progress,ignored',
            'user_id'=> 'required',
        ];
    }
}
