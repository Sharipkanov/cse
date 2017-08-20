<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTask extends FormRequest
{
    protected $redirectAction = "TasksController@index";

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
            'executor_id' => 'required',
            'execution_date' => 'required',
            'execution_time' => 'required',
            'info' => 'required|max:255'
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
            'executor_id.required' => 'Выберите исполнителя',
            'execution_date.required' => 'Срок исполнения обезателен к заполнению',
            'execution_time.required' => 'Время исполнения обезательно к заполнению',
            'info.required' => 'Информация задания обезательна к заполнению',
            'info.max' => 'Информация задания не должо быть более :max символов'
        ];
    }
}
