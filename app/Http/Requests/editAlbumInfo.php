<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editAlbumInfo extends FormRequest
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
            "album_name"        => "nullable|string",
            "released_date"     => "nullable|integer",
            "recording_studio"  => "nullable|string",
            "album_description" => "nullable|string",
            "id"                => "required|integer|exists:albums,id"
        ];
    }
}
