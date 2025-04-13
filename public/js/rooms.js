$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#dataTable').DataTable({
        ajax: {
            url: roomsIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.rooms;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'room_code' },
            { data: 'building.building_name' },
            { data: 'room_type.room_type_name' },
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
            { data: 'gender' },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary edit-room" 
                            data-id="${row.room_id}" 
                            data-room_code="${row.room_code}" 
                            data-building_name="${row.building.building_name}"
                            data-building_id="${row.building_id}" 
                            data-room_type_id="${row.room_type_id}" 
                            data-status="${row.status}" 
                            data-gender="${row.gender}" 
                            data-toggle="modal" 
                            data-target="#editRoomModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${roomsBaseUrl}/${row.room_id}" method="POST" style="display: inline;" class="delete-room-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-room"
                                data-id="${row.room_id}"
                                data-name="${row.room_code}">
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
                $(`#${formId === 'addRoomForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                if (field !== 'building_id' && field !== 'room_type_id' && field !== 'status' && field !== 'gender') {
                    $(this).val('');
                }
            } else {
                $(`#${formId === 'addRoomForm' ? 'add' : 'edit'}_${field}-error`).text('');
            }
        });
    }

    $('#addRoomModal').on('show.bs.modal', function() {
        $('#addRoomForm')[0].reset();
        $('#addRoomModal .text-danger').text('');
    });

    $('#addRoomForm').on('submit', function(e) {
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
                    $('#addRoomModal').modal('hide');
                    $('#addRoomForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi thêm phòng');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addRoomForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra khi thêm phòng: ' + xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    $('#dataTable').on('click', '.edit-room', function() {
        let button = $(this);
        let id = button.data('id');
        let buildingName = button.data('building_name');
        let fullRoomCode = button.data('room_code');
        let roomCode = fullRoomCode.replace(buildingName, '');

        $('#edit_room_id').val(id);
        $('#edit_room_code').val(roomCode);
        $('#edit_building_id').val(button.data('building_id'));
        $('#edit_room_type_id').val(button.data('room_type_id'));
        $('#edit_status').val(button.data('status'));
        $('#edit_gender').val(button.data('gender'));

        $('#editRoomForm').attr('action', `${roomsBaseUrl}/${id}`);
        $('#editRoomModal .text-danger').text('');
    });

    $('#editRoomForm').on('submit', function(e) {
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
                    $('#editRoomModal').modal('hide');
                    $('#editRoomForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật phòng');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('editRoomForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật phòng: ' + xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.delete-room', function() {
        let id = $(this).data('id');
        let roomName = $(this).data('name');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa phòng ${roomName}?`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${roomsBaseUrl}/${id}`,
                method: 'POST',
                data: {
                    '_method': 'DELETE',
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#deleteConfirmModal').modal('hide');
                        table.ajax.reload();
                        alert(response.message);
                    } else {
                        alert('Có lỗi xảy ra khi xóa phòng');
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra khi xóa phòng: ' + xhr.responseJSON.message);
                    console.log(xhr.responseText);
                }
            });
        });
    });
});