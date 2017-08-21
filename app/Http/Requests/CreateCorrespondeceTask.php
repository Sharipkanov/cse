<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CreateCorrespondeceTask extends FormRequest
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

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        return $this->redirector->to(route('page.correspondence.show', ['correspondence' => $this->request->get('correspondence_id')]))
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
