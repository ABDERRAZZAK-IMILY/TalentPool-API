<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidaturesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'candidat_id' => 'required|exists:candidats,id',
            'annonce_id' => 'required|exists:annonces,id',
            'cv' => 'required|string',
            'lettre_motivation' => 'required|string',
            'statu' => 'required|string'
        ];
    }
}
