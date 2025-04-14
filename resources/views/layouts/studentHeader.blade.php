<header>
        <div class="logo">Ký Túc Xá</div>
        <nav>
            <div class="hamburger"><i class="fas fa-bars"></i></div>
            <ul>
                <li><a href="{{ route('student.room_booking') }}">Loại phòng</a></li>
                <li><a href="{{ route('student.service_booking') }}">Dịch vụ</a></li>
                <li><a href="{{ route('student.facility_repair') }}">Sửa chữa cơ sở vật chất</a></li>
                <li><a href="{{ route('student.pay') }}">Thanh toán</a></li>
                <li><a href="{{ route('chat.show') }}">Chat</a></li>
                <li>
                    @php
                        $student = Auth::user()->student;
                    @endphp
                    <img src="{{ $student->image === 'default_profile.jpg' ? asset('images/profiles/default_profile.jpg') : asset('images/profiles/students/' . $student->image) }}" 
                     alt="Avatar" class="avatar" onclick="toggleDropdown()">
                    <div class="dropdown" id="dropdownMenu">
                        <a href="{{ route('student.profile') }}"><i class="fas fa-user fa-fw mr-2"></i> Thông tin cá nhân</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-fw mr-2"></i> Đăng xuất
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
</header>