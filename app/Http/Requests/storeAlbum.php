<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeAlbum extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "album-name" => 'nullable|string',
            "released-date" => 'nullable|string',
            "recording-studio" => 'nullable|string',
            "album-description" => 'nullable|string',
            "album-covers" => 'nullable|array'
        ];
    }
}
