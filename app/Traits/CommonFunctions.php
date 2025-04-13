<?php

namespace App\Traits;

trait CommonFunctions
{
    public function processImageUpload($image, $folder, $name, $oldImage = null)
    {
        // Kiểm tra nếu có image và tiến hành xóa ảnh cũ nếu cần
        if ($oldImage && file_exists(public_path($folder . '/' . $oldImage))) {
            unlink(public_path($folder . '/' . $oldImage));
        }
        $extension = $image->getClientOriginalExtension();
        $fileName = 'image' . time() . '-' . $name . '.' . $extension;
        // Di chuyển image vào thư mục
        $image->move(public_path($folder), $fileName);
        return $fileName;
    }

    public function processFileName($name)
    {
        // Loại bỏ dấu gạch ngang và khoảng trắng ở đầu và cuối
        $name = trim($name, ' -');

        // Loại bỏ tất cả khoảng trắng
        $name = preg_replace('/\s+/', '', $name);

        // Chỉ giữ lại các ký tự hợp lệ (chữ cái, số, dấu gạch ngang, dấu gạch dưới)
        $name = preg_replace('/[^A-Za-z0-9-_]/', '', $name);

        // Loại bỏ các dấu gạch ngang liên tiếp
        $name = preg_replace('/-+/', '-', $name);

        // Giới hạn độ dài tên file (tối đa 50 ký tự)
        return substr($name, 0, 50);
    }
}
