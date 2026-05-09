<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    /**
     * List semua user
     */
    public function index()
    {
        $this->data['page_title'] = 'Manajemen User';

        // Ambil semua user
        $this->data['users'] = $this->ion_auth->users()->result();

        // Ambil group untuk setiap user
        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        // Render view
        $this->data['content_view'] = 'user/index';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Tambah user baru
     */
    public function create()
    {
        $this->data['page_title'] = 'Tambah User';

        // Validasi form
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('company', 'Company', 'trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('groups', 'Groups', 'required|xss_clean');

        if ($this->form_validation->run() === TRUE) {
            // Prepare data
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'company' => $this->input->post('company'),
            );

            // Register user
            $user_id = $this->ion_auth->register($username, $password, $email, $additional_data);

            if ($user_id) {
                // Add ke group
                $groups = $this->input->post('groups');
                $this->ion_auth->add_to_group($groups, $user_id);

                // Set flashdata
                $this->session->set_flashdata('success',
                    $this->ion_auth->messages());

                redirect('user', 'refresh');
            } else {
                $this->session->set_flashdata('error',
                    $this->ion_auth->errors());
            }
        }

        // Tampilkan form
        $this->data['groups'] = $this->ion_auth->groups()->result_array();
        $this->data['content_view'] = 'user/create';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Edit user
     */
    public function edit($id)
    {
        $this->data['page_title'] = 'Edit User';

        // Cek apakah user ada
        $user = $this->ion_auth->user($id)->row();
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('user', 'refresh');
        }

        // Validasi form
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('company', 'Company', 'trim');

        // Password validation only if filled
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
            $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'matches[password]');
        }

        if ($this->form_validation->run() === TRUE) {
            // Update data
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'company' => $this->input->post('company'),
            );

            // Update password jika diisi
            if ($this->input->post('password')) {
                $data['password'] = $this->input->post('password');
            }

            // Update user
            if ($this->ion_auth->update($user->id, $data)) {
                // Update groups
                $groups = $this->input->post('groups');
                if ($groups) {
                    $this->ion_auth->remove_from_group('', $user->id);
                    foreach ($groups as $group) {
                        $this->ion_auth->add_to_group($group, $user->id);
                    }
                }

                $this->session->set_flashdata('success',
                    $this->ion_auth->messages());
                redirect('user', 'refresh');
            } else {
                $this->session->set_flashdata('error',
                    $this->ion_auth->errors());
            }
        }

        // Tampilkan form
        $this->data['user'] = $user;
        $this->data['groups'] = $this->ion_auth->groups()->result_array();
        $this->data['user_groups'] = $this->ion_auth->get_users_groups($user->id)->result();

        $this->data['content_view'] = 'user/edit';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Hapus user
     */
    public function delete($id)
    {
        // Cek apakah user bukan diri sendiri
        if ($id === $this->data['current_user']->id) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus akun sendiri.');
            redirect('user', 'refresh');
        }

        // Hapus user
        if ($this->ion_auth->delete_user($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user.');
        }

        redirect('user', 'refresh');
    }

    /**
     * Activate/Deactivate user
     */
    public function activate($id, $code = FALSE)
    {
        if ($this->ion_auth->activate($id, $code)) {
            $this->session->set_flashdata('success', 'User berhasil diaktifkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan user.');
        }

        redirect('user', 'refresh');
    }

    public function deactivate($id)
    {
        if ($id === $this->data['current_user']->id) {
            $this->session->set_flashdata('error', 'Tidak bisa menonaktifkan akun sendiri.');
            redirect('user', 'refresh');
        }

        if ($this->ion_auth->deactivate($id)) {
            $this->session->set_flashdata('success', 'User berhasil dinonaktifkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan user.');
        }

        redirect('user', 'refresh');
    }
}