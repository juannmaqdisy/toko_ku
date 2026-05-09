<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
        <a href="<?= base_url('user/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($users)): $no = 1; foreach($users as $user): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></td>
                        <td><?= htmlspecialchars($user->username) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td>
                            <?php foreach($user->groups as $group): ?>
                            <span class="badge badge-info"><?= $group->name ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php if($user->active): ?>
                            <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                            <span class="badge badge-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('user/edit/' . $user->id) ?>"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <?php if($user->active): ?>
                            <a href="<?= base_url('user/deactivate/' . $user->id) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Nonaktifkan user ini?')"
                               title="Nonaktifkan">
                                <i class="fas fa-ban"></i>
                            </a>
                            <?php else: ?>
                            <a href="<?= base_url('user/activate/' . $user->id) ?>"
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Aktifkan user ini?')"
                               title="Aktifkan">
                                <i class="fas fa-check"></i>
                            </a>
                            <?php endif; ?>

                            <?php if($user->id != $current_user->id): ?>
                            <a href="<?= base_url('user/delete/' . $user->id) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus user ini?')"
                               title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data user</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script: DataTables -->
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });
});
</script>