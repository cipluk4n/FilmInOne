<?php
session_start();
require_once './connection/db.php';

if (!isset($_SESSION['id'])) {
    exit(json_encode([]));
}

$search = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';

if ($search !== '') {
    // Cari user selain diri sendiri yang sedang login
    $current_user = $_SESSION['id'];
    $query = "SELECT id, username, email FROM users WHERE (username LIKE '%$search%' OR email LIKE '%$search%') AND id != '$current_user' LIMIT 5";
    $result = mysqli_query($conn, $query);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($users);
}
?>