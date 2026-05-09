<?php
/**
 * tambah.php - View untuk form tambah kategori baru
 *
 * File ini berisi form HTML untuk input data kategori baru
 */

// Card container
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">

            <!-- Card Header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle"></i> Form Tambah Kategori Baru
                </h6>
            </div>

            <!-- Card Body: Form Input -->
            <div class="card-body">

                <!-- Buka form dengan helper form CodeIgniter -->
                <!-- Form akan di-submit ke: kategori/kategori_simpan -->
                <?= form_open('kategori/kategori_tambah', ['id' => 'formTambahKategori']) ?>

                    <!-- Field: Nama Kategori -->
                    <div class="form-group">
                        <!-- Label untuk field nama -->
                        <?= form_label('Nama Kategori', 'name', ['class' => 'font-weight-bold']) ?>

                        <!-- Input text untuk nama kategori -->
                        <?= form_input([
                            'name' => 'name',
                            'id' => 'name',
                            'class' => 'form-control',
                            'placeholder' => 'Contoh: Makanan, Minuman, Peralatan Mandi',
                            'required' => 'required',         // Wajib diisi
                            'autofocus' => 'autofocus',       // Otomatis fokus saat halaman dimuat
                            'maxlength' => '100'
                        ]) ?>

                        <!-- Tampilkan pesan error validasi jika ada -->
                        <?= form_error('name', '<small class="text-danger"><i class="fas fa-exclamation-circle"></i> ', '</small>') ?>

                        <!-- Petunjuk pengisian -->
                        <small class="form-text text-muted">
                            Nama kategori harus unik (tidak boleh sama dengan yang sudah ada).
                        </small>
                    </div>
                    <!-- End Field: Nama Kategori -->

                    <!-- Field: Deskripsi -->
                    <div class="form-group">
                        <!-- Label untuk field deskripsi -->
                        <?= form_label('Deskripsi', 'description', ['class' => 'font-weight-bold']) ?>

                        <!-- Textarea untuk deskripsi (boleh kosong) -->
                        <?= form_textarea([
                            'name' => 'description',
                            'id' => 'description',
                            'class' => 'form-control',
                            'rows' => 3,
                            'placeholder' => 'Jelaskan jenis produk dalam kategori ini...'
                        ]) ?>

                        <!-- Tampilkan pesan error validasi jika ada -->
                        <?= form_error('description', '<small class="text-danger">', '</small>') ?>
                    </div>
                    <!-- End Field: Deskripsi -->

                    <hr>

                    <!-- Tombol Submit dan Navigasi -->
                    <div class="form-group">
                        <!-- Tombol simpan (submit) -->
                        <?= form_submit('submit', 'Simpan Kategori', 'class="btn btn-success btn-block"') ?>

                        <!-- Tombol reset untuk mengosongkan form -->
                        <?= form_reset('reset', 'Reset', 'class="btn btn-secondary btn-block"') ?>

                        <!-- Tombol kembali ke daftar kategori -->
                        <a href="<?= base_url('kategori') ?>" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                    <!-- End Tombol -->

                <?= form_close() ?>
                <!-- Tutup Form -->

            </div>
            <!-- End Card Body -->

        </div>
        <!-- End Card -->
    </div>
</div>

<!-- Script untuk validasi tambahan di client side -->
<script>
$(document).ready(function() {
    // Validasi form sebelum submit
    $('#formTambahKategori').on('submit', function(e) {
        var namaKategori = $('#name').val().trim();

        // Cek apakah nama kategori kosong
        if (namaKategori === '') {
            e.preventDefault();              // Batalkan submit
            Swal.fire({                       // Tampilkan pesan error
                icon: 'error',
                title: 'Error',
                text: 'Nama kategori wajib diisi!',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        // Cek panjang nama kategori
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