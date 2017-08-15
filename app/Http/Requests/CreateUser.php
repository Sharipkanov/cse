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
            'subdivision_id' => 'required'
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
            'name.required' => 'Тема документа обезательна к заполнению',
            'name.max' => 'Тема документа не должо быть более 255 символов',
            'files.required' => 'Прикрипите файл или файлы',
            'files.max' => 'Общий размер файла или файлов не должен привышать 10 мегабайт'
        ];
    }
}
