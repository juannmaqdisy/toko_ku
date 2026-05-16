<?php
/**
 * Transaksi_model.php
 * Model untuk mengelola transaksi penjualan
 *
 * @author SMK Assalafiyyah Sleman
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

    protected $tabel_transaksi = 'transactions';
    protected $tabel_item = 'transaction_items';

    /**
     * ============================================================
     * FUNGSI: simpan_transaksi()
     * ============================================================
     * Menyimpan transaksi penjualan baru
     *
     * @param array $data_keranjang   Array item di keranjang
     * @param float $total            Total belanja
     * @param float $bayar            Uang yang dibayar
     * @param float $kembalian        Kembalian
     * @param int $user_id            ID kasir
     * @return int|FALSE             ID transaksi jika sukses, FALSE jika gagal
     */
    public function simpan_transaksi($data_keranjang, $total, $bayar, $kembalian, $user_id)
    {
        // Generate nomor transaksi
        $nomor_transaksi = 'TRX' . date('Ymd') . rand(1000, 9999);

        // 1. Simpan data transaksi utama
        $data_transaksi = array(
            'transaction_no' => $nomor_transaksi,
            'user_id' => $user_id,
            'total' => $total,
            'pay_amount' => $bayar,
            'change_amount' => $kembalian,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert($this->tabel_transaksi, $data_transaksi);
        $transaksi_id = $this->db->insert_id();

        if (!$transaksi_id)
        {
            return FALSE;
        }

        // 2. Simpan setiap item di keranjang
        foreach ($data_keranjang as $item)
        {
            $data_item = array(
                'transaction_id' => $transaksi_id,
                'product_id' => $item['produk_id'],
                'quantity' => $item['jumlah'],
                'price' => $item['harga'],        // Harga jual saat transaksi
                'subtotal' => $item['subtotal'],
                'buy_price' => $item['harga_beli'] // Simpan harga beli untuk laporan laba
            );

            $this->db->insert($this->tabel_item, $data_item);

            // 3. Kurangi stok produk
            $this->db->set('stock', 'stock - ' . (int)$item['jumlah'], FALSE);
            $this->db->where('id', $item['produk_id']);
            $this->db->update('products');
        }

        return $transaksi_id;
    }

    /**
     * ============================================================
     * FUNGSI: ambil_semua()
     * ============================================================
     * Mengambil semua transaksi
     */
    public function ambil_semua($limit = 50)
    {
        $this->db->select('transactions.*, users.first_name, users.last_name');
        $this->db->from($this->tabel_transaksi);
        $this->db->join('users', 'users.id = transactions.user_id');
        $this->db->order_by('transactions.created_at', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: ambil_berdasarkan_id()
     * ============================================================
     * Mengambil transaksi berdasarkan ID beserta itemnya
     */
    public function ambil_berdasarkan_id($id)
    {
        // Ambil data transaksi
        $this->db->select('transactions.*, users.first_name, users.last_name');
        $this->db->from($this->tabel_transaksi);
        $this->db->join('users', 'users.id = transactions.user_id');
        $this->db->where('transactions.id', $id);
        $transaksi = $this->db->get()->row();

        if ($transaksi)
        {
            // Ambil item transaksi
            $this->db->select('transaction_items.*, products.name as nama_produk');
            $this->db->from($this->tabel_item);
            $this->db->join('products', 'products.id = transaction_items.product_id');
            $this->db->where('transaction_items.transaction_id', $id);
            $transaksi->items = $this->db->get()->result();
        }

        return $transaksi;
    }

    /**
     * ============================================================
     * FUNGSI: laporan_penjualan()
     * ============================================================
     * Mengambil data laporan penjualan
     */
    public function laporan_penjualan($tanggal_mulai = NULL, $tanggal_selesai = NULL, $kasir_id = NULL)
    {
        $this->db->select('transactions.*, users.first_name, users.last_name');
        $this->db->from($this->tabel_transaksi);
        $this->db->join('users', 'users.id = transactions.user_id');

        if ($tanggal_mulai)
        {
            $this->db->where('DATE(transactions.created_at) >=', $tanggal_mulai);
        }

        if ($tanggal_selesai)
        {
            $this->db->where('DATE(transactions.created_at) <=', $tanggal_selesai);
        }

        if ($kasir_id)
        {
            $this->db->where('transactions.user_id', $kasir_id);
        }

        $this->db->order_by('transactions.created_at', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * ============================================================
     * FUNGSI: hitung_omset()
     * ============================================================
     * Menghitung total omset penjualan
     */
    public function hitung_omset($tanggal_mulai = NULL, $tanggal_selesai = NULL)
    {
        if ($tanggal_mulai)
        {
            $this->db->where('DATE(created_at) >=', $tanggal_mulai);
        }

        if ($tanggal_selesai)
        {
            $this->db->where('DATE(created_at) <=', $tanggal_selesai);
        }

        $this->db->select_sum('total');
        $result = $this->db->get($this->tabel_transaksi)->row();

        return $result->total ?: 0;
    }

    /**
     * ============================================================
     * FUNGSI: produk_terlaris()
     * ============================================================
     * Mengambil produk paling laku
     */
    public function produk_terlaris($limit = 10, $tanggal_mulai = NULL, $tanggal_selesai = NULL)
    {
        $this->db->select('products.name, products.unit,
                          SUM(transaction_items.quantity) as total_jual,
                          SUM(transaction_items.subtotal) as total_omset');
        $this->db->from($this->tabel_item);
        $this->db->join('products', 'products.id = transaction_items.product_id');
        $this->db->join($this->tabel_transaksi, $this->tabel_transaksi . '.id = transaction_items.transaction_id');

        if ($tanggal_mulai)
        {
            $this->db->where('DATE(' . $this->tabel_transaksi . '.created_at) >=', $tanggal_mulai);
        }

        if ($tanggal_selesai)
        {
            $this->db->where('DATE(' . $this->tabel_transaksi . '.created_at) <=', $tanggal_selesai);
        }

        $this->db->group_by('products.id');
        $this->db->order_by('total_jual', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }
}