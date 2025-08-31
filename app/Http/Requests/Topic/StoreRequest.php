<?php
namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $type = $this->route('type');

        return [
            'slug'       => 'required|unique:topics,slug',
            'parent_id'  => [$type !== 'menu' ? 'required' : 'nullable', 'exists:topics,id'],
            'is_primary' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'parent_id.required' => 'Please select a parent topic.',
        ];
    }
}
