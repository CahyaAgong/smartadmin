<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-teal">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link navbar-teal">
    <img src="{{ asset('img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ env('NAMA_APP') }}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host observed"><div class="os-resize-observer" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 609px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{route('home')}}" class="nav-link <?php if(Request::segment(1) == 'home'){ ?> active <?php } ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>
              Beranda
            </p>
          </a>
        </li>
        <!-- Manajemen Menu -->
        <li class="nav-header">Manajemen</li>
        <li class="nav-item has-treeview <?php if(Request::segment(1) == 'agenda'
         || Request::segment(1) == 'slider'
         || Request::segment(1) == 'video'
         || Request::segment(1) == 'pengumuman'
         || Request::segment(1) == 'foto'
         || Request::segment(1) == 'runningtext' ){ ?> menu-open <?php } ?>">
          <a href="#" class="nav-link <?php if(Request::segment(1) == 'agenda'
           || Request::segment(1) == 'slider'
           || Request::segment(1) == 'video'
           || Request::segment(1) == 'pengumuman'
           || Request::segment(1) == 'foto'
           || Request::segment(1) == 'runningtext' ){ ?> active <?php } ?>">
            <i class="nav-icon fas fa-tv"></i>
            <p>
              Konten SmartTv
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('agenda')}}" class="nav-link <?php if(Request::segment(1) == 'agenda'){?> active <?php } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Agenda</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index2.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Gambar Slider</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index3.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Video</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('pengumuman')}}" class="nav-link <?php if(Request::segment(1) == 'pengumuman'){?> active <?php } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Pengumuman</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index3.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Foto</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('runningtext')}}" class="nav-link <?php if(Request::segment(1) == 'runningtext'){?> active <?php } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Running Text</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-wrench"></i>
            <p>
              Setting Apps
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./index.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Nama Aplikasi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index2.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Domisili / Tempat</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index3.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Alamat</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index3.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Logo</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Akun Menu -->
        <li class="nav-header">Akun</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Akun Anda
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-address-card"></i>
            <p>
              Status Langganan
            </p>
          </a>
        </li>


        <?php
        if (Auth::user()->Is_Admin == 1) { ?>
          <!-- Laporan Menu -->
          <li class="nav-header">Laporan</li>
          <li class="nav-item">
            <a href="{{route('account')}}" class="nav-link <?php if(Request::segment(1) == 'account'){?> active <?php } ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Akun Terdaftar
              </p>
            </a>
          </li>
      <?php } ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 48.4897%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
  <!-- /.sidebar -->
</aside>
