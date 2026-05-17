<?php
/**
 * view/stok/index.php
 * Dashboard Manajemen Stok
 *
 * Data dari stok_index():
 *   $ringkasan       → object ringkasan stok (total_produk, stok_normal, total_menipis, total_habis)
 *   $stok_menipis    → array produk dengan stok menipis (maks 10)
 *   $stok_habis      → array produk dengan stok habis
 *   $riwayat_terbaru → array 10 riwayat stok terbaru
 */
?>

<!-- Page Heading -->
 
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    
    <div>
        <a href="<?= base_url('stok/masuk') ?>" class="btn btn-success btn-sm shadow-sm">
            <i class="fas fa-plus-circle fa-sm"></i> Stok Masuk
        </a>
        <a href="<?= base_url('stok/keluar') ?>" class="btn btn-danger btn-sm shadow-sm ml-1">
            <i class="fas fa-minus-circle fa-sm"></i> Stok Keluar
        </a>
        <a href="<?= base_url('stok/riwayat') ?>" class="btn btn-primary btn-sm shadow-sm ml-1">
            <i class="fas fa-file-alt fa-sm"></i> Riwayat
        </a>
    </div>
</div>

<!-- Flash message sudah ditangani oleh layouts/main.php -->
<!-- (success, error, warning) tidak perlu diulang di sini -->

<!-- =============================================
     RINGKASAN STOK
     Sumber: $ringkasan dari Stok_model->ringkasan_stok()
     Properti yang diharapkan dari model:
       - total_produk, stok_normal, total_menipis, total_habis
     ============================================= -->
<div class="row mb-4">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Produk
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $ringkasan->total_produk ?? 0 ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Stok Normal
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $ringkasan->stok_normal ?? 0 ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Stok Menipis
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $ringkasan->total_menipis ?? 0 ?>
                        </div>
                        <small class="text-muted">Di bawah batas minimum</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Stok Habis
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= $ringkasan->total_habis ?? 0 ?>
                        </div>
                        <small class="text-muted">Perlu restock segera</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- =============================================
     BARIS KEDUA: Stok Menipis + Stok Habis
     (dua variabel terpisah dari controller)
     ============================================= -->
<div class="row">

    <!-- Stok Menipis — $stok_menipis dari ambil_stok_menipis(10) -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-warning">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-exclamation-triangle"></i> Stok Menipis
                </h6>
                <span class="badge badge-light">
                    <?= count($stok_menipis ?? []) ?> produk
                </span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($stok_menipis)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Min</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stok_menipis as $p): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($p->name) ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning">
                                            <?= number_format($p->stock) ?> <?= htmlspecialchars($p->unit) ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-muted">
                                        <?= number_format($p->min_stock ?? 0) ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- Sesuai stok_masuk(): product_id via query string -->
                                        <a href="<?= base_url('stok/masuk') ?>?product_id=<?= $p->id ?>"
                                           class="btn btn-success btn-xs" title="Restock">
                                            <i class="fas fa-plus"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                        Semua stok dalam kondisi aman.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stok Habis — $stok_habis dari ambil_stok_habis() -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-danger">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-times-circle"></i> Stok Habis
                </h6>
                <span class="badge badge-light">
                    <?= count($stok_habis ?? []) ?> produk
                </span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($stok_habis)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga Beli</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stok_habis as $p): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($p->name) ?></strong>
                                        <br>
                                        <span class="badge badge-danger">
                                            Stok: 0 <?= htmlspecialchars($p->unit) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($p->buy_price)): ?>
                                            Rp <?= number_format($p->buy_price, 0, ',', '.') ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('stok/masuk') ?>?product_id=<?= $p->id ?>"
                                           class="btn btn-success btn-xs" title="Restock">
                                            <i class="fas fa-plus"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-smile fa-2x text-success mb-2 d-block"></i>
                        Tidak ada produk yang stoknya habis.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- =============================================
     RIWAYAT TERBARU
     Sumber: $riwayat_terbaru dari ambil_riwayat(NULL, NULL, 10)
     Filter riwayat: /stok/riwayat?produk_id=&tipe=
     Tipe: 'in' (masuk) atau 'out' (keluar)
     ============================================= -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-info">
            <i class="fas fa-history"></i> Riwayat Pergerakan Stok Terbaru
        </h6>
        <div>
            <!-- Shortcut filter ke halaman riwayat, sesuai stok_riwayat() -->
            <a href="<?= base_url('stok/masuk') ?>?tipe=in"
               class="btn btn-success btn-xs mr-1">
                <i class="fas fa-arrow-up"></i> Masuk
            </a>
            <a href="<?= base_url('stok/keluar') ?>?tipe=out"
               class="btn btn-danger btn-xs mr-1">
                <i class="fas fa-arrow-down"></i> Keluar
            </a>
            <a href="<?= base_url('stok/riwayat') ?>" class="btn btn-info btn-sm">
                Lihat Semua <i class="fas fa-arrow-right fa-sm"></i>
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="18%">Tanggal</th>
                        <th>Produk</th>
                        <th width="10%" class="text-center">Tipe</th>
                        <th width="14%" class="text-center">Jumlah</th>
                        <th width="14%" class="text-center">Stok Akhir</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($riwayat_terbaru)): ?>
                        <?php foreach ($riwayat_terbaru as $r): ?>
                        <tr>
                            <td>
                                <small class="text-muted">
                                    <?= date('d M Y H:i', strtotime($r->created_at)) ?>
                                </small>
                            </td>
                            <td>
                                <a href="<?= base_url('stok/riwayat') ?>?produk_id=<?= $r->product_id ?>"
                                   class="text-dark">
                                    <strong><?= htmlspecialchars($r->product_name ?? '-') ?></strong>
                                </a>
                            </td>
                            <td class="text-center">
                                <!-- type: 'in' = masuk, 'out' = keluar -->
                                <?php if ($r->type === 'in'): ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-arrow-up"></i> Masuk
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-danger">
                                        <i class="fas fa-arrow-down"></i> Keluar
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center font-weight-bold
                                <?= $r->type === 'in' ? 'text-success' : 'text-danger' ?>">
                                <?= $r->type === 'in' ? '+' : '−' ?>
                                <?= number_format($r->quantity) ?>
                                <?= htmlspecialchars($r->unit ?? '') ?>
                            </td>
                            <td class="text-center">
                                <?= number_format($r->stock_after) ?>
                                <?= htmlspecialchars($r->unit ?? '') ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= htmlspecialchars($r->notes ?? '-') ?>
                                </small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Belum ada riwayat pergerakan stok.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>