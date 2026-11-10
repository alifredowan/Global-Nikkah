<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $packageId = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                $packageId ? Rule::unique('subscription_plans', 'slug')->ignore($packageId) : 'unique:subscription_plans,slug',
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'annual_price' => ['nullable', 'numeric', 'min:0'],
            'daily_profile_views' => ['required', 'integer'],
            'daily_interests' => ['required', 'integer'],
            'advanced_filters' => ['nullable', 'boolean'],
            'profile_boost' => ['nullable', 'boolean'],
            'see_who_viewed' => ['nullable', 'boolean'],
            'dedicated_advisor' => ['nullable', 'boolean'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}

