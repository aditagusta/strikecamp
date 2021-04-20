<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a><i class="fa fa-database"></i> Data Approve <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('approve_order') }}">Order Paket</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-database"></i> Data Master <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('banner') }}">Data Banner</a></li>
                    <li><a href="{{ route('katalog') }}">Data Katalog</a></li>
                    <li><a href="{{ route('cabang') }}">Data Cabang</a></li>
                    <li><a href="{{ route('user') }}">Data Member Cabang</a></li>
                </ul>
            </li>
            <li><a href="{{route('contact')}}"><i class="fa fa-home"></i> Contact Cabang</a>
            </li>
        </ul>
    </div>


</div>
