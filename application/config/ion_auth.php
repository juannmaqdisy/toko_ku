<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Ion Auth
|--------------------------------------------------------------------------
|
| Konfigurasi untuk Ion Auth library
|
*/

$config['identity'] = 'username'; // Login pakai username
$config['min_password_length'] = 6; // Minimal password
$config['max_password_length'] = 20;
$config['email_activation'] = FALSE; // Tidak butuh email activation
$config['manual_activation'] = TRUE;
$config['remember_users'] = TRUE;
$config['user_expire'] = 86500;
$config['user_extend_on_login'] = FALSE;
$config['track_login_attempts'] = TRUE; // Track percobaan login
$config['track_login_ip_address'] = TRUE;
$config['maximum_login_attempts'] = 3; // Max 3x salah
$config['lockout_time'] = 600; // Lock 10 menit (dalam detik)
$config['forgot_password_expiration'] = 0;
$config['recheck_timer'] = 0;

/*
|--------------------------------------------------------------------------
| Email Config
|--------------------------------------------------------------------------
*/
$config['use_ci_email'] = FALSE; // Pakai CI email library
$config['email_config'] = array(
    'mailtype' => 'html',
);