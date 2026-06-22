<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

require_once 'connection/db.php';

// Ambil semua jadwal yang berkaitan dengan project-project milik user
$user_id = $_SESSION['id'];
$query_sched = "SELECT s.*, p.project_name FROM schedules s
                JOIN projects p ON s.project_id = p.id
                JOIN project_members pm ON p.id = pm.project_id
                WHERE pm.user_id = '$user_id'
                ORDER BY s.start_time ASC";
$result_sched = mysqli_query($conn, $query_sched);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>

    <link rel="stylesheet" href="../css/schedule.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->

<nav class="navbar">

    <div class="nav-left">

        <img src="../assets/profile.png"
        class="profile-pic">

        <span class="path">
            Home / PKKBN Team
        </span>

    </div>

    <div class="nav-right">

        <i class="fa-regular fa-circle-question"></i>

        <i class="fa-regular fa-bell"></i>

        <i class="fa-solid fa-gear"></i>

    </div>

</nav>

<!-- MAIN -->

<div class="main-container">

    <div class="calendar-section">

        <!-- TOOLBAR -->

        <div class="calendar-toolbar">

            <div class="toolbar-left">

                <button class="today-btn">
                    Today
                </button>

                <button>
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <button>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <h2>
                    June 2026
                </h2>

            </div>

            <div class="toolbar-right">

                <select>

                    <option>
                        All Roles
                    </option>

                    <option>
                        Director
                    </option>

                    <option>
                        Writer
                    </option>

                    <option>
                        Editor
                    </option>

                    <option>
                        Videographer
                    </option>

                    <option>
                        Actor
                    </option>

                </select>

                <button class="view-btn active">
                    Day
                </button>

                <button class="view-btn">
                    Week
                </button>

                <button class="view-btn">
                    Month
                </button>

            </div>

        </div>

        <!-- CALENDAR -->

        <div class="calendar">

            <div class="day-name">Sun</div>
            <div class="day-name">Mon</div>
            <div class="day-name">Tue</div>
            <div class="day-name">Wed</div>
            <div class="day-name">Thu</div>
            <div class="day-name">Fri</div>
            <div class="day-name">Sat</div>

            <!-- WEEK 1 -->

            <div class="day empty"></div>

            <div class="day">
                <span>1</span>
            </div>

            <div class="day">
                <span>2</span>
            </div>

            <div class="day">
                <span>3</span>
            </div>

            <div class="day">
                <span>4</span>
            </div>

            <div class="day">
                <span>5</span>
            </div>

            <div class="day">
                <span>6</span>
            </div>

            <!-- WEEK 2 -->

            <div class="day">
                <span>7</span>
            </div>

            <div class="day">

                <span>8</span>

                <div class="event shoot">
                    09:00 Shooting
                </div>

            </div>

            <div class="day">

                <span>9</span>

                <div class="event edit">
                    13:00 Editing
                </div>

            </div>

            <div class="day">
                <span>10</span>
            </div>

            <div class="day">
                <span>11</span>
            </div>

            <div class="day">
                <span>12</span>
            </div>

            <div class="day">
                <span>13</span>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDEBAR -->

    <div class="page-switcher">

        <button class="switch-btn active">
            <i class="fa-regular fa-calendar"></i>
        </button>

        <button class="switch-btn">
            <i class="fa-regular fa-folder"></i>
        </button>

    </div>

</div>

<script src="../js/schedule.js"></script>

</body>
</html>