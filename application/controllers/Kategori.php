<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    private $data = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Kategori_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'form']);
    }

    /**
     * =========================================================
     * METHOD RENDER
     * =========================================================
     */
    private function render($view)
    {
        $this->data['content_view'] = $view;

        $this->load->view('layouts/main', $this->data);
    }

    /**
     * =========================================================
     * DAFTAR KATEGORI
     * URL: kategori
     * =========================================================
     */
    public function index()
    {
        $this->data['page_title'] = 'Daftar Kategori';

        // Ambil semua kategori + jumlah produk
        $this->data['kategori'] =
            $this->Kategori_model->ambil_semua();

        $this->render('kategori/index');
    }

    /**
     * =========================================================
     * TAMBAH KATEGORI
     * URL: kategori/tambah
     * =========================================================
     */
    public function tambah()
    {
        $this->data['page_title'] = 'Tambah Kategori';

        $this->validasi_form();

        if ($this->form_validation->run() == TRUE)
        {
            $data = [
                'name' => $this->input->post('name', TRUE),
                'description' => $this->input->post('description', TRUE)
            ];

            $insert = $this->Kategori_model->tambah($data);

            if ($insert)
            {
                $this->session->set_flashdata(
                    'success',
                    'Kategori berhasil ditambahkan.'
                );

                redirect('kategori');
            }

            $this->session->set_flashdata(
                'error',
                'Gagal menambahkan kategori.'
            );
        }

        $this->render('kategori/tambah');
    }

    /**
     * =========================================================
     * EDIT KATEGORI
     * URL: kategori/edit/1
     * =========================================================
     */
    public function edit($id = NULL)
    {
        if (!$id || !is_numeric($id))
        {
            show_404();
        }

        $kategori =
            $this->Kategori_model->ambil_berdasarkan_id($id);

        if (!$kategori)
        {
            $this->session->set_flashdata(
                'error',
                'Kategori tidak ditemukan.'
            );

            redirect('kategori');
        }

        $this->data['page_title'] = 'Edit Kategori';
        $this->data['kategori'] = $kategori;

        $this->validasi_form($id);

        if ($this->form_validation->run() == TRUE)
        {
            $data = [
                'name' => $this->input->post('name', TRUE),
                'description' => $this->input->post('description', TRUE)
            ];

            $update =
                $this->Kategori_model->update($id, $data);

            if ($update)
            {
                $this->session->set_flashdata(
                    'success',
                    'Kategori berhasil diupdate.'
                );

                redirect('kategori');
            }

            $this->session->set_flashdata(
                'error',
                'Gagal mengupdate kategori.'
            );
        }

        $this->render('kategori/edit');
    }

    /**
     * =========================================================
     * HAPUS KATEGORI
     * URL: kategori/hapus/1
     * =========================================================
     */
    public function hapus($id = NULL)
    {
        if (!$id || !is_numeric($id))
        {
            show_404();
        }

        $hapus = $this->Kategori_model->hapus($id);

        if ($hapus['status'])
        {
            $this->session->set_flashdata(
                'success',
                $hapus['message']
            );
        }
        else
        {
            $this->session->set_flashdata(
                'error',
                $hapus['message']
            );
        }

        redirect('kategori');
    }

    /**
     * =========================================================
     * VALIDASI FORM
     * =========================================================
     */
    private function validasi_form($id = NULL)
    {
        $rule = $id
            ? 'required|trim|callback_cek_nama_kategori['.$id.']'
            : 'required|trim|is_unique[categories.name]';

        $this->form_validation->set_rules(
            'name',
            'Nama Kategori',
            $rule
        );

        $this->form_validation->set_rules(
            'description',
            'Deskripsi',
            'trim'
        );

        $this->form_validation->set_message(
            'required',
            '{field} wajib diisi.'
        );

        $this->form_validation->set_message(
            'is_unique',
            '{field} sudah digunakan.'
        );
    }

    /**
     * =========================================================
     * CALLBACK VALIDASI NAMA
     * =========================================================
     */
    public function cek_nama_kategori($nama, $id)
    {
        if ($this->Kategori_model->cek_nama($nama, $id))
        {
            $this->form_validation->set_message(
                'cek_nama_kategori',
                '{field} sudah digunakan kategori lain.'
            );

            return FALSE;
        }

        return TRUE;
    }
}