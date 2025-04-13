$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#dataTable').DataTable({
        ajax: {
            url: servicesIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.services;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'service_name' },
            { data: 'price', render: $.fn.dataTable.render.number(',', '.', 0) },
            { 
                data: 'is_active', 
                render: function(data) {
                    return data ? '<span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></span>' : '<span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></span>';
                }
            },
            { 
                data: 'service_img_path',
                render: function(data, type, row) {
                    return `<img class="img_table" src="${servicesImageBaseUrl}/${data}" alt="${row.service_name}">`;
                }
            },
            { 
                data: null, 
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary edit-service" 
                            data-id="${row.service_id}" 
                            data-name="${row.service_name}" 
                            data-price="${row.price}" 
                            data-active="${row.is_active}" 
                            data-img="${row.service_img_path}" 
                            data-description="${row.service_description || ''}" 
                            data-toggle="modal" 
                            data-target="#editServiceModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${servicesBaseUrl}/${row.service_id}" method="POST" style="display: inline;" class="delete-service-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-service"
                                data-id="${row.service_id}"
                                data-name="${row.service_name}">
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
                $(`#${formId === 'addServiceForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                $(this).val('');
                if (field === 'service_img') {
                    $(this).closest('.form-group').find('.preview_img').attr('src', defaultImageUrl);
                }
            } else {
                $(`#${formId === 'addServiceForm' ? 'add' : 'edit'}_${field}-error`).text('');
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

    $('#addServiceModal').on('show.bs.modal', function() {
        $('#addServiceForm')[0].reset();
        $('#addServiceModal .text-danger').text('');
        $('#addServiceModal .preview_img').attr('src', defaultImageUrl);
    });

    $('#addServiceForm').on('submit', function(e) {
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
                    $('#addServiceModal').modal('hide');
                    $('#addServiceForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi thêm dịch vụ');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addServiceForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra khi thêm dịch vụ: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    $('#dataTable').on('click', '.edit-service', function() {
        let button = $(this);
        let id = button.data('id');

        $('#edit_service_id').val(id);
        $('#edit_service_name').val(button.data('name'));
        $('#edit_price').val(button.data('price'));
        $('#edit_is_active').prop('checked', button.data('active') === 1);
        $('#edit_service_img').val(''); // Reset input file
        $('#editServiceForm .preview_img').attr('src', button.data('img') ? `${servicesImageBaseUrl}/${button.data('img')}` : defaultImageUrl);
        $('#editServiceForm #image_old').val(button.data('img'));
        $('#edit_service_description').val(button.data('description'));

        $('#editServiceForm').attr('action', `${servicesBaseUrl}/${id}`);
        $('#editServiceModal .text-danger').text('');
    });

    $('#editServiceForm').on('submit', function(e) {
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
                    $('#editServiceModal').modal('hide');
                    $('#editServiceForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật dịch vụ');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('editServiceForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật dịch vụ: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-service', function() {
        let id = $(this).data('id');
        let serviceName = $(this).data('name');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa dịch vụ ${serviceName}?`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${servicesBaseUrl}/${id}`,
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
                        alert('Có lỗi xảy ra khi xóa dịch vụ');
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra khi xóa dịch vụ: ' + xhr.responseJSON.message);
                }
            });
        });
    });
});