<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChatRequest extends FormRequest
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
            'admin_id' => 'sometimes|required|numeric',
            'active' => 'sometimes|required|boolean',
            'readonly' => 'sometimes|required|boolean',
            'autodelete' => 'sometimes|required|boolean',
            'expires_at' => 'required_if:autodelete,true|sometimes|nullable|date',
        ];
    }
}
