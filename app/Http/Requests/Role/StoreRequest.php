<?php
namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

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
        $id = $this->route('role')?->id;;

        return [
            'name'        => 'required|unique:roles,name,' . ($id ?? 'NULL') . ',id',
            'permissions' => 'required|array|in:' . implode(',', Permission::pluck('name')->toArray()),
        ];
    }
}
