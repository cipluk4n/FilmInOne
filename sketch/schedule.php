<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nama Projek - Jadwal</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <header class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-3">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold">PP</div>
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
        
        <main class="flex-1 p-6 overflow-y-auto flex flex-col space-y-4">
            
            <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center space-x-3">
                    <select class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs bg-white focus:outline-none">
                        <option>April - Mei 2026</option>
                    </select>
                    <select class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs bg-white focus:outline-none">
                        <option>Week</option>
                    </select>
                    <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                        <button class="px-2 py-1 text-xs bg-white hover:bg-gray-50 border-r border-gray-300">&lt;</button>
                        <button class="px-2 py-1 text-xs bg-white hover:bg-gray-50">&gt;</button>
                    </div>
                </div>
                <div>
                    <select class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs bg-white focus:outline-none">
                        <option>Role</option>
                    </select>
                </div>
            </div>

            <div class="flex-1 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <?php
                // Setup data tanggal dan jam kerja
                $days = [
                    ['date' => '26', 'day' => 'Sunday'],
                    ['date' => '27', 'day' => 'Monday'],
                    ['date' => '28', 'day' => 'Tuesday'],
                    ['date' => '29', 'day' => 'Wednesday'],
                    ['date' => '30', 'day' => 'Thursday'],
                    ['date' => '1', 'day' => 'Friday'],
                    ['date' => '2', 'day' => 'Saturday'],
                ];
                $hours = ['07.00 AM', '08.00 AM', '09.00 AM', '10.00 AM', '11.00 AM', '12.00 PM', '13.00 PM', '14.00 PM', '15.00 PM', '16.00 PM'];
                ?>

                <div class="grid grid-cols-8 border-b border-gray-200 bg-gray-50 text-center">
                    <div class="p-3 border-r border-gray-200"></div> <?php foreach ($days as $d) : ?>
                        <div class="p-2 border-r border-gray-200 last:border-r-0">
                            <div class="text-sm font-bold text-gray-700"><?= $d['date']; ?></div>
                            <div class="text-xs text-gray-400"><?= $d['day']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex-1 grid grid-cols-8 divide-x divide-gray-200 relative">
                    <div class="flex flex-col justify-between py-2 text-right pr-3 text-[11px] font-medium text-gray-400 bg-gray-50/50">
                        <?php foreach ($hours as $hour) echo "<div class='h-12'>$hour</div>"; ?>
                    </div>

                    <div class="relative h-full">
                        <div class="absolute top-[10px] left-1 right-1 h-20 bg-blue-500 text-white rounded-lg p-1.5 text-[10px] font-semibold shadow-sm">
                            Spoting scene 1
                        </div>
                        <div class="absolute top-[110px] left-1 right-1 h-20 bg-blue-500 text-white rounded-lg p-1.5 text-[10px] font-semibold shadow-sm">
                            Spoting scene 2,3
                        </div>
                    </div>

                    <div class="relative h-full">
                        <div class="absolute top-[60px] left-1 right-1 h-24 bg-blue-500 text-white rounded-lg p-1.5 text-[10px] font-semibold shadow-sm">
                            Spoting scene 10
                        </div>
                        <div class="absolute top-[280px] left-1 right-1 h-24 bg-blue-500 text-white rounded-lg p-1.5 text-[10px] font-semibold shadow-sm">
                            Spoting scene 15
                        </div>
                    </div>

                    <div class="h-full"></div>
                    <div class="h-full"></div>
                    <div class="h-full"></div>
                    <div class="h-full"></div>
                    <div class="h-full"></div>
                </div>

            </div>
        </main>

        <aside class="w-20 border-l border-gray-200 bg-white flex flex-col items-center py-4 space-y-4">
            <a href="jadwal.php" class="h-12 w-12 rounded-lg border-2 border-black flex flex-col items-center justify-center text-[9px] text-gray-900 text-center font-bold bg-gray-50">
                <span>Logo</span><span>Schedule</span>
            </a>
            <a href="proyek.php" class="h-12 w-12 rounded-lg border border-gray-200 flex flex-col items-center justify-center text-[9px] text-gray-500 text-center font-medium hover:bg-gray-50">
                <span>Logo</span><span>Project</span>
            </a>
        </aside>

    </div>

</body>
</html>