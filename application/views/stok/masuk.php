<?php
/**
 * masuk.php - Form Stok Masuk
 */
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">

            <div class="card-header py-3 bg-success">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-plus-circle"></i> Form Stok Masuk (Restock)
                </h6>
            </div>

            <div class="card-body">

                <!-- Info Petunjuk -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Pilih produk dan masukkan jumlah stok yang masuk. Harga beli bersifat opsional.
                </div>

                <?= form_open('stok/masuk', ['id' => 'formStokMasuk']) ?>

                    <!-- Pilih Produk -->
                    <div class="form-group">
                        <?= form_label('Pilih Produk', 'product_id', ['class' => 'font-weight-bold']) ?>
                        <select name="product_id" id="product_id" class="form-control select2" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produk as $p): ?>
                            <option value="<?= $p->id ?>"
                                    data-stok="<?= $p->stock ?>"
                                    data-satuan="<?= $p->unit ?>"
                                    data-harga="<?= $p->categories ?>">
                                <?= htmlspecialchars($p->name) ?>
                                (Stok: <?= $p->stock ?> <?= $p->unit ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">
                            Stok saat ini: <strong id="infoStok">-</strong>
                        </small>
                    </div>

                    <!-- Jumlah Stok Masuk -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label('Jumlah Masuk', 'quantity', ['class' => 'font-weight-bold']) ?>
                                <div class="input-group">
                                    <?= form_input([
                                        'name' => 'quantity',
                                        'id' => 'quantity',
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'min' => '1',
                                        'required' => 'required',
                                        'autofocus' => 'autofocus'
                                    ]) ?>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="satuan">-</span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Estimasi stok setelah masuk: <strong id="estimasiStok">-</strong>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label('Harga Beli (Opsional)', 'price', ['class' => 'font-weight-bold']) ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <?= form_input([
                                        'name' => 'price',
                                        'id' => 'price',
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'min' => '0',
                                        'step' => '100',
                                        'placeholder' => '0'
                                    ]) ?>
                                </div>
                                <small class="form-text text-muted">
                                    Isi untuk update harga beli rata-rata
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                        <?= form_label('Keterangan', 'notes', ['class' => 'font-weight-bold']) ?>
                        <?= form_textarea([
                            'name' => 'notes',
                            'class' => 'form-control',
                            'rows' => 2,
                            'placeholder' => 'Contoh: Restock dari supplier PT. ABC'
                        ]) ?>
                    </div>

                    <hr>

                    <!-- Tombol Aksi -->
                    <div class="form-group">
                        <?= form_submit('submit', 'Simpan Stok Masuk', 'class="btn btn-success btn-block"') ?>
                        <a href="<?= base_url('stok') ?>" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>

            </div>
        </div>
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
        var satuan = $('#satuan.text();

        if (satuan === '-') {
            satuan = 'unit';
        }

        var totalStok = stokSaatIni + jumlahMasuk;
        $('#estimasiStok').text(totalStok + ' ' + satuan);
    }
});
</script>