<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| MY_Controller
|--------------------------------------------------------------------------
| Controller untuk halaman yang wajib login
|--------------------------------------------------------------------------
*/

class MY_Controller extends CI_Controller {

    public $data = array();

    public function __construct()
    {
        parent::__construct();

        
    }

    protected function set_global_data()
    {
        $user = $this->ion_auth->user()->row();

        $user_groups = $this->ion_auth
            ->get_users_groups($user->id)
            ->result();

        $this->data['current_user'] = $user;
        $this->data['current_user_groups'] = $user_groups;
        $this->data['is_admin'] = $this->ion_auth->is_admin();
    }
}

/*
|--------------------------------------------------------------------------
| Admin Controller
|--------------------------------------------------------------------------
*/

class Admin_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->is_admin()) {
            show_error(
                'Anda tidak memiliki akses',
                403
            );
        }
    }
}