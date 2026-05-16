<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Laporan Laba Rugi</h6>
    </div>

    <div class="card-body">

        <!-- Filter -->
        <form method="GET" class="form-inline mb-4">
            <label class="mr-2">Periode:</label>
            <input type="date" name="tanggal_mulai" class="form-control mr-2"
                   value="<?= $tanggal_mulai ?>" required>
            <span class="mr-2">s/d</span>
            <input type="date" name="tanggal_selesai" class="form-control mr-2"
                   value="<?= $tanggal_selesai ?>" required>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Tampilkan
            </button>
            <a href="<?= base_url('laporan/laporan_laba') ?>" class="btn btn-secondary">Reset</a>
        </form>

        <!-- Ringkasan Laba Rugi -->
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Omset
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= format_rupiah($total_omset) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Modal
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= format_rupiah($total_modal) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Laba Kotor
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= format_rupiah($total_laba) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <span class="badge badge-success"><?= number_format($persen_laba, 1) ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Perhitungan -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="table-secondary">
                        <th>Keterangan</th>
                        <th class="text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Total Penjualan (Omset)</strong></td>
                        <td class="text-right"><?= format_rupiah($total_omset) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Total Modal Barang Terjual</strong></td>
                        <td class="text-right text-danger"><?= format_rupiah($total_modal) ?></td>
                    </tr>
                    <tr class="table-success">
                        <td><strong>Laba Kotor</strong></td>
                        <td class="text-right"><strong><?= format_rupiah($total_laba) ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Persentase Laba</strong></td>
                        <td class="text-right"><?= number_format($persen_laba, 2) ?>%</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>