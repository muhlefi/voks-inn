<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
        $roomId = $this->route('room');
        $roomId = is_object($roomId) ? $roomId->getKey() : $roomId;

        return [
            'kode_kamar' => ['required', 'string', 'max:50', 'unique:rooms,kode_kamar'.($roomId ? ',' . $roomId : '')],
            'nama_kamar' => ['required', 'string', 'max:100'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'harga_per_malam' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:kosong,terisi,maintenance'],
        ];
    }
}
