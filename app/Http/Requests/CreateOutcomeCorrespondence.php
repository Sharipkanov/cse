<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOutcomeCorrespondence extends FormRequest
{
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
            'language_id' => 'required',
            'executor_fullname' => 'required',
            'pages' => 'required',
            'correspondent_id' => 'required',
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
            'pages.required' => 'Укажите количество страниц'
        ];
    }

    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        return $this->redirector->to(route('page.correspondence.outcome.create', ['document' => $this->request->get('document_id')]))
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
