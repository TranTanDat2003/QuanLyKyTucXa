$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#dataTable').DataTable({
        ajax: {
            url: utilityRatesIndexUrl,
            type: 'GET',
            dataType: 'json',
            dataSrc: function(response) {
                if (response.success) {
                    return response.utilityRates;
                } else {
                    console.error('Lỗi tải dữ liệu:', response.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'electricity_rate', render: $.fn.dataTable.render.number(',', '.', 0) },
            { data: 'water_rate', render: $.fn.dataTable.render.number(',', '.', 0) },
            { 
                data: 'effective_date',
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
                        <button type="button" class="btn btn-sm btn-primary edit-utility-rate" 
                            data-id="${row.rate_id}" 
                            data-electricity="${row.electricity_rate}" 
                            data-water="${row.water_rate}" 
                            data-date="${row.effective_date}" 
                            data-toggle="modal" 
                            data-target="#editUtilityRateModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="${utilityRatesBaseUrl}/${row.rate_id}" method="POST" style="display: inline;" class="delete-utility-rate-form">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger delete-utility-rate"
                                data-id="${row.rate_id}"
                                data-date="${row.effective_date}">
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
                $(`#${formId === 'addUtilityRateForm' ? 'add' : 'edit'}_${field}-error`).text(errors[field][0]);
                $(this).val('');
            } else {
                $(`#${formId === 'addUtilityRateForm' ? 'add' : 'edit'}_${field}-error`).text('');
            }
        });
    }

    $('#addUtilityRateModal').on('show.bs.modal', function() {
        $('#addUtilityRateForm')[0].reset();
        $('#addUtilityRateModal .text-danger').text('');
    });

    $('#addUtilityRateForm').on('submit', function(e) {
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
                    $('#addUtilityRateModal').modal('hide');
                    $('#addUtilityRateForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi thêm giá tiện ích');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('addUtilityRateForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra khi thêm giá tiện ích: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    $('#dataTable').on('click', '.edit-utility-rate', function() {
        let button = $(this);
        let id = button.data('id');

        $('#edit_rate_id').val(id);
        $('#edit_electricity_rate').val(button.data('electricity'));
        $('#edit_water_rate').val(button.data('water'));
        $('#edit_effective_date').val(moment(button.data('date')).format('YYYY-MM-DD'));

        $('#editUtilityRateForm').attr('action', `${utilityRatesBaseUrl}/${id}`);
        $('#editUtilityRateModal .text-danger').text('');
    });

    $('#editUtilityRateForm').on('submit', function(e) {
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
                    $('#editUtilityRateModal').modal('hide');
                    $('#editUtilityRateForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật giá tiện ích');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors('editUtilityRateForm', xhr.responseJSON.errors);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật giá tiện ích: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-utility-rate', function() {
        let id = $(this).data('id');
        let effectiveDate = moment($(this).data('date')).format('DD-MM-YYYY');
        $('#deleteConfirmModal .modal-body').text(`Bạn có chắc chắn muốn xóa giá tiện ích hiệu lực từ ngày ${effectiveDate}?`);
        $('#deleteConfirmModal').modal('show');

        $('#confirmDeleteButton').off('click').on('click', function() {
            $.ajax({
                url: `${utilityRatesBaseUrl}/${id}`,
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
                        alert('Có lỗi xảy ra khi xóa giá tiện ích');
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra khi xóa giá tiện ích: ' + xhr.responseJSON.message);
                }
            });
        });
    });
});