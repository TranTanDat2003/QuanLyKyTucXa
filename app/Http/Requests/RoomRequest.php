<?php

namespace App\Http\Requests;

use App\Models\Building;
use App\Models\Room;
use App\Rules\UniqueRoomCode;
use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'staff']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('roomId') ?? $this->input('room_id');
        return [
            'room_code' => [
                'required',
                'string',
                'max:100',
                'room_code_unique' => new UniqueRoomCode($this->input('building_id'), $id),
            ],
            'building_id' => 'required|exists:buildings,building_id',
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'status' => 'required|in:Đang sử dụng,Không sử dụng,Đang sửa chữa',
            'gender' => 'required|in:Nam,Nữ',
        ];
    }

    public function messages(): array
    {
        return [
            'room_code.required' => 'Mã phòng không được để trống',
            'room_code.string' => 'Mã phòng phải là chuỗi ký tự',
            'room_code.max' => 'Mã phòng không được quá 100 ký tự',
            'room_code.room_code_unique' => 'Mã phòng này đã được sử dụng trong tòa nhà này',
            'building_id.required' => 'Tòa nhà không được để trống',
            'building_id.exists' => 'Tòa nhà không tồn tại trong hệ thống',
            'room_type_id.required' => 'Loại phòng không được để trống',
            'room_type_id.exists' => 'Loại phòng không tồn tại trong hệ thống',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái phải là một trong: Đang sử dụng, Không sử dụng, Đang sửa chữa',
            'gender.required' => 'Giới tính không được để trống',
            'gender.in' => 'Giới tính phải là Nam hoặc Nữ',
        ];
    }
}
