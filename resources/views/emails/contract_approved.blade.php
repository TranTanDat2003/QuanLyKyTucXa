<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thông Báo Duyệt Hợp Đồng Ký Túc Xá</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f4f4f4;">
    <!-- Container chính -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; border: 0; min-height: 100vh;">
        <tr>
            <td style="text-align: center; vertical-align: middle;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; margin: 20px auto; border: 0;">
                    <!-- Nội dung chính -->
                    <tr>
                        <td style="padding: 20px;">
                            <h2 style="color: #1e3a8a; margin: 0 0 10px;">Thông Báo Duyệt Hợp Đồng Ký Túc Xá</h2>
                            <p style="margin: 0 0 10px;">Kính gửi <strong>{{ $contract->student->full_name }}</strong>,</p>
                            <p style="margin: 0 0 10px;">Chúng tôi xin thông báo rằng hợp đồng ký túc xá của bạn đã được duyệt thành công với các thông tin sau:</p>
                            <ul style="margin: 0 0 20px; padding-left: 20px; list-style-type: none;">
                                <li><strong>Phòng:</strong> {{ $contract->room ? $contract->room->room_code : 'Chưa phân phòng' }}</li>
                                <li><strong>Loại phòng:</strong> {{ $contract->roomType ? $contract->roomType->room_type_name : 'Chưa phân loại phòng' }}</li>
                                <li><strong>Học kỳ:</strong> {{ $contract->semester->semester_name }}</li>
                                <li><strong>Ngày bắt đầu:</strong> {{ $contract->contract_start_date->format('d/m/Y') }}</li>
                                <li><strong>Ngày kết thúc:</strong> {{ $contract->contract_end_date->format('d/m/Y') }}</li>
                                <li><strong>Số tiền cần thanh toán:</strong> {{ number_format($contract->contract_cost, 0, ',', '.') }} VNĐ</li>
                            </ul>
                            <p style="margin: 0 0 10px;">Vui lòng truy cập hệ thống để thanh toán:</p>
                            <p style="text-align: center; margin: 20px 0;">
                                <a href="{{ route('student.pay') }}" style="display: inline-block; padding: 10px 20px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; border-radius: 5px;">Thanh Toán Ngay</a>
                            </p>
                            <p style="margin: 0 0 10px;">Hạn chót thanh toán: <strong style="color: #e63946;">{{ $contract->semester->start_date->copy()->subDay()->format('d/m/Y') }}</strong></p>
                            <p style="margin: 0;">Trân trọng,<br>Hệ Thống Quản Lý Ký Túc Xá</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px; background-color: #f8f8f8; font-size: 12px; color: #666666; text-align: center;">
                            <p style="margin: 0 0 10px;">Bạn nhận được email này vì đã đăng ký ở ký túc xá. Nếu không phải là người nhận, vui lòng xóa email này.</p>
                            <p style="margin: 0 0 10px;"><strong>Liên hệ hỗ trợ:</strong><br>Email: <a href="mailto:support@kytucxa.edu.vn" style="color: #1e3a8a;">support@kytucxa.edu.vn</a><br>Hotline: 0123 456 789</p>
                            <p style="margin: 0;">© 2025 Ký Túc Xá. 123 Đường ABC, Thành phố XYZ, Việt Nam.<br><em>Vui lòng không trả lời email này.</em></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>