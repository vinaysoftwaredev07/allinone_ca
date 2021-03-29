<?php

namespace App\Http\Requests;

use App\Services;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDocMapsRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;

    }

    public function rules()
    {
        return [
            'service'     => [
                'required'],
            'documents.*' => [
                'integer'],
            'documents'   => [
                'required',
                'array'],
        ];

    }
}
