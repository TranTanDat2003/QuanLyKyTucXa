<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Ký Túc Xá</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-beta3/css/all.min.css') }}">

    <link rel="shortcut icon" href="{{ asset('images/logo/dorm.png') }}" type="image/x-icon">

    @vite(['resources/css/login.css', 'resources/js/app.js'])

    <style>
        body {
            background: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('{{ asset('images/login/RLC.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

</head>
<body>
    <div class="login-container">
        <div class="logo">
            <span>Ký Túc Xá</span>
        </div>
        <h2>Đăng Nhập</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Nhập Username" value="{{ old('username') }}" required>
                @error('username')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="login-btn">Đăng nhập</button>
            <div class="forgot-password">
                <a href="#">Quên mật khẩu?</a> {{-- {{ route('password.request') }} --}}
            </div>
        </form>
    </div>
</body>
</html>