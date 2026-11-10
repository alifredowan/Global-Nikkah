<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isSuperAdmin();
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        $email = $this->input('email');
        if (is_string($email)) {
            $this->merge(['email' => strtolower(trim($email))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $normalizedEmail = $this->input('email');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) use ($normalizedEmail) {
                    return $query->whereRaw('LOWER(email) = ?', [$normalizedEmail]);
                }),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in([UserRole::SuperAdmin->value, UserRole::Moderator->value])],
            'password' => ['required', Password::defaults()],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:manage_verifications,manage_reports,view_audit_logs,manage_settings'],
        ];
    }

    /**
     * Get error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'A user or staff member with this email address already exists.',
        ];
    }
}

