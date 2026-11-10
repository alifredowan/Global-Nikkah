<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StorePromoCodeRequest extends FormRequest
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
        if (is_string($this->input('code'))) {
            $this->merge(['code' => strtoupper(trim($this->input('code')))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:30', 'unique:discount_codes,code'],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'plan_slug' => ['nullable', 'string'],
            'allowed_emails' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:255'],
            'max_uses' => ['required', 'integer', 'min:1'],
            'expires_at' => ['nullable', function (string $attribute, mixed $value, Closure $fail): void {
                if (! $value) {
                    return;
                }
                try {
                    if (str_contains((string) $value, '/')) {
                        Carbon::createFromFormat('d/m/Y', (string) $value);
                    } else {
                        Carbon::parse((string) $value);
                    }
                } catch (\Throwable) {
                    $fail('Please enter the expiration date in dd/mm/yyyy format.');
                }
            }],
        ];
    }
}

