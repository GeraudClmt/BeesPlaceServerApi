<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
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
            'title' => 'required|string|max:20',
            'description' => 'required|string|max:200',
            'departement' => 'required|string|min:1|max:20|in:Drome,Ardeche',
            'website' => 'string|max:200',
            'image_path' => 'required|image|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'title.string' => 'Le titre doit être une chaîne de caractères',
            'title.max' => 'Le titre ne doit pas dépasser 20 caractères',
            'description.required' => 'La description est obligatoire',
            'description.string' => 'La description doit être une chaîne de caractères',
            'description.max' => 'La description ne doit pas dépasser 200 caractères',
            'departement.required' => 'Le département est obligatoire',
            'departement.string' => 'Le département doit être une chaîne de caractères',
            'departement.max' => 'Le département ne doit pas dépasser 20 caractères',
            'website.string' => 'Le site web doit être une chaîne de caractères',
            'website.max' => 'Le site web ne doit pas dépasser 200 caractères',
            'image_path.image' => 'Le fichier doit être une image',
            'image_path.max' => 'Le fichier ne doit pas dépasser 2Mo',
        ];
    }
}
