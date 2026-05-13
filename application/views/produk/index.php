<?php
/**
 * index.php - Daftar Produk
 */
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sb-admin-2.css'); ?>">
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-box"></i> Daftar Produk
        </h6>

        <a href="<?= base_url('produk/tambah/') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>

    <div class="card-body">
        <!-- Info stok menipis -->
        <?php
        $this->data['stok_menipis'] = $this->Produk_model->ambil_stok_menipis(10);
        if (!empty($stok_menipis)):
        ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Peringatan Stok!</strong>
            Ada <?= count($stok_menipis) ?> produk dengan stok menipis (≤ 10).
            <a href="#" class="alert-link">Lihat detail</a>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="80">Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-right">Harga Beli</th>
                        <th class="text-right">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($produk)): $no = 1; foreach ($produk as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <!-- Gambar Produk -->
                        <td class="text-center">
                            <?php if ($p->image): ?>
                            <img src="<?= base_url('uploads/products/' . $p->image) ?>"
                                 alt="<?= htmlspecialchars($p->name) ?>"
                                 class="img-thumbnail"
                                 style="max-width: 60px; max-height: 60px;">
                            <?php else: ?>
                            <span class="text-muted">
                                <i class="fas fa-image fa-2x"></i>
                            </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <strong><?= htmlspecialchars($p->name) ?></strong>
                            <?php if ($p->barcode): ?>
                            <br><small class="text-muted">
                                <i class="fas fa-barcode"></i> <?= $p->barcode ?>
                            </small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="badge badge-info">
                                <?= htmlspecialchars($p->category_name ?: 'Tanpa Kategori') ?>
                            </span>
                        </td>

                        <td class="text-right"><?= format_rupiah($p->buy_price) ?></td>

                        <td class="text-right">
                            <strong><?= format_rupiah($p->sell_price) ?></strong>
                        </td>

                        <td class="text-center">
                            <?php if ($p->stock <= 10): ?>
                            <span class="badge badge-danger"><?= $p->stock ?> <?= $p->unit ?></span>
                            <?php elseif ($p->stock <= 20): ?>
                            <span class="badge badge-warning"><?= $p->stock ?> <?= $p->unit ?></span>
                            <?php else: ?>
                            <span class="badge badge-success"><?= $p->stock ?> <?= $p->unit ?></span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <a href="<?= base_url('produk/edit/' . $p->id) ?>"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <button onclick="hapus_produk(<?= $p->id ?>, '<?= htmlspecialchars($p->name) ?>')"
                                    class="btn btn-danger btn-sm" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <i class="fas fa-box-open fa-2x"></i><br>
                            Belum ada data produk.<br>
                            <a href="<?= base_url('produk/tambah/') ?>">Tambah produk baru?</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']],  // Sort by stok descending
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });
});

function hapus_produk(id, nama) {
    Swal.fire({
        title: 'Hapus Produk',
        text: 'Apakah Anda yakin ingin menghapus produk "' + nama + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('produk/hapus/') ?>' + id;
        }
    });
}
</script>