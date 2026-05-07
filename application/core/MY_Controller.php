<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| MY_Controller
|--------------------------------------------------------------------------
|
| Base controller untuk semua controller
| Melakukan pengecekan auth secara otomatis
|
*/

class MY_Controller extends CI_Controller {

    public $data = array();

    public function __construct()
    {
        parent::__construct();

        // Cek login
        if (!$this->ion_auth->logged_in()) {
            // Jika belum login, redirect ke halaman login
            redirect('auth/login', 'refresh');
        }

        // Set data global untuk semua view
        $this->set_global_data();
    }

    /**
     * Set data yang digunakan di semua halaman
     */
    protected function set_global_data()
    {
        // Data user yang sedang login
        $user = $this->ion_auth->user()->row();

        // Data groups/role user
        $user_groups = $this->ion_auth->get_users_groups($user->id)->result();

        $this->data['current_user'] = $user;
        $this->data['current_user_groups'] = $user_groups;
        $this->data['is_admin'] = $this->ion_auth->is_admin();
    }
}

/*
|--------------------------------------------------------------------------
| Admin_Controller
|--------------------------------------------------------------------------
|
| Controller untuk halaman admin only
|
*/
class Admin_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        // Cek apakah user adalah admin
        if (!$this->ion_auth->is_admin()) {
            // Jika bukan admin, redirect atau show 403
            show_error('Anda tidak memiliki akses ke halaman ini.', 403);
        }
    }
}