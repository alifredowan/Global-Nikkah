<?php

namespace App\Http\Requests\Auth;

use App\Enums\Gender;
use App\Enums\UserRole;
use App\Rules\Turnstile;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Propaganistas\LaravelPhone\Rules\Phone;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $role = $this->input('role');
        if (! in_array($role, [UserRole::Seeker->value, UserRole::Wali->value], true)) {
            $role = UserRole::Seeker->value;
        }

        $email = $this->input('email');
        $phone = $this->input('phone');

        $this->merge([
            'role' => $role,
            'email' => is_string($email) ? strtolower(trim($email)) : $email,
            'phone' => is_string($phone) ? trim($phone) : $phone,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxBirthDate = Carbon::now()->subYears(18)->format('d/m/Y');
        $role = $this->input('role');
        $normalizedEmail = $this->input('email');

        $rules = [
            'role' => ['required', Rule::in([UserRole::Seeker->value, UserRole::Wali->value])],
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
            'phone_country' => ['nullable', 'required_with:phone', 'string', 'size:2'],
            'phone' => ['nullable', 'string', new Phone, 'unique:users,phone'],
            'gender' => ['required', Rule::in(Gender::values())],
            'dob' => ['required', 'date_format:d/m/Y', "before_or_equal:{$maxBirthDate}"],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
            'cf-turnstile-response' => [new Turnstile],
        ];

        if ($role === UserRole::Seeker->value) {
            $rules['marital_status'] = ['required', 'in:never_married,divorced,widowed,annulled'];
        } else {
            $rules['marital_status'] = ['nullable', 'in:never_married,divorced,widowed,annulled'];
            $rules['relationship_type'] = ['nullable', 'string', 'in:father,brother,uncle,grandfather,other_mahram'];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'An account with this email address already exists. Please sign in or use forgot password.',
            'phone.phone' => 'Please enter a valid phone number for the selected country.',
            'dob.date_format' => 'Please enter the date of birth in dd/mm/yyyy format.',
            'dob.before_or_equal' => 'You must be at least 18 years old to register for Global Nikkah.',
        ];
    }
}

