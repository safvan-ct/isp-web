<?php
namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;

class HadithStoreRequest extends FormRequest
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
            "topic_id"          => "required|exists:topics,id",
            "type"              => "required|in:answer",
            "hadith_book_id"    => "required|exists:hadith_books,id",
            "hadith_chapter_id" => "required|exists:hadith_chapters,id",
            "hadith_verse_id"   => "required|exists:hadith_verses,id",
            "simplified"        => "required",
            "translation_json"  => "required|json",
        ];
    }
}
