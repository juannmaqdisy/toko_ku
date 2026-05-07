<?php
public function get_all() {
    return $this->db->get('product')->result();
}