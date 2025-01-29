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
    <nav class="bg-black opacity-80 p-2 w-full md:hidden mt-2">
        <div class="flex justify-between items-center mx-4">
            <a href="#" class="text-white text-md font-semibold">WARRANTY</a>
            <input id="search2"
                type="text"
                placeholder="Search..."
                class="px-1 py-0 rounded bg-white text-sm text-gray-800">
            <button id="mobileMenuButton" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <div id="mobileMenu" class="hidden flex gap-2 mt-2 text-sm ">
            <a href="../../dashboard.php" class="block text-white py-2">DASHBOARD | </a>
            <a href="../dashboards/masterReport.php" class="block text-white py-2">MASTER REPORT |</a>
            <a href="../dashboards/masterInvoice.php" class="block text-white py-2">MASTER INVOICE</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class=" p-3 mx-auto mt-8">
        <div class="bg-transparent">
            <div id="hot" class="overflow-x-auto"></div>
        </div>
    </div>
    <button id="openModalBtn" class="fixed bottom-8 right-8 bg-black opacity-80 text-white p-6 rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-3xl">
        +
    </button>

    <!-- Modal -->
    <div id="myModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white w-full h-full  overflow-hidden shadow-xl transform transition-all relative">
            <div class="bg-black opacity-80 px-2 py-0 flex justify-between rounded items-center">
                <h2 class="leading-6 text-md font-semibold px-2 text-white">Add New Item</h2>
                <button id="closeModalBtn" class="text-white bg-red-600 p-2 rounded hover:bg-red-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 h-full overflow-y-auto">
                <p class="text-gray-700">This is where you can add content to your full-screen modal.</p>
                <!-- Add your form fields or content here -->
            </div>
            <div class="bg-gray-100 p-4 flex justify-end">
                <button id="closeModalBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Close</button>
            </div>

        </div>
    </div>

</body>

</html>