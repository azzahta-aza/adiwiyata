<?php

session_start();

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| CEK LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| DATA USER
|--------------------------------------------------------------------------
*/

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];


/*
|--------------------------------------------------------------------------
| STATISTIK DASHBOARD
|--------------------------------------------------------------------------
*/

// Total jenis barang
$query_barang = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM barang"
);

$total_barang = mysqli_fetch_assoc($query_barang)['total'];


// Total stok
$query_stok = mysqli_query(
    $koneksi,
    "SELECT COALESCE(SUM(stok), 0) AS total FROM barang"
);

$total_stok = mysqli_fetch_assoc($query_stok)['total'];


// Barang hampir habis
$query_hampir_habis = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM barang 
     WHERE stok <= 5"
);

$barang_hampir_habis = mysqli_fetch_assoc($query_hampir_habis)['total'];


// Barang rusak
$query_rusak = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM barang 
     WHERE kondisi = 'Rusak'"
);

$barang_rusak = mysqli_fetch_assoc($query_rusak)['total'];


// Barang sedang dipinjam
$query_dipinjam = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM peminjaman 
     WHERE status = 'Dipinjam'"
);

$barang_dipinjam = mysqli_fetch_assoc($query_dipinjam)['total'];


/*
|--------------------------------------------------------------------------
| TRANSAKSI TERBARU
|--------------------------------------------------------------------------
*/

$query_transaksi = mysqli_query(
    $koneksi,
    "SELECT 
        transaksi.*,
        barang.nama_barang,
        users.nama AS nama_user

     FROM transaksi

     LEFT JOIN barang
        ON transaksi.id_barang = barang.id_barang

     LEFT JOIN users
        ON transaksi.id_user = users.id_user

     ORDER BY transaksi.tanggal DESC,
              transaksi.id_transaksi DESC

     LIMIT 5"
);


/*
|--------------------------------------------------------------------------
| SAPAAN
|--------------------------------------------------------------------------
*/

$jam = date("H");

