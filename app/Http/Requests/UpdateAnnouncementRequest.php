<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string|max:20',
            'description' => 'string|max:200',
            'departement' => 'string|max:20',
            'website' => 'string|max:200',
            'image_path' => 'image|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'title.string' => 'Le titre doit être une chaîne de caractères',
            'title.max' => 'Le titre ne doit pas dépasser 20 caractères',
            'description.string' => 'La description doit être une chaîne de caractères',
            'description.max' => 'La description ne doit pas dépasser 200 caractères',
            'departement.string' => 'Le département doit être une chaîne de caractères',
            'departement.max' => 'Le département ne doit pas dépasser 20 caractères',
            'website.string' => 'Le site web doit être une chaîne de caractères',
            'website.max' => 'Le site web ne doit pas dépasser 200 caractères',
            'image_path.image' => 'Le fichier doit être une image',
            'image_path.max' => 'Le fichier ne doit pas dépasser 2Mo',
        ];
    }
}
