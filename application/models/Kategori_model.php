<?php
/**
 * Kategori_model.php
 *
 * Model untuk mengelola data kategori produk
 * Semua fungsi menggunakan nama bahasa Indonesia agar mudah dipahami
 *
 * @author    SMK Assalafiyyah Sleman
 * @version   1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    // Nama tabel di database
    protected $tabel = 'categories';

    /**
     * ============================================================
     * FUNGSI: ambil_semua()
     * ============================================================
     * Mengambil semua data kategori dari database
     *
     * @return array    Object array berisi semua kategori
     *
     * Contoh penggunaan:
     * $kategori = $this->kategori_model->ambil_semua();
     */
    public function ambil_semua()
    {
        $this->db->select('
            categories.*,
            COUNT(products.id) as jumlah_produk
        ');

        $this->db->from($this->tabel);

        $this->db->join(
            'products',
            'products.category_id = categories.id',
            'left'
        );

        $this->db->group_by('categories.id');

        $this->db->order_by('categories.name', 'ASC');

        return $this->db->get()->result();
    }
    /**
     * ============================================================
     * FUNGSI: ambil_berdasarkan_id($id)
     * ============================================================
     * Mengambil satu data kategori berdasarkan ID
     *
     * @param int $id    ID kategori yang akan dicari
     * @return object    Object berisi data kategori, atau NULL jika tidak ditemukan
     *
     * Contoh penggunaan:
     * $kategori = $this->kategori_model->ambil_berdasarkan_id(5);
     */
    public function ambil_berdasarkan_id($id)
    {
        // Query: SELECT * FROM categories WHERE id = $id
        $query = $this->db->get_where($this->tabel, ['id' => $id]);

        // Kembalikan satu baris data (row)
        return $query->row();
    }

    /**
     * ============================================================
     * FUNGSI: tambah($data)
     * ============================================================
     * Menambahkan data kategori baru ke database
     *
     * @param array $data    Array berisi data kategori yang akan ditambahkan
     *                       Format: ['name' => 'Nama Kategori', 'description' => 'Deskripsi']
     * @return int           ID dari data yang baru ditambahkan (insert_id)
     *
     * Contoh penggunaan:
     * $data = ['name' => 'Makanan', 'description' => 'Produk makanan kering'];
     * $id = $this->kategori_model->tambah($data);
     */
    public function tambah($data)
    {
        // Insert data ke tabel categories
        $this->db->insert($this->tabel, $data);

        // Kembalikan ID dari data yang baru ditambahkan
        return $this->db->insert_id();
    }

    /**
     * ============================================================
     * FUNGSI: update($id, $data)
     * ============================================================
     * Mengupdate data kategori yang sudah ada
     *
     * @param int $id       ID kategori yang akan diupdate
     * @param array $data   Array berisi data kategori yang baru
     * @return bool         TRUE jika berhasil, FALSE jika gagal
     *
     * Contoh penggunaan:
     * $data = ['name' => 'Makanan Ringan', 'description' => 'Snack dan crackers'];
     * $hasil = $this->kategori_model->update(5, $data);
     */
    public function update($id, $data)
    {
        // Update data berdasarkan ID
        // Query: UPDATE categories SET name = ?, description = ? WHERE id = ?
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * ============================================================
     * FUNGSI: hapus($id)
     * ============================================================
     * Menghapus data kategori dari database
     *
     * PENTING: Fungsi ini mengecek apakah kategori sedang digunakan
     * oleh produk. Jika ya, maka tidak bisa dihapus.
     *
     * @param int $id    ID kategori yang akan dihapus
     * @return array     Array berisi status dan pesan
     *                   Format: ['status' => TRUE/FALSE, 'message' => 'Pesan']
     *
     * Contoh penggunaan:
     * $hasil = $this->kategori_model->hapus(5);
     * if ($hasil['status']) {
     *     echo "Berhasil dihapus";
     * } else {
     *     echo $hasil['message']; // "Kategori sedang digunakan oleh produk"
     * }
     */
    public function hapus($id)
    {
        // Cek apakah kategori digunakan oleh produk
        $this->db->where('category_id', $id);
        $jumlah_produk = $this->db->count_all_results('products');

        // Jika ada produk yang menggunakan kategori ini
        if ($jumlah_produk > 0) {
            return [
                'status' => FALSE,
                'message' => 'Kategori tidak bisa dihapus karena masih digunakan oleh ' . $jumlah_produk . ' produk.'
            ];
        }

        // Jika tidak digunakan, hapus kategori
        // Query: DELETE FROM categories WHERE id = ?
        $this->db->where('id', $id);
        $hasil = $this->db->delete($this->tabel);

        return [
            'status' => $hasil,
            'message' => $hasil ? 'Kategori berhasil dihapus.' : 'Gagal menghapus kategori.'
        ];
    }

    /**
     * ============================================================
     * FUNGSI: hitung_semua()
     * ============================================================
     * Menghitung jumlah total kategori
     *
     * @return int    Jumlah total kategori
     *
     * Contoh penggunaan:
     * $jumlah = $this->kategori_model->hitung_semua();
     * echo "Total kategori: " . $jumlah;
     */
    public function hitung_semua()
    {
        // Query: SELECT COUNT(*) FROM categories
        return $this->db->count_all($this->tabel);
    }

    /**
     * ============================================================
     * FUNGSI: cek_nama($nama, $id = NULL)
     * ============================================================
     * Mengecek apakah nama kategori sudah ada di database
     *
     * @param string $nama    Nama kategori yang akan dicek
     * @param int $id         ID kategori (opsional, untuk exclude diri sendiri saat edit)
     * @return bool          TRUE jika nama sudah ada, FALSE jika belum
     *
     * Contoh penggunaan:
     * // Saat tambah baru
     * if ($this->kategori_model->cek_nama('Makanan')) {
     *     echo "Nama sudah ada, cari yang lain!";
     * }
     *
     * // Saat edit (exclude diri sendiri)
     * if ($this->kategori_model->cek_nama('Makanan', 5)) {
     *     echo "Nama sudah digunakan kategori lain!";
     * }
     */
    public function cek_nama($nama, $id = NULL)
    {
        $this->db->where('name', $nama);

        // Jika $id ada, exclude kategori dengan ID tersebut
        // (untuk keperluan edit, agar tidak cek dengan dirinya sendiri)
        if ($id !== NULL) {
            $this->db->where('id !=', $id);
        }

        $query = $this->db->get($this->tabel);

        // Return TRUE jika ada data (nama sudah dipakai)
        return $query->num_rows() > 0;
    }
}