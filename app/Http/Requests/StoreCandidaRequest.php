<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreCandidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return User::isRecruiter();
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255'
        ];
    }
}