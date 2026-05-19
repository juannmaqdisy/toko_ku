<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Ion Auth Core Config
|--------------------------------------------------------------------------
*/
$config['identity']                 = 'username'; 
$config['min_password_length']      = 6;
$config['max_password_length']      = 20;
$config['email_activation']         = FALSE;
$config['manual_activation']        = TRUE;
$config['remember_users']           = TRUE;
$config['user_expire']              = 86500;
$config['user_extend_on_login']     = FALSE;
$config['track_login_attempts']     = TRUE;
$config['track_login_ip_address']   = TRUE;
$config['maximum_login_attempts']   = 3;
$config['lockout_time']             = 600;
$config['forgot_password_expiration'] = 0;
$config['recheck_timer']            = 0;

/*
|--------------------------------------------------------------------------
| Email Config
|--------------------------------------------------------------------------
*/
$config['use_ci_email']             = FALSE;
$config['email_config']             = array('mailtype' => 'html');

/*
|--------------------------------------------------------------------------
| Tables Config (Penyebab Error Jika Hilang)
|--------------------------------------------------------------------------
*/
$config['tables']['users']           = 'users';
$config['tables']['groups']          = 'groups';
$config['tables']['users_groups']     = 'users_groups';
$config['tables']['login_attempts']   = 'login_attempts';

/*
|--------------------------------------------------------------------------
| Columns Config
|--------------------------------------------------------------------------
*/
$config['columns']['users']['id']       = 'id';
$config['columns']['users']['username'] = 'username';
$config['columns']['users']['password'] = 'password';
$config['columns']['users']['email']    = 'email';
$config['columns']['users']['active']   = 'active';