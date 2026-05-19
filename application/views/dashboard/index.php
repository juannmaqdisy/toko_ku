<div class="container-fluid">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sb-admin-2.css'); ?>">
    <!-- Judul Halaman -->
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <!-- Kartu Statistik -->
    <div class="row">

        <!-- Total Kategori -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Kategori
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $total_kategori ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Produk 1
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $total_produk ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Pesan Selamat Datang -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5>Selamat datang !</h5>
            <p class="text-muted">Anda login sebagai user</p>
        </div>
    </div>

</div>