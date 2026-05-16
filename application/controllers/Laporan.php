<?php
/**
 * Laporan.php
 * Controller untuk berbagai laporan
 *
 * @author SMK Assalafiyyah Sleman
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Transaksi_model');
        $this->load->model('Stok_model');
        $this->load->model('Produk_model');
    }

    /**
     * ============================================================
     * FUNCTION: laporan_penjualan()
     * ============================================================
     * Halaman laporan penjualan
     */
    public function laporan_penjualan()
    {
        $this->data['judul_halaman'] = 'Laporan Penjualan';

        // Filter
        $tanggal_mulai = $this->input->get('tanggal_mulai') ?: date('Y-m-d');
        $tanggal_selesai = $this->input->get('tanggal_selesai') ?: date('Y-m-d');
        $kasir_id = $this->input->get('kasir_id') ?: NULL;

        // Ambil data
        $this->data['penjualan'] = $this->Transaksi_model->laporan_penjualan(
            $tanggal_mulai,
            $tanggal_selesai,
            $kasir_id
        );

        // Hitung total
        $this->data['total_omset'] = 0;
        $this->data['total_transaksi'] = 0;

        foreach ($this->data['penjualan'] as $p)
        {
            $this->data['total_omset'] += $p->total;
            $this->data['total_transaksi']++;
        }

        // Filter values untuk view
        $this->data['tanggal_mulai'] = $tanggal_mulai;
        $this->data['tanggal_selesai'] = $tanggal_selesai;
        $this->data['kasir_id'] = $kasir_id;

        $this->data['view_konten'] = 'laporan/penjualan';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: laporan_laba()
     * ============================================================
     * Halaman laporan laba rugi
     *
     * Rumus:
     * Laba Kotor = Omset - Modal
     * Modal = Sum(Harga Beli × Jumlah Terjual)
     */
    public function laporan_laba()
    {
        $this->data['judul_halaman'] = 'Laporan Laba Rugi';

        $tanggal_mulai = $this->input->get('tanggal_mulai') ?: date('Y-m-d');
        $tanggal_selesai = $this->input->get('tanggal_selesai') ?: date('Y-m-d');

        // Ambil data transaksi dengan item
        $this->db->select('transactions.*, transaction_items.buy_price,
                          transaction_items.quantity, transaction_items.subtotal');
        $this->db->from('transactions');
        $this->db->join('transaction_items', 'transaction_items.transaction_id = transactions.id');
        $this->db->where('DATE(transactions.created_at) >=', $tanggal_mulai);
        $this->db->where('DATE(transactions.created_at) <=', $tanggal_selesai);
        $transaksi = $this->db->get()->result();

        // Hitung
        $total_omset = 0;
        $total_modal = 0;
        $total_laba = 0;

        foreach ($transaksi as $t)
        {
            $total_omset += $t->subtotal;
            $total_modal += ($t->buy_price * $t->quantity);
        }

        $total_laba = $total_omset - $total_modal;
        $persen_laba = $total_omset > 0 ? ($total_laba / $total_omset * 100) : 0;

        $this->data['total_omset'] = $total_omset;
        $this->data['total_modal'] = $total_modal;
        $this->data['total_laba'] = $total_laba;
        $this->data['persen_laba'] = $persen_laba;
        $this->data['tanggal_mulai'] = $tanggal_mulai;
        $this->data['tanggal_selesai'] = $tanggal_selesai;

        $this->data['view_konten'] = 'laporan/laba';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: laporan_produk_terlaris()
     * ============================================================
     * Halaman laporan produk terlaris
     */
    public function laporan_produk_terlaris()
    {
        $this->data['judul_halaman'] = 'Laporan Produk Terlaris';

        $tanggal_mulai = $this->input->get('tanggal_mulai') ?: date('Y-m-01'); // Awal bulan ini
        $tanggal_selesai = $this->input->get('tanggal_selesai') ?: date('Y-m-d');

        $this->data['produk_terlaris'] = $this->Transaksi_model->produk_terlaris(
            20, // Top 20
            $tanggal_mulai,
            $tanggal_selesai
        );

        $this->data['tanggal_mulai'] = $tanggal_mulai;
        $this->data['tanggal_selesai'] = $tanggal_selesai;

        $this->data['view_konten'] = 'laporan/produk_terlaris';
        $this->load->view('layouts/utama', $this->data);
    }
}