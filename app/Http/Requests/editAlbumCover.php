<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editAlbumCover extends FormRequest
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
            'edit_album_covers'     => 'nullable|array',
            'edit_album_covers.*'   => 'nullable|file',
            'id'                    => 'required',
            'delete_pictures'       => 'nullable|array'
        ];
    }
}
