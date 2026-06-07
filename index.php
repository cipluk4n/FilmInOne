<?php
// session_start();

// cek login
// if (!isset($_SESSION['id'])) {
//     header("Location: login.php");
//     exit;
// }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>


<body>

<!-- NAVBAR -->
<nav class="navbar">

    <div class="nav-left">

        <div class="profile-menu">

            <i class="fa-solid fa-circle-user profile-icon"></i>
            <div class="dropdown" id="profileDropdown">

                <a href="profile.php">
                    Profile
                </a>

                <a href="logout.php">
                    Logout
                </a>
            </div>

        </div>

    </div>

    <div class="nav-center">
        HOME
    </div>

    <div class="nav-right">
        <i class="fa-solid fa-circle-question"></i>
        <i class="fa-solid fa-bell"></i>
        <i class="fa-solid fa-gear"></i>
    </div>

</nav>

<!-- DASHBOARD -->
<div class="dashboard">

    <!-- KIRI -->
    <div class="left-panel">

        <!-- HISTORY -->
        <div class="card history">

            <h3>History Files</h3>

            <ul>
                <li>Script_A</li>
                <li>Project_B</li>
                <li>Board_C</li>
            </ul>

        </div>

        <!-- TEAM -->
        <div class="card team">

            <h3>Teams</h3>

            <ul>
                <li>Frontend Team</li>
                <li>Backend Team</li>
            </ul>

            <button id="addTeamBtn">
                Add Team
            </button>

        </div>

    </div>

    <!-- KANAN -->
    <div class="main-panel">

        <div class="card create-page">

            <h2>Create New Page</h2>

            <div class="page-buttons">

                <button>Script</button>
                <button>Board</button>
                <button>Project</button>
                <button>Schedule</button>

            </div>

        </div>

        <div class="card project-group">

            <h2>Multi-Page Project</h2>

            <button>Create Group Project</button>

        </div>

    </div>

</div>

<!-- Add team -->

<div id="teamModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">
            <h2>Add Team</h2>
            <span id="closeModal">&times;</span>
        </div>

        <form action="create_team.php" method="POST">

            <label>Team Name</label>

            <input
                type="text"
                name="team_name"
                required
            >

            <h3>Add Member</h3>

            <input
                type="text"
                id="searchUser"
                placeholder="Search username..."
            >

            <div id="searchResult"></div>

            <h3>Selected Members</h3>

            <div id="memberContainer">
                
            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    id="cancelBtn"
                >
                    Cancel
                </button>

                <button type="submit">
                    Create Team
                </button>

            </div>

        </form>
        

    </div>

</div>



<script src="js/profile.js"></script>
<script src="js/addTeam.js"></script>
</body>
</html>