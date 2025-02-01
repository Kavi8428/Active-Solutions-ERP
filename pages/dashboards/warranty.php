<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WARRANTY</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@12.1.0/dist/handsontable.full.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <script src="../../pages/dashboards/js/warranty.js"></script>
    <link rel="stylesheet" href="../../pages/dashboards/css/warranty.css">
</head>

<body class="bg-gray-100">
<div id="loadingScreen" style="display: none;" class="loading-overlay">
    <div id="loader" class="loader"></div>

    <div class="loading-text">Loading...</div>
</div>

<div id="spinner" style="display: none;" class="loading-overlay">
    <div id="loader" class="loader"></div>

    <div class="loading-text">Loading...</div>
</div>
    <!-- Navbar -->
    <nav class="bg-black opacity-80 p-2 w-75 hidden md:block rounded">
        <div class="flex justify-between items-center max-w-screen-xl mx-auto px-4">
            <a href="#" class="text-white text-xl font-semibold">WARRANTY</a>
            <input id="search"
                type="text"
                placeholder="Search..."
                class="px-12 py-1 rounded bg-white opacity-8 text-sm text-gray-800">
            <ul class="flex space-x-4">
                <li><a href="../../dashboard.php" class="text-white">DASHBOARD</a></li>
                <li><a href="../dashboards/masterReport.php" class="text-white">MASTER REPORT</a></li>
                <li><a href="../dashboards/masterInvoice.php" class="text-white">MASTER INVOICE</a></li>
            </ul>
        </div>
    </nav>
    <!-- Mobile Navbar -->
    <nav class="bg-black opacity-80 p-2 w-full md:hidden text-white mt-2">
        <div class="d-flex  flex-column justify-between gap-1 w-100">
            <div class="flex justify-between  gap-5 ps-2">
                <a href="#" class="text-white text-md font-semibold">WARRANTY</a>
                <button id="mobileMenuButton" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
            <div class="w-full flex flex-grow ps-2">
                <input id="search2"
                    type="search"
                    placeholder="Search..."
                    class="px-1 py-0 rounded bg-white w-full text-sm text-gray-800">
            </div>
            <div id="mobileMenu" class="hidden flex gap-2 text-sm ps-2 ">
                <a href="../../dashboard.php" class="block text-white py-2">DASHBOARD | </a>
                <a href="../dashboards/masterReport.php" class="block text-white py-2">MASTER REPORT |</a>
                <a href="../dashboards/masterInvoice.php" class="block text-white py-2">MASTER INVOICE</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class=" p-1 mx-auto">
        <div class="bg-transparent">
            <div id="hot"></div>
        </div>
    </div>
    <!-- <button id="openModalBtn" class="fixed bottom-8 right-8 bg-black opacity-80 text-white p-6 rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-3xl">
        +
    </button> -->

</body>

</html>