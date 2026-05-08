<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'email'     => ['nullable', 'email', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:32'],
            'subject'   => ['required', 'string', 'max:200'],
            'body'      => ['required', 'string', 'min:20', 'max:5000'],
            'attachment'=> ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'subject.required'   => 'Subjek pengaduan wajib diisi.',
            'body.required'      => 'Isi pengaduan wajib diisi.',
            'body.min'           => 'Isi pengaduan minimal 20 karakter agar dapat diproses.',
            'attachment.max'     => 'Lampiran maksimal 5 MB.',
            'attachment.mimes'   => 'Lampiran hanya boleh PDF, gambar, atau dokumen.',
        ];
    }
}
