<?php

require_once __DIR__ . "/config/koneksi.php";

$username = "admin";
$password = "admin123";

echo "<h2>CEK LOGIN</h2>";


// 1. Cek koneksi
if (!$koneksi) {
    die("❌ Koneksi database gagal");
}

echo "✅ Koneksi database berhasil<br><br>";


// 2. Cari username
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users WHERE username = '$username'"
);

if (!$query) {
    die("❌ Query gagal: " . mysqli_error($koneksi));
}


// 3. Cek apakah user ditemukan
if (mysqli_num_rows($query) == 0) {

    die("❌ Username 'admin' TIDAK ditemukan di tabel users");

}

echo "✅ Username 'admin' ditemukan<br><br>";


// 4. Ambil data user
$user = mysqli_fetch_assoc($query);


// 5. Tampilkan data penting
echo "Nama: " . htmlspecialchars($user['nama']) . "<br>";
echo "Username: " . htmlspecialchars($user['username']) . "<br>";
echo "Role: " . htmlspecialchars($user['role']) . "<br><br>";


// 6. Cek hash password
echo "Panjang hash password: " . strlen($user['password']) . "<br>";

echo "Awalan hash: "
    . htmlspecialchars(substr($user['password'], 0, 7))
    . "<br><br>";


// 7. Verifikasi password
if (password_verify($password, $user['password'])) {

    echo "<h3 style='color:green'>✅ PASSWORD BENAR</h3>";

} else {

    echo "<h3 style='color:red'>❌ PASSWORD SALAH</h3>";

}