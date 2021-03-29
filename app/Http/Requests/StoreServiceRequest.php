<?php

namespace App\Http\Requests;

use App\Services;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreServiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'name'     => [
                'required'],
            'description'    => [
                'required'],
            'icon' => [
                    'required',
                    'mimes:jpeg,jpg,png,gif,svg|required|max:512'],
        ];

    }
}
