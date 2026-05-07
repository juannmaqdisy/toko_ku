<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        // Test koneksi database
        $this->load->database();

        if ($this->db->conn_id) {
            echo "✅ Koneksi Database BERHASIL!<br>";
            echo "Database: " . $this->db->database . "<br>";
            echo "Host: " . $this->db->hostname . "<br>";

            // Test query
            $query = $this->db->get('users');
            echo "Jumlah user: " . $query->num_rows();
        } else {
            echo "❌ Koneksi Database GAGAL!";
            echo "<br>Error: " . $this->db->_error_message();
        }
    }
}