<!-- resources/views/student/student.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản lý Ký Túc Xá')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('vendor/toastr/toastr.min.css') }}" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('images/logo/dorm.png') }}" type="image/x-icon">

    @vite(['resources/css/student.css', 'resources/js/student.js'])

    @stack('styles')
</head>
<body>
    <!-- Loading Animation -->
    <div class="loading">
        <div class="spinner"></div>
    </div>

    <div id="app">
        <!-- Header -->
        @include('layouts.studentHeader')

        <main>
            <!-- Page Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.studentFooter')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script>
        // Hamburger Menu Toggle
        const hamburger = document.querySelector('.hamburger');
        const navUl = document.querySelector('nav ul');

        if (hamburger && navUl) {
            hamburger.addEventListener('click', () => {
                navUl.classList.toggle('active');
            });
        }

        // Dropdown Toggle
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            if (dropdown) {
                dropdown.classList.toggle('active');
            }
        }

        // Close Dropdown when clicking outside
        document.addEventListener('click', (event) => {
            const dropdown = document.getElementById('dropdownMenu');
            const avatar = document.querySelector('.avatar');
            if (dropdown && avatar && !avatar.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>

    <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>

    @stack('scripts')
</body>
</html>