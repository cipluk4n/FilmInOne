<?php
session_start();
require_once './connection/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = intval($_POST['project_id']);
    $creator_id = $_SESSION['id'];
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));

    if (empty($title) || empty($project_id)) {
        echo "<script>alert('Data progress tidak lengkap!'); window.history.back();</script>";
        exit;
    }

    mysqli_begin_transaction($conn);

    try {
        // 1. Masukkan ke table progresses
        $query_progress = "INSERT INTO progresses (project_id, creator_id, title, description) VALUES ('$project_id', '$creator_id', '$title', '$description')";
        mysqli_query($conn, $query_progress);
        $progress_id = mysqli_insert_id($conn);

        // 2. Handle Upload File jika ada file yang dipilih
        if (isset($_FILES['asset_file']) && $_FILES['asset_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['asset_file']['tmp_name'];
            $file_name = time() . '_' . basename($_FILES['asset_file']['name']);
            $upload_dir = 'uploads/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                // Simpan metadata ke progress_assets
                $query_asset = "INSERT INTO progress_assets (progress_id, file_name) VALUES ('$progress_id', '$file_name')";
                mysqli_query($conn, $query_asset);
            }
        }

        mysqli_commit($conn);
        echo "<script>alert('Progress berhasil ditambahkan!'); window.location.href='project.php?id=" . $project_id . "';</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal menyimpan progress.'); window.history.back();</script>";
    }
}
?>