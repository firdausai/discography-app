<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeSong extends FormRequest
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
            'album_id'      => 'required|exists:albums,id',
            'song_index'    => 'nullable|integer',
            'song_title'    => 'nullable|string',
            'song_writer'   => 'nullable|string',
            'singer'        => 'nullable|string',
            'arranger'      => 'nullable|string',
            'band_leader'   => 'nullable|string',
            'band_name'     => 'nullable|string',
            'audio_file'    => 'required|file|mimes:mp3',
        ];
    }
}
