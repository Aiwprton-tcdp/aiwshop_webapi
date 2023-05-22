<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|numeric',
            'admin_id' => 'sometimes|required|numeric',
            'readonly' => 'sometimes|required|boolean',
            'autodelete' => 'sometimes|required|boolean',
            'expires_at' => 'required_if:autodelete,true|sometimes|nullable|date',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'user_id.required' => 'The `user_id` field is required',
    //         'user_id.numeric' => 'The `user_id` field must be an numeric',
    //         'admin_id.numeric' => 'The `admin_id` field must be an numeric',
    //         'readonly.boolean' => 'The `readonly` field must be a boolean',
    //         'autodelete.boolean' => 'The `autodelete` field must be a boolean',
    //         'expires_at.date' => 'The `expires_at` field must be a date',
    //     ];
    // }
}
