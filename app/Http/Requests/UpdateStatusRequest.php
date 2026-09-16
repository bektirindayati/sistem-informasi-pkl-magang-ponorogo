<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|in:pending,revisi,diterima,ditolak',

            'alasan_penolakan' => 'nullable|required_if:status,ditolak|string',
            'alasan_revisi' => 'nullable|required_if:status,revisi|string',
        ];
    }

    public function messages()
    {
        return [
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi kalau status diubah menjadi Ditolak.',
            'alasan_revisi.required_if' => 'Alasan revisi wajib diisi kalau status diubah menjadi Revisi.',
        ];
    }
}