<?php
/**
 * edit.php - View untuk form edit kategori
 *
 * File ini berisi form HTML untuk mengedit data kategori yang sudah ada
 * Form akan diisi dengan data kategori yang sudah ada
 */

// Pastikan data kategori tersedia
if (!isset($kategori)) {
    show_error('Data kategori tidak ditemukan!', 404);
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sb-admin-2.css'); ?>">
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">

            <!-- Card Header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-edit"></i> Edit Kategori: <?= htmlspecialchars($kategori->name) ?>
                </h6>
            </div>

            <!-- Card Body: Form Edit -->
            <div class="card-body">

                <!-- Info kategori yang sedang diedit -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Anda sedang mengedit kategori dengan ID: <strong><?= $kategori->id ?></strong>
                </div>

                <!-- Buka form -->
                <!-- Form akan di-submit ke fungsi yang sama (kategori_edit) dengan method POST -->
                <?= form_open('kategori/edit/' . $kategori->id, ['id' => 'formEditKategori']) ?>

                    <!-- Field: Nama Kategori -->
                    <div class="form-group">
                        <?= form_label('Nama Kategori', 'name', ['class' => 'font-weight-bold']) ?>

                        <!-- Input text dengan value dari database -->
                        <?= form_input([
                            'name' => 'name',
                            'id' => 'name',
                            'class' => 'form-control',
                            'value' => set_value('name', $kategori->name),  // Value dari database
                            'placeholder' => 'Contoh: Makanan, Minuman, dll',
                            'required' => 'required',
                            'autofocus' => 'autofocus',
                            'maxlength' => '100'
                        ]) ?>

                        <!-- Tampilkan pesan error validasi jika ada -->
                        <?= form_error('name', '<small class="text-danger"><i class="fas fa-exclamation-circle"></i> ', '</small>') ?>

                        <small class="form-text text-muted">
                            Nama kategori harus unik.
                        </small>
                    </div>

                    <!-- Field: Deskripsi -->
                    <div class="form-group">
                        <?= form_label('Deskripsi', 'description', ['class' => 'font-weight-bold']) ?>

                        <!-- Textarea dengan value dari database -->
                        <?= form_textarea([
                            'name' => 'description',
                            'id' => 'description',
                            'class' => 'form-control',
                            'value' => set_value('description', $kategori->description),
                            'rows' => 3,
                            'placeholder' => 'Jelaskan jenis produk dalam kategori ini...'
                        ]) ?>

                        <?= form_error('description', '<small class="text-danger">', '</small>') ?>
                    </div>

                    <hr>

                    <!-- Tombol Aksi -->
                    <div class="form-group">
                        <!-- Tombol Update -->
                        <?= form_submit('submit', 'Update Kategori', 'class="btn btn-warning btn-block"') ?>

                        <!-- Tombol Batal -->
                        <a href="<?= base_url('kategori') ?>" class="btn btn-outline-secondary btn-block">
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
    // Validasi form sebelum submit
    $('#formEditKategori').on('submit', function(e) {
        var namaKategori = $('#name').val().trim();

        if (namaKategori === '') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Nama kategori wajib diisi!',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        if (namaKategori.length < 3) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Nama kategori minimal 3 karakter!',
                confirmButtonColor: '#d33'
            });
            return false;
        }
    });
});
</script>