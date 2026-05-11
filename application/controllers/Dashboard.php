<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['judul_halaman'] = 'Dashboard';

        // Statistik ringkas untuk ditampilkan di dashboard
        $this->load->model('Kategori_model');
        $this->load->model('Produk_model');

        $this->data['total_kategori']  = $this->Kategori_model->hitung_semua();
        $this->data['total_produk']    = $this->Produk_model->hitung_semua();

        $this->data['view_konten'] = 'dashboard/index';
        $this->load->view('layouts/utama', $this->data);
    }
}