<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
        return [
            'nama_tamu' => ['required', 'string', 'max:255'],
            'no_identitas' => ['required', 'string', 'max:50'],
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'before:check_out'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'jumlah_tamu' => ['required', 'integer', 'min:1'],
            'denda' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
