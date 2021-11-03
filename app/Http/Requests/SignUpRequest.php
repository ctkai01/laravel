<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if(is_numeric($this->phoneOrEmail)) {
            $rules =  [ 
                "phoneOrEmail" => 'required|unique:users,phone|regex:/[0-9]{9}/',
            ];
        }else{
            $rules = ['phoneOrEmail' => 'required|email|unique:users,email'];
        }
        
        $validate = [
            'password'=>'required|min:6|max:30',
            'full_name' => 'required|max:30',
            'user_name' => 'required|min:3|max:20|unique:users,user_name|regex:/^[a-zA-Z0-9@.]+$/'
        ];

        $rules = array_merge($rules, $validate);
        return $rules;
    }
}
