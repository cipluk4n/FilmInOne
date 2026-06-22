<?php
session_start();
require_once './connection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = mysqli_real_escape_string($conn, trim($_POST['username_email']));
    $password = trim($_POST['password']);

    if (empty($username_email) || empty($password)) {
        echo "<script>alert('Isi semua field!'); window.location.href='login.php';</script>";
        exit;
    }

    // Cari user berdasarkan username atau email
    $query = "SELECT * FROM users WHERE username = '$username_email' OR email = '$username_email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session login
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            
            header("Location: index.php");
            exit;
        }
    }

    echo "<script>alert('Username/Email atau Password salah!'); window.location.href='login.php';</script>";
} else {
    header("Location: login.php");
    exit;
}
?>