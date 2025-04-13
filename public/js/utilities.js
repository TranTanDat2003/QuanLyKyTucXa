$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let currentRate = null;

    let table = $('#dataTable').DataTable({
        ajax: {
            url: utilitiesIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    currentRate = response.currentRate;
                    return response.rooms;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            },
            error: function(xhr) {
                console.error('Lỗi AJAX:', xhr.responseText);
            }
        },
        columns: [
            { data: 'room_code' },
            { 
                data: 'room_type.room_type_name', 
                defaultContent: 'N/A'
            },
            { 
                data: 'building.building_name', 
                defaultContent: 'N/A'
            },
            { 
                data: null, 
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary add-utility" 
                            data-id="${row.room_id}" 
                            data-room_code="${row.room_code}"
                            data-toggle="modal" 
                            data-target="#addUtilityModal">
                            <i class="fas fa-plus"></i> Thêm điện nước
                        </button>
                        <button type="button" class="btn btn-sm btn-info view-utility" 
                            data-id="${row.room_id}" 
                            data-room_code="${row.room_code}"
                            data-toggle="modal" 
                            data-target="#viewUtilityModal">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </button>
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
                $(`#${formId === 'addUtilityForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                if (field !== 'month' && field !== 'room_id' && field !== 'rate_id') $(this).val('');
            } else {
                $(`#${formId === 'addUtilityForm' ? 'add' : 'edit'}_${field}-error`).text('');
            }
        });
    }

    $('#addUtilityModal').on('show.bs.modal', function(e) {
        const button = $(e.relatedTarget);
        const roomId = button.data('id');
        const roomCode = button.data('room_code');

        $('#add_room_id').val(roomId);
        $('#add_room_code').text(roomCode);
        $('#add_electricity_rate').text(currentRate ? currentRate.electricity_rate : 'N/A');
        $('#add_water_rate').text(currentRate ? currentRate.water_rate : 'N/A');
        $('#add_rate_id').val(currentRate ? currentRate.rate_id : '');
        $('#addUtilityForm')[0].reset();
        $('#addUtilityModal .text-danger').text('');
        $('#month').val(new Date().toISOString().slice(0, 10).substring(0, 7) + '-01');
    });

    $('#addUtilityForm').on('submit', function(e) {
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
                    $('#addUtilityModal').modal('hide');
                    $('#addUtilityForm')[0].reset();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi thêm tiện ích');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || { message: [xhr.responseJSON.message] };
                    if (errors.month) {
                        alert(errors.month[0]);
                    } else {
                        handleValidationErrors('addUtilityForm', errors);
                    }
                } else {
                    alert('Có lỗi xảy ra khi thêm tiện ích: ' + xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    $('#editUtilityForm').on('submit', function(e) {
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
                    $('#editUtilityModal').modal('hide');
                    $('#editUtilityForm')[0].reset();
                    $('#utilitiesTable').DataTable().ajax.reload();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật tiện ích');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('editUtilityForm', xhr.responseJSON.errors || { message: [xhr.responseJSON.message] });
                } else {
                    alert('Có lỗi xảy ra khi cập nhật tiện ích: ' + xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    $('#dataTable').on('click', '.view-utility', function() {
        let id = $(this).data('id');
        let roomCode = $(this).data('room_code');

        $('#view_room_code').text(roomCode);
        $('#view_room_code_display').text(roomCode);
        $('#view_electricity_rate').text(currentRate ? currentRate.electricity_rate : 'N/A');
        $('#view_water_rate').text(currentRate ? currentRate.water_rate : 'N/A');

        let utilitiesTable = $('#utilitiesTable').DataTable({
            destroy: true,
            ajax: {
                url: `${utilitiesBaseUrl}/${id}`,
                type: 'GET',
                dataType: 'json',
                dataSrc: function(response) {
                    if (response.success) {
                        return response.utilities;
                    } else {
                        console.error('Lỗi tải dữ liệu:', response.message);
                        return [];
                    }
                }
            },
            columns: [
                { data: 'month' },
                { data: 'electricity_reading' },
                { data: 'water_reading' },
                { data: 'electricity_usage' },
                { data: 'water_usage' },
                { data: 'utility_cost', render: $.fn.dataTable.render.number(',', '.', 0) },
                { data: 'created_by' },
                { data: 'updated_by' },
                { 
                    data: null, 
                    render: function(data, type, row) {
                        return `
                            <button type="button" class="btn btn-sm btn-primary edit-utility" 
                                data-id="${row.utility_id}" 
                                data-month="${row.month}" 
                                data-electricity_reading="${row.electricity_reading}"
                                data-water_reading="${row.water_reading}"
                                data-room_id="${id}"
                                data-rate_id="${row.rate_id}"
                                data-toggle="modal" 
                                data-target="#editUtilityModal">
                                <i class="fas fa-edit"></i>
                            </button>
                        `;
                    }
                }
            ]
        });

        $('#utilitiesTable').off('click', '.edit-utility'); // Xóa sự kiện cũ nếu có
        $('#utilitiesTable').on('click', '.edit-utility', function() {
            let button = $(this);
            let id = button.data('id');
            let month = button.data('month');
            let electricityReading = button.data('electricity_reading');
            let waterReading = button.data('water_reading');
            let roomId = button.data('room_id');
            let rateId = button.data('rate_id');
            let roomCode = $('#view_room_code').text();

            // Debug để kiểm tra dữ liệu
            console.log('Edit Utility:', { id, month, electricityReading, waterReading, roomId, rateId, roomCode });

            // Điền dữ liệu vào modal Edit
            $('#edit_utility_id').val(id);
            $('#edit_month').val(month.split('/').reverse().join('-') + '-01');
            $('#edit_month_title').text(month.split('/')[0]);
            $('#edit_electricity_reading').val(electricityReading);
            $('#edit_water_reading').val(waterReading);
            $('#edit_room_id').val(roomId);
            $('#edit_rate_id').val(rateId);
            $('#edit_room_code').text(roomCode);
            $('#edit_electricity_rate').text(currentRate ? currentRate.electricity_rate : 'N/A');
            $('#edit_water_rate').text(currentRate ? currentRate.water_rate : 'N/A');

            $('#editUtilityForm').attr('action', `${utilitiesBaseUrl}/${id}`);
            $('#editUtilityModal .text-danger').text('');

            $('#editUtilityModal').modal('show');
        });

        $('#viewUtilityModal').modal('show');
    });
});