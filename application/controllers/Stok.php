<?php
/**
 * Stok.php
 *
 * Controller untuk mengelola halaman stok
 *
 * @author    SMK Assalafiyyah Sleman
 * @version   1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends Admin_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Load model
        $this->load->model('Stok_model');
        $this->load->model('Produk_model');
        $this->load->model('Kategori_model');

        // Load library
        $this->load->library('form_validation');

        // Load helper
        $this->load->helper('url');
    }

    /**
     * ============================================================
     * FUNCTION: stok_index()
     * ============================================================
     * Halaman dashboard stok
     * URL: /stok
     *
     * Menampilkan:
     * - Ringkasan stok (total, menipis, habis)
     * - Daftar stok menipis
     * - Daftar stok habis
     * - Riwayat stok terbaru
     */
    public function stok_index()
    {
        $this->data['judul_halaman'] = 'Dashboard Manajemen Stok';

        // Ambil data ringkasan stok
        $this->data['ringkasan'] = $this->Stok_model->ringkasan_stok();

        // Ambil stok menipis
        $this->data['stok_menipis'] = $this->Stok_model->ambil_stok_menipis(10);

        // Ambil stok habis
        $this->data['stok_habis'] = $this->Stok_model->ambil_stok_habis();

        // Ambil riwayat stok terbaru (10 data)
        $this->data['riwayat_terbaru'] = $this->Stok_model->ambil_riwayat(NULL, NULL, 10);

        $this->data['view_konten'] = 'stok/index';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: stok_masuk()
     * ============================================================
     * Halaman form stok masuk
     * URL: /stok/masuk
     *
     * Proses:
     * 1. Pilih produk dari dropdown
     * 2. Input jumlah stok masuk
     * 3. (Opsional) Input harga beli terbaru
     * 4. Simpan → Stok bertambah
     */
    public function stok_masuk()
    {
        $this->data['judul_halaman'] = 'Stok Masuk (Restock)';
        $this->data['produk'] = $this->Produk_model->ambil_aktif();

        // Validasi form
        $this->form_validation->set_rules('product_id', 'Produk', 'required');
        $this->form_validation->set_rules('quantity', 'Jumlah', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('price', 'Harga Beli', 'numeric|greater_than[0]');
        $this->form_validation->set_rules('notes', 'Keterangan', 'trim');

        $this->form_validation->set_message('required', '{field} wajib diisi!');
        $this->form_validation->set_message('greater_than', '{field} harus lebih dari 0!');

        if ($this->form_validation->run() === TRUE)
        {
            $produk_id = $this->input->post('product_id');
            $jumlah = $this->input->post('quantity');
            $harga = $this->input->post('price') ?: NULL;
            $keterangan = $this->input->post('notes') ?: 'Stok masuk manual';

            // Panggil model untuk proses stok masuk
            $hasil = $this->Stok_model->stok_masuk(
                $produk_id,
                $jumlah,
                $harga,
                $keterangan,
                $this->data['current_user']->id
            );

            if ($hasil)
            {
                $this->session->set_flashdata('sukses',
                    'Stok berhasil ditambahkan sejumlah ' . $jumlah . ' unit.');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal menambahkan stok.');
            }

            redirect('stok', 'refresh');
        }

        $this->data['view_konten'] = 'stok/masuk';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: stok_keluar()
     * ============================================================
     * Halaman form stok keluar
     * URL: /stok/keluar
     *
     * Proses:
     * 1. Pilih produk dari dropdown
     * 2. Input jumlah stok keluar
     * 3. Pilih alasan (rusak, expired, dll)
     * 4. Simpan → Stok berkurang
     */
    public function stok_keluar()
    {
        $this->data['judul_halaman'] = 'Stok Keluar';
        $this->data['produk'] = $this->Produk_model->ambil_aktif();

        // Validasi form
        $this->form_validation->set_rules('product_id', 'Produk', 'required');
        $this->form_validation->set_rules('quantity', 'Jumlah', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('reason', 'Alasan', 'required|trim');
        $this->form_validation->set_rules('notes', 'Keterangan Tambahan', 'trim');

        $this->form_validation->set_message('required', '{field} wajib diisi!');

        if ($this->form_validation->run() === TRUE)
        {
            $produk_id = $this->input->post('product_id');
            $jumlah = $this->input->post('quantity');
            $alasan = $this->input->post('reason');
            $keterangan_tambahan = $this->input->post('notes');

            // Gabungkan alasan dan keterangan
            $keterangan_lengkap = $alasan;
            if ($keterangan_tambahan)
            {
                $keterangan_lengkap .= ': ' . $keterangan_tambahan;
            }

            // Panggil model untuk proses stok keluar
            $hasil = $this->Stok_model->stok_keluar(
                $produk_id,
                $jumlah,
                $keterangan_lengkap,
                $this->data['current_user']->id
            );

            if ($hasil['status'])
            {
                $this->session->set_flashdata('sukses', $hasil['message']);
            }
            else
            {
                $this->session->set_flashdata('error', $hasil['message']);
            }

            redirect('stok', 'refresh');
        }

        $this->data['view_konten'] = 'stok/keluar';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: stok_riwayat()
     * ============================================================
     * Halaman riwayat pergerakan stok
     * URL: /stok/riwayat
     *
     * Menampilkan semua riwayat stok masuk dan keluar
     */
    public function stok_riwayat()
    {
        $this->data['judul_halaman'] = 'Riwayat Pergerakan Stok';

        // Filter dari query string
        $produk_id = $this->input->get('produk_id') ?: NULL;
        $tipe = $this->input->get('tipe') ?: NULL;  // 'in' atau 'out'

        $this->data['riwayat'] = $this->Stok_model->ambil_riwayat($produk_id, $tipe, 100);
        $this->data['produk_id'] = $produk_id;
        $this->data['tipe'] = $tipe;
        $this->data['semua_produk'] = $this->Produk_model->ambil_aktif();

        $this->data['view_konten'] = 'stok/riwayat';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: stok_laporan()
     * ============================================================
     * Halaman laporan stok dengan filter
     * URL: /stok/laporan
     */
    public function stok_laporan()
    {
        $this->data['judul_halaman'] = 'Laporan Stok';

        // Filter dari query string
        $tanggal_mulai = $this->input->get('tanggal_mulai');
        $tanggal_selesai = $this->input->get('tanggal_selesai');
        $produk_id = $this->input->get('produk_id');

        $this->data['laporan'] = $this->Stok_model->laporan_stok(
            $tanggal_mulai,
            $tanggal_selesai,
            $produk_id
        );

        $this->data['semua_produk'] = $this->Produk_model->ambil_aktif();
        $this->data['tanggal_mulai'] = $tanggal_mulai;
        $this->data['tanggal_selesai'] = $tanggal_selesai;
        $this->data['produk_id'] = $produk_id;

        $this->data['view_konten'] = 'stok/laporan';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * AJAX: ambil_info_produk()
     * ============================================================
     * Mengambil info produk via AJAX
     * URL: /stok/ambil_info_produk
     *
     * Digunakan untuk:
     * - Menampilkan info produk saat dipilih di dropdown
     * - Menampilkan stok saat ini, harga beli terakhir, dll
     */
    public function ambil_info_produk()
    {
        $produk_id = $this->input->post('produk_id');
        $produk = $this->Produk_model->ambil_berdasarkan_id($produk_id);

        if ($produk)
        {
            echo json_encode([
                'status' => TRUE,
                'data' => [
                    'nama' => $produk->name,
                    'stok' => $produk->stock,
                    'satuan' => $produk->unit,
                    'harga_beli' => $produk->buy_price,
                    'kategori' => $produk->category_id
                ]
            ]);
        }
        else
        {
            echo json_encode(['status' => FALSE, 'message' => 'Produk tidak ditemukan']);
        }
    }
}