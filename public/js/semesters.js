$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#dataTable').DataTable({
        ajax: {
            url: semestersIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.semesters;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'semester_name' },
            { data: 'academic_year' },
            { 
                data: 'start_date',
                render: function(data) {
                    return moment(data).format('DD-MM-YYYY');
                }
            },
            { 
                data: 'end_date',
                render: function(data) {
                    return moment(data).format('DD-MM-YYYY');
                }
            },
            { 
                data: 'status', 
                render: function(data) {
                    return data ? '<span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></span>' : '<span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></span>';
                }
            },
            { 
                data: null, 
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-info view-semester" 
                            data-id="${row.semester_id}" 
                            data-toggle="modal" 
                            data-target="#viewSemesterModal">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary edit-semester" 
                            data-id="${row.semester_id}" 
                            data-name="${row.semester_name}" 
                            data-year="${row.academic_year}" 
                            data-start="${row.start_date}" 
                            data-end="${row.end_date}" 
                            data-toggle="modal" 
                            data-target="#editSemesterModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${semestersBaseUrl}/${row.semester_id}" method="POST" style="display: inline;" class="delete-semester-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-semester"
                                data-id="${row.semester_id}"
                                data-name="${row.semester_name}">
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
                $(`#${formId === 'addSemesterForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                $(this).val('');
            } else {
                $(`#${formId === 'addSemesterForm' ? 'add' : 'edit'}_${field}-error`).text('');
            }
        });
    }

    $('#addSemesterModal').on('show.bs.modal', function() {
        $('#addSemesterForm')[0].reset();
        $('#addSemesterModal .text-danger').text('');
    });

    $('#addSemesterForm').on('submit', function(e) {
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
                    $('#addSemesterModal').modal('hide');
                    $('#addSemesterForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi thêm học kỳ');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addSemesterForm', xhr.responseJSON.errors);
                } else {
                    toastr.error(xhr.responseJSON.message);
                }
            }
        });
    });

    $('#dataTable').on('click', '.edit-semester', function() {
        let button = $(this);
        let id = button.data('id');

        $('#edit_semester_id').val(id);
        $('#edit_semester_name').val(button.data('name'));
        $('#edit_academic_year').val(button.data('year'));
        $('#edit_start_date').val(moment(button.data('start')).format('YYYY-MM-DD'));
        $('#edit_end_date').val(moment(button.data('end')).format('YYYY-MM-DD'));

        $('#editSemesterForm').attr('action', `${semestersBaseUrl}/${id}`);
        $('#editSemesterModal .text-danger').text('');
    });

    $('#editSemesterForm').on('submit', function(e) {
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
                    $('#editSemesterModal').modal('hide');
                    $('#editSemesterForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi cập nhật học kỳ');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('editSemesterForm', xhr.responseJSON.errors);
                } else {
                    toastr.error(xhr.responseJSON.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-semester', function() {
        let id = $(this).data('id');
        let semesterName = $(this).data('name');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa học kỳ ${semesterName}?`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${semestersBaseUrl}/${id}`,
                method: 'POST',
                data: {
                    '_method': 'DELETE',
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#deleteConfirmModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message || 'Có lỗi xảy ra khi xóa học kỳ');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                }
            });
        });
    });

    $('#dataTable').on('click', '.view-semester', function() {
        let id = $(this).data('id');

        $.ajax({
            url: `${semestersBaseUrl}/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let semester = response.semester;
                    $('#view_semester_name').text(semester.semester_name || 'Không có dữ liệu');
                    $('#view_academic_year').text(semester.academic_year || 'Không có dữ liệu');
                    $('#view_start_date').text(moment(semester.start_date).format('DD-MM-YYYY') || 'Không có dữ liệu');
                    $('#view_end_date').text(moment(semester.end_date).format('DD-MM-YYYY') || 'Không có dữ liệu');
                    $('#view_status').text(semester.status ? 'Hoạt động' : 'Không hoạt động');

                    let contractsTable = $('#contractsTable').DataTable({
                        destroy: true,
                        data: response.contracts,
                        columns: [
                            { data: 'contract_id' },
                            { data: 'student_id' },
                            { data: 'room_id' },
                            { 
                                data: 'status',
                                render: function(data) {
                                    if (data === 'Đã duyệt') return '<span class="badge badge-success">Đã duyệt</span>';
                                    if (data === 'Đang ở') return '<span class="badge badge-success">Đang ở</span>';
                                    if (data === 'Chờ duyệt') return '<span class="badge badge-warning">Chờ duyệt</span>';
                                    if (data === 'Hết hạn') return '<span class="badge badge-danger">Hết hạn</span>';
                                    if (data === 'Hủy') return '<span class="badge badge-danger">Đã hủy</span>';
                                    return `<span class="badge badge-secondary">${data}</span>`;
                                }
                            }
                        ],
                    });

                    $('#viewSemesterModal').modal('show');
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi tải thông tin học kỳ');
                }
            },
            error: function(xhr) {
                toastr.error((xhr.responseJSON ? xhr.responseJSON.message : 'Không xác định'));
            }
        });
    });
});