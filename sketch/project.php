<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nama Projek - Berkas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <header class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-3">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-xs text-center font-semibold">PP</div>
            <nav class="text-sm text-gray-500">
                Home / Nama Team / <span class="text-gray-900 font-medium">Nama Projek</span>
            </nav>
        </div>
        <h1 class="text-xl font-bold text-gray-800 absolute left-1/2 -translate-x-1/2">Nama Projek</h1>
        <div class="flex space-x-3">
            <button class="h-9 px-3 rounded-lg border border-gray-300 text-xs font-medium hover:bg-gray-50">Logo Help</button>
            <button class="h-9 px-3 rounded-lg border border-gray-300 text-xs font-medium hover:bg-gray-50">Logo Notif</button>
            <button class="h-9 px-3 rounded-lg border border-gray-300 text-xs font-medium hover:bg-gray-50">Logo Setting</button>
        </div>
    </header>

    <div class="flex h-[calc(100vh-65px)]">
        
        <aside class="w-64 border-r border-gray-200 bg-white p-4 flex flex-col space-y-4">
            <div class="relative">
                <input type="text" placeholder="Search Box" class="w-full rounded-lg border border-gray-300 py-2 pl-3 pr-10 text-sm focus:border-blue-500 focus:outline-none">
                <span class="absolute right-3 top-2.5 text-gray-400 text-sm">🔍</span>
            </div>
            <div class="flex flex-col space-y-2 text-sm text-gray-600">
                <a href="#" class="font-medium text-blue-600 hover:underline">#naskah_revisi1.pdf</a>
                <a href="#" class="hover:text-gray-900">#naskah.pdf</a>
            </div>
        </aside>

        <main class="flex-1 p-6 overflow-y-auto">
            <div class="max-w-3xl mx-auto space-y-6 relative before:absolute before:top-4 before:bottom-4 before:left-7 before:w-0.5 before:bg-gray-200">
                
                <?php
                // Simulasi data dari PHP
                $timeline_data = [
                    ['title' => 'Storyboard film', 'file' => 'Storyboard.pdf', 'user' => 'User 1', 'time' => 'Date, Time'],
                    ['title' => 'Revisi Naskah Scene 30', 'file' => 'Naskah_revisi2.pdf', 'user' => 'User 2', 'time' => 'Date, Time'],
                    ['title' => 'Revisi Naskah Scene 4', 'file' => 'Naskah_revisi1.pdf', 'user' => 'User 3', 'time' => 'Date, Time'],
                    ['title' => 'Naskah Awal', 'file' => 'Naskah.pdf', 'user' => 'User 4', 'time' => 'Date, Time'],
                ];

                foreach ($timeline_data as $item) :
                ?>
                <div class="flex items-start space-x-6 relative">
                    <div class="z-10 flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-full border-2 border-gray-300 bg-white p-1 text-center text-[10px] leading-tight text-gray-500 font-medium shadow-sm">
                        <span>PP</span>
                        <span class="text-[8px] text-gray-400">uploader</span>
                    </div>
                    <div class="flex-1 rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-semibold text-gray-800 text-sm"><?= $item['title']; ?></h3>
                            <span class="text-xs text-gray-400"><?= $item['time']; ?></span>
                        </div>
                        <span class="text-xs text-blue-500 hover:underline cursor-pointer flex items-center">
                            📄 <?= $item['file']; ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </main>

        <aside class="w-20 border-l border-gray-200 bg-white flex flex-col items-center py-4 space-y-4">
            <a href="jadwal.php" class="h-12 w-12 rounded-lg border border-gray-200 flex flex-col items-center justify-center text-[9px] text-gray-500 text-center font-medium hover:bg-gray-50">
                <span>Logo</span><span>Schedule</span>
            </a>
            <a href="proyek.php" class="h-12 w-12 rounded-lg border-2 border-black flex flex-col items-center justify-center text-[9px] text-gray-900 text-center font-bold bg-gray-50">
                <span>Logo</span><span>Project</span>
            </a>
        </aside>

    </div>

</body>
</html>