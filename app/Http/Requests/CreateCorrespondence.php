<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCorrespondence extends FormRequest
{
    protected $redirectAction = 'CorrespondencesController@create';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->check()) {
            if(auth()->user()->position_id == 4) return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language_id' => 'required',
            'correspondent_id' => 'required',
            'executor_fullname' => 'required',
            'outcome_number' => 'required',
            'outcome_date' => 'required',
            'pages' => 'required',
            'files' => 'max:10000'
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
            'language_id.required' => 'Выберите язык обращения',
            'correspondent_id.required' => 'Укажите корреспондента',
            'executor_fullname.required' => 'Заполните ФИО исполнителя',
            'outcome_number.required' => 'Заполните исходящий номер',
            'outcome_date.required' => 'Выберите дата исходящего',
            'pages.required' => 'Укажите количество страниц',
            'files.max' => 'Общий размер файла или файлов не должен привышать 10 мегабайт'
        ];
    }
}
