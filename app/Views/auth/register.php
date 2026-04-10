<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-sm" style="width: 400px;">
        <div class="card-body">
            <h4 class="text-center mb-4">Register</h4>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form method="post" action="/register">

                <?php if (!empty($next)): ?>
                    <input type="hidden" name="next" value="<?= esc($next) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Register
                </button>
            </form>

            <hr class="my-4">

            <!-- 🔥 GOOGLE REGISTER -->
            <a href="/auth/google<?= !empty($next) ? '?next=' . urlencode($next) : '' ?>"
               class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <img src="https://developers.google.com/identity/images/g-logo.png" width="18">
                Daftar dengan Google
            </a>

            <div class="text-center mt-3">
                <small>
                    Sudah punya akun?
                    <a href="/login<?= !empty($next) ? '?next=' . urlencode($next) : '' ?>">
                        Login
                    </a>
                </small>
            </div>

        </div>
    </div>
</div>

</body>
</html>
