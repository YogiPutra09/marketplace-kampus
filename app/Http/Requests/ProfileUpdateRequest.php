<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            Rule::unique(User::class)->ignore($this->user()->id),
        ],
        'whatsapp_number' => [
            'required',
            'regex:/^62[0-9]{9,13}$/',
            Rule::unique(User::class)->ignore($this->user()->id),
        ],
        'faculty' => ['nullable', 'string', 'max:100'],
        'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'], // Maksimal 1MB
    ];
}

    /**
     * Kustomisasi pesan error (opsional).
     */
    public function messages(): array
    {
        return [
            'whatsapp_number.regex' => 'Format nomor WhatsApp harus diawali 62 (contoh: 6281234567890).',
            'whatsapp_number.unique' => 'Nomor WhatsApp ini sudah digunakan oleh akun lain.',
        ];
    }
}