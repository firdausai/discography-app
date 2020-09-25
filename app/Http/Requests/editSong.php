<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editSong extends FormRequest
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
            'edit_song_index'   => 'nullable|integer',
            'edit_song_title'   => 'nullable|string',
            'edit_singer'       => 'nullable|string',
            'edit_arranger'     => 'nullable|string',
            'edit_band_leader'  => 'nullable|string',
            'edit_band_name'    => 'nullable|string',
            'edit_audio_file'   => 'nullable|file',
            'id'                => 'required|integer|exists:songs,id'
        ];
    }
}
