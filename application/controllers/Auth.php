<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation'); // ← tambah ini
        $this->load->library('session');
        $this->load->helper('url');
    }

    /**
     * Halaman Login
     */
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->ion_auth->logged_in()) {
            redirect('dashboard', 'refresh');
        }

        $this->data['page_title'] = 'Login - Toko Online';

        // Validasi form
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === TRUE) {
            // Cek login
            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'),
                                        $this->input->post('password'), $remember)) {
                // Login berhasil
                $this->session->set_flashdata('success',
                    $this->ion_auth->messages());

                // Redirect ke dashboard
                redirect('dashboard', 'refresh');
            } else {
                // Login gagal
                $this->session->set_flashdata('error',
                    $this->ion_auth->errors());

                redirect('auth/login', 'refresh');
            }
        } else {
            // Tampilkan form login
            $this->load->view('layouts/auth_header', $this->data);
            $this->load->view('auth/login', $this->data);
            $this->load->view('layouts/auth_footer');
        }
    }
    
    /**
     * Alias untuk index()
     */
    public function login()
    {
        $this->index();
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $this->data['title'] = "Logout";

        // Logout
        $logout = $this->ion_auth->logout();

        // Redirect ke halaman login
        $this->session->set_flashdata('success', $this->ion_auth->messages());
        redirect('auth/login', 'refresh');
    }

    /**
     * Forgot Password (opsional, untuk future)
     */
    public function forgot_password()
    {
        // Implementasi nanti
        $this->session->set_flashdata('warning',
            'Fitur reset password belum tersedia. Hubungi admin.');
        redirect('auth/login', 'refresh');
    }
}