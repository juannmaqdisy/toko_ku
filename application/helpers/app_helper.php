<?php
defined('BASEPATH') OR exit('No direct script access reserved');


/**
 * Format angka menjadi format Rupiah
 * Contoh: format_rupiah(15000) → "Rp 15.000"
 */
if (!function_exists('format_rupiah')) {
    function format_rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

/**
 * Format tanggal ke format Indonesia
 *
 * @param string $date
 * @return string
 */
if (!function_exists('format_date_id')) {
    function format_date_id($date)
    {
        if (empty($date)) return '-';

        $timestamp = strtotime($date);

        $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        return $hari[date('w', $timestamp)] . ', ' .
               date('j', $timestamp) . ' ' .
               $bulan[date('n', $timestamp) - 1] . ' ' .
               date('Y', $timestamp);
    }
}

/**
 * Format tanggal waktu ke format Indonesia
 *
 * @param string $datetime
 * @return string
 */
if (!function_exists('format_datetime_id')) {
    function format_datetime_id($datetime)
    {
        if (empty($datetime)) return '-';

        return format_date_id($datetime) . ' ' . date('H:i', strtotime($datetime));
    }
}

/**
 * Generate nomor transaksi
 *
 * @param string $prefix
 * @return string
 */
if (!function_exists('generate_transaction_no')) {
    function generate_transaction_no($prefix = 'TRX')
    {
        return $prefix . date('Ymd') . rand(1000, 9999);
    }
}

/**
 * Cek apakah user adalah admin
 *
 * @return bool
 */
if (!function_exists('is_admin')) {
    function is_admin()
    {
        $CI =& get_instance();
        return $CI->ion_auth->is_admin();
    }
}

/**
 * Preprint data untuk debugging
 *
 * @param mixed $data
 * @param bool $die
 */
if (!function_exists('pre')) {
    function pre($data, $die = FALSE)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        if ($die) die();
    }
}