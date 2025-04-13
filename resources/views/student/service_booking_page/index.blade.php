@extends('layouts.student')

@section('title', 'Đăng Ký Dịch Vụ - Ký Túc Xá')

@section('content')
    <!-- Service Booking Section -->
    <section class="service-booking">
        <div class="semester-info">
            <h2>Thông tin đăng ký dịch vụ</h2>
            @if ($semester)
                <p><strong>Học kỳ:</strong> {{ $semester->semester_name }}</p>
                <p><strong>Năm học:</strong> {{ $semester->academic_year }}</p>
                <p><strong>Thời gian đăng ký:</strong> {{ $registrationStart->format('d/m/Y') }} - {{ $registrationEnd->format('d/m/Y') }}</p>
                <p><strong>Thời gian sử dụng:</strong> {{ $semester->start_date->format('d/m/Y') }} - {{ $semester->end_date->format('d/m/Y') }}</p>
            @else
                <p>Không có học kỳ nào hiện tại hoặc sắp tới.</p>
            @endif
        </div>

        <div class="service-list" id="service-list">
            <!-- Danh sách dịch vụ sẽ được load bằng AJAX -->
        </div>
    </section>
@endsection

@push('styles')
    <style>
        main {
            min-height: 522px;
        }
        /* Service Booking Section */
        .service-booking {
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

        .service-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .service-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .service-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .service-info {
            padding: 15px;
        }

        .service-info h3 {
            font-size: 18px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }

        .service-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .service-info .input-group {
            margin-bottom: 15px;
        }

        .service-info .input-group label {
            display: block;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 5px;
        }

        .service-info .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .service-info .input-group input:focus {
            border-color: #facc15;
            outline: none;
        }

        .service-actions {
            display: flex;
            justify-content: center;
        }

        .service-actions .book-now {
            padding: 10px 20px;
            background: #16a34a;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
            cursor: pointer;
        }

        .service-actions .book-now:hover {
            background: #15803d;
        }

        .service-actions .book-now.disabled {
            background: #d1d5db;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Mobile Optimization */
        @media (max-width: 768px) {
            .service-card img {
                height: 150px;
            }
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

            loadServiceList();

            function loadServiceList() {
                $.ajax({
                    url: '{{ route("student.services.filter") }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.success && response.services.length > 0) {
                            let servicesHtml = '';
                            let isRegistrationOpen = {{ $isRegistrationOpen ? 'true' : 'false' }};

                            response.services.forEach(function(service) {
                                servicesHtml += `
                                    <div class="service-card" data-service="${service.service_id}">
                                        <img src="${service.service_img_path ? '{{ asset("images/services") }}/' + service.service_img_path : '{{ asset("images/profiles/default_profile.jpg") }}'}" alt="${service.service_name}">
                                        <div class="service-info">
                                            <h3>${service.service_name}</h3>
                                            <p><strong>Giá:</strong> ${service.price.toLocaleString('vi-VN', { maximumFractionDigits: 0 })} VNĐ/tháng</p>
                                            <p><strong>Mô tả:</strong> ${service.service_description || 'Không có mô tả'}</p>
                                            ${service.service_name.toLowerCase().includes('xe máy') ? `
                                                <div class="input-group">
                                                    <label for="bike-plate-${service.service_id}">Biển số xe</label>
                                                    <input type="text" id="bike-plate-${service.service_id}" name="bike_plate" placeholder="VD: 65B152172">
                                                    <span class="text-danger" id="bike-plate-error-${service.service_id}"></span>
                                                </div>
                                            ` : ''}
                                            <div class="service-actions">
                                                ${isRegistrationOpen && service.is_active ?
                                                    `<a href="#" class="book-now" data-service-id="${service.service_id}">Đặt ngay</a>` :
                                                    `<a href="#" class="book-now disabled">Đặt ngay</a>`}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            $('#service-list').html(servicesHtml);
                        } else {
                            $('#service-list').html('<p>Không có dịch vụ nào khả dụng.</p>');
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        alert('Có lỗi xảy ra khi tải danh sách dịch vụ.');
                    }
                });
            }

            // Book service
            $(document).on('click', '.book-now:not(.disabled)', function(e) {
                e.preventDefault();
                let serviceId = $(this).data('service-id');
                let bikePlate = $(`#bike-plate-${serviceId}`).val() || null;

                // Reset lỗi trước khi gửi request
                $(`#bike-plate-error-${serviceId}`).text('');

                $.ajax({
                    url: '{{ route("service_bills.store") }}',
                    method: 'POST',
                    data: {
                        service_id: serviceId,
                        student_id: '{{ auth()->user()->student->student_id ?? '' }}',
                        semester_id: '{{ $semester ? $semester->semester_id : '' }}',
                        bike_plate: bikePlate
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            loadServiceList();
                        } else {
                            alert('Đăng ký thất bại: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.bike_plate) {
                                $(`#bike-plate-error-${serviceId}`).text(errors.bike_plate[0]);
                            }
                        } else {
                            alert('Có lỗi xảy ra: ' + (xhr.responseJSON?.message || 'Không xác định'));
                        }
                    }
                });
            });
        });
    </script>
@endpush