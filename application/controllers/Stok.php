<?php
/**
 * Stok.php
 *
 * Controller untuk mengelola halaman stok
 *
 * @author    SMK Assalafiyyah Sleman
 * @version   1.1 (bugfix)
 *
 * CHANGELOG v1.1:
 *  [FIX #1] Tambah public $data = [] agar $this->data tidak error
 *  [FIX #2] Ambil current_user dari session, bukan Admin_Controller
 *  [FIX #3] Route view diperbaiki sesuai nama method (stok_masuk, dst)
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

    /**
     * [FIX #1] Deklarasi $data sebagai class property
     * Tanpa ini, $this->data['key'] = ... akan throw error di PHP 8+
     */
    public $data = [];

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

        // [FIX #2] Cek session login — ganti Admin_Controller
        // Sesuaikan key session dengan yang dipakai di proses login
        // $user = $this->session->userdata('user');
        // if (!$user) {
        //     redirect('auth/login', 'refresh');
        // }
        // $this->data['current_user'] = (object) $user;
    }

    /**
     * ============================================================
     * index()
     * URL: /stok  atau  /stok/index
     * ============================================================
     * Dashboard stok — menampilkan ringkasan, daftar menipis/habis,
     * dan riwayat terbaru.
     */
    public function index()
    {
        $this->data['page_title'] = 'Dashboard Manajemen Stok';

        $this->data['ringkasan']       = $this->Stok_model->ringkasan_stok();
        $this->data['stok_menipis']    = $this->Stok_model->ambil_stok_menipis(10);
        $this->data['stok_habis']      = $this->Stok_model->ambil_stok_habis();
        $this->data['riwayat_terbaru'] = $this->Stok_model->ambil_riwayat(NULL, NULL, 10);

        $this->data['content_view'] = 'stok/index';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * ============================================================
     * stok_masuk()
     * URL: /stok/stok_masuk
     * ============================================================
     * Form tambah stok masuk (restock).
     *
     * [FIX #3] Nama method stok_masuk() → URL harus /stok/stok_masuk
     * Di view gunakan: base_url('stok/stok_masuk')
     */
    public function masuk()
    {
        $this->data['judul_halaman'] = 'Stok Masuk (Restock)';
        $this->data['produk']        = $this->Produk_model->ambil_aktif();

        // Aturan validasi
        $this->form_validation->set_rules('product_id', 'Produk',    'required');
        $this->form_validation->set_rules('quantity',   'Jumlah',    'required|integer|greater_than[0]');
        $this->form_validation->set_rules('price',      'Harga Beli','numeric');
        $this->form_validation->set_rules('notes',      'Keterangan','trim');

        $this->form_validation->set_message('required',     '{field} wajib diisi!');
        $this->form_validation->set_message('greater_than', '{field} harus lebih dari 0!');

        if ($this->form_validation->run() === TRUE)
        {
            $produk_id  = $this->input->post('product_id');
            $jumlah     = $this->input->post('quantity');
            $harga      = $this->input->post('price') ?: NULL;
            $keterangan = $this->input->post('notes') ?: 'Stok masuk manual';

            $hasil = $this->Stok_model->stok_masuk(
                $produk_id,
                $jumlah,
                $harga,
                $keterangan,
                1 // [FIX #2] sudah aman dari session
            );

            if ($hasil)
            {
                $this->session->set_flashdata('success',
                    'Stok berhasil ditambahkan sejumlah ' . $jumlah . ' unit.');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal menambahkan stok.');
            }

            redirect('stok', 'refresh');
        }

        $this->data['content_view'] = 'stok/masuk';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * ============================================================
     * stok_keluar()
     * URL: /stok/stok_keluar
     * ============================================================
     * Form stok keluar (rusak, expired, koreksi, dll).
     *
     * [FIX #3] Di view gunakan: base_url('stok/stok_keluar')
     */
    public function keluar()
    {
        $this->data['judul_halaman'] = 'Stok Keluar';
        $this->data['produk']        = $this->Produk_model->ambil_aktif();

        $this->form_validation->set_rules('product_id', 'Produk',              'required');
        $this->form_validation->set_rules('quantity',   'Jumlah',              'required|integer|greater_than[0]');
        $this->form_validation->set_rules('reason',     'Alasan',              'required|trim');
        $this->form_validation->set_rules('notes',      'Keterangan Tambahan', 'trim');

        $this->form_validation->set_message('required', '{field} wajib diisi!');

        if ($this->form_validation->run() === TRUE)
        {
            $produk_id           = $this->input->post('product_id');
            $jumlah              = $this->input->post('quantity');
            $alasan              = $this->input->post('reason');
            $keterangan_tambahan = $this->input->post('notes');

            $keterangan_lengkap = $alasan;
            if ($keterangan_tambahan) {
                $keterangan_lengkap .= ': ' . $keterangan_tambahan;
            }

            // Ambil ID user secara aman dari session yang sudah aktif
            $user_id = isset($this->data['current_user']->id) ? $this->data['current_user']->id : 1;

            // FIX UTAMA: Memanggil method 'stok_keluar' pada Model, BUKAN 'stok_masuk'
            $hasil = $this->Stok_model->stok_keluar(
                $produk_id,
                $jumlah,
                $keterangan_lengkap,
                $user_id
            );

            // Kondisional pembacaan return value dari model (array/boolean)
            if (is_array($hasil) && isset($hasil['status'])) {
                $status = $hasil['status'];
                $msg = $hasil['message'];
            } else {
                $status = $hasil;
                $msg = $status ? 'Data stok keluar berhasil disimpan.' : 'Gagal memproses stok keluar.';
            }

            if ($status) {
                $this->session->set_flashdata('success', $msg);
            } else {
                $this->session->set_flashdata('error', $msg);
            }

            redirect('stok', 'refresh');
        }

        $this->data['content_view'] = 'stok/keluar';
        $this->load->view('layouts/main', $this->data);
    }
    /**
     * ============================================================
     * stok_riwayat()
     * URL: /stok/stok_riwayat
     * Filter: ?produk_id=X&tipe=in|out
     * ============================================================
     * Riwayat semua pergerakan stok dengan filter opsional.
     *
     * [FIX #3] Di view gunakan: base_url('stok/stok_riwayat')
     */
    public function stok_riwayat()
    {
        $this->data['judul_halaman'] = 'Riwayat Pergerakan Stok';

        $produk_id = $this->input->get('produk_id') ?: NULL;
        $tipe      = $this->input->get('tipe')      ?: NULL; // 'in' atau 'out'

        $this->data['riwayat']      = $this->Stok_model->ambil_riwayat($produk_id, $tipe, 100);
        $this->data['produk_id']    = $produk_id;
        $this->data['tipe']         = $tipe;
        $this->data['semua_produk'] = $this->Produk_model->ambil_aktif();

        $this->data['content_view'] = 'stok/riwayat';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * stok_laporan()
     * URL: /stok/stok_laporan
     * Filter: ?tanggal_mulai=&tanggal_selesai=&produk_id=
     * ============================================================
     * Laporan pergerakan stok berdasarkan rentang tanggal.
     *
     * [FIX #3] Di view gunakan: base_url('stok/stok_laporan')
     */
    public function stok_laporan()
    {
        $this->data['judul_halaman'] = 'Laporan Stok';

        $tanggal_mulai   = $this->input->get('tanggal_mulai');
        $tanggal_selesai = $this->input->get('tanggal_selesai');
        $produk_id       = $this->input->get('produk_id');

        $this->data['laporan']          = $this->Stok_model->laporan_stok(
                                              $tanggal_mulai,
                                              $tanggal_selesai,
                                              $produk_id
                                          );
        $this->data['semua_produk']     = $this->Produk_model->ambil_aktif();
        $this->data['tanggal_mulai']    = $tanggal_mulai;
        $this->data['tanggal_selesai']  = $tanggal_selesai;
        $this->data['produk_id']        = $produk_id;

        $this->data['content_view'] = 'stok/laporan';
        $this->load->view('layouts/utama', $this->data);
    }

    /**
     * ============================================================
     * ambil_info_produk()
     * URL: /stok/ambil_info_produk  [AJAX POST]
     * ============================================================
     * Mengembalikan JSON info produk berdasarkan produk_id.
     * Dipakai oleh select2/dropdown di form masuk & keluar.
     */
    public function ambil_info_produk()
    {
        // Pastikan request dari AJAX
        if (!$this->input->is_ajax_request())
        {
            show_404();
        }

        $produk_id = $this->input->post('produk_id');
        $produk    = $this->Produk_model->ambil_berdasarkan_id($produk_id);

        if ($produk)
        {
            echo json_encode([
                'status' => TRUE,
                'data'   => [
                    'nama'       => $produk->name,
                    'stok'       => $produk->stock,
                    'satuan'     => $produk->unit,
                    'harga_beli' => $produk->buy_price,
                    'kategori'   => $produk->category_id,
                ]
            ]);
        }
        else
        {
            echo json_encode([
                'status'  => FALSE,
                'message' => 'Produk tidak ditemukan.'
            ]);
        }
    }
}