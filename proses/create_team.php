<?php
session_start();
require_once './connection/db.php';

// Pastikan user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_SESSION['id'];
    $project_name = mysqli_real_escape_string($conn, trim($_POST['team_name'])); // Menggunakan team_name sebagai representasi nama project/grup
    $description = "Project grup baru untuk " . $project_name;

    if (empty($project_name)) {
        echo "<script>alert('Nama Tim/Project tidak boleh kosong!'); window.location.href='index.php';</script>";
        exit;
    }

    mysqli_begin_transaction($conn);

    try {
        // 1. Simpan project ke table `projects`
        $query_project = "INSERT INTO projects (owner_id, project_name, description) VALUES ('$owner_id', '$project_name', '$description')";
        mysqli_query($conn, $query_project);
        $project_id = mysqli_insert_id($conn);

        // 2. Tambahkan Owner ke `project_members`
        $query_owner_member = "INSERT INTO project_members (project_id, user_id, role) VALUES ('$project_id', '$owner_id', 'Owner')";
        mysqli_query($conn, $query_owner_member);

        // 3. Tambahkan anggota terpilih (asumsi data dikirim via array POST 'member_ids')
        if (!empty($_POST['member_ids']) && is_array($_POST['member_ids'])) {
            foreach ($_POST['member_ids'] as $member_id) {
                $member_id = intval($member_id);
                // Mencegah duplikasi owner dimasukkan kembali
                if ($member_id !== $owner_id) {
                    // Default role diatur ke 'Actor' atau disesuaikan dengan form input Anda nanti
                    $query_member = "INSERT INTO project_members (project_id, user_id, role) VALUES ('$project_id', '$member_id', 'Actor')";
                    mysqli_query($conn, $query_member);
                }
            }
        }

        mysqli_commit($conn);
        echo "<script>alert('Tim dan Project berhasil dibuat!'); window.location.href='index.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal membuat tim: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>