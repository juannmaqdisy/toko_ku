<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Login Toko Online">
    <title>Login - Toko Online</title>

    <!-- Custom Fonts -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>"
          rel="stylesheet" type="text/css">

    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>"
          rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>"
          rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        .login-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }
        .login-heading {
            font-weight: 500;
            text-align: center;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 1rem;
            font-size: 3rem;
            color: #667eea;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card login-card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-5">

            <!-- Logo -->
            <div class="login-logo">
                <i class="fas fa-shopping-bag"></i>
            </div>

            <!-- Heading -->
            <div class="login-heading mb-4">
                <h3>Selamat Datang</h3>
                <p class="text-muted">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Flash Messages -->
            <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>

            <!-- Form Login -->
            <?= form_open('auth/login', ['id' => 'loginForm']) ?>

                <!-- Identity (Username) -->
                <div class="form-group">
                    <?= form_label('Username', 'identity') ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <?= form_input([
                            'name' => 'identity',
                            'id' => 'identity',
                            'class' => 'form-control',
                            'placeholder' => 'Masukkan username',
                            'required' => 'required',
                            'autofocus' => 'autofocus',
                            'value' => set_value('identity')
                        ]) ?>
                    </div>
                    <?= form_error('identity', '<small class="text-danger">', '</small>') ?>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <?= form_label('Password', 'password') ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <?= form_password([
                            'name' => 'password',
                            'id' => 'password',
                            'class' => 'form-control',
                            'placeholder' => 'Masukkan password',
                            'required' => 'required'
                        ]) ?>
                    </div>
                    <?= form_error('password', '<small class="text-danger">', '</small>') ?>
                </div>

                <!-- Remember Me -->
                <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                        <?= form_checkbox([
                            'name' => 'remember',
                            'id' => 'remember',
                            'class' => 'custom-control-input'
                        ]) ?>
                        <?= form_label('Ingat Saya', 'remember', ['class' => 'custom-control-label']) ?>
                    </div>
                </div>

                <!-- Login Button -->
                <?= form_submit([
                    'name' => 'submit',
                    'value' => 'Login',
                    'class' => 'btn btn-primary btn-block'
                ]) ?>

            <?= form_close() ?>

            <hr>

            <!-- Info -->
            <div class="text-center">
                <small class="text-muted">
                    &copy; <?= date('Y') ?> Toko Online SMK Assalafiyyah
                </small>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
$(document).ready(function() {
    // Handle form submit dengan AJAX
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        // Disable tombol
        $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Login...');

        // Submit form
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Check jika redirect
                if(response.indexOf('window.location') !== -1) {
                    window.location.href = '<?= base_url('dashboard') ?>';
                } else {
                    location.reload();
                }
            },
            error: function() {
                $('button[type="submit"]').prop('disabled', false).html('Login');
                location.reload();
            }
        });
    });

    // Show SweetAlert jika ada pesan error
    <?php if($this->session->flashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: '<?= $this->session->flashdata('error') ?>',
        confirmButtonColor: '#667eea'
    });
    <?php endif; ?>
});
</script>

</body>
</html>