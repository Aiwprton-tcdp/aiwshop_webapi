<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'good' => 'required|numeric',
            'buyer' => 'required|numeric',
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'is_returned' => 'boolean',
        ];
    }
}
