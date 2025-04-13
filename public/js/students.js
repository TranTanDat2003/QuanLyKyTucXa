$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Khởi tạo DataTables với AJAX
    let table = $('#dataTable').DataTable({
        ajax: {
            url: studentsIndexUrl, // Được truyền từ Blade
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.students;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'student_code' },
            { data: 'full_name' },
            { data: 'date_of_birth' },
            { 
                data: 'gender',
                render: data => data === 0 ? 'Nam' : 'Nữ'
            },
            { data: 'email' },
            { 
                data: 'status',
                render: data => data === 1 ? '<span class="badge badge-status badge-success">Hoạt động</span>' : '<span class="badge badge-status badge-error">Khóa</span>'
            },
            { 
                data: null, 
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-info view-student" data-id="${row.student_id}" data-toggle="modal" data-target="#viewStudentModal">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary edit-student" 
                            data-id="${row.student_id}" 
                            data-name="${row.full_name}" 
                            data-dob="${row.date_of_birth}" 
                            data-gender="${row.gender}" 
                            data-phone="${row.phone || ''}" 
                            data-address="${row.address || ''}" 
                            data-email="${row.email}" 
                            data-major="${row.major || ''}" 
                            data-class="${row.class || ''}"
                            data-enrollment_year="${row.enrollment_year}"
                            data-img="${row.image}"
                            data-toggle="modal" 
                            data-target="#editStudentModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${studentsBaseUrl}/${row.student_id}" method="POST" style="display: inline;" class="delete-student-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-student" data-id="${row.student_id}" data-name="${row.full_name}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    `;
                }
            }
        ]
    });

    function handleValidationErrors(formId, errors) {
        $(`#${formId} .text-danger`).text('');
        $(`#${formId} :input`).each(function() {
            let field = $(this).attr('name');
            if (field && errors[field]) {
                $(`#${formId === 'addStudentForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                $(this).val('');
                if (field === 'image') {
                    $(this).closest('.form-group').find('.preview_img').attr('src', defaultProfileImageUrl);
                }
            } else {
                $(`#${formId === 'addStudentForm' ? 'add' : 'edit'}_${field}-error`).text('');
            }
        });
    }

    $('.file_upload_input').on('change', function() {
        let file = this.files[0];
        if (file) {
            let url = URL.createObjectURL(file);
            $(this).closest('.form-group').find('.preview_img').attr('src', url);
        }
    });

    $('#addStudentModal').on('show.bs.modal', function() {
        $('#addStudentForm')[0].reset();
        $('#addStudentModal .text-danger').text('');
        $('#addStudentModal .preview_img').attr('src', defaultProfileImageUrl);
    });

    $('#addStudentForm').on('submit', function(e) {
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
                    $('#addStudentModal').modal('hide');
                    table.ajax.reload();
                    alert(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addStudentForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                    console.log(xhr.responseJSON);
                }
            }
        });
    });

    $('#dataTable').on('click', '.edit-student', function() {
        let button = $(this);

        // Reset input image
        $('#edit_image').val('');

        // Điền dữ liệu vào form
        $('#edit_student_id').val(button.data('id'));
        $('#edit_full_name').val(button.data('name'));
        $('#edit_date_of_birth').val(button.data('dob'));
        $('#edit_gender').val(button.data('gender'));
        $('#edit_phone').val(button.data('phone'));
        $('#edit_address').val(button.data('address'));
        $('#edit_email').val(button.data('email'));
        $('#edit_major').val(button.data('major'));
        $('#edit_class').val(button.data('class'));
        $('#edit_enrollment_year').val(button.data('enrollment_year'));
        $('#editStudentForm .preview_img').attr('src', button.data('img') ? `${studentsImageBaseUrl}/${button.data('img')}` : defaultProfileImageUrl);
        $('#editStudentForm #image_old').val(button.data('img'));
        $('#editStudentForm').attr('action', `${studentsBaseUrl}/${button.data('id')}`);
        
        // Xóa lỗi cũ khi mở modal
        $('#editStudentModal .text-danger').text('');
    });

    $('#editStudentForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
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
                    $('#editStudentModal').modal('hide');
                    $('#editStudentForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                }
            },
            error: function(xhr) {
                console.log('XHR Response:', xhr.responseJSON);
                if (xhr.status === 422) {
                    handleValidationErrors('editStudentForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-student', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa sinh viên ${name}?`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${studentsBaseUrl}/${id}`,
                method: 'POST',
                data: { '_method': 'DELETE' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#deleteConfirmModal').modal('hide');
                        table.ajax.reload();
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                }
            });
        });
    });

    let serviceBillsTable = $('#serviceBillsTable').DataTable({
        destroy: true,
        columns: [
            { data: 'service_bill_id' },
            { data: 'total_amount' },
            { data: 'amount_paid' },
            { data: 'issued_date' },
            { data: 'due_date' },
            { 
                data: 'status',
                render: data => data === 'paid' ? '<span class="badge badge-success">Đã thanh toán</span>' : '<span class="badge badge-warning">Chưa thanh toán</span>'
            },
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-info view-bill-items" data-id="${row.service_bill_id}" data-toggle="modal" data-target="#viewServiceBillItemsModal">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </button>
                    `;
                }
            }
        ],
    });

    let serviceBillItemsTable = $('#serviceBillItemsTable').DataTable({
        destroy: true,
        columns: [
            { data: 'service_name' },
            { data: 'service_price' },
            { data: 'total_amount' },
            { data: 'start_date' },
            { data: 'end_date' }
        ],
    });

    $('#dataTable').on('click', '.view-student', function() {
        let id = $(this).data('id');

        $.ajax({
            url: `${studentsBaseUrl}/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Response từ server:', response);
                if (response.success) {
                    let student = response.students || {};
                    $('#view_student_code').text(student.student_code || '');
                    $('#view_full_name').text(student.full_name || '');
                    $('#view_date_of_birth').text(student.date_of_birth || '');
                    $('#view_gender').text(student.gender ? 'Nữ' : 'Nam' || '');         
                    $('#view_phone').text(student.phone || '');
                    $('#view_address').text(student.address || '');
                    $('#view_email').text(student.email || '');
                    $('#view_major').text(student.major || '');
                    $('#view_class').text(student.class || '');
                    $('#view_status').text(student.status ? 'Hoạt động' : 'Khóa' || '').addClass(student.status === 1 ? 'active' : 'locked');                    
                    $('#view_image').attr('src', student.image 
                        ? (student.image === "default_profile.jpg" ? defaultProfileImageUrl : `${studentsImageBaseUrl}/${student.image}`) 
                        : defaultProfileImageUrl);

                    serviceBillsTable.clear();
                    serviceBillsTable.rows.add(response.service_bills || []);
                    serviceBillsTable.draw();

                    $('#viewStudentModal').modal('show');
                }
            },
            error: function(xhr) {
                console.log('Lỗi AJAX:', xhr.status, xhr.responseJSON);
                alert('Lỗi khi tải dữ liệu: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Không có phản hồi từ server'));
            }
        });
    });

    $('#serviceBillsTable').on('click', '.view-bill-items', function() {
        let billId = $(this).data('id');

        $.ajax({
            url: `${studentsBaseUrl}/service-bills/${billId}/items`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    serviceBillItemsTable.clear();
                    serviceBillItemsTable.rows.add(response.items || []);
                    serviceBillItemsTable.draw();
                    $('#viewServiceBillItemsModal').modal('show');
                }
            },
            error: function(xhr) {
                alert('Lỗi khi tải chi tiết hóa đơn: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Không có phản hồi từ server'));
            }
        });
    });
});