<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDepartment extends FormRequest
{
    protected $redirectAction = "DepartmentsController@department_create";

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth()->check() && auth()->user()->position_id == 1) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|unique:departments'
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
            'name.required' => 'Название отдела обезательно к заполнению',
            'name.max' => 'Название отдела не должо быть более :max символов',
            'name.unique' => 'Отдел с таким названием уже существует',
        ];
    }
}
