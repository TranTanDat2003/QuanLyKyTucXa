$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Khởi tạo DataTables với AJAX
    let table = $('#dataTable').DataTable({
        ajax: {
            url: roomTypesIndexUrl, // Được truyền từ Blade
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.roomTypes;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'room_type_name' },
            { data: 'capacity' },
            { data: 'room_type_price', render: $.fn.dataTable.render.number(',', '.', 0) },
            { 
                data: 'has_air_conditioner', 
                render: function(data) {
                    return data ? '<span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></span>' : '<span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></span>';
                }
            },
            { 
                data: 'allow_cooking', 
                render: function(data) {
                    return data ? '<span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></span>' : '<span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></span>';
                }
            },
            { 
                data: 'room_type_img_path',
                render: function(data, type, row) {
                    return `<img class="img_table" src="${roomTypesImageBaseUrl}/${data}" alt="${row.room_type_name}">`;
                }
            },
            { 
                data: null, 
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-info view-room-type" 
                            data-id="${row.room_type_id}" 
                            data-toggle="modal" 
                            data-target="#viewRoomTypeModal">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary edit-room-type" 
                            data-id="${row.room_type_id}" 
                            data-name="${row.room_type_name}" 
                            data-capacity="${row.capacity}" 
                            data-price="${row.room_type_price}" 
                            data-img="${row.room_type_img_path}" 
                            data-ac="${row.has_air_conditioner}" 
                            data-cooking="${row.allow_cooking}" 
                            data-toggle="modal" 
                            data-target="#editRoomTypeModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${roomTypesBaseUrl}/${row.room_type_id}" method="POST" style="display: inline;" class="delete-room-type-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-room-type"
                                data-id="${row.room_type_id}"
                                data-name="${row.room_type_name}">
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
                $(`#${formId === 'addRoomTypeForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                $(this).val('');
                if (field === 'room_type_img') {
                    $(this).closest('.form-group').find('.preview_img').attr('src', defaultProfileImageUrl);
                }
            } else {
                $(`#${formId === 'addRoomTypeForm' ? 'add' : 'edit'}_${field}-error`).text('');
            }
        });
    }

    // Xử lý preview ảnh khi chọn file
    $('.file_upload_input').on('change', function() {
        let file = this.files[0];
        if (file) {
            let url = URL.createObjectURL(file);
            $(this).closest('.form-group').find('.preview_img').attr('src', url);
        }
    });


    // Reset modal thêm loại phòng khi mở
    $('#addRoomTypeModal').on('show.bs.modal', function() {
        $('#addRoomTypeForm')[0].reset();
        $('#addRoomTypeModal .text-danger').text('');
        $('#addRoomTypeModal .preview_img').attr('src', defaultProfileImageUrl);
    });

    // Xử lý thêm loại phòng
    $('#addRoomTypeForm').on('submit', function(e) {
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
                    $('#addRoomTypeModal').modal('hide');
                    $('#addRoomTypeForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi thêm loại phòng');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addRoomTypeForm', xhr.responseJSON.errors);
                } else {
                    toastr.error(xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    // Xử lý chỉnh sửa loại phòng
    $('#dataTable').on('click', '.edit-room-type', function() {
        let button = $(this);
        let id = button.data('id');

        // Reset input image
        $('#edit_room_type_img').val('');

        // Điền dữ liệu vào form
        $('#edit_room_type_id').val(id);
        $('#edit_room_type_name').val(button.data('name'));
        $('#edit_capacity').val(button.data('capacity'));
        $('#edit_room_type_price').val(button.data('price'));
        $('#editRoomTypeForm .preview_img').attr('src', button.data('img') ? `${roomTypesImageBaseUrl}/${button.data('img')}` : defaultProfileImageUrl);
        $('#editRoomTypeForm #image_old').val(button.data('img'));
        $('#edit_has_air_conditioner').prop('checked', button.data('ac') === 1);
        $('#edit_allow_cooking').prop('checked', button.data('cooking') === 1);

        $('#editRoomTypeForm').attr('action', `${roomTypesBaseUrl}/${id}`);

        // Xóa lỗi cũ khi mở modal
        $('#editRoomTypeModal .text-danger').text('');
    });

    $('#editRoomTypeForm').on('submit', function(e) {
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
                    $('#editRoomTypeModal').modal('hide');
                    $('#editRoomTypeForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi cập nhật loại phòng');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('editRoomTypeForm', xhr.responseJSON.errors);
                } else {
                    toastr.error(xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    // Xử lý xóa loại phòng
    $(document).on('click', '.delete-room-type', function() {
        let id = $(this).data('id');
        let roomTypeName = $(this).data('name');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa loại phòng ${roomTypeName}?`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${roomTypesBaseUrl}/${id}`,
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
                        toastr.error(response.message || 'Có lỗi xảy ra khi xóa loại phòng');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                    console.log(xhr.responseText);
                }
            });
        });
    });

    // Xử lý xem chi tiết loại phòng
    $('#dataTable').on('click', '.view-room-type', function() {
        let id = $(this).data('id');

        // Tải thông tin chi tiết và danh sách phòng bằng AJAX
        $.ajax({
            url: `${roomTypesBaseUrl}/${id}`, // Sử dụng route đã định nghĩa
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Điền thông tin chi tiết của room_type
                    let roomType = response.roomTypes || {}; // Nếu API trả về roomType riêng
                    $('#view_room_type_name').text(roomType.room_type_name || 'Không có dữ liệu');
                    $('#view_capacity').text(roomType.capacity || '0');
                    $('#view_room_type_price').text($.fn.dataTable.render.number(',', '.', 0).display(roomType.room_type_price || 0));
                    $('#view_has_air_conditioner').text(roomType.has_air_conditioner ? 'Có' : 'Không');
                    $('#view_allow_cooking').text(roomType.allow_cooking ? 'Có' : 'Không');
                    $('#view_room_type_img').attr('src', roomType.room_type_img_path ? `${roomTypesImageBaseUrl}/${roomType.room_type_img_path}` : defaultProfileImageUrl);

                    // Khởi tạo DataTables cho bảng roomsTable
                let roomsTable = $('#roomsTable').DataTable({
                    destroy: true, // Hủy bảng cũ nếu đã tồn tại
                    data: response.rooms, // Dữ liệu từ response
                    columns: [
                        { data: 'room_code' },
                        { data: 'available_slots' },
                        { 
                            data: 'status',
                            render: function(data, type, row) {
                                if (data === 'Đang sử dụng') {
                                    return '<span class="badge badge-success">Đang sử dụng</span>';
                                } else if (data === 'Không sử dụng') {
                                    return '<span class="badge badge-secondary">Không sử dụng</span>';
                                } else if (data === 'Đang sửa chữa') {
                                    return '<span class="badge badge-warning">Đang sửa chữa</span>';
                                }
                                return data; // Trường hợp dữ liệu không khớp
                            }
                        },
                        { data: 'gender' }
                    ],
                });

                    $('#viewRoomTypeModal').modal('show');
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi tải thông tin loại phòng');
                }
            },
            error: function(xhr) {
                toastr.error((xhr.responseJSON ? xhr.responseJSON.message : 'Không xác định'));
                console.log(xhr.responseText);
            }
        });
    });
});