<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

require_once 'connection/db.php';

$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data detail project
$query_p = "SELECT * FROM projects WHERE id = '$project_id' LIMIT 1";
$res_p = mysqli_query($conn, $query_p);
$project_data = mysqli_fetch_assoc($res_p);

if (!$project_data) {
    echo "Project tidak ditemukan!";
    exit;
}

// Ambil data lini masa (progress) untuk project ini
$query_progress = "SELECT p.*, u.username FROM progresses p 
                   JOIN users u ON p.creator_id = u.id 
                   WHERE p.project_id = '$project_id' ORDER BY p.created_at DESC";
$result_progress = mysqli_query($conn, $query_progress);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Teaser PKKBN</title>

    <link rel="stylesheet" href="css/project.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<header>

    <div class="breadcrumb">
        <a href="index.php">Home</a> 
        / PKKBN / Teaser PKKBN
    </div>

    <div class="title">
        Teaser PKKBN
    </div>

    <div class="header-icons">

        <i class="fa-regular fa-circle-question"></i>

        <i class="fa-regular fa-bell"></i>

        <i class="fa-solid fa-gear"></i>

    </div>

</header>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="search-box">

            <input
            type="text"
            placeholder="Search">

        </div>

        <div class="scene-list">

            <div class="scene">
                Scene 2
            </div>

            <div class="scene active">

                Scene 4

                <div class="asset">
                    chaos-shot_Meja Belajar.xml
                </div>

                <div class="asset">
                    color-grade_4.3dl
                </div>

            </div>

            <div class="scene">
                Scene 13-15
            </div>

        </div>

    </aside>

    <!-- TIMELINE -->

    <main class="timeline-area">
        
        <div class="form-progress">
            <h3>Update Progress Baru</h3>
            <form action="proses/upload_progress.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="<?= $project_id; ?>">
                <input type="text" name="title" placeholder="Judul Progress (cth: Storyboard Selesai / Revisi Audio)" required>
                <textarea name="description" placeholder="Tulis catatan atau detail progress di sini..." rows="3"></textarea>
                <label style="font-size: 13px; color:#555;">Lampirkan File/Aset Tambahan:</label>
                <input type="file" name="asset_file">
                <button type="submit">Kirim Lini Masa</button>
            </form>
        </div>

        <div class="timeline">
            <?php if(mysqli_num_rows($result_progress) > 0): ?>
                <?php while($prog = mysqli_fetch_assoc($result_progress)): ?>
                    <div class="timeline-item">
                        <div class="node blue"></div>
                        <div class="card">
                            <h4 style="margin:0 0 5px 0; color:#333;"><?= htmlspecialchars($prog['title']); ?></h4>
                            <p style="margin:0; font-size:14px; color:#555;"><?= htmlspecialchars($prog['description']); ?></p>
                            
                            <?php 
                            $p_id = $prog['id'];
                            $asset_q = "SELECT * FROM progress_assets WHERE progress_id = '$p_id' LIMIT 1";
                            $asset_res = mysqli_query($conn, $asset_q);
                            if($asset = mysqli_fetch_assoc($asset_res)):
                            ?>
                                <a class="asset-link" href="proses/uploads/<?= $asset['file_name']; ?>" target="_blank">
                                    <i class="fa-solid fa-paperclip"></i> Lihat File Lampiran
                                </a>
                            <?php endif; ?>

                            <div style="margin-top: 10px; font-size:11px; color:#999; text-align: right;">
                                Diposting oleh: <strong><?= htmlspecialchars($prog['username']); ?></strong> pada <?= $prog['created_at']; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #999; text-align: center; margin-top:30px;">Belum ada catatan aktivitas progress pada project ini.</p>
            <?php endif; ?>
        </div>
    </main>

</div>

<script src="js/project.js"></script>

</body>
</html>