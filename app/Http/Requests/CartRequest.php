<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'numeric',
            'good_id' => 'required|numeric'
        ];
    }
}
