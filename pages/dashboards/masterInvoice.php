<?php

include '../../connection.php';
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page
    header("Location: ../../../index.php");
    exit();
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../../assets/img/logo (2).png">
    <title>
        MASTER INVOICE
    </title>

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.0.1/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.0.1/dist/handsontable.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Then load xlsx library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="../../pages/dashboards/css/masterInvoice.css">
    <script src="../../pages/dashboards/js/masterInvoice.js"></script>

</head>

<body>
    
 <main>
 <div id="loadingScreen" class="loading-overlay">
        <div class="spinner-container">
            <div class="spinner-circle"></div>
            <div class="spinner-circle spinner-circle-2"></div>
            <div class="logo">
            </div>
        </div>
        <div class="loading-text">Loading...</div>
    </div>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-3 shadow-sm">
            <div class="container-fluid">

                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb" class="me-auto">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;" class="text-dark">
                                <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" xmlns="http://www.w3.org/2000/svg">
                                    <title>shop</title>
                                    <!-- SVG content here -->
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="../../dashboard.php" class="text-dark">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="../../pages/dashboards/invoice.php" class="text-dark">Invoice</a>
                        </li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Master Invoice</li>
                        <li class="ms-6">
                            <div class="input-group">
                                <input id="search" type="search" class=" ms-5 h-25 form-control-sm" placeholder="What are you looking for?" aria-label="Search">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <!-- Upload Button -->
                <ul class="navbar-nav ms-auto">
                    <li class="navbar-nav ms-auto me-5">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarContent">
                            <!-- Category Dropdown -->
                            <div class="dropdown me-5">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    REPORTS
                                </button>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../../pages/dashboards/monthlySalesReport.php">MONTHLY SALE REPORT</a></li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#">GP REPORT</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Men's</a></li>
                                            <li><a class="dropdown-item" href="#">Women's</a></li>
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle" href="#">Kids</a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Boys</a></li>
                                                    <li><a class="dropdown-item" href="#">Girls</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#">ITEM REPORT</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Men's</a></li>
                                            <li><a class="dropdown-item" href="#">Women's</a></li>
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle" href="#">Kids</a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Boys</a></li>
                                                    <li><a class="dropdown-item" href="#">Girls</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Books</a></li>

                                </ul>
                            </div>
                    </li>
                    <li class="nav-item ">
                        <button class="btn btn-sm btn-outline-secondary" id="uploadArrow" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fa fa-upload" aria-hidden="true"></i> .xlsx
                        </button>
                    </li>
                </ul>
            </div>
            </div>
        </nav>



        <div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chartModalLabel">CHARt OF DATA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <canvas id="myChart" width="400" height="200"></canvas>

                    </div>
                    <div class="modal-footer">


                    </div>
                </div>
            </div>
        </div>






        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="upload-container">
                            <input type="file" id="fileInput" accept=".xlsx, .xls" />
                            <button class="upload-button" onclick="document.getElementById('fileInput').click()">
                                Choose XLSX File
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <progress id="progressBar" max="100" value="0" style="width: 100%;"></progress>
                        <p id="progressText">select file</p>

                    </div>
                </div>
            </div>
        </div>

        <div id="hot"></div>
        <div class="row">
            <div class="col-10">
            </div>
            <div class="col-2 text-end">
                <button id="generateChart" data-toggle="modal" data-target="#chartModal">Generate Chart</button>

                <button class="btn btn-group-lg " id="addRow">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>
            </div>
        </div><!--  -->
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>







</body>

</html>