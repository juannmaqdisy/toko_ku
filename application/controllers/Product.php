<?php
public function index() {
    $data['product'] = $this->product_model->get_all();
    $this->load->view('product/index', $data);
}