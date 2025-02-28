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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <link href="../../pages/dashboards/css/invoice.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</head>

<body>

    <main>
        <div id="loadingScreen" class="loading-overlay">
            <div class="loader"></div>

            <div class="loading-text">Loading...</div>
        </div>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-1 shadow-sm">
            <div class="container-fluid">
                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb" class="bg-light rounded p-1">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="../../dashboard.php" class="text-decoration-none">
                                <i class="fa fa-home" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none">Advanced Reports</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Master Invoice</li>
                        <li class="ms-6">
                            <div class="input-group">
                                <input id="search" type="search" class=" ms-5 h-25 form-control-sm"
                                    placeholder="What are you looking for?" aria-label="Search">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <!-- Upload Button -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item pe-2 ">
                        <a class="btn btn-sm btn-outline-info" href="../../pages/dashboards/invoice_new.php">+
                            INVOICE</a>
                    </li>
                    <li class="nav-item pe-2 ">
                        <a class="btn btn-sm btn-outline-primary"
                            href="../../pages/dashboards/monthlySalesReport.php">REPORTS</a>
                    </li>
                    <li class="nav-item ">
                        <button class="btn btn-sm btn-outline-secondary" id="uploadArrow" data-bs-toggle="modal"
                            data-bs-target="#uploadModal">
                            <i class="fa fa-upload" aria-hidden="true"></i> .xlsx
                        </button>
                    </li>
                </ul>
            </div>
            </div>
        </nav>

        <div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel"
            aria-hidden="true">
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

        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
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
    <script src="../../pages/dashboards/js/invoice.js"></script>







</body>

</html>