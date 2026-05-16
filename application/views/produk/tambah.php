<?php
/**
 * tambah.php - Form Tambah Produk
 */
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sb-admin-2.css'); ?>">
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle"></i> Form Tambah Produk Baru
                </h6>
            </div>

            <div class="card-body">

                <!-- Info penting tentang stok -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> Stok awal produk akan diset ke <strong>0 (nol)</strong>.
                    Setelah disimpan, silakan tambahkan stok melalui menu <strong>Stok Masuk</strong>.
                </div>

                <!-- Form dengan enctype untuk upload file -->
                <?= form_open_multipart('produk/tambah', ['id' => 'formTambahProduk']) ?>

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-8">

                            <!-- Kategori -->
                            <div class="form-group">
                                <?= form_label('Kategori', 'category_id', ['class' => 'font-weight-bold']) ?>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k->id ?>">
                                        <?= htmlspecialchars($k->name) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('category_id', '<small class="text-danger">', '</small>') ?>
                            </div>

                            <!-- Nama Produk -->
                            <div class="form-group">
                                <?= form_label('Nama Produk', 'name', ['class' => 'font-weight-bold']) ?>
                                <?= form_input([
                                    'name' => 'name',
                                    'id' => 'name',
                                    'class' => 'form-control',
                                    'placeholder' => 'Contoh: Indomie Goreng',
                                    'required' => 'required',
                                    'autofocus' => 'autofocus'
                                ]) ?>
                                <?= form_error('name', '<small class="text-danger">', '</small>') ?>
                            </div>

                            <!-- Deskripsi -->
                            <div class="form-group">
                                <?= form_label('Deskripsi', 'description', ['class' => 'font-weight-bold']) ?>
                                <?= form_textarea([
                                    'name' => 'description',
                                    'id' => 'description',
                                    'class' => 'form-control',
                                    'rows' => 3,
                                    'placeholder' => 'Jelaskan produk ini...'
                                ]) ?>
                            </div>

                            <!-- Barcode -->
                            <div class="form-group">
                                <?= form_label('Barcode (Opsional)', 'barcode', ['class' => 'font-weight-bold']) ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                    </div>
                                    <?= form_input([
                                        'name' => 'barcode',
                                        'id' => 'barcode',
                                        'class' => 'form-control',
                                        'placeholder' => 'Scan atau ketik kode barcode'
                                    ]) ?>
                                </div>
                                <small class="form-text text-muted">
                                    Kosongkan jika produk tidak punya barcode.
                                </small>
                                <?= form_error('barcode', '<small class="text-danger">', '</small>') ?>
                            </div>

                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-4">

                            <!-- Upload Gambar -->
                            <div class="form-group">
                                <?= form_label('Gambar Produk', 'image', ['class' => 'font-weight-bold']) ?>
                                <div class="custom-file">
                                    <?= form_upload([
                                        'name' => 'image',
                                        'id' => 'image',
                                        'class' => 'custom-file-input'
                                    ]) ?>
                                    <?= form_label('Pilih Gambar...', 'image', ['class' => 'custom-file-label']) ?>
                                </div>
                                <small class="form-text text-muted">    
                                    Format: JPG, PNG, GIF, JPEG, WEBP. Maks: 2MB.
                                </small>
                                <div id="preview_gambar" class="mt-2 text-center"></div>
                            </div>

                            <hr>

                            <!-- Harga Beli -->
                            <div class="form-group">
                                <?= form_label('Harga Beli', 'buy_price', ['class' => 'font-weight-bold']) ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <?= form_input([
                                        'name' => 'buy_price',
                                        'id' => 'buy_price',
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'min' => '0',
                                        'step' => '100',
                                        'placeholder' => '0',
                                        'required' => 'required'
                                    ]) ?>
                                </div>
                                <small class="form-text text-muted">
                                    Harga beli dari supplier (untuk hitung laba)
                                </small>
                                <?= form_error('buy_price', '<small class="text-danger">', '</small>') ?>
                            </div>

                            <!-- Harga Jual -->
                            <div class="form-group">
                                <?= form_label('Harga Jual', 'sell_price', ['class' => 'font-weight-bold']) ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <?= form_input([
                                        'name' => 'sell_price',
                                        'id' => 'sell_price',
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'min' => '0',
                                        'step' => '100',
                                        'placeholder' => '0',
                                        'required' => 'required'
                                    ]) ?>
                                </div>
                                <?= form_error('sell_price', '<small class="text-danger">', '</small>') ?>
                            </div>

                            <!-- Satuan -->
                            <div class="form-group">
                                <?= form_label('Satuan', 'unit', ['class' => 'font-weight-bold']) ?>
                                <?= form_input([
                                    'name' => 'unit',
                                    'id' => 'unit',
                                    'class' => 'form-control',
                                    'placeholder' => 'Contoh: pcs, dus, kg',
                                    'value' => 'pcs'
                                ]) ?>
                                <small class="form-text text-muted">
                                    Default: pcs (pieces/potong)
                                </small>
                            </div>

                            <!-- Info Stok (Readonly) -->
                            <div class="form-group">
                                <?= form_label('Stok Awal', 'stock', ['class' => 'font-weight-bold']) ?>
                                <?= form_input([
                                    'name' => 'stock',
                                    'class' => 'form-control',
                                    'value' => '0',
                                    'readonly' => 'readonly',
                                    'style' => 'background-color: #e9ecef;'
                                ]) ?>
                                <small class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Stok awal = 0. Tambah lewat menu Stok Masuk.
                                </small>
                            </div>

                        </div>
                    </div>

                    <hr>

                    <!-- Tombol Aksi -->
                    <div class="form-group">
                        <?= form_submit('submit', 'Simpan Produk', 'class="btn btn-primary btn-block"') ?>
                        <a href="<?= base_url('produk/tambah') ?>" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>

<!-- Script untuk preview gambar -->
<script>
$(document).ready(function() {
    // Preview gambar saat dipilih
    $('#image').on('change', function() {
        var file = this.files[0];

        if (file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview_gambar').html(
                    '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">'
                );
            }

            reader.readAsDataURL(file);
        }
    });

    // Hitung estimasi laba
    $('#buy_price, #sell_price').on('input', function() {
        var harga_beli = parseFloat($('#buy_price').val()) || 0;
        var harga_jual = parseFloat($('#sell_price').val()) || 0;

        if (harga_jual > harga_beli) {
            var laba = harga_jual - harga_beli;
            var persen = (laba / harga_beli * 100).toFixed(1);

            // Bisa ditampilkan di UI jika perlu
        }
    });
});
</script>