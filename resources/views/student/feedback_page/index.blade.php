@extends('layouts.student')

@section('title', 'Sửa Chữa Cơ Sở Vật Chất - Ký Túc Xá')

@section('content')
    <!-- Repair Section -->
    <section class="repair-section">
        <div class="repair-form">
            <h2>Gửi yêu cầu sửa chữa</h2>
            @if ($currentRoom)
                <p><strong>Phòng hiện tại:</strong> {{ $currentRoom->room->room_code }}</p>
                <form id="repairForm" method="POST" action="{{ route('feedbacks.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $currentRoom->room_id }}">
                    <input type="hidden" name="student_id" value="{{ auth()->user()->student->student_id }}">
                    <div class="form-group">
                        <label for="reason">Lý do</label>
                        <textarea id="reason" name="content" placeholder="Mô tả vấn đề cần sửa chữa" required>{{ old('content') }}</textarea>
                        @error('content')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Ảnh minh họa</label>
                        <div class="file-upload">
                            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <label for="image">Chọn ảnh</label>
                            <span id="file-name">Chưa chọn ảnh</span>
                        </div>
                        <img id="image-preview" class="image-preview" alt="Ảnh xem trước" style="display: none;">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="quantity">Số lượng</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="255" value="{{ old('quantity', 1) }}" required>
                        @error('quantity')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="submit-btn">Gửi yêu cầu</button>
                </form>
            @else
                <p class="text-danger">Bạn hiện không ở ký túc xá, không thể gửi yêu cầu sửa chữa.</p>
            @endif
        </div>

        <div class="repair-list">
            <h2>Danh sách yêu cầu sửa chữa</h2>
            <div id="repair-items">
                <!-- Danh sách sẽ được load bằng AJAX -->
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Kế thừa CSS từ index.html */
        .repair-section {
            padding: 100px 20px 40px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .repair-form {
            flex: 1;
            min-width: 300px;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .repair-form h2 {
            font-size: 20px;
            color: #1e3a8a;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 5px;
        }

        .form-group textarea,
        .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #facc15;
            outline: none;
        }

        .form-group .text-danger {
            font-size: 12px;
            margin-top: 5px;
        }

        .form-group .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .form-group .file-upload input[type="file"] {
            display: none;
        }

        .form-group .file-upload label {
            display: block;
            padding: 10px;
            background: #1e3a8a;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-group .file-upload label:hover {
            background: #152c6e;
        }

        .form-group .file-upload span {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }

        .form-group .image-preview {
            margin-top: 10px;
            max-width: 100%;
            border-radius: 5px;
            display: none;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #15803d;
        }

        .repair-list {
            flex: 2;
            min-width: 300px;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .repair-list h2 {
            font-size: 20px;
            color: #1e3a8a;
            margin-bottom: 20px;
        }

        .repair-item {
            padding: 15px;
            border-bottom: 1px solid #d1d5db;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .repair-item:last-child {
            border-bottom: none;
        }

        .repair-item p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .repair-item .status {
            font-weight: 600;
        }

        .repair-item .status.pending {
            color: #facc15;
        }

        .repair-item .status.approved {
            color: #16a34a;
        }

        .repair-item .status.rejected {
            color: #dc2626;
        }

        @media (max-width: 768px) {
            .repair-section {
                flex-direction: column;
            }

            .repair-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');
            const fileName = document.getElementById('file-name');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
            } else {
                preview.style.display = 'none';
                fileName.textContent = 'Chưa chọn ảnh';
            }
        }

        function loadFeedbacks() {
            $.get('/facility-repair', function(response) {
                if (response.success) {
                    let feedbacks = response.feedbacks;
                    let html = feedbacks.map(f => `
                        <div class="repair-item">
                            <div>
                                <p><strong>#${f.feedback_id}</strong> - ${f.content}</p>
                                <p><strong>Số lượng:</strong> ${f.quantity}</p>
                                <p><strong>Ngày gửi:</strong> ${new Date(f.created_at).toLocaleDateString('vi-VN')}</p>
                                ${f.status === 'approved' && f.scheduled_fix_date ? 
                                    `<p><strong>Ngày hẹn sửa:</strong> ${new Date(f.scheduled_fix_date).toLocaleDateString('vi-VN')}</p>` : ''}
                            </div>
                            <p class="status ${f.status}">${f.status === 'pending' ? 'Chờ duyệt' : (f.status === 'approved' ? 'Đã duyệt' : 'Từ chối')}</p>
                        </div>
                    `).join('');
                    $('#repair-items').html(html || '<p>Chưa có yêu cầu sửa chữa nào.</p>');
                }
            }).fail(function() {
                $('#repair-items').html('<p>Có lỗi xảy ra khi tải danh sách feedback.</p>');
            });
        }

        $(document).ready(function() {
            loadFeedbacks(); // Load danh sách khi trang sẵn sàng

            $('#repairForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            loadFeedbacks();
                            $('#repairForm')[0].reset();
                            $('#image-preview').hide();
                            $('#file-name').text('Chưa chọn ảnh');
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error((xhr.responseJSON?.message || 'Không xác định'));
                    }
                });
            });
        });
    </script>
@endpush