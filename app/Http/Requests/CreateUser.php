<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
{
    protected $redirectAction = "UsersController@create";

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth()->check()) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'middle_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'department_id' => 'required',
            'position_id' => 'required'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'Укажите Имя пользователя',
            'first_name.max' => 'Имя не должно превышать 255 символов',
            'last_name.required' => 'Укажите Фамилию пользователя',
            'last_name.max' => 'Фамилия не должна превышать 255 символов',
            'middle_name.required' => 'Укажите Отчество пользователя',
            'middle_name.max' => 'Отчество не должно превышать 255 символов',
            'email.required' => 'Укажите электроную почту пользователя',
            'email.email' => 'Электронный адрес должен быть дейсвительным',
            'email.max' => 'Электронный адрес не должен превышать 255 символов',
            'department_id.required' => 'Укажите отдел пользователя',
            'position_id.required' => 'Укажите должность пользователя',
        ];
    }
}
