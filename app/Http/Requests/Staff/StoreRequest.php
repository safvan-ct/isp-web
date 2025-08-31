<?php
namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $userId = $this->route('staff')?->id;

        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone'      => 'required|digits:10',
            'role'       => 'required|exists:roles,name',
            'password'   => [$userId ? 'nullable' : 'required', 'min:6'],
        ];
    }
}
