<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card shadow mb-4">

    <!-- Header -->
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-danger">
            <i class="fas fa-minus-circle"></i> Form Stok Keluar
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

        <!-- SINKRON: URL ke stok/keluar & ID form menjadi formKeluar -->
        <?= form_open('stok/keluar', ['id' => 'formKeluar']) ?>

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
                                    data-satuan="<?= $p->unit ?>">
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
                    <label><strong>Jumlah Stok Keluar</strong></label>
                    <input type="number"
                           name="quantity"
                           id="quantity"
                           class="form-control"
                           min="1"
                           required
                           placeholder="Masukkan jumlah stok keluar">
                    <small class="form-text text-muted">Estimasi sisa stok: <strong id="estimasiStok">-</strong></small>
                    <span id="satuan" class="d-none">-</span>
                </div>
            </div>

        </div>

        <div class="row">

            <!-- Alasan Keluar -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Alasan Keluar</strong></label>
                    <select name="reason" id="reason" class="form-control" required>
                        <option value="">-- Pilih Alasan --</option>
                        <option value="Rusak">Rusak / Cacat</option>
                        <option value="Kadaluwarsa">Kadaluwarsa (Expired)</option>
                        <option value="Koreksi Stok">Koreksi / Opname Stok</option>
                        <option value="Dipakai Sendiri">Dipakai Internal</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan Tambahan -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Keterangan Tambahan (Opsional)</strong></label>
                    <input type="text"
                           name="notes"
                           class="form-control"
                           placeholder="Contoh: Nomor dokumen atau detail kerusakan">
                </div>
            </div>

        </div>

        <hr>

        <!-- Tombol -->
        <div class="text-right">
            <a href="<?= base_url('stok') ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>

            <button type="submit" class="btn btn-danger">
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

        if ($(this).val() !== "") {
            $('#infoStok').text(stok + ' ' + satuan);
            $('#satuan').text(satuan);
            $('#quantity').attr('max', stok);
        } else {
            $('#infoStok').text('-');
            $('#satuan').text('-');
            $('#quantity').removeAttr('max');
        }

        hitungEstimasi();
    });

    $('#quantity').on('input', function() {
        hitungEstimasi();
    });

    function hitungEstimasi() {
        var stokSaatIni = parseInt($('#product_id').find('option:selected').data('stok')) || 0;
        var jumlahKeluar = parseInt($('#quantity').val()) || 0;
        var satuan = $('#satuan').text();   

        if (satuan === '-') {
            satuan = 'unit';
        }

        var totalStok = stokSaatIni - jumlahKeluar;
        
        if (totalStok < 0) {
            $('#estimasiStok').html('<span class="text-danger">Stok tidak mencukupi (Sisa: ' + totalStok + ' ' + satuan + ')</span>');
        } else {
            $('#estimasiStok').text(totalStok + ' ' + satuan);
        }
    }

    // SINKRON: Menggunakan ID #formKeluar agar validasi submit berfungsi
    $('#formKeluar').on('submit', function(e) {
        var stokSaatIni = parseInt($('#product_id').find('option:selected').data('stok')) || 0;
        var jumlahKeluar = parseInt($('#quantity').val()) || 0;

        if (jumlahKeluar > stokSaatIni) {
            alert('Gagal! Jumlah stok keluar tidak boleh melebihi stok yang tersedia saat ini.');
            e.preventDefault();
        }
    });
});
</script>