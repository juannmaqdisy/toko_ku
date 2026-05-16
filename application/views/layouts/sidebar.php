<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="<?= base_url('dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Toko Online</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading: Master -->
    <div class="sidebar-heading">
        Master Data
    </div>

    <!-- Nav Item - Kategori -->
    
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('kategori') ?>">
            <i class="fas fa-fw fa-folder"></i>
            <span>Kategori</span>
        </a>
    </li>

    <!-- Nav Item - Produk -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('produk') ?>">
            <i class="fas fa-fw fa-box"></i>
            <span>Produk</span>
        </a>
    </li>

    <!-- Nav Item - Stok -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('stok') ?>">
            <i class="fas fa-fw fa-warehouse"></i>
            <span>Stok</span>
        </a>
    </li>

    <!-- Nav Item - User -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('user') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>User</span>
        </a>
    </li>
    

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading: Transaksi -->
    <div class="sidebar-heading">
        Transaksi
    </div>

    <!-- Nav Item - POS -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('transaction/pos') ?>">
            <i class="fas fa-fw fa-cash-register"></i>
            <span>Kasir / POS</span>
        </a>
    </li>

    <!-- Nav Item - Riwayat Transaksi -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('transaction/history') ?>">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading: Laporan -->
    
    <div class="sidebar-heading">
        Laporan
    </div>

    <!-- Nav Item - Laporan Penjualan -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('report/sales') ?>">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Penjualan</span>
        </a>
    </li>

    <!-- Nav Item - Laporan Laba -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('report/profit') ?>">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Laba Rugi</span>
        </a>
    </li>
    

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->