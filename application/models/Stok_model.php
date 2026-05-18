<?php
/**
 * Stok_model.php
 *
 * Model untuk mengelola semua operasi stok
 *
 * @author     SMK Assalafiyyah Sleman
 * @version    1.1 (Ditambahkan fungsi stok_keluar)
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model {

    // Nama tabel
    protected $tabel = 'stock_history';

    /**
     * ============================================================
     * FUNGSI: stok_masuk()
     * ============================================================
     * Mencatat stok masuk dan menambah stok produk
     *
     * @param int $produk_id       ID produk
     * @param int $jumlah          Jumlah stok masuk
     * @param float $harga         Harga beli (opsional, untuk update harga beli rata-rata)
     * @param string $keterangan   Keterangan tambahan
     * @param int $user_id         ID user yang mencatat
     * @return bool                TRUE jika berhasil
     */
    public function stok_masuk($produk_id, $jumlah, $harga = NULL, $keterangan = '', $user_id = NULL)
    {
        // Insert history
        $data_history = array(
            'product_id' => $produk_id,
            'type'       => 'in',
            'quantity'   => $jumlah,
            'price'      => $harga,
            'notes'      => $keterangan ?: 'Stok masuk manual',
            'created_by' => $user_id
        );

        $this->db->insert('stock_history', $data_history);

        // Ambil data produk dulu
        $produk = $this->db->get_where('products', [
            'id' => $produk_id
        ])->row();

        if (!$produk) {
            return false;
        }

        // Update stok
        $this->db->set('stock', 'stock + ' . (int)$jumlah, FALSE);

        // Update harga beli jika ada
        if ($harga !== NULL && $harga > 0)
        {
            $stok_total = $produk->stock + $jumlah;

            $total_lama = ($produk->buy_price ?? 0) * $produk->stock;
            $total_baru = $harga * $jumlah;

            $harga_rata = ($total_lama + $total_baru) / $stok_total;

            $this->db->set('buy_price', $harga_rata);
        }

        // WAJIB where lagi sebelum update
        $this->db->where('id', $produk_id);

        return $this->db->update('products');
    }

    /**
     * ============================================================
     * FUNGSI BARU: stok_keluar()
     * ============================================================
     * Mencatat stok keluar dan mengurangi stok produk di database
     *
     * @param int $produk_id       ID produk
     * @param int $jumlah          Jumlah stok keluar
     * @param string $keterangan   Keterangan / Alasan keluar
     * @param int $user_id         ID user yang mencatat
     * @return array               Status boolean dan pesan teks
     */
    public function stok_keluar($produk_id, $jumlah, $keterangan = '', $user_id = NULL)
    {
        // 1. Ambil data produk saat ini untuk pengecekan stok di sisi server
        $produk = $this->db->get_where('products', ['id' => $produk_id])->row();

        if (!$produk) {
            return [
                'status'  => FALSE, 
                'message' => 'Produk tidak ditemukan!'
            ];
        }

        // Pengaman berlapis jika user membobol validasi JavaScript javascript
        if ($produk->stock < $jumlah) {
            return [
                'status'  => FALSE, 
                'message' => 'Gagal! Stok saat ini (' . $produk->stock . ') tidak mencukupi untuk dikeluarkan sejumlah ' . $jumlah . '.'
            ];
        }

        // 2. Jalankan Database Transaction agar aman dan sinkron
        $this->db->trans_start();

        // Mencatat riwayat pengeluaran ke tabel stock_history (type = 'out')
        $data_history = array(
            'product_id' => $produk_id,
            'type'       => 'out',
            'quantity'   => $jumlah,
            'price'      => NULL, // Keluar barang tidak memengaruhi harga beli
            'notes'      => $keterangan ?: 'Stok keluar manual',
            'created_by' => $user_id
        );
        $this->db->insert('stock_history', $data_history);

        // Mengurangi jumlah stok di tabel products
        $this->db->set('stock', 'stock - ' . (int)$jumlah, FALSE);
        $this->db->where('id', $produk_id);
        $this->db->update('products');

        $this->db->trans_complete();

        // 3. Kembalikan status hasil transaksi ke controller
        if ($this->db->trans_status() === FALSE) {
            return [
                'status'  => FALSE, 
                'message' => 'Terjadi kesalahan sistem saat memproses pengurangan stok.'
            ];
        }

        return [
            'status'  => TRUE, 
            'message' => 'Stok berhasil dikurangi sejumlah ' . $jumlah . ' unit.'
        ];
    }

    /**
     * ============================================================
     * FUNGSI: ambil_riwayat()
     * ============================================================
     * Mengambil riwayat pergerakan stok
     */
    public function ambil_riwayat($produk_id = NULL, $tipe = NULL, $limit = 50)
    {
        // Select dengan join
        $this->db->select('stock_history.*, products.name as nama_produk,
                          products.unit, users.first_name, users.last_name');
        $this->db->from($this->tabel);
        $this->db->join('products', 'products.id = stock_history.product_id');
        $this->db->join('users', 'users.id = stock_history.created_by');

        // Filter berdasarkan produk
        if ($produk_id !== NULL)
        {
            $this->db->where('stock_history.product_id', $produk_id);
        }

        // Filter berdasarkan tipe
        if ($tipe !== NULL)
        {
            $this->db->where('stock_history.type', $tipe);
        }

        // Urutkan dari yang terbaru
        $this->db->order_by('stock_history.date', 'DESC');

        // Batas jumlah data
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: ambil_stok_menipis()
     * ============================================================
     */
    public function ambil_stok_menipis($batas = 10)
    {
        $this->db->select('products.*, categories.name as nama_kategori');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->where('products.stock <=', $batas);
        $this->db->where('products.stock >', 0);         // Masih ada stok
        $this->db->where('products.is_active', 1);
        $this->db->order_by('products.stock', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: ambil_stok_habis()
     * ============================================================
     */
    public function ambil_stok_habis()
    {
        $this->db->select('products.*, categories.name as nama_kategori');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->where('products.stock', 0);
        $this->db->where('products.is_active', 1);
        $this->db->order_by('products.name', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: laporan_stok()
     * ============================================================
     */
    public function laporan_stok($tanggal_mulai = NULL, $tanggal_selesai = NULL, $produk_id = NULL)
    {
        // Select dengan join
        $this->db->select('stock_history.*, products.name as nama_produk,
                          products.unit, users.first_name');
        $this->db->from($this->tabel);
        $this->db->join('products', 'products.id = stock_history.product_id');
        $this->db->join('users', 'users.id = stock_history.created_by');

        // Filter tanggal
        if ($tanggal_mulai)
        {
            $this->db->where('DATE(stock_history.date) >=', $tanggal_mulai);
        }

        if ($tanggal_selesai)
        {
            $this->db->where('DATE(stock_history.date) <=', $tanggal_selesai);
        }

        // Filter produk
        if ($produk_id)
        {
            $this->db->where('stock_history.product_id', $produk_id);
        }

        $this->db->order_by('stock_history.date', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: ringkasan_stok()
     * ============================================================
     */
    public function ringkasan_stok()
    {
        $data = array();

        // Total produk aktif
        $this->db->where('is_active', 1);
        $data['total_produk'] = $this->db->count_all_results('products');

        // Stok menipis (≤ 10)
        $data['stok_menipis'] = count($this->ambil_stok_menipis(10));

        // Stok habis
        $data['stok_habis'] = count($this->ambil_stok_habis());

        // Stok aman (> 10)
        $data['stok_aman'] = $data['total_produk'] - $data['stok_menipis'] - $data['stok_habis'];

        return $data;
    }
}