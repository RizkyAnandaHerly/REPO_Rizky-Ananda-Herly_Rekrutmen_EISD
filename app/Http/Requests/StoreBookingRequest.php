<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isResident();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'scheduled_date' => 'required|date|after_or_equal:today',
            'waste_categories' => 'required|array|min:1',
            'waste_categories.*.id' => 'required|exists:waste_categories,id',
            'waste_categories.*.estimated_weight' => 'required|numeric|min:0.1',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'scheduled_date.required' => 'Tanggal setoran wajib diisi.',
            'scheduled_date.after_or_equal' => 'Tanggal setoran harus hari ini atau setelahnya.',
            'waste_categories.required' => 'Pilih minimal 1 kategori sampah.',
            'waste_categories.min' => 'Pilih minimal 1 kategori sampah.',
            'waste_categories.*.id.required' => 'Kategori sampah tidak valid.',
            'waste_categories.*.id.exists' => 'Kategori sampah tidak ditemukan.',
            'waste_categories.*.estimated_weight.required' => 'Perkiraan berat wajib diisi.',
            'waste_categories.*.estimated_weight.min' => 'Perkiraan berat minimal 0.1.',
            'waste_categories.*.estimated_weight.numeric' => 'Perkiraan berat harus berupa angka.',
        ];
    }
}
