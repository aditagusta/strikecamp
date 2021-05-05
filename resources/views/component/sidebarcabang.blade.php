<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
            <li><a href="{{url('/home')}}"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a><i class="fa fa-database"></i> Data Master <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('bank')}}">Data Bank</a></li>
                    <li><a href="{{route('trainer')}}">Data Trainer</a></li>
                    <li><a href="{{route('jadwal')}}">Data Jadwal</a></li>
                    <li><a href="{{route('member')}}">Data Member</a></li>
                </ul>
            </li>
            <li><a href="{{route('profilecabang')}}"><i class="fa fa-user"></i> Profile</a>
            </li>
            <li><a href="{{route('order_paket')}}"><i class="fa fa-edit"></i> Entry Order Paket</a>
            </li>
            <li><a href="{{route('booking')}}"><i class="fa fa-edit"></i> Entry Booking</a>
            </li>
        </ul>
    </div>


</div>
