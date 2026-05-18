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
                    <i class="fas fa-plus-circle"></i> Form Edit Produk 
                </h6>
            </div>

            <div class="card-body">


                <!-- Form dengan enctype untuk upload file -->
                <?= form_open_multipart('produk/edit/'. $produk->id, ['id' => 'formEditProduk']) ?>

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-8">

                            <!-- Kategori -->
                            <div class="form-group">
                                <label class="font-weight-bold">Kategori</label>
                                <select name="category_id"
                                        class="form-control"
                                        required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach($kategori as $k): ?>
                                    <option value="<?= $k->id ?>"
                                        <?= $produk->category_id == $k->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($k->name) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('category_id',
                                    '<small class="text-danger">',
                                    '</small>'); ?>
                            </div>

                            <!-- Nama Produk -->
                            <div class="form-group">
                                <?= form_label('Nama Produk', 'name', ['class' => 'font-weight-bold']) ?>
                                <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="<?= htmlspecialchars($produk->name) ?>"
                                   required>
                                
                                <?= form_error('name', '<small class="text-danger">', '</small>') ?>
                            </div>

                            <!-- Deskripsi -->
                            <div class="form-group">
                                <?= form_label('Deskripsi', 'description', ['class' => 'font-weight-bold']) ?>
                                <textarea name="description"
                                      class="form-control"
                                      rows="4"><?= htmlspecialchars($produk->description) ?></textarea>
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
                                   <input type="text"
                                          name="barcode"
                                          class="form-control"
                                          value="<?= htmlspecialchars($produk->barcode) ?>">
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

                            <label class="font-weight-bold">
                                Gambar Produk
                            </label>
                            <div class="mb-3 text-center">
                                <?php if($produk->image): ?>
                                    <img src="<?= base_url('uploads/products/' . $produk->image) ?>"
                                         class="img-thumbnail"
                                         style="max-width:200px;">
                                <?php else: ?>
                                    <div class="text-muted">
                                        Tidak ada gambar
                                    </div>
                                <?php endif; ?>
                            </div>
                        
                            <?= form_label('Pilih Gambar...', 'image', ['class' => 'custom-file-label']) ?>
                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti gambar.
                            </small>
                        </div>


                            <hr>

                            <!-- Harga Beli -->
                            <div class="form-group">
                                <?= form_label('Harga Beli', 'buy_price', ['class' => 'font-weight-bold']) ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input  type="number"
                                            name="buy_price"
                                            class="form-control"
                                            value="<?= $produk->buy_price ?>"
                                            required>

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
                                   <input   type="number"
                                            name="sell_price"
                                            class="form-control"
                                            value="<?= $produk->sell_price ?>"
                                            required>
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
                    <button type="submit"
                        class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i>
                        Update Produk
                    </button>
                    <a href="<?= base_url('produk') ?>"
                    class="btn btn-outline-secondary btn-block">
                        Kembali
                    </a>
                    <?= form_close(); ?>

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