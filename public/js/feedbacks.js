$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#dataTable').DataTable({
        ajax: {
            url: feedbacksIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.feedbacks;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'room.room_code' },
            { data: 'student.full_name' },
            { data: 'content' },
            { data: 'quantity' },
            {
                data: 'status',
                render: function(data) {
                    if (data === 'pending') return '<span class="badge badge-warning">Chờ duyệt</span>';
                    if (data === 'approved') return '<span class="badge badge-success">Đã duyệt</span>';
                    if (data === 'rejected') return '<span class="badge badge-danger">Từ chối</span>';
                    return data;
                }
            },
            {
                data: 'image',
                render: function(data) {
                    return data ? `<img class="img_table" src="${feedbacksImageBaseUrl}/${data}" alt="Ảnh minh họa">` : 'Không có ảnh';
                }
            },
            { 
                data: 'created_at',
                render: function(data) {
                    return new Date(data).toLocaleDateString('vi-VN');
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    let actions = `
                        <button type="button" class="btn btn-sm btn-info view-feedback" 
                            data-id="${row.feedback_id}" 
                            data-toggle="modal" 
                            data-target="#viewFeedbackModal">
                            <i class="fas fa-eye"></i>
                        </button>
                    `;
                    
                    // Chỉ hiển thị nút Edit và Reject nếu status là pending
                    if (row.status === 'pending') {
                        actions += `
                            <button type="button" class="btn btn-sm btn-warning edit-feedback" 
                                data-id="${row.feedback_id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger reject-feedback" 
                                data-id="${row.feedback_id}">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                    }
                    return actions;
                }
            }
        ]
    });

    $('#dataTable').on('click', '.edit-feedback', function() {
        let id = $(this).data('id');
        $.ajax({
            url: `${feedbacksBaseUrl}/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let feedback = response.feedback;
                    if (feedback.status === 'pending') {
                        $('#approveDateModal').modal('show');
                        $('#submitApproveDateBtn').off('click').on('click', function() {
                            let scheduledFixDate = $('#approve_scheduled_fix_date').val();
                            if (!scheduledFixDate) {
                                $('#approve_scheduled_fix_date-error').text('Vui lòng chọn ngày hẹn sửa chữa.');
                                return;
                            }
                            $.ajax({
                                url: `${feedbacksBaseUrl}/${id}`,
                                method: 'POST',
                                data: {
                                    '_method': 'PUT',
                                    'status': 'approved',
                                    'scheduled_fix_date': scheduledFixDate
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        $('#approveDateModal').modal('hide');
                                        table.ajax.reload();
                                        toastr.success(response.message);
                                    }
                                },
                                error: function(xhr) {
                                    toastr.error(xhr.responseJSON.message);
                                }
                            });
                        });
                    } else if (feedback.status === 'approved') {
                        // Nếu đã duyệt, cho phép thay đổi ngày hẹn
                        $('#approveDateModal').modal('show');
                        $('#approve_scheduled_fix_date').val(feedback.scheduled_fix_date);
                        $('#submitApproveDateBtn').off('click').on('click', function() {
                            let scheduledFixDate = $('#approve_scheduled_fix_date').val();
                            if (!scheduledFixDate) {
                                $('#approve_scheduled_fix_date-error').text('Vui lòng chọn ngày hẹn sửa chữa.');
                                return;
                            }
                            $.ajax({
                                url: `${feedbacksBaseUrl}/${id}`,
                                method: 'POST',
                                data: {
                                    '_method': 'PUT',
                                    'status': 'approved',
                                    'scheduled_fix_date': scheduledFixDate
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        $('#approveDateModal').modal('hide');
                                        table.ajax.reload();
                                        toastr.success(response.message);
                                    }
                                },
                                error: function(xhr) {
                                    toastr.error(xhr.responseJSON.message);
                                }
                            });
                        });
                    } else {
                        // Nếu trạng thái là rejected, chỉ đổi thành approved
                        if (confirm('Bạn có muốn duyệt yêu cầu này không?')) {
                            $('#approveDateModal').modal('show');
                            $('#submitApproveDateBtn').off('click').on('click', function() {
                                let scheduledFixDate = $('#approve_scheduled_fix_date').val();
                                if (!scheduledFixDate) {
                                    $('#approve_scheduled_fix_date-error').text('Vui lòng chọn ngày hẹn sửa chữa.');
                                    return;
                                }
                                $.ajax({
                                    url: `${feedbacksBaseUrl}/${id}`,
                                    method: 'POST',
                                    data: {
                                        '_method': 'PUT',
                                        'status': 'approved',
                                        'scheduled_fix_date': scheduledFixDate
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.success) {
                                            $('#approveDateModal').modal('hide');
                                            table.ajax.reload();
                                            toastr.success(response.message);
                                        }
                                    },
                                    error: function(xhr) {
                                        toastr.error(xhr.responseJSON.message);
                                    }
                                });
                            });
                        }
                    }
                }
            }
        });
    });

    $(document).on('click', '.reject-feedback', function() {
        let id = $(this).data('id');
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${feedbacksBaseUrl}/${id}/reject`,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#deleteConfirmModal').modal('hide');
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

    $('#dataTable').on('click', '.view-feedback', function() {
        let id = $(this).data('id');
        $.ajax({
            url: `${feedbacksBaseUrl}/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let feedback = response.feedback;
                    $('#view_room_code').text(feedback.room.room_code);
                    $('#view_student_name').text(feedback.student.full_name);
                    $('#view_content').text(feedback.content);
                    $('#view_quantity').text(feedback.quantity);
                    $('#view_status').text(feedback.status === 'pending' ? 'Chờ duyệt' : feedback.status === 'approved' ? 'Đã duyệt' : 'Từ chối');
                    $('#view_created_at').text(new Date(feedback.created_at).toLocaleDateString('vi-VN'));
                    $('#view_scheduled_fix_date').text(feedback.scheduled_fix_date ? new Date(feedback.scheduled_fix_date).toLocaleDateString('vi-VN') : 'Chưa có');
                    $('#view_staff_name').text(feedback.staff ? feedback.staff.full_name : 'Chưa có');
                    $('#view_image').attr('src', feedback.image ? `${feedbacksImageBaseUrl}/${feedback.image}` : defaultProfileImageUrl);

                    $('#viewFeedbackModal').modal('show');
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message);
            }
        });
    });
});