<?php
/**
 * index.php - View untuk menampilkan daftar kategori
 *
 * File ini menampilkan:
 * 1. Judul halaman
 * 2. Tombol tambah kategori
 * 3. Tabel daftar kategori dengan DataTables
 * 4. Tombol aksi (edit, hapus) untuk setiap kategori
 */

// Card container untuk tampilan yang rapi
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sb-admin-2.css'); ?>">
<div class="card shadow mb-4">

    <!-- Card Header: Judul dan tombol tambah -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-folder"></i> Daftar Kategori
        </h6>

        <!-- Tombol tambah kategori baru -->
        <a href="<?= base_url('kategori/tambah') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
    <!-- End Card Header -->

    <!-- Card Body: Tabel kategori -->
    <div class="card-body">

        <!-- Container tabel responsive (bisa scroll horizontal di layar kecil) -->
        <div class="table-responsive">

            <!-- Tabel dengan ID dataTable untuk DataTables -->
            <table class="table table-bordered table-striped table-hover"
                   id="dataTable" width="100%" cellspacing="0">

                <!-- Header Tabel -->
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>           <!-- Nomor urut -->
                        <th>Nama Kategori</th>          <!-- Nama kategori -->
                        <th>Deskripsi</th>              <!-- Deskripsi kategori -->
                        <th width="100">Jumlah Produk</th> <!-- Hitungan produk -->
                        <th width="150">Aksi</th>        <!-- Tombol edit/hapus -->
                    </tr>
                </thead>

                <!-- Body Tabel -->
                <tbody>
                    <?php
                    // Cek apakah ada data kategori
                    if (!empty($kategori)):
                        // Inisialisasi nomor urut
                        $no = 1;

                        // Loop setiap kategori
                        foreach ($kategori as $kat):
                    ?>
                        <tr>
                            <!-- Nomor urut -->
                            <td><?= $no++ ?></td>

                            <!-- Nama kategori dengan htmlspecialchars untuk keamanan -->
                            <td>
                                <strong><?= htmlspecialchars($kat->name) ?></strong>
                            </td>

                            <!-- Deskripsi, kosongkan jika tidak ada -->
                            <td><?= htmlspecialchars($kat->description ?: '-') ?></td>

                            <!-- Jumlah produk dalam kategori ini -->
                            <td class="text-center">
                                <?php
                                // Hitung jumlah produk dalam kategori ini
                                $this->db->where('category_id', $kat->id);
                                $jumlah_produk = $this->db->count_all_results('products');

                                // Tampilkan dengan badge warna
                                if ($jumlah_produk > 0) {
                                    echo '<span class="badge badge-info">' . $jumlah_produk . ' produk</span>';
                                } else {
                                    echo '<span class="badge badge-secondary">0 produk</span>';
                                }
                                ?>
                            </td>

                            <!-- Tombol aksi -->
                            <td>
                                <!-- Tombol Edit -->
                                <a href="<?= base_url('kategori/edit/' . $kat->id) ?>"
                                
                                   class="btn btn-warning btn-sm"
                                   title="Edit Kategori">
                                    <i class="fas fa-edit">Edit</i>
                                </a>

                                <!-- Tombol Hapus dengan konfirmasi SweetAlert -->
                                <button onclick="hapus_kategori(<?= $kat->id ?>, '<?= htmlspecialchars($kat->name) ?>')"
                                        class="btn btn-danger btn-sm"
                                        title="Hapus Kategori">
                                    <i class="fas fa-trash">Hapus</i>
                                </button>
                            </td>
                        </tr>
                    <?php
                        endforeach;
                    else:
                        // Jika tidak ada data kategori
                    ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-folder-open"></i>
                                Belum ada data kategori.
                                <a href="<?= base_url('kategori/tambah') ?>">Tambah kategori baru?</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <!-- End Body Tabel -->

            </table>
            <!-- End Table -->

        </div>
        <!-- End Table Responsive -->

    </div>
    <!-- End Card Body -->

</div>
<!-- End Card -->

<!-- Script JavaScript untuk halaman ini -->
<script>
$(document).ready(function() {
    // Inisialisasi DataTables
    $('#dataTable').DataTable({
        responsive: true,              // Responsive untuk mobile
        pageLength: 25,                // Tampilkan 25 data per halaman
        language: {                    // Bahasa Indonesia
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });
});

/**
 * Fungsi JavaScript untuk menghapus kategori dengan konfirmasi SweetAlert
 *
 * @param int id        ID kategori yang akan dihapus
 * @param string nama   Nama kategori untuk ditampilkan di pesan konfirmasi
 */
function hapus_kategori(id, nama) {
    // Tampilkan dialog konfirmasi SweetAlert2
    Swal.fire({
        title: 'Hapus Kategori',
        text: 'Apakah Anda yakin ingin menghapus kategori "' + nama + '"? ' +
              'Semua produk dalam kategori ini juga akan terdampak.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',        // Warna tombol hapus (merah)
        cancelButtonColor: '#3085d6',      // Warna tombol batal (biru)
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        // Jika user mengkonfirmasi
        if (result.isConfirmed) {
            // Redirect ke fungsi hapus
            window.location.href = '<?= base_url('kategori/hapus/') ?>' + id;
        }
    });
}
</script>