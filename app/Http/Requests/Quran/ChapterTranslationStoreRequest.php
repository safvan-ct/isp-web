<?php
namespace App\Http\Requests\Quran;

use Illuminate\Foundation\Http\FormRequest;

class ChapterTranslationStoreRequest extends FormRequest
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
            'name'             => 'required',
            'lang'             => 'required|in:' . implode(',', array_diff(array_keys(config('app.languages')), ['ar'])),
            'translation'      => 'required',
            'quran_chapter_id' => 'required|exists:quran_chapters,id',
        ];
    }
}
