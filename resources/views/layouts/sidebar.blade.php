<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #00008C">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/dashboard') }}">
        <div class="sidebar-brand">
            <!-- Ganti ikon dengan gambar -->
            <img src="{{ asset('pupr.png') }}" alt="BWSK Logo" style="width: 40px; height: auto;">
        </div>
        <div class="sidebar-brand-text mx-3 mt-3 font-weight-bold">BWSK I Pontianak</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Data Peminjaman -->
    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="{{ route('peminjaman.index') }}">
            <i class="fas fa-fw fa-archive"></i>
            <span>Peminjaman Barang</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="{{ route('ruangan.peminjaman') }}">
            <i class="fas fa-fw fa-archive"></i>
            <span>Peminjaman Ruangan</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="{{ route('jaringan.admin') }}">
            <i class="fas fa-fw fa-wifi"></i> <!-- You can choose a different icon here -->
            <span>Pengaduan Jaringan</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <!-- Nav Item - Data Barang -->
    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="{{ route('barang.index') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Data Barang</span>
        </a>
    </li>
    <!-- Nav Item - Kategori Barang -->
    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="{{ route('kategori_barang.index') }}">
            <i class="fas fa-fw fa-list"></i> <!-- You can choose a different icon here -->
            <span>Kategori Barang</span>
        </a>
    </li>
    <!-- Nav Item - Data Ruangan -->
    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="{{ route('ruangan.index') }}">
            <i class="fas fa-fw fa-building"></i>
            <span>Data Ruangan</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="{{ route('user.index') }}" aria-expanded="true">
            <i class="fas fa-fw fa-user"></i>
            <span>User</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
