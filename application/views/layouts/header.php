<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi Toko Online SMK Assalafiyyah">
    <meta name="author" content="SMK Assalafiyyah Sleman">

    <?php if(isset($page_title)): ?>
    <title><?= $page_title ?> - Toko Online</title>
    <?php else: ?>
    <title>Toko Online - SMK Assalafiyyah</title>
    <?php endif; ?>

    <!-- Custom Fonts -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>"
          rel="stylesheet" type="text/css">

    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>"
          rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>"
          rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>"
          rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
</head>

<body id="page-top">