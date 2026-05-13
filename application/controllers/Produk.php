<?php
/**
 * Produk.php
 *
 * Controller untuk mengelola halaman produk
 *
 * @author    SMK Assalafiyyah Sleman
 * @version   1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends Admin_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Load model
        $this->load->model('Produk_model');
        $this->load->model('Kategori_model');

        // Load library
        $this->load->library('form_validation');
        $this->load->library('upload');     // Untuk upload gambar

        // Load helper
        $this->load->helper(array('form', 'url', 'file'));
    }

    /**
     * ============================================================
     * FUNCTION: produk_index()
     * ============================================================
     * Halaman daftar semua produk
     * URL: /produk
     */
    public function produk_index()
    {
        $this->data['judul_halaman'] = 'Daftar Produk';
        $this->data['produk'] = $this->Produk_model->ambil_semua();
        $this->data['view_konten'] = 'produk/index';

        $this->load->view('layouts/main', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: produk_tambah()
     * ============================================================
     * Halaman form tambah produk baru
     * URL: /produk/tambah
     *
     * CATATAN: Stok awal produk = 0
     * Stok akan ditambah melalui modul Stok Masuk
     */
    public function produk_tambah()
    {
        $this->data['judul_halaman'] = 'Tambah Produk Baru';
        $this->data['kategori'] = $this->Kategori_model->ambil_semua();

        // Aturan validasi form
        $this->form_validation->set_rules('category_id', 'Kategori', 'required');
        $this->form_validation->set_rules('name', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('buy_price', 'Harga Beli', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('sell_price', 'Harga Jual', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('unit', 'Satuan', 'trim');
        $this->form_validation->set_rules('barcode', 'Barcode', 'trim|callback_cek_barcode_produk');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim');

        // Pesan error bahasa Indonesia
        $this->form_validation->set_message('required', '{field} wajib diisi!');
        $this->form_validation->set_message('numeric', '{field} harus berupa angka!');
        $this->form_validation->set_message('greater_than', '{field} harus lebih dari 0!');

        if ($this->form_validation->run() === TRUE)
        {
            // Handle upload gambar
            $nama_gambar = $this->_upload_gambar();

            if ($nama_gambar === FALSE)
            {
                // Upload gagal, tampilkan form lagi
                 $this->data['content_view'] = 'kategori/index';  // ✅ key benar
                 $this->load->view('layouts/main', $this->data);   // ✅ nama file benar
                return;
            }
    
            // Siapkan data produk
            // CATATAN: Stok awal SELALU 0, akan diisi lewat Stok Masuk
            $data_produk = array(
                'category_id' => $this->input->post('category_id'),
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'buy_price' => $this->input->post('buy_price'),
                'sell_price' => $this->input->post('sell_price'),
                'stock' => 0,                          // STOK AWAL = 0!
                'unit' => $this->input->post('unit') ?: 'pcs',
                'barcode' => $this->input->post('barcode'),
                'image' => $nama_gambar,
                'is_active' => 1
            );

            // Simpan ke database
            $id_produk = $this->Produk_model->tambah($data_produk);

            if ($id_produk)
            {
                $this->session->set_flashdata('success',
                    'Produk <b>' . $data_produk['name'] . '</b> berhasil ditambahkan! ' .
                    'Silakan tambahkan stok melalui menu Stok Masuk.');

                redirect('produk', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk.');
            }
        }

        $this->data['content_view'] = 'kategori/index';  // ✅ key benar
        $this->load->view('layouts/main', $this->data);   // ✅ nama file benar
    }

    /**
     * ============================================================
     * FUNCTION: produk_edit($id)
     * ============================================================
     * Halaman form edit produk
     * URL: /produk/edit/{id}
     */
    public function produk_edit($id = NULL)
    {
        $this->data['judul_halaman'] = 'Edit Produk';
        $this->data['kategori'] = $this->Kategori_model->ambil_semua();

        // Ambil data produk yang akan diedit
        $produk = $this->Produk_model->ambil_berdasarkan_id($id);

        if (!$produk)
        {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan!');
            redirect('produk', 'refresh');
        }

        // Aturan validasi (sama dengan tambah)
        $this->form_validation->set_rules('category_id', 'Kategori', 'required');
        $this->form_validation->set_rules('name', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('buy_price', 'Harga Beli', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('sell_price', 'Harga Jual', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('stock', 'Stok', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('unit', 'Satuan', 'trim');
        $this->form_validation->set_rules('barcode', 'Barcode', 'trim|callback_cek_barcode_produk[' . $id . ']');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim');

        $this->form_validation->set_message('required', '{field} wajib diisi!');
        $this->form_validation->set_message('numeric', '{field} harus berupa angka!');
        $this->form_validation->set_message('greater_than', '{field} harus lebih dari 0!');
        $this->form_validation->set_message('greater_than_equal_to', '{field} tidak boleh negatif!');

        if ($this->form_validation->run() === TRUE)
        {
            // Handle upload gambar baru jika ada
            $nama_gambar = $produk->image;  // Default: pakai gambar lama

            if (!empty($_FILES['image']['name']))
            {
                $nama_gambar_baru = $this->_upload_gambar();

                if ($nama_gambar_baru === FALSE)
                {
                    // Upload gagal
                    $this->data['produk'] = $produk;
                    $this->data['content_view'] = 'kategori/index';  // ✅ key benar
                    $this->load->view('layouts/main', $this->data);   // ✅ nama file benar
                    return;
                }

                // Hapus gambar lama
                if ($produk->image)
                {
                    $path_gambar_lama = FCPATH . 'uploads/products/' . $produk->image;
                    if (file_exists($path_gambar_lama))
                    {
                        unlink($path_gambar_lama);
                    }
                }

                $nama_gambar = $nama_gambar_baru;
            }

            // Siapkan data update
            $data_produk = array(
                'category_id' => $this->input->post('category_id'),
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'buy_price' => $this->input->post('buy_price'),
                'sell_price' => $this->input->post('sell_price'),
                'stock' => $this->input->post('stock'),
                'unit' => $this->input->post('unit') ?: 'pcs',
                'barcode' => $this->input->post('barcode'),
                'image' => $nama_gambar
            );

            // Update data
            if ($this->Produk_model->update($id, $data_produk))
            {
                $this->session->set_flashdata('success',
                    'Produk <b>' . $data_produk['name'] . '</b> berhasil diupdate!');

                redirect('produk', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('error', 'Gagal mengupdate produk.');
            }
        }

        $this->data['produk'] = $produk;
        $this->data['content_view'] = 'kategori/index';  // ✅ key benar
        $this->load->view('layouts/main', $this->data);   // ✅ nama file benar
    }

    /**
     * ============================================================
     * FUNCTION: produk_hapus($id)
     * ============================================================
     * Hapus produk
     * URL: /produk/hapus/{id}
     */
    public function produk_hapus($id)
    {
        if ($this->Produk_model->hapus($id))
        {
            $this->session->set_flashdata('success', 'Berhasil Dihapus.'); 
        }
        else
        {
            $this->session->set_flashdata('error', 'Gagal menghapus produk.');
        }

        redirect('produk', 'refresh');
    }

    /**
     * ============================================================
     * PRIVATE FUNCTION: _upload_gambar()
     * ============================================================
     * Upload gambar produk
     *
     * @return string|FALSE    Nama file gambar jika sukses, FALSE jika gagal
     *
     * Konfigurasi upload:
     * - Path: uploads/products/
     * - Tipe: jpg, jpeg, png, gif
     * - Max size: 2MB
     * - Nama file: Encrypted (random)
     */
    private function _upload_gambar()
    {
        // Konfigurasi upload
        $config['upload_path'] = FCPATH . 'uploads/products/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048;              // 2MB dalam kilobyte
        $config['encrypt_name'] = TRUE;          // Nama file di-acak

        // Load library upload dengan konfigurasi di atas
        $this->load->library('upload', $config);

        // Lakukan upload
        if (!$this->upload->do_upload('image'))
        {
            // Upload gagal, set pesan error
            $this->session->set_flashdata('error',
                'Upload gambar gagal: ' . $this->upload->display_errors('', ' '));

            return FALSE;
        }

        // Upload berhasil, kembalikan nama file
        return $this->upload->data('file_name');
    }

    /**
     * ============================================================
     * CALLBACK: cek_barcode_produk($barcode, $id)
     * ============================================================
     * Cek apakah barcode sudah digunakan
     */
    public function cek_barcode_produk($barcode, $id = '')
    {
        if (empty($barcode))
        {
            return TRUE;  // Barcode boleh kosong
        }

        if ($this->Produk_model->cek_barcode($barcode, $id))
        {
            $this->form_validation->set_message('cek_barcode_produk',
                '{field} sudah digunakan produk lain!');
            return FALSE;
        }

        return TRUE;
    }
}