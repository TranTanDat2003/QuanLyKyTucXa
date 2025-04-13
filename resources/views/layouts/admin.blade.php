<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản lý Ký Túc Xá')</title>

    @vite(['resources/js/app.js'])

    <link rel="shortcut icon" href="{{ asset('images/logo/dorm.png') }}" type="image/x-icon">

    <!-- Custom fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/assets.css') }}" rel="stylesheet" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Thêm style tùy chỉnh nếu cần -->
    @stack('styles')
</head>
<body id="page-top">
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/admin') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="sidebar-brand-text mx-3">QUẢN LÝ KÝ TÚC XÁ</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Trang chủ</span>
                </a>
            </li>

            <!-- Quản lý người dùng -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Quản lý người dùng</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStudents" aria-expanded="true" aria-controls="collapseStudents">
                    <i class="fas fa-fw fa-people-arrows"></i>
                    <span>Sinh viên</span>
                </a>
                <div id="collapseStudents" class="collapse {{ request()->routeIs('students.*') ? 'show' : '' }}" aria-labelledby="headingStudents" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Sinh viên:</h6>
                        <a class="collapse-item {{ request()->routeIs('students.index') ? 'active' : '' }}" href="{{ route('students.index') }}">Danh sách sinh viên</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStaffs" aria-expanded="true" aria-controls="collapseStaffs">
                    <i class="fas fa-fw fa-user-tie"></i>
                    <span>Nhân viên</span>
                </a>
                <div id="collapseStaffs" class="collapse {{ request()->routeIs('staff.*') && !request()->routeIs('staff.profile') && !request()->routeIs('staff.password.update') ? 'show' : '' }}" aria-labelledby="headingStaffs" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Nhân viên:</h6>
                        <a class="collapse-item {{ request()->routeIs('staff.index') ? 'active' : '' }}" href="{{ route('staff.index') }}">Danh sách nhân viên</a>
                    </div>
                </div>
            </li>

            <!-- Quản lý ký túc xá -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Quản lý ký túc xá</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRooms" aria-expanded="true" aria-controls="collapseRooms">
                    <i class="fas fa-fw fa-door-open"></i>
                    <span>Phòng & Tòa nhà</span>
                </a>
                <div id="collapseRooms" class="collapse {{ request()->routeIs('room_types.*') || request()->routeIs('rooms.*') || request()->routeIs('buildings.*') ? 'show' : '' }}" aria-labelledby="headingRooms" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Loại phòng:</h6>
                        <a class="collapse-item {{ request()->routeIs('room_types.index') ? 'active' : '' }}" href="{{ route('room_types.index') }}">Danh sách loại phòng</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Phòng:</h6>
                        <a class="collapse-item {{ request()->routeIs('rooms.index') ? 'active' : '' }}" href="{{ route('rooms.index') }}">Danh sách phòng</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Tòa nhà:</h6>
                        <a class="collapse-item {{ request()->routeIs('buildings.index') ? 'active' : '' }}" href="{{ route('buildings.index') }}">Danh sách tòa nhà</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('contracts.index') }}">
                    <i class="fas fa-fw fa-file-contract"></i>
                    <span>Hợp đồng</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('semesters.index') }}">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Học kỳ</span>
                </a>
            </li>

            <!-- Quản lý tài chính -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Quản lý tài chính</div>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('utilities.index') }}">
                    <i class="fas fa-fw fa-plug"></i>
                    <span>Tiện ích</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/utility-rates') }}">
                    <i class="fas fa-fw fa-dollar-sign"></i>
                    <span>Đơn giá tiện ích</span>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBills" aria-expanded="true" aria-controls="collapseBills">
                    <i class="fas fa-fw fa-file-invoice"></i>
                    <span>Hóa đơn</span>
                </a>
                <div id="collapseBills" class="collapse {{ request()->routeIs('utility_bills.*') || request()->routeIs('service_bills.*') ? 'show' : '' }}" aria-labelledby="headingBills" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Hóa đơn:</h6>
                        <a class="collapse-item {{ request()->routeIs('utility_bills.index') ? 'active' : '' }}" href="{{ route('utility_bills.index') }}">Hóa đơn tiện ích</a>
                        <a class="collapse-item {{ request()->routeIs('service_bills.index') ? 'active' : '' }}" href="{{ route('service_bills.index') }}">Hóa đơn dịch vụ</a>
                    </div>
                </div>
            </li> --}}

            <!-- Quản lý dịch vụ -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Quản lý dịch vụ</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseServices" aria-expanded="true" aria-controls="collapseServices">
                    <i class="fas fa-fw fa-headset"></i>
                    <span>Dịch vụ</span>
                </a>
                <div id="collapseServices" class="collapse {{ request()->routeIs('services.*') ? 'show' : '' }}" aria-labelledby="headingServices" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Dịch vụ:</h6>
                        <a class="collapse-item {{ request()->routeIs('services.index') ? 'active' : '' }}" href="{{ route('services.index') }}">Danh sách dịch vụ</a>
                        <a class="collapse-item" href="#">Dịch vụ sinh viên</a>
                    </div>
                </div>
            </li>

            <!-- Báo cáo & Phản hồi -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Báo cáo & Phản hồi</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports" aria-expanded="true" aria-controls="collapseReports">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Thống kê</span>
                </a>

                <div id="collapseReports" class="collapse {{ request()->routeIs('report.*') ? 'show' : '' }}" aria-labelledby="headingReports" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Báo cáo:</h6>
                        <a class="collapse-item {{ request()->routeIs('report.index') ? 'active' : '' }}" href="{{ route('report.index') }}">Tổng quan</a>
                        <a class="collapse-item {{ request()->routeIs('report.utilities') ? 'active' : '' }}" href="{{ route('report.utilities') }}">Điện nước</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('feedbacks.index') }}">
                    <i class="fas fa-fw fa-comment"></i>
                    <span>Phản hồi</span>
                </a>
            </li>

            <!-- Cài đặt -->
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Cài đặt</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAccount" aria-expanded="true" aria-controls="collapseAccount">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Trung tâm tài khoản</span>
                </a>
                <div id="collapseAccount" class="collapse {{ request()->routeIs('staff.profile') || request()->routeIs('staff.password.form') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Quyền riêng tư:</h6>
                        <a class="collapse-item {{ request()->routeIs('staff.profile') ? 'active' : '' }}" href="{{ route('staff.profile') }}">Thông tin cá nhân</a>
                        <a class="collapse-item {{ request()->routeIs('staff.password.form') ? 'active' : '' }}" href="{{ route('staff.password.form') }}">Đổi mật khẩu</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @php
                                    $staff = Auth::user()->staff;
                                    $image = $staff->image === 'default_profile.jpg' ? asset('images/profiles/default_profile.jpg') : asset('images/profiles/staff/' . $staff->image);
                                @endphp
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $staff->full_name }}</span>
                                <img class="img-profile rounded-circle" src="{{ $image }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('staff.profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Thông tin cá nhân
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Đăng xuất
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Ký Túc Xá 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận đăng xuất?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">Bạn có chắc chắn đăng xuất.</div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary" href="login.html">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript -->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages -->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Thêm script tùy chỉnh nếu cần -->
    @stack('scripts')
</body>
</html>