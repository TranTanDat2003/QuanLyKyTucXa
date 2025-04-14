@extends('layouts.student')

@section('title', 'Đăng Ký Phòng - Ký Túc Xá')

@section('content')
    <!-- Room Booking Section -->
    <section class="room-booking">
        <div class="semester-info">
            <h2>Thông tin đăng ký</h2>
            @if ($semester)
                <div class="row-css">
                    <p><strong>Học kỳ:</strong> {{ $semester->semester_name }}</p>
                    <p><strong>Năm học:</strong> {{ $semester->academic_year }}</p>
                </div>
                <div class="row-css">
                    <p><strong>Thời gian đăng ký:</strong> {{ $registrationStart->format('d/m/Y') }} - {{ $registrationEnd->format('d/m/Y') }}</p>
                    <p><strong>Thời gian ở:</strong> {{ $semester->start_date->format('d/m/Y') }} - {{ $semester->end_date->format('d/m/Y') }}</p>
                </div>
            @else
                <p>Không có học kỳ nào hiện tại hoặc sắp tới.</p>
            @endif
        </div>

        <div class="filters">
            <div class="filter-group">
                <label for="room-type">Loại phòng</label>
                <select id="room-type" name="capacity">
                    <option value="">Tất cả</option>
                    <option value="2">2 người</option>
                    <option value="4">4 người</option>
                    <option value="6">6 người</option>
                    <option value="8">8 người</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="amenities">Tiện ích</label>
                <select id="amenities" name="amenities">
                    <option value="">Tất cả</option>
                    <option value="ac">Máy lạnh</option>
                    <option value="cooking">Cho phép nấu ăn</option>
                    <option value="both">Máy lạnh + Nấu ăn</option>
                </select>
            </div>
        </div>

        <div class="room-list" id="room-list">
            <!-- Danh sách room_types sẽ được load bằng AJAX -->
        </div>
    </section>

    <!-- Modal -->
    <div class="modal" id="roomModal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">×</span>
            <div id="modal-details"></div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Room Booking Section */
        .room-booking {
            padding: 100px 20px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .semester-info {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .semester-info h2 {
            font-size: 20px;
            color: #1e3a8a;
            margin-bottom: 15px;
        }

        .semester-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .filters {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #1e3a8a;
        }

        .filter-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .filter-group select:focus {
            border-color: #facc15;
            outline: none;
        }

        .room-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .room-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .room-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .room-info {
            padding: 15px;
        }

        .room-info h3 {
            font-size: 18px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }

        .room-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .room-status {
            font-weight: 600;
            margin: 10px 0;
        }

        .room-status.available {
            color: #16a34a;
        }

        .room-status.booked {
            color: #dc2626;
        }

        .room-actions {
            display: flex;
            gap: 10px;
        }

        .room-actions a {
            flex: 1;
            text-align: center;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
            cursor: pointer;
        }

        .room-actions .book-now {
            background: #facc15;
            color: #fff;
        }

        .room-actions .book-now:hover {
            background: #ea580c;
        }

        .room-actions .details {
            background: #1e3a8a;
            color: #fff;
        }

        .room-actions .details:hover {
            background: #152c6e;
        }

        /* Modal */
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
        }

        .modal-content {
            background: #fff;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 20px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-content .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #333;
            cursor: pointer;
        }

        .modal-content img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .modal-content h2 {
            font-size: 24px;
            color: #1e3a8a;
            margin-bottom: 15px;
        }

        .modal-content p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .modal-content .amenities {
            margin: 15px 0;
        }

        .modal-content .amenities span {
            display: inline-block;
            background: #1e3a8a;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .book-now.disabled {
            background: #d1d5db;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadRoomList();

            $('#room-type, #amenities').on('change', function() {
                loadRoomList();
            });

            function loadRoomList() {
                let amenities = $('#amenities').val();
                let capacity = $('#room-type').val();

                $.ajax({
                    url: '{{ route("student.rooms.filter") }}',
                    method: 'GET',
                    data: {
                        amenities: amenities,
                        capacity: capacity,
                    },
                    success: function(response) {
                        if (response.success && response.roomTypes.length > 0) {
                            let roomsHtml = '';
                            let isRegistrationOpen = {{ $isRegistrationOpen ? 'true' : 'false' }};

                            response.roomTypes.forEach(function(roomType) {
                                let status = roomType.has_available ? 'available' : 'booked';
                                let statusText = roomType.available_slots > 0 ? 'Phòng trống' : 'Phòng đã đủ người';

                                roomsHtml += `
                                    <div class="room-card" data-room="${roomType.room_type_id}">
                                        <img src="${roomType.room_type_img_path ? '{{ asset("images/room_types") }}/' + roomType.room_type_img_path : '{{ asset("images/profiles/default_profile.jpg") }}'}" alt="${roomType.room_type_name}">
                                        <div class="room-info">
                                            <h3>${roomType.room_type_name}</h3>
                                            <p>Loại: ${roomType.capacity} người</p>
                                            <p>Giá: ${roomType.room_type_price.toLocaleString('vi-VN', { maximumFractionDigits: 0 })} VNĐ/tháng</p>
                                            <p class="room-status ${status}">${statusText}</p>
                                            <div class="room-actions">
                                                ${isRegistrationOpen && roomType.has_available ?
                                                    `<a href="#" class="book-now" data-room-type-id="${roomType.room_type_id}">Đặt ngay</a>` : 
                                                    `<a href="#" class="book-now disabled">Đặt ngay</a>`}
                                                <a href="#" class="details" onclick="event.preventDefault(); openModal(${roomType.room_type_id})">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            $('#room-list').html(roomsHtml);
                        } else {
                            $('#room-list').html('<p>Không có loại phòng phù hợp.</p>');
                            toastr.warning(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        toastr.error('Có lỗi xảy ra khi tải danh sách phòng.');
                    }
                });
            }

            // Book room
            $(document).on('click', '.book-now:not(.disabled)', function(e) {
                e.preventDefault();
                let roomTypeId = $(this).data('room-type-id');

                $.ajax({
                    url: '{{ route("contracts.store") }}',
                    method: 'POST',
                    data: {
                        room_type_id: roomTypeId,
                        semester_id: '{{ $semester ? $semester->semester_id : '' }}',
                        student_id: '{{ auth()->user()->student->student_id ?? '' }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Đăng ký phòng thành công!');
                            loadRoomList();
                        } else {
                            toastr.error('Đăng ký thất bại: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error((xhr.responseJSON?.message || 'Không xác định'));
                    }
                });
            });
        });

        // Modal Functions
        const modal = document.getElementById('roomModal');
        const modalDetails = document.getElementById('modal-details');

        function openModal(roomTypeId) {
            $.ajax({
                url: `/room-booking/${roomTypeId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let roomType = response.roomTypes;
                        let content = `
                            <img src="${roomType.room_type_img_path ? '{{ asset("images/room_types") }}/' + roomType.room_type_img_path : '{{ asset("images/profiles/default_profile.jpg") }}'}" alt="${roomType.room_type_name}">
                            <h2>${roomType.room_type_name}</h2>
                            <p><strong>Loại:</strong> ${roomType.capacity} người</p>
                            <p><strong>Giá:</strong> ${roomType.room_type_price.toLocaleString('vi-VN', { maximumFractionDigits: 0 })} VNĐ/tháng</p>
                            <p><strong>Mô tả:</strong> Phòng ${roomType.has_air_conditioner ? 'có máy lạnh' : 'không có máy lạnh'}, ${roomType.allow_cooking ? 'cho phép nấu ăn' : 'không cho phép nấu ăn'}.</p>
                            <div class="amenities">
                                ${roomType.has_air_conditioner ? '<span>Máy lạnh</span>' : ''}
                                ${roomType.allow_cooking ? '<span>Cho phép nấu ăn</span>' : ''}
                            </div>
                        `;
                        modalDetails.innerHTML = content;
                        modal.style.display = 'flex';
                    } else {
                        modalDetails.innerHTML = '<p>Không có dữ liệu chi tiết.</p>';
                        modal.style.display = 'flex';
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    modalDetails.innerHTML = '<p>Có lỗi khi tải chi tiết phòng.</p>';
                    modal.style.display = 'flex';
                }
            });
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
@endpush