if ($jam < 12) {

    $sapaan = "Selamat Pagi";

} elseif ($jam < 18) {

    $sapaan = "Selamat Siang";

} else {

    $sapaan = "Selamat Malam";
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

    <title>Dashboard | Inventory Adiwiyata</title>


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


<body class="bg-black text-white">


<!-- ========================================================= -->
<!-- NAVBAR -->
<!-- ========================================================= -->

<nav
    class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-secondary border-opacity-25 sticky-top"
>

    <div class="container-fluid px-4">


        <!-- BRAND -->

        <a
            class="navbar-brand fw-bold d-flex align-items-center gap-2"
            href="index.php"
        >

            <span
                class="bg-light text-dark rounded-3 p-2 d-flex align-items-center justify-content-center"
            >

                <i class="bi bi-box-seam-fill"></i>

            </span>

            <span>
                Inventory Adiwiyata
            </span>

        </a>


        <!-- MOBILE BUTTON -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarMenu"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">


                <!-- USER -->

                <li class="nav-item">

                    <div
                        class="d-flex align-items-center gap-3 px-3 py-2"
                    >

                        <div
                            class="bg-dark border border-secondary rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;"
                        >

                            <i class="bi bi-person-fill"></i>

                        </div>


                        <div>

                            <div class="fw-semibold small">

                                <?= htmlspecialchars($nama); ?>

                            </div>

                            <div class="text-secondary small">

                                <?= htmlspecialchars($role); ?>

                            </div>

                        </div>

                    </div>

                </li>


                <!-- LOGOUT -->

                <li class="nav-item">

                    <a
                        href="../logout.php"
                        class="btn btn-outline-light btn-sm rounded-3 px-3"
                    >

                        <i class="bi bi-box-arrow-right me-1"></i>

                        Keluar

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- ========================================================= -->
<!-- MAIN -->
<!-- ========================================================= -->

<main class="container-fluid px-4 py-4">


    <!-- ===================================================== -->
    <!-- HERO -->
    <!-- ===================================================== -->

    <div
        class="card bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg mb-4"
    >

        <div class="card-body p-4 p-lg-5">

            <div class="row align-items-center">


                <!-- TEXT -->

                <div class="col-lg-8">

                    <span
                        class="badge bg-light text-dark rounded-pill px-3 py-2 mb-3"
                    >

                        <i class="bi bi-grid-1x2-fill me-1"></i>

                        DASHBOARD

                    </span>


                    <h1 class="fw-bold display-6 mb-2">

                        <?= $sapaan; ?>,
                        <?= htmlspecialchars($nama); ?>

                    </h1>


                    <p class="text-secondary fs-5 mb-0">

                        Pantau inventory Adiwiyata,
                        transaksi, dan peminjaman
                        dalam satu tempat.

                    </p>

                </div>


                <!-- ICON -->

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <div
                        class="d-inline-flex align-items-center justify-content-center bg-black border border-secondary border-opacity-50 rounded-4 p-4"
                    >

                        <i
                            class="bi bi-boxes"
                            style="font-size: 80px;"
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- ===================================================== -->
    <!-- STATISTICS -->
    <!-- ===================================================== -->

    <div class="row g-4 mb-4">


        <!-- TOTAL BARANG -->

        <div class="col-12 col-sm-6 col-xl">

            <div
                class="card h-100 bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg"
            >

                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between align-items-start"
                    >

                        <div>

                            <p class="text-secondary mb-2">
                                Total Barang
                            </p>

                            <h2 class="fw-bold mb-1">

                                <?= number_format($total_barang); ?>

                            </h2>

                            <small class="text-secondary">
                                Jenis barang
                            </small>

                        </div>


                        <div
                            class="bg-black border border-secondary border-opacity-50 rounded-3 p-3"
                        >

                            <i class="bi bi-box-seam fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- TOTAL STOK -->

        <div class="col-12 col-sm-6 col-xl">

            <div
                class="card h-100 bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg"
            >

                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between align-items-start"
                    >

                        <div>

                            <p class="text-secondary mb-2">
                                Total Stok
                            </p>

                            <h2 class="fw-bold mb-1">

                                <?= number_format($total_stok); ?>

                            </h2>

                            <small class="text-secondary">
                                Seluruh inventory
                            </small>

                        </div>


                        <div
                            class="bg-black border border-secondary border-opacity-50 rounded-3 p-3"
                        >

                            <i class="bi bi-stack fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- HAMPIR HABIS -->

        <div class="col-12 col-sm-6 col-xl">

            <div
                class="card h-100 bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg"
            >

                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between align-items-start"
                    >

                        <div>

                            <p class="text-secondary mb-2">
                                Hampir Habis
                            </p>

                            <h2 class="fw-bold mb-1">

                                <?= number_format($barang_hampir_habis); ?>

                            </h2>

                            <small class="text-secondary">
                                Stok ≤ 5
                            </small>

                        </div>


                        <div
                            class="bg-black border border-secondary border-opacity-50 rounded-3 p-3"
                        >

                            <i class="bi bi-exclamation-triangle fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- RUSAK -->

        <div class="col-12 col-sm-6 col-xl">

            <div
                class="card h-100 bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg"
            >

                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between align-items-start"
                    >

                        <div>

                            <p class="text-secondary mb-2">
                                Barang Rusak
                            </p>

                            <h2 class="fw-bold mb-1">

                                <?= number_format($barang_rusak); ?>

                            </h2>

                            <small class="text-secondary">
                                Perlu diperiksa
                            </small>

                        </div>


                        <div
                            class="bg-black border border-secondary border-opacity-50 rounded-3 p-3"
                        >

                            <i class="bi bi-tools fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- DIPINJAM -->

        <div class="col-12 col-sm-6 col-xl">

            <div
                class="card h-100 bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg"
            >

                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between align-items-start"
                    >

                        <div>

                            <p class="text-secondary mb-2">
                                Sedang Dipinjam
                            </p>

                            <h2 class="fw-bold mb-1">

                                <?= number_format($barang_dipinjam); ?>

                            </h2>

                            <small class="text-secondary">
                                Belum dikembalikan
                            </small>

                        </div>


                        <div
                            class="bg-black border border-secondary border-opacity-50 rounded-3 p-3"
                        >

                            <i class="bi bi-arrow-left-right fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- ===================================================== -->
    <!-- CONTENT -->
    <!-- ===================================================== -->

    <div class="row g-4">


        <!-- ================================================= -->
        <!-- MENU CEPAT -->
        <!-- ================================================= -->

        <div class="col-lg-4">

            <div
                class="card bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg h-100"
            >

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Akses Cepat
                            </h5>

                            <p class="text-secondary small">
                                Menu utama sistem
                            </p>

                        </div>

                        <i class="bi bi-lightning-charge fs-4"></i>

                    </div>


                    <div class="d-grid gap-2 mt-3">


                        <a
                            href="../barang/index.php"
                            class="btn btn-light text-start rounded-3 py-3"
                        >

                            <i class="bi bi-box-seam me-2"></i>

                            Kelola Barang

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="../kategori/index.php"
                            class="btn btn-dark border-secondary text-start rounded-3 py-3"
                        >

                            <i class="bi bi-tags me-2"></i>

                            Kelola Kategori

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="../lokasi/index.php"
                            class="btn btn-dark border-secondary text-start rounded-3 py-3"
                        >

                            <i class="bi bi-geo-alt me-2"></i>

                            Kelola Lokasi

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="../transaksi/index.php"
                            class="btn btn-dark border-secondary text-start rounded-3 py-3"
                        >

                            <i class="bi bi-arrow-left-right me-2"></i>

                            Transaksi

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="../peminjaman/index.php"
                            class="btn btn-dark border-secondary text-start rounded-3 py-3"
                        >

                            <i class="bi bi-arrow-repeat me-2"></i>

                            Peminjaman

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>



        <!-- ================================================= -->
        <!-- TRANSAKSI TERBARU -->
        <!-- ================================================= -->

        <div class="col-lg-8">

            <div
                class="card bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg h-100"
            >

                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between align-items-center mb-3"
                    >

                        <div>

                            <h5 class="fw-bold mb-1">
                                Transaksi Terbaru
                            </h5>

                            <p class="text-secondary small mb-0">
                                Aktivitas inventory terbaru
                            </p>

                        </div>


                        <a
                            href="../transaksi/index.php"
                            class="btn btn-outline-light btn-sm rounded-3"
                        >

                            Lihat Semua

                        </a>

                    </div>


                    <div class="table-responsive">

                        <table
                            class="table table-dark table-borderless align-middle mb-0"
                        >

                            <thead>

                                <tr class="border-bottom border-secondary border-opacity-25">

                                    <th>
                                        Barang
                                    </th>

                                    <th>
                                        Jenis
                                    </th>

                                    <th>
                                        Jumlah
                                    </th>

                                    <th>
                                        Tanggal
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php if (mysqli_num_rows($query_transaksi) > 0) : ?>

                                    <?php while ($transaksi = mysqli_fetch_assoc($query_transaksi)) : ?>

                                        <tr class="border-bottom border-secondary border-opacity-10">

                                            <td>

                                                <div class="fw-semibold">

                                                    <?= htmlspecialchars(
                                                        $transaksi['nama_barang']
                                                    ); ?>

                                                </div>

                                                <small class="text-secondary">

                                                    <?= htmlspecialchars(
                                                        $transaksi['nama_user']
                                                    ); ?>

                                                </small>

                                            </td>


                                            <td>

                                                <?php if ($transaksi['jenis_transaksi'] == "Masuk") : ?>

                                                    <span class="badge bg-light text-dark rounded-pill">

                                                        <i class="bi bi-arrow-down me-1"></i>

                                                        Masuk

                                                    </span>

                                                <?php else : ?>

                                                    <span class="badge bg-secondary rounded-pill">

                                                        <i class="bi bi-arrow-up me-1"></i>

                                                        Keluar

                                                    </span>

                                                <?php endif; ?>

                                            </td>


                                            <td class="fw-semibold">

                                                <?= number_format(
                                                    $transaksi['jumlah']
                                                ); ?>

                                            </td>


                                            <td class="text-secondary">

                                                <?= date(
                                                    "d/m/Y",
                                                    strtotime($transaksi['tanggal'])
                                                ); ?>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else : ?>

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="text-center text-secondary py-5"
                                        >

                                            <i
                                                class="bi bi-inbox fs-1 d-block mb-2"
                                            ></i>

                                            Belum ada transaksi.

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- ===================================================== -->
    <!-- FOOTER -->
    <!-- ===================================================== -->

    <div
        class="text-center text-secondary small py-4"
    >

        <i class="bi bi-shield-check me-1"></i>

        Inventory Adiwiyata

        &nbsp;•&nbsp;

        Sistem Informasi Inventory

        &nbsp;•&nbsp;

        2026

    </div>

</main>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>