<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?= isset($page_title) ? $page_title . ' - Toko Online' : 'Toko Online' ?>
    </title>

    <!-- Font Awesome -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">

    <!-- SB Admin CSS -->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">

    <?php $this->load->view('layouts/sidebar'); ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <?php $this->load->view('layouts/topnav'); ?>

            <div class="container-fluid">

                <?php if(isset($page_title)): ?>
                    <h1 class="h3 mb-4 text-gray-800">
                        <?= htmlspecialchars($page_title) ?>
                    </h1>
                <?php endif; ?>

                <?php
                if(isset($content_view)) {
                    $this->load->view($content_view);
                } else {
                    echo '<div class="alert alert-danger">View tidak ditemukan</div>';
                }
                ?>

            </div>

        </div>

        <?php $this->load->view('layouts/footer'); ?>

    </div>
</div>

<!-- JS -->
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>

</body>
</html>