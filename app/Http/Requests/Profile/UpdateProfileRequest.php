<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && ! $this->user()->isWali() && ! $this->user()->isSuperAdmin() && ! $this->user()->isModerator();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'height_cm' => ['nullable', 'integer', 'min:120', 'max:230'],
            'education_level' => ['nullable', 'string', 'max:100'],
            'profession' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'mother_tongue' => ['nullable', 'string', 'max:50'],
            'citizenship' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:2000'],

            // Religious fields
            'sect_madhhab' => ['nullable', 'string', 'in:Sunni - Hanafi,Sunni - Shafi\'i,Sunni - Maliki,Sunni - Hanbali,Shia - Jafari,Muslim (Unspecified)'],
            'prayer_frequency' => ['nullable', 'string', 'in:5x_daily,occasional,rarely'],
            'hijab_niqab_practice' => ['nullable', 'string', 'in:hijab,niqab,modest_without_hijab,planning_to_wear'],
            'beard_practice' => ['nullable', 'string', 'in:full_sunnah_beard,trimmed_beard,clean_shaven'],
            'quran_knowledge' => ['nullable', 'string', 'in:hafiz,fluent_reciter,basic_reader,learning'],
            'mosque_attendance' => ['nullable', 'string', 'in:daily,weekly_jummah,occasional'],
            'halal_dietary_adherence' => ['nullable', 'string', 'in:strictly_halal,halal_meat_only,flexible'],
            'polygamy_opinion' => ['nullable', 'string', 'max:50'],
            'desired_family_structure' => ['nullable', 'string', 'in:independent,joint_family'],

            // Family fields
            'parents_status' => ['nullable', 'string', 'max:100'],
            'parents_occupation' => ['nullable', 'string', 'max:100'],
            'siblings_count' => ['nullable', 'integer', 'min:0', 'max:20'],
            'family_religiosity' => ['nullable', 'string', 'in:very_practicing,moderate,traditional'],

            // Partner preferences
            'pref_age_min' => ['nullable', 'integer', 'min:18', 'max:80', 'lte:pref_age_max'],
            'pref_age_max' => ['nullable', 'integer', 'min:18', 'max:80'],
            'pref_sect' => ['nullable', 'string', 'max:100'],
            'pref_location' => ['nullable', 'string', 'max:100'],
            'pref_education' => ['nullable', 'string', 'max:100'],

            // Privacy & Wali
            'wali_required' => ['nullable', 'boolean'],
        ];
    }
}

