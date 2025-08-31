<?php
namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;

class TranslationStoreRequest extends FormRequest
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
            'lang'      => 'required|in:' . implode(',', array_keys(config('app.languages'))),
            'type'      => 'required|in:menu,module,question,answer',
            'topic_id'  => 'required|exists:topics,id',
            'title'     => 'required',
            'sub_title' => 'nullable',
            'content'   => 'nullable',
        ];
    }
}
