<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateExpertise extends FormRequest
{
    protected $redirectAction = "ExpertisesController@create";

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
            'info' => 'required',
            'case_number' => 'required',
            'category_id' => 'required',
            'article_number' => 'required',
            'expertise_status' => 'required',
            'expertise_additional_status' => 'required',
            'expertise_speciality_ids' => 'required',
            'expertise_region_id' => 'required',
            'expertise_agency_id' => 'required',
            'expertise_organ_id' => 'required',
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
            'info.required' => 'Заполните фабулу экспертизы',
            'category_id.required' => 'Выберите категорию дела',
            'case_number.required' => 'Заполните номер дела',
            'article_number.required' => 'Заполните номер статьи',
            'expertise_primary_status.required' => 'Выберите первичный статус',
            'expertise_status.required' => 'Выберите статус',
            'expertise_additional_status.required' => 'Выберите дополнительный статус',
            'expertise_speciality_ids.required' => 'Укажите шифры экспертиз',
            'expertise_region_id.required' => 'Выберите регион назначивший экспертизу',
            'expertise_agency_id.required' => 'Выберите наименование органа',
            'expertise_organ_id.required' => 'Выберите орган назначивший экспертизу',
            'files.required' => 'Прикрипите файл или файлы',
            'files.max' => 'Общий размер файла или файлов не должен привышать 10 мегабайт'
        ];
    }
}
