<div class="modal fade" id="addBuildingModal" tabindex="-1" role="dialog" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBuildingModalLabel">Thêm tòa nhà mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="{{ route('buildings.store') }}" id="addBuildingForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="building_name">Tên tòa nhà</label>
                        <input type="text" name="building_name" id="building_name" class="form-control" value="{{ old('building_name') }}">
                        <span class="text-danger" id="add_building_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        <span class="text-danger" id="add_description-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm tòa nhà</button>
                </div>
            </form>
        </div>
    </div>
</div>