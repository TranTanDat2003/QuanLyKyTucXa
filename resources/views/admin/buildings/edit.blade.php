<div class="modal fade" id="editBuildingModal" tabindex="-1" role="dialog" aria-labelledby="editBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBuildingModalLabel">Chỉnh sửa tòa nhà</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" id="editBuildingForm">
                @method('PUT')
                @csrf
                <input type="hidden" name="building_id" id="edit_building_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_building_name">Tên tòa nhà</label>
                        <input type="text" name="building_name" id="edit_building_name" class="form-control">
                        <span class="text-danger" id="edit_building_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Mô tả</label>
                        <textarea name="description" id="edit_description" class="form-control"></textarea>
                        <span class="text-danger" id="edit_description-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>