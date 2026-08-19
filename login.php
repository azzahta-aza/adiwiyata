<?php
session_start();

require_once __DIR__ . "/config/koneksi.php";

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users WHERE username = '$username'"
    );

    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard/index.php");
        exit;

    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | Inventory Adiwiyata</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

</head>

<body
    class="bg-black text-white"
    style="
        min-height: 100vh;
        background:
            radial-gradient(circle at top, rgba(255,255,255,0.06), transparent 30%),
            #000;
    "
>

<div class="container-fluid min-vh-100">

    <div class="row min-vh-100 align-items-center justify-content-center">

        <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">

            <!-- HEADER -->
            <div class="text-center mb-4">

                <div
                    class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                    style="
                        width: 75px;
                        height: 75px;
                        background: linear-gradient(145deg, #1f1f1f, #0b0b0b);
                        border: 1px solid rgba(255,255,255,0.15);
                        box-shadow:
                            0 0 0 8px rgba(255,255,255,0.02),
                            0 10px 35px rgba(0,0,0,0.8);
                    "
                >

                    <i
                        class="bi bi-box-seam-fill fs-2 text-light"
                    ></i>

                </div>

                <h1 class="fw-bold mb-1">
                    Inventory
                </h1>

                <h1 class="fw-bold text-secondary">
                    Adiwiyata
                </h1>

                <p class="text-secondary mb-0">
                    Sistem Informasi Inventory
                </p>

            </div>


            <!-- LOGIN CARD -->
            <div
                class="card bg-dark text-white border-0 rounded-4"
                style="
                    background: linear-gradient(
                        145deg,
                        #151515,
                        #0c0c0c
                    ) !important;

                    box-shadow:
                        0 25px 70px rgba(0,0,0,0.8),
                        inset 0 1px 0 rgba(255,255,255,0.06);

                    border: 1px solid rgba(255,255,255,0.08) !important;
                "
            >

                <div class="card-body p-4 p-md-5">

                    <!-- LOGIN TITLE -->
                    <div class="mb-4">

                        <h4 class="fw-bold mb-1">
                            Selamat Datang 👋
                        </h4>

                        <p class="text-secondary mb-0">
                            Masuk untuk melanjutkan ke sistem.
                        </p>

                    </div>


                    <!-- ERROR -->
                    <?php if ($error != "") : ?>

                        <div
                            class="alert alert-danger border-0 rounded-3 d-flex align-items-center"
                            role="alert"
                        >

                            <i class="bi bi-exclamation-circle-fill me-2"></i>

                            <div>
                                <?= $error; ?>
                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- FORM -->
                    <form method="POST">

                        <!-- USERNAME -->
                        <div class="mb-4">

                            <label
                                class="form-label text-light fw-semibold"
                            >
                                Username
                            </label>

                            <div class="input-group">

                                <span
                                    class="input-group-text bg-black text-secondary border-secondary"
                                >

                                    <i class="bi bi-person"></i>

                                </span>

                                <input
                                    type="text"
                                    name="username"
                                    class="form-control bg-black text-white border-secondary py-2"
                                    placeholder="Masukkan username"
                                    autocomplete="username"
                                    required
                                    style="
                                        box-shadow: none;
                                    "
                                >

                            </div>

                        </div>


                        <!-- PASSWORD -->
                        <div class="mb-4">

                            <div class="d-flex justify-content-between">

                                <label
                                    class="form-label text-light fw-semibold"
                                >
                                    Password
                                </label>

                            </div>

                            <div class="input-group">

                                <span
                                    class="input-group-text bg-black text-secondary border-secondary"
                                >

                                    <i class="bi bi-lock"></i>

                                </span>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control bg-black text-white border-secondary py-2"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
                                    required
                                    style="
                                        box-shadow: none;
                                    "
                                >

                                <button
                                    type="button"
                                    class="btn btn-dark border border-secondary"
                                    onclick="togglePassword()"
                                >

                                    <i
                                        class="bi bi-eye"
                                        id="eyeIcon"
                                    ></i>

                                </button>

                            </div>

                        </div>


                        <!-- REMEMBER INFO -->
                        <div class="d-flex align-items-center mb-4">

                            <div class="form-check">

                                <input
                                    class="form-check-input bg-black border-secondary"
                                    type="checkbox"
                                    id="remember"
                                >

                                <label
                                    class="form-check-label text-secondary small"
                                    for="remember"
                                >
                                    Tetap masuk
                                </label>

                            </div>

                        </div>


                        <!-- BUTTON -->
                        <div class="d-grid">

                            <button
                                type="submit"
                                name="login"
                                class="btn btn-light fw-bold py-3 rounded-3"
                                style="
                                    transition: 0.3s;
                                "
                                onmouseover="
                                    this.style.transform='translateY(-2px)';
                                    this.style.boxShadow='0 10px 25px rgba(255,255,255,0.12)';
                                "
                                onmouseout="
                                    this.style.transform='translateY(0)';
                                    this.style.boxShadow='none';
                                "
                            >

                                <i class="bi bi-box-arrow-in-right me-2"></i>

                                Masuk ke Sistem

                            </button>

                        </div>

                    </form>


                    <!-- SECURITY -->
                    <div
                        class="text-center mt-4 pt-4"
                        style="
                            border-top: 1px solid rgba(255,255,255,0.07);
                        "
                    >

                        <div
                            class="d-inline-flex align-items-center text-secondary small"
                        >

                            <i class="bi bi-shield-lock me-2"></i>

                            Akses sistem dilindungi

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="text-center mt-4">

                <p class="text-secondary small mb-1">

                    © 2026 Inventory Adiwiyata

                </p>

                <p
                    class="text-secondary"
                    style="font-size: 11px;"
                >

                    Sistem Manajemen Inventory

                </p>

            </div>

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (password.type === "password") {

        password.type = "text";

        eyeIcon.classList.remove("bi-eye");

        eyeIcon.classList.add("bi-eye-slash");

    } else {

        password.type = "password";

        eyeIcon.classList.remove("bi-eye-slash");

        eyeIcon.classList.add("bi-eye");

    }

}

</script>

</body>

</html>