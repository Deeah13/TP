<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $identifier = (string) $this->input('identifier', '');
        $role = (string) $this->input('role', 'teacher');
        $remember = $this->has('remember') ? filter_var($this->input('remember'), FILTER_VALIDATE_BOOLEAN) : null;

        $this->merge(array_filter([
            'identifier' => trim($identifier),
            'role' => $role !== '' ? $role : 'teacher',
            'remember' => $remember,
        ], static fn ($value) => $value !== null));
    }

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
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string', Rule::in(['teacher'])],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}
