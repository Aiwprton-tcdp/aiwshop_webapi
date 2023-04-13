<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|unique:name',
            'email' => 'required|string|unique:email',
            'password' => 'required|string|'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A `name` is required',
            'email.required' => 'A `email` is required',
            'password.required' => 'A `password` is required',
            'name.unique'  => 'This name is already taken',
            'email.unique'  => 'This email is already taken',
        ];
    }

    public function all($keys = null)
    {
      // return $this->all();
      $data = parent::all($keys);
      switch ($this->getMethod())
      {
        // case 'PUT':
        // case 'PATCH':
        // case 'DELETE':
        //   $data['date'] = $this->route('day');
      }
      return $data;
    }
    
    protected $stopOnFirstFailure = true;
}
