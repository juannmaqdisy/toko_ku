<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card shadow mb-4">

    <!-- Header -->
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-success">
            <i class="fas fa-plus-circle"></i> Form Stok Masuk
        </h6>

        <a href="<?= base_url('stok') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Body -->
    <div class="card-body">

        <!-- Error validation -->
        <?php if(validation_errors()): ?>
            <div class="alert alert-danger">
                <?= validation_errors(); ?>
            </div>
        <?php endif; ?>

        <?= form_open('stok/masuk', ['id' => 'formStokMasuk']) ?>

        <div class="row">

            <!-- Pilih Produk -->
            <div class="col-md-6">
                <div class="form-group">
                    <?= form_label('Pilih Produk', 'product_id', ['class' => 'font-weight-bold']) ?>
                    <select name="product_id" id="product_id" class="form-control select2" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($produk as $p): ?>
                            <option value="<?= $p->id ?>"
                                    data-stok="<?= $p->stock ?>"
                                    data-satuan="<?= $p->unit ?>"
                                    data-harga="0">
                                <?= htmlspecialchars($p->name) ?> (Stok: <?= $p->stock ?> <?= $p->unit ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">Stok saat ini: <strong id="infoStok">-</strong></small>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Jumlah Stok Masuk</strong></label>
                    <input type="number"
                           name="quantity"
                           id="quantity"
                           class="form-control"
                           min="1"
                           required
                           placeholder="Masukkan jumlah stok">
                    <small class="form-text text-muted">Estimasi total stok: <strong id="estimasiStok">-</strong></small>
                    <span id="satuan" class="d-none">-</span>
                </div>
            </div>

        </div>

        <div class="row">

            <!-- Harga Beli -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Harga Beli (Opsional)</strong></label>
                    <input type="number"
                           name="price"
                           id="price"
                           class="form-control"
                           placeholder="Masukkan harga beli">
                </div>
            </div>

            <!-- Keterangan -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Keterangan</strong></label>
                    <input type="text"
                           name="notes"
                           class="form-control"
                           placeholder="Contoh: Restock supplier">
                </div>
            </div>

        </div>

        <hr>

        <!-- Tombol -->
        <div class="text-right">
            <a href="<?= base_url('stok') ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>

        <?= form_close() ?>

    </div>
</div>

<!-- Script -->
<script>
$(document).ready(function() {
    // Update info saat produk dipilih
    $('#product_id').on('change', function() {
        var selected = $(this).find('option:selected');
        var stok = selected.data('stok');
        var satuan = selected.data('satuan');
        var harga = selected.data('harga');

        // Update info stok
        $('#infoStok').text(stok + ' ' + satuan);
        $('#satuan').text(satuan);

        // Update harga beli terakhir
        if (harga > 0) {
            $('#price').val(harga);
        }

        // Hitung estimasi
        hitungEstimasi();
    });

    // Hitung estimasi saat jumlah berubah
    $('#quantity').on('input', function() {
        hitungEstimasi();
    });

    function hitungEstimasi() {
        var stokSaatIni = parseInt($('#product_id').find('option:selected').data('stok')) || 0;
        var jumlahMasuk = parseInt($('#quantity').val()) || 0;
        var satuan = $('#satuan').text();   

        if (satuan === '-') {
            satuan = 'unit';
        }

        var totalStok = stokSaatIni + jumlahMasuk;
        $('#estimasiStok').text(totalStok + ' ' + satuan);
    }
});
</script>   