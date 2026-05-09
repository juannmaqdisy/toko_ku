<?php
/**
 * Produk_model.php
 *
 * Model untuk mengelola data produk
 * Termasuk relasi dengan kategori dan upload gambar
 *
 * @author    SMK Assalafiyyah Sleman
 * @version   1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model {

    // Nama tabel
    protected $tabel = 'products';

    /**
     * ============================================================
     * FUNGSI: ambil_semua()
     * ============================================================
     * Mengambil semua data produk dengan join kategori
     *
     * @return array    Object array berisi semua produk + nama kategori
     *
     * Query yang dihasilkan:
     * SELECT products.*, categories.name as category_name
     * FROM products
     * LEFT JOIN categories ON categories.id = products.category_id
     * ORDER BY products.id DESC
     */
    public function ambil_semua()
    {
        // Select semua kolom dari produk dan nama kategori
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from($this->tabel);

        // Join dengan tabel kategori
        // LEFT JOIN agar produk tetap muncul meskipun kategori dihapus
        $this->db->join('categories', 'categories.id = products.category_id', 'left');

        // Urutkan dari yang terbaru
        $this->db->order_by('products.id', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: ambil_aktif()
     * ============================================================
     * Mengambil produk yang aktif saja (untuk dropdown di POS/Stok)
     *
     * @return array    Object array produk aktif
     *
     * Digunakan untuk:
     * - Dropdown saat stok masuk
     * - Dropdown saat transaksi penjualan
     */
    public function ambil_aktif()
    {
        // Hanya ambil kolom yang diperlukan
        $this->db->select('id, name, sell_price, stock, unit, barcode, category_id');
        $this->db->where('is_active', 1);           // Hanya produk aktif
        $this->db->order_by('name', 'ASC');

        return $this->db->get($this->tabel)->result();
    }

    /**
     * ============================================================
     * FUNGSI: ambil_berdasarkan_id($id)
     * ============================================================
     * Mengambil satu produk berdasarkan ID
     *
     * @param int $id    ID produk yang dicari
     * @return object    Object produk, atau NULL jika tidak ditemukan
     */
    public function ambil_berdasarkan_id($id)
    {
        return $this->db->get_where($this->tabel, ['id' => $id])->row();
    }

    /**
     * ============================================================
     * FUNGSI: ambil_berdasarkan_barcode($barcode)
     * ============================================================
     * Mengambil produk berdasarkan barcode
     *
     * @param string $barcode    Kode barcode produk
     * @return object            Object produk, atau NULL
     *
     * Digunakan untuk:
     * - Scan barcode di POS
     */
    public function ambil_berdasarkan_barcode($barcode)
    {
        $this->db->where('barcode', $barcode);
        $this->db->where('is_active', 1);
        return $this->db->get($this->tabel)->row();
    }

    /**
     * ============================================================
     * FUNGSI: tambah($data)
     * ============================================================
     * Menambahkan produk baru
     *
     * @param array $data    Array data produk
     * @return int           ID produk yang baru ditambahkan
     *
     * Format data:
     * [
     *     'category_id' => 1,
     *     'name' => 'Indomie Goreng',
     *     'description' => 'Mie instan goreng',
     *     'buy_price' => 2500,
     *     'sell_price' => 3000,
     *     'stock' => 0,              // Stok awal SELALU 0!
     *     'unit' => 'pcs',
     *     'barcode' => '899600101001',
     *     'image' => 'indomie.jpg'
     * ]
     */
    public function tambah($data)
    {
        $this->db->insert($this->tabel, $data);
        return $this->db->insert_id();
    }

    /**
     * ============================================================
     * FUNGSI: update($id, $data)
     * ============================================================
     * Mengupdate data produk
     *
     * @param int $id       ID produk
     * @param array $data   Data produk yang baru
     * @return bool         TRUE jika berhasil
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * ============================================================
     * FUNGSI: update_stok($id, $jumlah)
     * ============================================================
     * Update stok produk (bertambah atau berkurang)
     *
     * @param int $id           ID produk
     * @param int $jumlah       Perubahan stok (positif/negatif)
     * @return bool             TRUE jika berhasil
     *
     * Contoh:
     * - update_stok(5, 10)  → Stok produk ID 5 bertambah 10
     * - update_stok(5, -5)  → Stok produk ID 5 berkurang 5
     *
     * CATATAN: Ini menggunakan fungsi SQL directly
     * Query: UPDATE products SET stock = stock + ? WHERE id = ?
     */
    public function update_stok($id, $jumlah)
    {
        // Gunakan fungsi SQL untuk update langsung
        $this->db->set('stock', 'stock + ' . (int)$jumlah, FALSE);
        $this->db->where('id', $id);
        return $this->db->update($this->tabel);
    }

    /**
     * ============================================================
     * FUNGSI: hapus($id)
     * ============================================================
     * Menghapus produk (dan gambarnya)
     *
     * @param int $id    ID produk yang dihapus
     * @return bool      TRUE jika berhasil
     *
     * Fungsi ini akan:
     * 1. Menghapus file gambar dari folder uploads
     * 2. Menghapus data dari database
     */
    public function hapus($id)
    {
        // Ambil data produk dulu untuk dapat nama gambar
        $produk = $this->ambil_berdasarkan_id($id);

        if ($produk && $produk->image)
        {
            // Path lengkap file gambar
            $path_gambar = FCPATH . 'uploads/products/' . $produk->image;

            // Hapus file jika ada
            if (file_exists($path_gambar))
            {
                unlink($path_gambar);
            }
        }

        // Hapus dari database
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }

    /**
     * ============================================================
     * FUNGSI: cari($keyword)
     * ============================================================
     * Mencari produk berdasarkan nama atau barcode
     *
     * @param string $keyword    Kata kunci pencarian
     * @return array             Array hasil pencarian
     *
     * Digunakan untuk:
     * - Autocomplete di POS
     * - Pencarian produk
     */
    public function cari($keyword)
    {
        $this->db->select('id, name, barcode, sell_price, stock');
        $this->db->like('name', $keyword);          // Cari di nama
        $this->db->or_like('barcode', $keyword);     // Cari di barcode
        $this->db->where('is_active', 1);
        $this->db->limit(10);                         // Batasi hasil

        return $this->db->get($this->tabel)->result();
    }

    /**
     * ============================================================
     * FUNGSI: ambil_stok_menipis($batas)
     * ============================================================
     * Mengambil produk dengan stok menipis
     *
     * @param int $batas    Batas stok (default: 10)
     * @return array        Array produk stok menipis
     *
     * Digunakan untuk:
     * - Notifikasi stok menipis
     * - Dashboard admin
     */
    public function ambil_stok_menipis($batas = 10)
    {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from($this->tabel);
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->where('stock <=', $batas);       // Stok <= batas
        $this->db->where('stock >', 0);              // Masih ada stok
        $this->db->where('is_active', 1);
        $this->db->order_by('stock', 'ASC');         // Yang paling sedikit dulu

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: cek_barcode($barcode, $id = NULL)
     * ============================================================
     * Cek apakah barcode sudah digunakan
     *
     * @param string $barcode    Barcode yang dicek
     * @param int $id            ID produk (exclude saat edit)
     * @return bool              TRUE jika sudah ada
     */
    public function cek_barcode($barcode, $id = NULL)
    {
        $this->db->where('barcode', $barcode);

        if ($id !== NULL)
        {
            $this->db->where('id !=', $id);
        }

        $query = $this->db->get($this->tabel);
        return $query->num_rows() > 0;
    }
}