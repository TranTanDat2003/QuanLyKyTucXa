@extends('layouts.student')

@section('title', 'Thanh Toán - Ký Túc Xá')

@section('content')
    <!-- Payment Section -->
    <section class="payment">
        <div class="invoice-list">
            <div class="filter-tabs">
                <div class="filter-tab active" data-type="room">Tiền phòng</div>
                <div class="filter-tab" data-type="electricity_water">Điện nước</div>
                <div class="filter-tab" data-type="services">Dịch vụ</div>
            </div>

            <!-- Hóa đơn tiền phòng -->
            @foreach ($contracts as $contract)
                <div class="invoice-card room" data-id="R{{ $contract->contract_id }}">
                    <h3>Hóa đơn phòng #R{{ $contract->contract_id }}</h3>
                    <div class="info-row">
                        <p><strong>Học kỳ</strong> {{ $contract->semester->semester_name }}</p>
                        <p><strong>Năm học:</strong> {{ $contract->semester->academic_year }}</p>
                        @if ($contract->is_paid)
                            <div class="due-date"><p><strong>Kỳ hạn:</strong> {{ $contract->semester->start_date->format('d/m/Y') }}</p></div>
                        @endif
                    </div>
                    <div class="info-row">
                        <p><strong>Phải đóng:</strong> {{ number_format($contract->contract_cost, 0, ',', '.') }} VNĐ</p>
                        <p><strong>Đã đóng:</strong> {{ number_format($contract->paid_amount, 0, ',', '.') }} VNĐ</p>
                    </div>
                    <div class="info-row">
                        <p><strong>Loại phòng:</strong> {{ $contract->roomType->room_type_name }} người</p>
                        <p><strong>Tên phòng:</strong> {{ $contract->room ? $contract->room->room_code : 'Chưa phân phòng' }}</p>
                    </div>
                    <div class="info-row">
                        <p><strong>Thời gian ở:</strong> {{ $contract->contract_start_date->format('d/m/Y') }} - {{ $contract->contract_end_date->format('d/m/Y') }}</p>
                        @if ($contract->is_paid)
                            <p><strong>Ngày đóng:</strong> {{ $contract->updated_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    <p class="status {{ $contract->is_paid ? 'paid' : 'unpaid' }}">
                        {{ $contract->is_paid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        @if ($contract->status === 'Chờ duyệt')
                            <span class="pending"> (Chờ duyệt)</span>
                        @elseif ($contract->status === 'Đã hủy')
                            <span class="canceled"> (Đã hủy)</span>
                        @endif
                    </p>
                    @if (!$contract->is_paid && $contract->status !== 'Chờ duyệt' && $contract->status !== 'Đã hủy')
                        <a href="#" class="pay-btn" 
                            data-id="{{ $contract->contract_id }}" 
                            data-type="contract" 
                            data-amount="{{ $contract->contract_cost - $contract->paid_amount }}">
                                Thanh toán ngay
                        </a>
                    @endif
                </div>
            @endforeach

            <!-- Hóa đơn điện nước -->
            @foreach ($utilityBills as $bill)
                <div class="invoice-card electricity_water" data-id="E{{ $bill->utility_bill_id }}">
                    <h3>Hóa đơn điện nước #E{{ $bill->utility_bill_id }}</h3>
                    <div class="due-date"><p><strong>Kỳ hạn:</strong> {{ $bill->utility->month->format('m/Y') }}</p></div>
                    <div class="info-row">
                        <p><strong>Phải đóng:</strong> {{ number_format($bill->share_amount, 0, ',', '.') }} VNĐ</p>
                        <p><strong>Đã đóng:</strong> {{ number_format($bill->amount_paid, 0, ',', '.') }} VNĐ</p>
                    </div>
                    <div class="info-row">
                        <p><strong>Lượng điện sử dụng:</strong> {{ number_format($bill->utility->electricity_usage, 0, ',', '.') }} VNĐ/kWh</p>
                        <p><strong>Lượng nước sử dụng:</strong> {{ number_format($bill->utility->water_usage, 0, ',', '.') }} VNĐ/m³</p>
                    </div>
                    <div class="info-row">
                        <p><strong>Phòng:</strong> {{ $bill->utility->room->room_code }}</p>
                        <p><strong>Học kỳ:</strong> {{ $bill->contract->semester->semester_name }}</p>
                        @if ($bill->is_paid)
                            <p><strong>Ngày đóng:</strong> {{ $bill->paid_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    <p class="status {{ $bill->is_paid ? 'paid' : 'unpaid' }}">{{ $bill->is_paid ? 'Đã thanh toán' : 'Chưa thanh toán' }}</p>
                    @if (!$bill->is_paid)
                        <a href="#" 
                            class="pay-btn" 
                            data-id="{{ $bill->utility_bill_id }}" 
                            data-type="utility" 
                            data-amount="{{ $bill->share_amount - $bill->amount_paid }}">
                                Thanh toán ngay
                        </a>
                    @endif
                </div>
            @endforeach

            <!-- Hóa đơn dịch vụ -->
            @foreach ($serviceBills as $bill)
                <div class="invoice-card services" data-id="S{{ $bill->service_bill_id }}">
                    <h3 onclick="toggleDetails('S{{ $bill->service_bill_id }}')">Hóa đơn dịch vụ #S{{ $bill->service_bill_id }}</h3>
                    <div class="info-row">
                        <p><strong>Kỳ hạn:</strong> {{ $bill->issued_date->format('m/Y') }}</p>
                        @if ($bill->status === 'paid')
                            <p><strong>Đã đóng:</strong> {{ number_format($bill->amount_paid, 0, ',', '.') }} VNĐ</p>
                            <p><strong>Ngày đóng:</strong> {{ $bill->updated_at->format('d/m/Y') }}</p>
                        @else
                            <p><strong>Phải đóng:</strong> {{ number_format($bill->total_amount - $bill->amount_paid, 0, ',', '.') }} VNĐ</p>
                        @endif
                    </div>
                    <div class="invoice-details" id="details-S{{ $bill->service_bill_id }}">
                        <ul>
                            @foreach ($bill->items as $item)
                                <li>{{ $item->service->service_name }}: {{ number_format($item->total_amount, 0, ',', '.') }} VNĐ</li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="status {{ $bill->status === 'paid' ? 'paid' : 'unpaid' }}">{{ $bill->status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</p>
                    @if ($bill->status !== 'paid')
                        <a href="#" 
                            class="pay-btn" 
                            data-id="{{ $bill->service_bill_id }}" 
                            data-type="service" 
                            data-amount="{{ $bill->total_amount - $bill->amount_paid }}">
                                Thanh toán ngay
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="payment-methods">
            <h2>Phương thức thanh toán</h2>
            <div class="method" data-method="VNPAY">
                <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-VNPAY-QR-1.png" alt="VNPAY">
                <span>VNPAY</span>
            </div>
            <div class="method" data-method="MOMO">
                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="MoMo">
                <span>MoMo</span>
            </div>
            <div class="method" data-method="ZALOPAY">
                <img src="{{ asset('images/logo/zalopay.png') }}" alt="ZaloPay">
                <span>ZaloPay</span>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <i class="fas fa-check-circle"></i>
            <h2>Thanh toán thành công!</h2>
            <p>Hóa đơn của bạn đã được thanh toán qua <span id="paymentMethod"></span>.</p>
            <button class="close-btn" onclick="closePaymentModal()">Đóng</button>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pay.css') }}">
@endpush

@push('scripts')
    <script>
        // Filter Invoices
        const tabs = document.querySelectorAll('.filter-tab');
        const invoices = document.querySelectorAll('.invoice-card');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const type = this.getAttribute('data-type');
                invoices.forEach(invoice => {
                    invoice.classList.remove('active');
                    if (invoice.classList.contains(type)) {
                        invoice.classList.add('active');
                    }
                });
            });
        });

        // Toggle Service Details
        function toggleDetails(invoiceId) {
            const details = document.getElementById(`details-${invoiceId}`);
            details.classList.toggle('active');
        }

        // Payment Handling
        let selectedMethod = '';
        const methods = document.querySelectorAll('.method');
        const modal = document.getElementById('paymentModal');
        const paymentMethodSpan = document.getElementById('paymentMethod');

        methods.forEach(method => {
            method.addEventListener('click', function() {
                selectedMethod = this.getAttribute('data-method');
                methods.forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        document.querySelectorAll('.pay-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!selectedMethod) {
                    alert('Vui lòng chọn phương thức thanh toán!');
                    return;
                }

                const id = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
                const amount = this.getAttribute('data-amount');
                let url = '';

                // Tạo URL động dựa trên type
                if (type === 'contract') {
                    url = `/contracts/${id}/payment`;
                } else if (type === 'utility') {
                    url = `/utility-bills/${id}/payment`;
                } else if (type === 'service') {
                    url = `/service-bills/${id}/payment`;
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: { 
                        payment_method: selectedMethod, 
                        amount: amount,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        if (response.success && response.redirect) {
                            window.location.href = response.redirect;
                        } else if (response.success && !response.redirect) {
                            paymentMethodSpan.textContent = selectedMethod;
                            modal.style.display = 'flex';

                            const invoiceCard = document.querySelector(`.invoice-card[data-id="${type === 'contract' ? 'R' : type === 'utility' ? 'E' : 'S'}${id}"]`);
                            const status = invoiceCard.querySelector('.status');
                            status.textContent = 'Đã thanh toán';
                            status.classList.remove('unpaid');
                            status.classList.add('paid');
                            invoiceCard.querySelector('.pay-btn').style.display = 'none';

                            const infoRow = invoiceCard.querySelector('.info-row');
                            const today = new Date().toLocaleDateString('vi-VN');
                            infoRow.innerHTML += `<p><strong>Ngày đóng:</strong> ${today}</p>`;
                        } else {
                            alert('Thanh toán không thành công: ' + (response.message || 'Lỗi không xác định'));
                        }
                    },
                    error: function(xhr) {
                        alert('Lỗi: ' + (xhr.responseJSON?.message || 'Không xác định'));
                    }
                });
            });
        });

        function closePaymentModal() {
            modal.style.display = 'none';
            selectedMethod = '';
            methods.forEach(m => m.classList.remove('selected'));
        }

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closePaymentModal();
            }
        });

        // Hiển thị mặc định danh sách tiền phòng
        document.querySelector('.filter-tab[data-type="room"]').click();
    </script>
@endpush