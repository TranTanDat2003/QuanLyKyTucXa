$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#dataTable').DataTable({
        ajax: {
            url: staffIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.staffs;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'staff_code' },
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
                        <button type="button" class="btn btn-sm btn-info view-staff" data-id="${row.staff_id}" data-toggle="modal" data-target="#viewStaffModal">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary edit-staff" 
                            data-id="${row.staff_id}" 
                            data-name="${row.full_name}" 
                            data-dob="${row.date_of_birth}" 
                            data-gender="${row.gender}" 
                            data-phone="${row.phone || ''}" 
                            data-address="${row.address || ''}" 
                            data-email="${row.email}" 
                            data-img="${row.image}"
                            data-toggle="modal" 
                            data-target="#editStaffModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${staffBaseUrl}/${row.staff_id}" method="POST" style="display: inline;" class="delete-staff-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-staff" data-id="${row.staff_id}" data-name="${row.full_name}">
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
                $(`#${formId === 'addStaffForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                $(this).val('');
                if (field === 'image') {
                    $(this).closest('.form-group').find('.preview_img').attr('src', defaultProfileImageUrl);
                }
            } else {
                $(`#${formId === 'addStaffForm' ? 'add' : 'edit'}_${field}-error`).text('');
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

    $('#addStaffModal').on('show.bs.modal', function() {
        $('#addStaffForm')[0].reset();
        $('#addStaffModal .text-danger').text('');
        $('#addStaffModal .preview_img').attr('src', defaultProfileImageUrl);
    });

    $('#addStaffForm').on('submit', function(e) {
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
                    $('#addStaffModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addStaffForm', xhr.responseJSON.errors);
                } else {
                    toastr.error('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                    console.log(xhr.responseJSON);
                }
            }
        });
    });

    $('#dataTable').on('click', '.edit-staff', function() {
        let button = $(this);

        $('#edit_image').val('');
        $('#edit_staff_id').val(button.data('id'));
        $('#edit_full_name').val(button.data('name'));
        $('#edit_date_of_birth').val(button.data('dob'));
        $('#edit_gender').val(button.data('gender'));
        $('#edit_phone').val(button.data('phone'));
        $('#edit_address').val(button.data('address'));
        $('#edit_email').val(button.data('email'));
        $('#editStaffForm .preview_img').attr('src', button.data('img')
                                                        ? (button.data('img') === "default_profile.jpg" 
                                                                    ? defaultProfileImageUrl
                                                                    : `${staffImageBaseUrl}/${button.data('img')}`
                                                        )
                                                        : defaultProfileImageUrl);
        $('#editStaffForm #image_old').val(button.data('img'));
        $('#editStaffForm').attr('action', `${staffBaseUrl}/${button.data('id')}`);
        
        $('#editStaffModal .text-danger').text('');
    });

    $('#editStaffForm').on('submit', function(e) {
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
                    $('#editStaffModal').modal('hide');
                    $('#editStaffForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                console.log('XHR Response:', xhr.responseJSON);
                if (xhr.status === 422) {
                    handleValidationErrors('editStaffForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-staff', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa nhân viên ${name}?`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${staffBaseUrl}/${id}`,
                method: 'POST',
                data: { '_method': 'DELETE' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#deleteConfirmModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                }
            });
        });
    });

    $('#dataTable').on('click', '.view-staff', function() {
        let id = $(this).data('id');

        $.ajax({
            url: `${staffBaseUrl}/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Response từ server:', response);
                if (response.success) {
                    let staff = response.staffs || {};
                    $('#view_staff_code').text(staff.staff_code || '');
                    $('#view_full_name').text(staff.full_name || '');
                    $('#view_date_of_birth').text(staff.date_of_birth || '');
                    $('#view_gender').text(staff.gender ? 'Nữ' : 'Nam' || '');         
                    $('#view_phone').text(staff.phone || '');
                    $('#view_address').text(staff.address || '');
                    $('#view_email').text(staff.email || '');
                    $('#view_status').text(staff.status ? 'Hoạt động' : 'Khóa' || '')
                                     .addClass(staff.status === 1 ? 'active' : 'locked');                    
                    $('#view_image').attr('src', staff.image 
                        ? (staff.image === "default_profile.jpg" ? defaultProfileImageUrl : `${staffImageBaseUrl}/${staff.image}`) 
                        : defaultProfileImageUrl);

                    $('#viewStaffModal').modal('show');
                }
            },
            error: function(xhr) {
                console.log('Lỗi AJAX:', xhr.status, xhr.responseJSON);
                alert('Lỗi khi tải dữ liệu: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Không có phản hồi từ server'));
            }
        });
    });
});