@extends('layouts.student')

@section('title', 'Thông Tin Cá Nhân - Ký Túc Xá')

@section('content')
    <!-- Profile Section -->
    <section class="profile">
        <div class="profile-info">
            <img src="{{ $student->image === 'default_profile.jpg' ? asset('images/profiles/default_profile.jpg') : asset('images/profiles/students/' . $student->image) }}" 
                 alt="Avatar" class="avatar" id="currentAvatar">
            <h2>{{ $student->full_name }}</h2>
            <p><strong>Mã sinh viên:</strong> {{ $student->student_code }}</p>
            <p><strong>Ngày sinh:</strong> {{ $student->date_of_birth ?? 'Chưa cập nhật' }}</p>
            <p><strong>Số điện thoại:</strong> {{ $student->phone ?? 'Chưa cập nhật' }}</p>
            <p><strong>Email:</strong> {{ $student->email ?? 'Chưa cập nhật' }}</p>
            <p><strong>Ngành học:</strong> {{ $student->major ?? 'Chưa cập nhật' }}</p>
            <p><strong>Lớp:</strong> {{ $student->class ?? 'Chưa cập nhật' }}</p>
            <p><strong>Số phòng:</strong> {{ $currentContract && $currentContract->room ? $currentContract->room->room_code : 'Chưa có phòng' }}</p>
            <p><strong>Ngày vào ở:</strong> {{ $currentContract ? $currentContract->contract_start_date->format('d/m/Y') : 'Chưa có' }}</p>
            <button class="edit-btn" onclick="openEditModal()">Chỉnh sửa thông tin</button>
            <button class="edit-btn" onclick="openPasswordModal()">Đổi mật khẩu</button>
        </div>

        <div class="profile-history">
            <h2>Lịch sử</h2>
            <div class="history-tabs">
                <button class="active" onclick="showHistory('bookings')">Đặt phòng</button>
                <button onclick="showHistory('payments')">Thanh toán</button>
            </div>
            <div class="history-content active" id="bookings">
                @foreach ($bookingHistory as $booking)
                    <div class="history-item" data-page="{{ ceil($loop->iteration / 3) }}">
                        <p><strong>Phòng:</strong> {{ $booking->room ? $booking->room->room_code : 'Chưa phân phòng' }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $booking->created_at->format('d/m/Y') }}</p>
                        <p><strong>Trạng thái:</strong> {{ $booking->status }}</p>
                    </div>
                @endforeach
                <div class="pagination" id="bookings-pagination"></div>
            </div>
            <div class="history-content" id="payments">
                @foreach ($paymentHistory as $payment)
                    <div class="history-item" data-page="{{ ceil($loop->iteration / 3) }}">
                        <p><strong>Hóa đơn #{{ $payment['id'] }}:</strong> {{ number_format($payment['amount'], 0, ',', '.') }} VNĐ</p>
                        <p><strong>Ngày thanh toán:</strong> {{ $payment['payment_date'] }}</p>
                        <p><strong>Loại:</strong> {{ $payment['type'] }}</p>
                    </div>
                @endforeach
                <div class="pagination" id="payments-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Edit Profile Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h2>Chỉnh sửa thông tin</h2>
            <img src="{{ $student->image === 'default_profile.jpg' ? asset('images/profiles/default_profile.jpg') : asset('images/profiles/students/' . $student->image) }}" 
                 alt="Preview Avatar" class="preview-avatar" id="previewAvatar">
            <!-- Trong phần form của Edit Profile Modal -->
            <form id="editProfileForm" action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="avatar">Thay đổi ảnh đại diện</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="avatar" name="image" accept="image/*">
                        <span class="file-label">Chọn ảnh</span>
                        <span class="file-name" id="fileName">Chưa chọn ảnh</span>
                    </div>
                    <span class="text-danger" id="edit_image-error"></span>
                </div>
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input type="text" id="name" name="full_name" value="{{ $student->full_name }}">
                    <span class="text-danger" id="edit_full_name-error"></span>
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Ngày sinh</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ $student->date_of_birth }}">
                    <span class="text-danger" id="edit_date_of_birth-error"></span>
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" id="phone" name="phone" value="{{ $student->phone }}">
                    <span class="text-danger" id="edit_phone-error"></span>
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" id="address" name="address" value="{{ $student->address }}">
                    <span class="text-danger" id="edit_address-error"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $student->email }}">
                    <span class="text-danger" id="edit_email-error"></span>
                </div>
                <div class="form-group">
                    <label for="major">Ngành học</label>
                    <input type="text" id="major" name="major" value="{{ $student->major }}">
                    <span class="text-danger" id="edit_major-error"></span>
                </div>
                <div class="form-group">
                    <label for="class">Lớp</label>
                    <input type="text" id="class" name="class" value="{{ $student->class }}">
                    <span class="text-danger" id="edit_class-error"></span>
                </div>
                <div class="form-group">
                    <label for="studentId">Mã sinh viên</label>
                    <input type="text" id="studentId" value="{{ $student->student_code }}" readonly>
                </div>
                <button type="submit" class="save-btn">Lưu thay đổi</button>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <h2>Đổi mật khẩu</h2>
            <form id="changePasswordForm" action="{{ route('student.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="oldPassword">Mật khẩu cũ</label>
                    <input type="password" id="oldPassword" name="old_password" placeholder="Nhập mật khẩu cũ">
                    <span class="text-danger" id="edit_old_password-error"></span>
                </div>
                <div class="form-group">
                    <label for="newPassword">Mật khẩu mới</label>
                    <input type="password" id="newPassword" name="new_password" placeholder="Nhập mật khẩu mới">
                    <span class="text-danger" id="edit_new_password-error"></span>
                </div>
                <div class="form-group">
                    <label for="newPasswordConfirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" id="newPasswordConfirmation" name="new_password_confirmation" placeholder="Nhập lại mật khẩu mới">
                    <span class="text-danger" id="edit_new_password_confirmation-error"></span>
                </div>
                <button type="submit" class="save-btn">Lưu thay đổi</button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Giữ nguyên CSS từ index.html */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        .profile {
            padding: 100px 20px 40px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .profile-info {
            flex: 1;
            min-width: 300px;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }

        .profile-info h2 {
            font-size: 20px;
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 15px;
        }

        .profile-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .profile-info .edit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #facc15;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
            cursor: pointer;
            margin-top: 10px;
        }

        .profile-info .edit-btn:hover {
            background: #ea580c;
        }

        .profile-history {
            flex: 2;
            min-width: 300px;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-history h2 {
            text-align: center;
            font-size: 20px;
            color: #1e3a8a;
            margin-bottom: 20px;
        }

        .history-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .history-tabs button {
            padding: 10px 20px;
            border: none;
            background: #d1d5db;
            color: #333;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .history-tabs button.active {
            background: #1e3a8a;
            color: #fff;
        }

        .history-tabs button:hover {
            background: #facc15;
            color: #fff;
        }

        .history-content {
            display: none;
        }

        .history-content.active {
            display: block;
        }

        .history-item {
            padding: 15px;
            border-bottom: 1px solid #d1d5db;
            display: none;
        }

        .history-item.active {
            display: block;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-item p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination button {
            padding: 5px 10px;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #333;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .pagination button.active {
            background: #1e3a8a;
            color: #fff;
            border-color: #1e3a8a;
        }

        .pagination button:hover {
            background: #facc15;
            color: #fff;
            border-color: #facc15;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
        }

        .modal-content {
            background: #fff;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-content h2 {
            font-size: 20px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }

        .modal-content .preview-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .modal-content .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .modal-content label {
            display: block;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 5px;
        }

        .modal-content input[type="text"],
        .modal-content input[type="password"],
        .modal-content input[type="email"],
        .modal-content input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .modal-content input[type="text"]:focus,
        .modal-content input[type="password"]:focus,
        .modal-content input[type="email"]:focus,
        .modal-content input[type="date"]:focus {
            border-color: #facc15;
            outline: none;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type="file"] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-wrapper .file-label {
            display: block;
            width: 100%;
            padding: 10px;
            background: #1e3a8a;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .file-input-wrapper .file-label:hover {
            background: #ea580c;
        }

        .file-input-wrapper .file-name {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .modal-content .save-btn {
            width: 100%;
            padding: 10px;
            background: #facc15;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .modal-content .save-btn:hover {
            background: #ea580c;
        }

        .text-danger {
            color: red;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .profile {
                flex-direction: column;
            }

            .modal-content {
                width: 95%;
                max-height: 85vh;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // History Tabs and Pagination
        function showHistory(tab) {
            document.querySelectorAll('.history-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.history-tabs button').forEach(btn => {
                btn.classList.remove('active');
            });

            const content = document.getElementById(tab);
            content.classList.add('active');
            event.target.classList.add('active');
            updatePagination(tab);
        }

        function updatePagination(tab) {
            const items = document.querySelectorAll(`#${tab} .history-item`);
            const itemsPerPage = 3;
            const totalPages = Math.ceil(items.length / itemsPerPage);
            const pagination = document.getElementById(`${tab}-pagination`);
            pagination.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.addEventListener('click', () => showPage(tab, i));
                if (i === 1) btn.classList.add('active');
                pagination.appendChild(btn);
            }

            showPage(tab, 1);
        }

        function showPage(tab, page) {
            const items = document.querySelectorAll(`#${tab} .history-item`);
            const itemsPerPage = 3;
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            items.forEach((item, index) => {
                item.classList.remove('active');
                if (index >= start && index < end) {
                    item.classList.add('active');
                }
            });

            const buttons = document.querySelectorAll(`#${tab}-pagination button`);
            buttons.forEach(btn => btn.classList.remove('active'));
            buttons[page - 1].classList.add('active');
        }

        // Edit Profile Modal
        const editModal = document.getElementById('editModal');
        const avatarInput = document.getElementById('avatar');
        const previewAvatar = document.getElementById('previewAvatar');
        const currentAvatar = document.getElementById('currentAvatar');
        const fileName = document.getElementById('fileName');

        function openEditModal() {
            editModal.style.display = 'flex';
            previewAvatar.src = currentAvatar.src;
            fileName.textContent = 'Chưa chọn ảnh';
            avatarInput.value = ''; // Reset input file
            document.querySelectorAll('#editProfileForm .text-danger').forEach(el => el.textContent = '');

            // Chuyển định dạng ngày sinh
            const dateOfBirth = '{{ $student->date_of_birth }}';
            if (dateOfBirth) {
                const [day, month, year] = dateOfBirth.split('/');
                document.getElementById('date_of_birth').value = `${year}-${month}-${day}`;
            } else {
                document.getElementById('date_of_birth').value = '';
            }

        }

        avatarInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewAvatar.src = e.target.result;
                };
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
            } else {
                fileName.textContent = 'Chưa chọn ảnh';
            }
        });

        $('#editProfileForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        editModal.style.display = 'none';
                        document.querySelector('.profile-info h2').textContent = response.student.full_name;
                        currentAvatar.src = response.student.image === 'default_profile.jpg' 
                            ? '{{ asset('images/profiles/default_profile.jpg') }}' 
                            : `/images/profiles/students/${response.student.image}`;
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            document.getElementById(`edit_${field}-error`).textContent = errors[field][0];
                        }
                    } else {
                        toastr.error((xhr.responseJSON?.message || 'Không xác định'));
                    }
                }
            });
        });

        // Change Password Modal
        const passwordModal = document.getElementById('passwordModal');

        function openPasswordModal() {
            passwordModal.style.display = 'flex';
            document.getElementById('oldPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.querySelectorAll('#changePasswordForm .text-danger').forEach(el => el.textContent = '');
        }

        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        passwordModal.style.display = 'none';
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        // Xóa các thông báo lỗi cũ
                        document.querySelectorAll('#changePasswordForm .text-danger').forEach(el => el.textContent = '');
                        // Hiển thị lỗi mới
                        for (let field in errors) {
                            let errorElement = document.getElementById(`edit_${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                            }
                        }
                    } else {
                        toastr.error((xhr.responseJSON?.message || 'Không xác định'));
                    }
                }
            });
        });

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === editModal) {
                editModal.style.display = 'none';
            }
            if (event.target === passwordModal) {
                passwordModal.style.display = 'none';
            }
        });

        // Khởi tạo phân trang cho tab mặc định
        updatePagination('bookings');
    </script>
@endpush