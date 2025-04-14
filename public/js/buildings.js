$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#dataTable').DataTable({
        ajax: {
            url: buildingsIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.buildings;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'building_name' },
            { data: 'description' },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary edit-building" 
                            data-id="${row.building_id}" 
                            data-name="${row.building_name}" 
                            data-description="${row.description || ''}" 
                            data-toggle="modal" 
                            data-target="#editBuildingModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${buildingsBaseUrl}/${row.building_id}" method="POST" style="display: inline;" class="delete-building-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-building"
                                data-id="${row.building_id}"
                                data-name="${row.building_name}">
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
                $(`#${formId === 'addBuildingForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                if (field !== 'description') {
                    $(this).val('');
                }
            } else {
                $(`#${formId === 'addBuildingForm' ? 'add' : 'edit'}_${field}-error`).text('');
            }
        });
    }

    $('#addBuildingModal').on('show.bs.modal', function() {
        $('#addBuildingForm')[0].reset();
        $('#addBuildingModal .text-danger').text('');
    });

    $('#addBuildingForm').on('submit', function(e) {
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
                    $('#addBuildingModal').modal('hide');
                    $('#addBuildingForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi thêm tòa nhà');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addBuildingForm', xhr.responseJSON.errors);
                } else {
                    toastr.error(xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    $('#dataTable').on('click', '.edit-building', function() {
        let button = $(this);
        let id = button.data('id');

        $('#edit_building_id').val(id);
        $('#edit_building_name').val(button.data('name'));
        $('#edit_description').val(button.data('description'));

        $('#editBuildingForm').attr('action', `${buildingsBaseUrl}/${id}`);

        $('#editBuildingModal .text-danger').text('');
    });

    $('#editBuildingForm').on('submit', function(e) {
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
                    $('#editBuildingModal').modal('hide');
                    $('#editBuildingForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Có lỗi xảy ra khi cập nhật tòa nhà');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('editBuildingForm', xhr.responseJSON.errors);
                } else {
                    toastr.error(xhr.responseJSON.message);
                }
                console.log(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.delete-building', function() {
        let id = $(this).data('id');
        let buildingName = $(this).data('name');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa tòa nhà ${buildingName}? (Các phòng liên quan cũng sẽ bị xóa)`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${buildingsBaseUrl}/${id}`,
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
                        toastr.error(response.message || 'Có lỗi xảy ra khi xóa tòa nhà');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                    console.log(xhr.responseText);
                }
            });
        });
    });
});