<?php
session_start();
require_once './connection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('Semua data wajib diisi!'); window.location.href='register.php';</script>";
        exit;
    }

    // Cek apakah username atau email sudah terdaftar
    $check_query = "SELECT id FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username atau Email sudah digunakan!'); window.location.href='register.php';</script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru
    $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    
    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat registrasi.'); window.location.href='register.php';</script>";
    }
} else {
    header("Location: register.php");
    exit;
}
?>