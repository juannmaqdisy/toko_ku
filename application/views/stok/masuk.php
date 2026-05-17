<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card shadow mb-4">

    <!-- Header -->
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-success">
            <i class="fas fa-plus-circle"></i> Form Stok Masuk
        </h6>

        <a href="<?= base_url('stok') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Body -->
    <div class="card-body">

        <!-- Error validation -->
        <?php if(validation_errors()): ?>
            <div class="alert alert-danger">
                <?= validation_errors(); ?>
            </div>
        <?php endif; ?>

        <?= form_open('stok/stok_masuk') ?>

        <div class="row">

            <!-- Pilih Produk -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Pilih Produk</strong></label>

                    <select name="product_id" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>

                        <?php foreach($produk as $p): ?>
                            <option value="<?= $p->id ?>">
                                <?= $p->name ?> 
                                (Stok: <?= $p->stock ?> <?= $p->unit ?>)
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Jumlah Stok Masuk</strong></label>

                    <input type="number"
                           name="quantity"
                           class="form-control"
                           min="1"
                           required
                           placeholder="Masukkan jumlah stok">
                </div>
            </div>

        </div>

        <div class="row">

            <!-- Harga Beli -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Harga Beli (Opsional)</strong></label>

                    <input type="number"
                           name="price"
                           class="form-control"
                           placeholder="Masukkan harga beli">
                </div>
            </div>

            <!-- Keterangan -->
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Keterangan</strong></label>

                    <input type="text"
                           name="notes"
                           class="form-control"
                           placeholder="Contoh: Restock supplier">
                </div>
            </div>

        </div>

        <hr>

        <!-- Tombol -->
        <div class="text-right">
            <a href="<?= base_url('stok') ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>

        <?= form_close() ?>

    </div>
</div>