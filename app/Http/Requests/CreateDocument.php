<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocument extends FormRequest
{
    protected $redirectAction = "DocumentsController@create";

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
            'name' => 'required|max:255',
            'info' => 'nullable',
            'files' => 'required|max:10000'
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
