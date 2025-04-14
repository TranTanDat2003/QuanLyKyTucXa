$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Khởi tạo DataTables với AJAX
    let table = $('#dataTable').DataTable({
        ajax: {
            url: contractsIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.contracts;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'student.student_code' },
            { data: 'student.full_name' },
            { 
                data: 'room.room_code',
                render: function(data, type, row) {
                    return row.room ? row.room.room_code : 'Chưa phân phòng';
                }
            },
            { data: 'semester.semester_name' },
            { 
                data: 'status',
                render: function(data) {
                    if (data === 'Chờ duyệt') return '<span class="badge badge-secondary">Chờ duyệt</span>';
                    if (data === 'Đã duyệt') return '<span class="badge badge-info">Đã duyệt</span>';
                    if (data === 'Đang ở') return '<span class="badge badge-success">Đang ở</span>';
                    if (data === 'Hết hạn') return '<span class="badge badge-warning">Hết hạn</span>';
                    if (data === 'Hủy') return '<span class="badge badge-danger">Hủy</span>';
                    return data;
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    let buttons = `
                        <button class="btn btn-sm btn-info view-contract" data-id="${row.contract_id}" data-toggle="modal" data-target="#viewContractModal">
                            <i class="fas fa-eye"></i>
                        </button>
                    `;
                    if (row.status === 'Chờ duyệt') {
                        buttons += `
                            <button class="btn btn-sm btn-success approve-contract" data-id="${row.contract_id}" data-student="${row.student.full_name}">
                                Duyệt
                            </button>
                            <button class="btn btn-sm btn-danger cancel-contract" data-id="${row.contract_id}" data-student="${row.student.full_name}">
                                Huỷ
                            </button>
                        `;
                    }
                    if (row.status === 'Đang ở') {
                        buttons += `
                            <button class="btn btn-sm btn-warning checkout-contract" data-id="${row.contract_id}" data-student="${row.student.full_name}">
                                Trả phòng
                            </button>
                        `;
                    }
                    return buttons;
                }
            }
        ]
    });

    function handleValidationErrors(formId, errors) {
        $(`#${formId} .text-danger`).text('');
        $(`#${formId} :input`).each(function() {
            let field = $(this).attr('name');
            if (field && errors[field]) {
                $(`#${formId}_${field}-error`).text(errors[field][0]);
                if (field !== 'amount') $(this).val('');
            } else {
                $(`#${formId}_${field}-error`).text('');
            }
        });
    }

    // Reset modal thêm hợp đồng
    $('#addContractModal').on('show.bs.modal', function() {
        $('#addContractForm')[0].reset();
        $('#addContractModal .text-danger').text('');
    });

    // Thêm hợp đồng
    $('#addContractForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#addContractModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addContractForm', xhr.responseJSON.errors);
                } else {
                    toastr.error(xhr.responseJSON.message);
                }
            }
        });
    });

    // Duyệt hợp đồng
    $(document).on('click', '.approve-contract', function() {
        let id = $(this).data('id');
        let studentName = $(this).data('student');
        $('#approve_student_name').text(studentName);
        $('#approveContractModal').modal('show');

        $('#confirmApproveButton').off('click').on('click', function() {
            $.ajax({
                url: `${contractsBaseUrl}/${id}/approve`,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#approveContractModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                }
            });
        });
    });

    // Hủy hợp đồng
    $(document).on('click', '.cancel-contract', function() {
        let id = $(this).data('id');
        let studentName = $(this).data('student');
        $('#cancel_student_name').text(studentName);
        $('#cancelContractModal').modal('show');

        $('#confirmCancelButton').off('click').on('click', function() {
            $.ajax({
                url: `${contractsBaseUrl}/${id}/cancel`,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#cancelContractModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                }
            });
        });
    });

    // Trả phòng
    $(document).on('click', '.checkout-contract', function() {
        let id = $(this).data('id');
        let studentName = $(this).data('student');
        $('#checkout_student_name').text(studentName);
        $('#checkoutContractModal').modal('show');

        $('#confirmCheckoutButton').off('click').on('click', function() {
            $.ajax({
                url: `${contractsBaseUrl}/${id}/checkout`,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#checkoutContractModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                    console.error(xhr.responseJSON.message);
                }
            });
        });
    });

    // Xem chi tiết hợp đồng
    $(document).on('click', '.view-contract', function() {
        let id = $(this).data('id');
        $.ajax({
            url: `${contractsBaseUrl}/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let contract = response.contract;
                    $('#view_student_code').text(contract.student.student_code || '');
                    $('#view_student_name').text(contract.student.full_name || '');
                    $('#view_room_code').text(contract.room ? contract.room.room_code : 'Chưa phân phòng');
                    $('#view_semester_name').text(contract.semester.semester_name || '');
                    $('#view_status').text(contract.status || '');
                    $('#view_contract_start_date').text(moment(contract.contract_start_date).format('DD-MM-YYYY') || '');
                    $('#view_contract_end_date').text(moment(contract.contract_end_date).format('DD-MM-YYYY') || '');
                    $('#view_contract_cost').text($.fn.dataTable.render.number(',', '.', 0).display(contract.contract_cost ? contract.contract_cost : 0) + ' VND');
                    $('#view_paid_amount').text($.fn.dataTable.render.number(',', '.', 0).display(contract.paid_amount ? contract.paid_amount : 0) + ' VND');
                    $('#viewContractModal').modal('show');
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message);
            }
        });
    });
});