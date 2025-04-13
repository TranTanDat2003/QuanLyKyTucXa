<div class="modal fade" id="addSemesterModal" tabindex="-1" role="dialog" aria-labelledby="addSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSemesterModalLabel">Thêm học kỳ mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="{{ route('semesters.store') }}" id="addSemesterForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="semester_name">Tên học kỳ</label>
                        <input type="text" name="semester_name" id="semester_name" class="form-control" value="{{ old('semester_name') }}">
                        <span class="text-danger" id="add_semester_name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="academic_year">Năm học</label>
                        <input type="text" name="academic_year" id="academic_year" class="form-control" value="{{ old('academic_year') }}">
                        <span class="text-danger" id="add_academic_year-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Ngày bắt đầu</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                        <span class="text-danger" id="add_start_date-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Ngày kết thúc</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                        <span class="text-danger" id="add_end_date-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="addSemesterBtn">Thêm học kỳ</button>
                </div>
            </form>
        </div>
    </div>
</div>