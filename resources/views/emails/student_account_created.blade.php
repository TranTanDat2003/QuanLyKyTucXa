<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thông Báo Tài Khoản Sinh Viên</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; border: 0; min-height: 100vh;">
        <tr>
            <td style="text-align: center; vertical-align: middle;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; margin: 20px auto; border: 0;">
                    <tr>
                        <td style="padding: 20px;">
                            <h2 style="color: #1e3a8a; margin: 0 0 10px;">Thông Báo Tài Khoản Sinh Viên</h2>
                            <p style="margin: 0 0 10px;">Kính gửi <strong>{{ $student->full_name }}</strong>,</p>
                            <p style="margin: 0 0 10px;">Tài khoản sinh viên của bạn đã được tạo thành công trên Hệ Thống Quản Lý Ký Túc Xá. Dưới đây là thông tin đăng nhập:</p>
                            <ul style="margin: 0 0 20px; padding-left: 20px; list-style: none !important;">
                                <li style="list-style: none;"><strong>Mã sinh viên (Tên đăng nhập):</strong> {{ $student->student_code }}</li>
                                <li style="list-style: none;"><strong>Mật khẩu:</strong> {{ $password }}</li>
                            </ul>
                            <p style="margin: 0 0 10px;">Vui lòng đăng nhập vào hệ thống để kiểm tra thông tin và đổi mật khẩu ngay lần đầu tiên:</p>
                            <p style="text-align: center; margin: 20px 0;">
                                <a href="{{ url('/login') }}" style="display: inline-block; padding: 10px 20px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; border-radius: 5px;">Đăng Nhập Ngay</a>
                            </p>
                            <p style="margin: 0 0 10px; color: #e63946;">Lưu ý: Đây là mật khẩu tạm thời, vui lòng đổi mật khẩu sau khi đăng nhập để đảm bảo an toàn.</p>
                            <p style="margin: 0;">Trân trọng,<br>Hệ Thống Quản Lý Ký Túc Xá</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; background-color: #f8f8f8; font-size: 12px; color: #666666; text-align: center;">
                            <p style="margin: 0 0 10px;">Bạn nhận được email này vì tài khoản sinh viên của bạn đã được tạo. Nếu không phải là người nhận, vui lòng xóa email này.</p>
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