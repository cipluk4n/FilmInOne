<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Script PKKBN</title>

    <link rel="stylesheet" href="css/script.css">

    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<!-- HEADER -->

<header>

    <div class="breadcrumb">
        <a href="index.php">Home</a> / PKKBN / Script PKKBN
    </div>

    <div class="title">
        Script PKKBN
    </div>

    <div class="header-right">

        <img
        src="assets/profile.png"
        class="profile">

        <button class="share-btn">
            Share
        </button>

        <i class="fa-solid fa-bars"></i>

    </div>

</header>

<!-- TOOLBAR -->

<div class="toolbar">

    <select>
        <option>Teks Normal</option>
    </select>

    <button>B</button>
    <button>I</button>
    <button>U</button>

    <button>
        <i class="fa-solid fa-align-left"></i>
    </button>

    <button>
        <i class="fa-regular fa-comment"></i>
    </button>

    <button>
        <i class="fa-regular fa-eye">
            <select id="modeSelect">

                <option value="view">
                    Viewing
                </option>

                <option value="edit">
                    Editing
                </option>

            </select>
        </i>
    </button>

    <!-- ZOOM -->
    <select id="zoomSelect">
    
        <option value="75">
            75%
        </option>
    
        <option value="100" selected>
            100%
        </option>
    
        <option value="125">
            125%
        </option>
    
        <option value="150">
            150%
        </option>
    
    </select>

    <!-- Sidebar Toggle -->
    <button id="toggleSidebar">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
</div>

<!-- CONTENT -->

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="bookmark-header">
            Bookmark
        </div>

        <input
        type="text"
        placeholder="Search">

        <div class="scene-list">

            <div class="scene">
                Scene 12
            </div>

            <div class="scene">

                Scene 23

                <div class="shot">
                    Shot 1 : Ruangan Bagas
                </div>

            </div>

        </div>

    </aside>

    <!-- SCRIPT -->

    <main class="editor-area">

        <div
        class="editor"
        contenteditable="true">
            <h1>Script PKKBN</h1>

            <p>
                Bla bla bla bla bla bla bla bla bla bla bla bla
            </p>

            <p>
                Ndfakdfjkdjfkafjndfkdjfdkjbuwekfdnfa
            </p>

            <p>
                Bkjfkajdfkjfdkjdfkfjskfjakjfkfjfklkja
            </p>

        </div>

    </main>

    <!-- PANEL KANAN -->

    <aside class="right-panel">

    </aside>

</div>

<script src="js/script.js"></script>

</body>
</html>