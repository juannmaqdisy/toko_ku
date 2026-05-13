<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="wrapper">

    <?php if(isset($page_title)): ?>
    <title><?= $page_title ?> - Toko Online</title>
    <?php else: ?>
    <title>Toko Online - SMK Assalafiyyah</title>
    <?php endif; ?>

    <!-- SIDEBAR -->
    <?php $this->load->view('layouts/sidebar'); ?>

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- MAIN CONTENT -->
        <div id="content">

            <!-- TOPBAR -->
            <?php $this->load->view('layouts/topnav'); ?>

            <!-- PAGE CONTENT -->
            <div class="container-fluid">

                <!-- PAGE TITLE -->
                <?php if(isset($page_title)): ?>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                        <h1 class="h3 mb-0 text-gray-800">

                            <?= htmlspecialchars($page_title) ?>

                        </h1>

                    </div>

                <?php endif; ?>

                <!-- FLASH SUCCESS -->
                <?php if($this->session->flashdata('success')): ?>

                    <div class="alert alert-success alert-dismissible fade show shadow-sm">

                        <i class="fas fa-check-circle mr-1"></i>

                        <?= $this->session->flashdata('success') ?>

                        <button type="button"
                                class="close"
                                data-dismiss="alert">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>

                <!-- FLASH ERROR -->
                <?php if($this->session->flashdata('error')): ?>

                    <div class="alert alert-danger alert-dismissible fade show shadow-sm">

                        <i class="fas fa-times-circle mr-1"></i>

                        <?= $this->session->flashdata('error') ?>

                        <button type="button"
                                class="close"
                                data-dismiss="alert">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>

                <!-- FLASH WARNING -->
                <?php if($this->session->flashdata('warning')): ?>

                    <div class="alert alert-warning alert-dismissible fade show shadow-sm">

                        <i class="fas fa-exclamation-triangle mr-1"></i>

                        <?= $this->session->flashdata('warning') ?>

                        <button type="button"
                                class="close"
                                data-dismiss="alert">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>

                <!-- CONTENT -->
                <?php
                if(isset($content_view))
                {
                    $this->load->view($content_view);
                }
                else
                {
                    echo '<div class="alert alert-danger">
                            View tidak ditemukan.
                          </div>';
                }
                ?>

            </div>
            <!-- END CONTAINER -->

        </div>
        <!-- END MAIN CONTENT -->

        <!-- FOOTER -->
        <?php $this->load->view('layouts/footer'); ?>

    </div>
    <!-- END CONTENT WRAPPER -->

</div>
<!-- END WRAPPER -->