<?php
/**
 * Kategori.php
 *
 * Controller untuk mengelola halaman kategori produk
 * Semua nama function menggunakan bahasa Indonesia
 *
 * @author    SMK Assalafiyyah Sleman
 * @version   1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends Admin_Controller {

    /**
     * Constructor - dipanggil saat pertama kali controller di-load
     * Fungsi ini untuk load model, library, dll yang dibutuhkan
     */
    public function __construct()
    {
        parent::__construct();

        // Load model Kategori_model
        $this->load->model('Kategori_model');

        // Load library form_validation untuk validasi input form
        $this->load->library('form_validation');

        // Load helper URL untuk fungsi base_url(), site_url(), dll
        $this->load->helper('url');
    }

    /**
     * ============================================================
     * FUNCTION: kategori_index()
     * ============================================================
     * Halaman daftar semua kategori
     * URL: /kategori
     *
     * Fungsi ini:
     * 1. Mengambil semua data kategori dari database
     * 2. Menampilkan data ke tabel dengan DataTables
     */
    public function kategori_index()
    {
        // Set judul halaman
        $this->data['judul_halaman'] = 'Daftar Kategori Produk';

        // Ambil semua data kategori dari model
        $this->data['kategori'] = $this->Kategori_model->ambil_semua();

        // Set view yang akan ditampilkan
        $this->data['view_konten'] = 'kategori/index';

        // Load layout utama dengan data di atas
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * ============================================================
     * FUNCTION: kategori_tambah()
     * ============================================================
     * Halaman form tambah kategori baru
     * URL: /kategori/tambah
     *
     * Fungsi ini:
     * 1. Menampilkan form input kategori
     * 2. Memproses data saat form disubmit
     * 3. Validasi input form
     * 4. Simpan data ke database jika valid
     */
    public function kategori_tambah()
    {
        // Set judul halaman
        $this->data['judul_halaman'] = 'Tambah Kategori Baru';

        // Aturan validasi form
        // - name: wajib diisi, harus unik (tidak boleh sama dengan yang sudah ada)
        // - description: boleh kosong
        $this->form_validation->set_rules(
            'name',                          // Nama field input
            'Nama Kategori',                 // Label human-readable
            'required|trim|is_unique[categories.name]'  // Aturan validasi
        );

        $this->form_validation->set_rules(
            'description',
            'Deskripsi',
            'trim'
        );

        // Aturan pesan error dalam bahasa Indonesia
        $this->form_validation->set_message('required', '{field} wajib diisi!');
        $this->form_validation->set_message('is_unique', '{field} sudah ada, gunakan nama lain!');

        // Cek apakah form sudah disubmit dan valid
        if ($this->form_validation->run() === TRUE)
        {
            // Siapkan data untuk disimpan ke database
            $data_kategori = array(
                'name' => $this->input->post('name'),           // Ambil dari input form
                'description' => $this->input->post('description')
            );

            // Simpan ke database melalui model
            $id_kategori = $this->Kategori_model->tambah($data_kategori);

            // Cek apakah penyimpanan berhasil
            if ($id_kategori)
            {
                // Set pesan sukses menggunakan flashdata
                $this->session->set_flashdata('success',
                    'Kategori <b>' . $data_kategori['name'] . '</b> berhasil ditambahkan!');

                // Redirect ke halaman daftar kategori
                redirect('kategori', 'refresh');
            }
            else
            {
                // Set pesan error
                $this->session->set_flashdata('error',
                    'Gagal menambahkan kategori. Silakan coba lagi.');
            }
        }

        // Jika form belum disubmit atau validasi gagal, tampilkan form
        $this->data['content_view'] = 'kategori/index';  // ✅ key benar
        $this->load->view('layouts/main', $this->data);   // ✅ nama file benar
    }

    /**
     * ============================================================
     * FUNCTION: kategori_edit($id)
     * ============================================================
     * Halaman form edit kategori
     * URL: /kategori/edit/{id}
     *
     * @param int $id    ID kategori yang akan diedit
     *
     * Fungsi ini:
     * 1. Mengambil data kategori berdasarkan ID
     * 2. Menampilkan form edit dengan data yang sudah ada
     * 3. Memproses update data saat form disubmit
     */
    public function kategori_edit($id = NULL)
    {
        // Set judul halaman
        $this->data['judul_halaman'] = 'Edit Kategori';

        // Ambil data kategori yang akan diedit
        $kategori = $this->Kategori_model->ambil_berdasarkan_id($id);

        // Cek apakah kategori ada
        if (!$kategori)
        {
            // Jika tidak ada, tampilkan pesan error dan redirect
            $this->session->set_flashdata('error',
                'Kategori tidak ditemukan!');

            redirect('kategori', 'refresh');
        }

        // Aturan validasi form
        // Sama dengan tambah, tapi nama harus unik KEUALI dirinya sendiri
        $this->form_validation->set_rules(
            'name',
            'Nama Kategori',
            'required|trim|callback_cek_nama_kategori[' . $id . ']'
        );

        $this->form_validation->set_rules(
            'description',
            'Deskripsi',
            'trim'
        );

        // Aturan pesan error
        $this->form_validation->set_message('required', '{field} wajib diisi!');

        // Cek apakah form disubmit dan valid
        if ($this->form_validation->run() === TRUE)
        {
            // Siapkan data untuk diupdate
            $data_kategori = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            );

            // Update data melalui model
            $hasil = $this->Kategori_model->update($id, $data_kategori);

            // Cek apakah update berhasil
            if ($hasil)
            {
                $this->session->set_flashdata('success',
                    'Kategori <b>' . $data_kategori['name'] . '</b> berhasil diupdate!');

                redirect('kategori', 'refresh');
            }
            else
            {
                $this->session->set_flashdata('error',
                    'Gagal mengupdate kategori. Silakan coba lagi.');
            }
        }

        // Kirim data kategori ke view
        $this->data['kategori'] = $kategori;

        // Tampilkan form edit
        $this->data['content_view'] = 'kategori/index';  // ✅ key benar
        $this->load->view('layouts/main', $this->data);   // ✅ nama file benar
    }

    /**
     * ============================================================
     * FUNCTION: kategori_hapus($id)
     * ============================================================
     * Menghapus data kategori
     * URL: /kategori/hapus/{id}
     *
     * @param int $id    ID kategori yang akan dihapus
     *
     * Fungsi ini:
     * 1. Memanggil model untuk menghapus data
     * 2. Mengecek apakah kategori bisa dihapus (tidak ada produk yang menggunakannya)
     * 3. Memberikan feedback ke user
     */
    public function kategori_hapus($id)
    {
        // Panggil model untuk menghapus
        $hasil = $this->Kategori_model->hapus($id);

        // Cek hasil operasi hapus
        if ($hasil['status'] === TRUE)
        {
            // Jika berhasil
            $this->session->set_flashdata('success', $hasil['message']);
        }
        else
        {
            // Jika gagal (misalnya masih ada produk yang menggunakan)
            $this->session->set_flashdata('error', $hasil['message']);
        }

        // Redirect kembali ke daftar kategori
        redirect('kategori', 'refresh');
    }

    /**
     * ============================================================
     * CALLBACK: cek_nama_kategori($nama, $id)
     * ============================================================
     * Callback function untuk validasi nama kategori unique
     * Dicek saat edit: nama tidak boleh sama dengan kategori lain
     *
     * @param string $nama    Nama kategori yang dicek
     * @param int $id         ID kategori yang sedang diedit
     * @return bool           TRUE jika valid, FALSE jika tidak
     */
    public function cek_nama_kategori($nama, $id)
    {
        // Panggil fungsi cek_nama dari model
        if ($this->Kategori_model->cek_nama($nama, $id))
        {
            // Jika nama sudah ada
            $this->form_validation->set_message('cek_nama_kategori',
                '{field} sudah digunakan oleh kategori lain!');

            return FALSE;
        }

        // Jika nama belum ada, valid
        return TRUE;
    }
}