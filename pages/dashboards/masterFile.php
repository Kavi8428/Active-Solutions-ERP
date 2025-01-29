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
        MASTER FILE
    </title>

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="../../../assets/css/material-dashboard.css?v=3.0.6" rel="stylesheet" />
    <script src="../../../assets/js/core/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.contextMenu.min.css">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include JavaScript for context menu library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.contextMenu.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.1.0/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.1.0/dist/handsontable.full.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>




    <style type="style/css">
        .context-menu {
            display: none; /* Hide the context menu initially */
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 8px 0;
            z-index: 1000;
        }

        /* Other styles remain unchanged */

        .context-menu-item {
            padding: 8px 16px;
            cursor: pointer;
        }

        .context-menu-item:hover {
            background-color: #f8f9fa;
        }

        input {
  background: transparent;
}  
.page-break {
    page-break-before: always;
    page-break-after: always;
}
#letterhead {
    display: block; /* Show by default */
}

.hide-on-subsequent-pages #letterhead {
    display: none; /* Hide on subsequent pages */
}

.modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .toast {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 0;
  left: 50%;
  bottom: 30px;
  font-size: 17px;
}

.toast.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}


    
    </style>

</head>

<body class=" bg-gray-200">
    <input type="text" name="p_employee" id="p_employee" value="<?php echo $emailpm ?>" hidden>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg position-sticky px-0 mx-4 shadow-none border-radius-sm z-index-sticky"
        id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-3 text-dark" href="javascript:;">
                            <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>shop </title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1716.000000, -439.000000)" fill="#252f40"
                                        fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(0.000000, 148.000000)">
                                                <path
                                                    d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                                </path>
                                                <path
                                                    d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                    <li class="breadcrumb-item text-sm text-dark " aria-current="page"> <a href="../../dashboard.php">dashboard</a> </li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page"> <a href="">masterfile</a> </li>
                </ol>
            </nav>

            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <div class="input-group input-group-outline me-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            </div>
                            <input type="search" id="search" class="form-control h-75 " placeholder="Tell me anything to find" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item dropdown pe-2">
                            <select class="nav-link text-body p-0 border-0 bg-transparent position-relative " data-bs-toggle="tooltip" data-bs-placement="bottom" title="Menu">
                                <option>SELECT</option>
                            </select>
                        </li>
                        <li class="nav-item px-3">
                            <a href="javascript:;" id="newSale" class="nav-link text-body p-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add New Sale">
                                <i class="material-icons fixed-plugin-button-nav cursor-pointer">
                                    add
                                </i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="importBtn" class="nav-link text-body p-0 position-relative" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Upload Data">
                                <i class="material-icons me-sm-1">
                                    upload
                                </i>
                            </a>
                        </li>

                        <script>
                            // Initialize Bootstrap tooltips
                            $(function() {
                                $('[data-bs-toggle="tooltip"]').tooltip();
                            });
                        </script>
                    </ul>
                </div>
            </div>
    </nav>
    <div>
        <div id="section1">
            <div class="row">
                <div class="col-6">

                </div>
                <div class="col-6 text-end">
                </div>

                <div class="modal fade" id="import" tabindex="-1" aria-labelledby="ModalLabelImport" aria-hidden="true">
                    <div class="modal-dialog modal-lg mt-lg-5"> <!-- Change modal-fullscreen to modal-lg -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabelImport">Add New Sale</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <section id="general" class=" border-bottom-lg">
                                    <h5>General</h5>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="ivn" placeholder="Invoice Number">
                                                <label for="ivn">Invoice Num</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <select class="form-select" id="sale" aria-label="Floating label select example">
                                                    <option>Select</option>
                                                    <option value="ACTIVE">ACTIVE</option>
                                                    <option value="EITS">EITS</option>
                                                </select>
                                                <label for="sale">Sale</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="gpMonth" placeholder="GP Month">
                                                <label for="gpMonth">GP Month</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="invDate" placeholder="Invoice Date">
                                                <label for="invDate">Invoice Date</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="soldTo" placeholder="Sold To">
                                                <label for="soldTo"> Sold To</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="endCustomer" placeholder="End Customer">
                                                <label for="endCustomer"> End Customer</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="directPartner" placeholder="Direct Patner">
                                                <label for="directPartner"> Direct Partner</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="salesPerson" placeholder="salesPerson ">
                                                <label for=" salesPerson ">Sales Person</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="rep" placeholder="Representer">
                                                <label for="rep"> Rep</label>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section id="sale" class="border-bottom-lg mt-2">
                                    <h5>Sales</h5>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="totalInvValue" placeholder="totalInvValue">
                                                <label for="totalInvValue"> Total Inv Value</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="vat" placeholder="VAt">
                                                <label for="vat"> VAT</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="nbt" placeholder="NBT ">
                                                <label for=" nbt ">NBT</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="salesExcluTax" placeholder="Sales Excluding Tax">
                                                <label for="salesExcluTax"> Sales Exclu Tax</label>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section id="saleBreakdown" class="border-bottom-lg mt-2">
                                    <h5>Sales Breakdown</h5>
                                    <div class="row">
                                        <!-- Total -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="total" placeholder="Total">
                                                <label for="total"> Total</label>
                                            </div>
                                        </div>
                                        <!-- Synology -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="synology" placeholder="Synology">
                                                <label for="synology"> Synology</label>
                                            </div>
                                        </div>
                                        <!-- Bdcom -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="bdcom" placeholder="Bdcom">
                                                <label for="bdcom"> Bdcom</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="draytec" placeholder="Draytec">
                                                <label for="draytec"> Draytec</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Zyxel -->
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="zyxel" placeholder="Zyxel">
                                                <label for="zyxel"> Zyxel</label>
                                            </div>
                                        </div>
                                        <!-- Hard Drives -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="hardDrives" placeholder="Hard Drives">
                                                <label for="hardDrives"> Hard Drives</label>
                                            </div>
                                        </div>
                                        <!-- Acronis -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="acronis" placeholder="Acronis">
                                                <label for="acronis"> Acronis</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="gaj" placeholder="Gajshield">
                                                <label for="gaj"> Gajshield</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Network -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="network" placeholder="Network">
                                                <label for="network"> Network</label>
                                            </div>
                                        </div>
                                        <!-- Maintenance -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="maintain" placeholder="Maintenance">
                                                <label for="maintain"> Maintenance</label>
                                            </div>
                                        </div>
                                        <!-- Labour -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="labour" placeholder="Labour">
                                                <label for="labour"> Labour</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="other" placeholder="Other">
                                                <label for="other"> Other</label>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section id="productCost" class="border-bottom-lg mt-2">
                                    <h5>Product Cost</h5>
                                    <div class="row">
                                        <!-- Product -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="product" placeholder="Product">
                                                <label for="product"> Product</label>
                                            </div>
                                        </div>
                                        <!-- Labour4 -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="lb4" placeholder="Labour4">
                                                <label for="lb4"> Labour4</label>
                                            </div>
                                        </div>
                                        <!-- Other5 -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="other5" placeholder="Other5">
                                                <label for="other5"> Other5</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="totalExclud" placeholder="Total Excluding Tax">
                                                <label for="totalExclud"> Total Exclud</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- PVat -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="pVat" placeholder="PVat">
                                                <label for="pVat"> PVat</label>
                                            </div>
                                        </div>
                                        <!-- Total6 -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="total6" placeholder="Total6">
                                                <label for="total6"> Total6</label>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="row border-bottom-lg mt-2">
                                    <div class="col-6">
                                        <section id="totals" class="sec">
                                            <div class="row">
                                                <h5>Totals</h5>
                                                <!-- Cost -->
                                                <div class="col-6">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="cost" placeholder="Cost">
                                                        <label for="cost"> Cost</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="sales" placeholder="Sales">
                                                        <label for="sales"> Sales</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div class="col-6">
                                        <section id="gpSection" class="sec">
                                            <div class="row">
                                                <h5>GP</h5>
                                                <!-- GP -->
                                                <div class="col-6">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="gp" placeholder="GP">
                                                        <label for="gp"> GP</label>
                                                    </div>
                                                </div>
                                                <!-- GPP -->
                                                <div class="col-6">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="gpp" placeholder="GPP">
                                                        <label for="gpp"> GPP</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                <section id="payments" class="border-bottom-lg mt-2">
                                    <h5>Payments</h5>
                                    <!-- Total Amount -->
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="totalAmount" placeholder="Total Amount">
                                                <label for="totalAmount"> Total Amount</label>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="paid" placeholder="Paid">
                                                <label for="paid"> Paid</label>
                                            </div>
                                        </div>
                                        <!-- Balance -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="balance" placeholder="Balance">
                                                <label for="balance"> Balance</label>
                                            </div>
                                        </div>
                                        <!-- Days -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="days" placeholder="Days">
                                                <label for="days"> Days</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Viable -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="viable" placeholder="Viable">
                                                <label for="viable"> Viable</label>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="datePaid" placeholder="Date Paid">
                                                <label for="datePaid"> Date Paid</label>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section id="recurring" class=" mt-2">
                                    <h5>Recurring</h5>
                                    <div class="row">
                                        <!-- Sales Type -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="salesType" placeholder="Sales Type">
                                                <label for="salesType"> Sales Type</label>
                                            </div>
                                        </div>
                                        <!-- Warranty Expiry -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="warrantyEx" placeholder="Warranty Expiry">
                                                <label for="warrantyEx"> Warranty Ex</label>
                                            </div>
                                        </div>
                                        <!-- Next Invoice -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="nextInv" placeholder="Next Invoice">
                                                <label for="nextInv"> Next Invoice</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="contract" placeholder="Contract">
                                                <label for="contract"> Contract</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Next Sales Person -->
                                        <div class="col-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="nextSalesPerson" placeholder="Next Sales Person">
                                                <label for="nextSalesPerson"> Next Sales Person</label>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </section>
                            <div>
                                <div class="modal-footer">
                                    <button type="button" class="btn bg-gradient-info btn-sm" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn bg-gradient-info btn-sm" onclick=logUserInputs()>Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="importModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">IMPORT SALES</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="importFile">
                                <label class="input-group-text" for="importFile"></label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="saveImport" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="dataTable"></div>
            <div id="handsontable-container"></div>



            <div id="contextMenu" class="context-menu " style="position: absolute; top:-10rem ;  left:-10rem;">
                <div class="context-menu-item"> <i class="fa fa-pencil"></i> &nbsp;Edit</div>
                <div class="context-menu-item"><i class="fa fa-trash "></i> &nbsp;Delete</div>
            </div>


            <div id="toast" class="toast"></div>
        </div>


        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>



        <!-- Initialize DataTable -->
        <script>
            document.getElementById('importBtn').addEventListener('click', function() {
                var importModals = new bootstrap.Modal(document.getElementById('importModal'));
                importModals.show();
            });

            document.getElementById('importFile').addEventListener('change', function() {
                var fileInput = document.getElementById('importFile');
                var file = fileInput.files[0];
                var errorMsg = document.getElementById('fileErrorMsg');

                if (file) {
                    var fileName = file.name;
                    var fileType = file.type;
                    var allowedTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

                    if (allowedTypes.includes(fileType) || fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                        document.getElementById("saveImport").disabled = false; // Enable save button
                    } else {
                        alert('Please enter an Excel file.');
                        fileInput.value = ''; // Clear the input
                        document.getElementById("saveImport").disabled = true; // Disable save button
                        return;
                    }
                } else {
                    document.getElementById("saveImport").disabled = true; // Disable save button
                }
            });

            document.getElementById('saveImport').addEventListener('click', function() {
                var fileInput = document.getElementById('importFile');
                var file = fileInput.files[0];

                if (file) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var data = new Uint8Array(e.target.result);
                        var workbook = XLSX.read(data, {
                            type: 'array'
                        });

                        workbook.SheetNames.forEach(function(sheetName) {
                            var XL_row_object = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
                            var groupedData = groupByInvoice(XL_row_object);
                            // Call the function and pass XL_row_object as a parameter
                            masterFileFilter(XL_row_object);

                            var concatenatedData = concatenateRows(groupedData);
                            var filteredData = filterGroupedData(concatenatedData);
                            //  displayFilteredData(filteredData);
                            //console.log('concatenatedData',concatenatedData);
                            showToast('File imported and data processed successfully.');
                        });
                    };

                    reader.readAsArrayBuffer(file);
                } else {
                    alert('No file selected.');
                }
            });


            function masterFileFilter(XL_row_object) {
                // Filter rows where 'Account Type' is 'Income'
                const filteredData = XL_row_object.filter(row => row['Account Type'] === "Income" || row['Account'] === 'VAT Tax Payable');
                // Mapping the filtered data to display only the required fields
                const displayFields = filteredData.map(row => {
                    return {
                        Type: row.Type,
                        Date: convertExcelDate(row.Date),
                        Num: row.Num,
                        Name: row.Name,
                        'Name Address': row['Name Address'],
                        'Name City': row['Name City'],
                        'Due Date': convertExcelDate(row['Due Date']),
                        Item: row.Item,
                        Memo: row.Memo,
                        'Item Description': row['Item Description'],
                        Account: row.Account,
                        Rep: row.Rep,
                        'Sales Tax Code': row['Sales Tax Code'],
                        Split: row.Split,
                        Paid: row.Paid,
                        Qty: row.Qty,
                        'Sales Price': row['Sales Price'],
                        Amount: row.Amount,
                        'Account Type': row['Account Type']
                    };
                });

                // Display the filtered and mapped data in the console
                //  console.log("Filtered Data with Specific Fields:", displayFields);

                return displayFields;
            }


            function convertExcelDate(excelDate) {
                const excelEpoch = new Date(1899, 11, 30); // Excel date starts from 30th Dec 1899
                return new Date(excelEpoch.getTime() + excelDate * 86400000).toISOString().split('T')[0]; // Converts to YYYY-MM-DD
            }


            function groupByInvoice(data) {
                return data.reduce((acc, item) => {
                    const invoiceNum = item.Num;

                    if (!acc[invoiceNum]) {
                        acc[invoiceNum] = {
                            Num: invoiceNum,
                            Details: []
                        };
                    }

                    acc[invoiceNum].Details.push(item);
                    return acc;
                }, {});
            }

            function concatenateRows(groupedData) {
                const concatenatedData = {};

                for (const invoiceNum in groupedData) {
                    if (groupedData.hasOwnProperty(invoiceNum)) {
                        const details = groupedData[invoiceNum].Details;
                        const combinedDetail = {};

                        details.forEach(detail => {
                            for (const key in detail) {
                                if (detail.hasOwnProperty(key)) {
                                    if (!combinedDetail[key]) {
                                        combinedDetail[key] = detail[key];
                                    } else if (combinedDetail[key] !== detail[key]) {
                                        combinedDetail[key] += `, ${detail[key]}`;
                                    }
                                }
                            }
                        });

                        concatenatedData[invoiceNum] = combinedDetail;
                    }
                }

                return concatenatedData;
            }

            function filterGroupedData(groupedData) {
                //   console.log('groupedData',groupedData);
                const fieldsToDisplay = [
                    'Account', 'Account Type', 'Item', 'Item Description', 'Memo',
                    'Name', 'Name Address', 'Name City', 'Num', 'Open Balance',
                    'P. O. #', 'Paid', 'Qty', 'Rep', 'Sales Price', 'Sales Tax Code', 'Type', 'Date'
                ];

                const filteredData = [];

                for (const invoiceNum in groupedData) {
                    const invoiceData = groupedData[invoiceNum];

                    // Extract all S/N and corresponding item codes from Memo
                    let itemSerialMapping = [];
                    if (invoiceData['Memo']) {
                        const memoText = invoiceData['Memo'];
                        const snPattern = /S\/N\s*:\s*([^\n,]+)/g;
                        const itemPattern = /([^,]*?)\s*,\s*S\/N\s*:\s*([^\n,]+)/g;

                        let snMatch;
                        while ((snMatch = snPattern.exec(memoText)) !== null) {
                            itemSerialMapping.push({
                                serial: snMatch[1].trim(),
                                item: null
                            });
                        }

                        let itemMatch;
                        while ((itemMatch = itemPattern.exec(memoText)) !== null) {
                            const item = itemMatch[1].trim();
                            const serialList = itemMatch[2].split(/\s+/).filter(sn => sn.trim());

                            serialList.forEach(sn => {
                                const existingEntry = itemSerialMapping.find(entry => entry.serial === sn.trim());
                                if (existingEntry) {
                                    existingEntry.item = item;
                                } else {
                                    itemSerialMapping.push({
                                        serial: sn.trim(),
                                        item: item
                                    });
                                }
                            });
                        }

                        // Filter out any null item codes to ensure consistency
                        itemSerialMapping = itemSerialMapping.filter(entry => entry.item !== null);
                    }

                    const itemCodes = invoiceData['Item'] ? invoiceData['Item'].split(',').map(item => item.trim()) : [];
                    const serials = itemSerialMapping.map(entry => entry.serial);

                    for (let i = 0; i < itemCodes.length; i++) {
                        const itemCode = itemCodes[i];
                        const serial = serials[i] || '';

                        const newEntry = {
                            InvoiceNum: invoiceNum,
                            ItemCode: itemCode,
                            Serial: serial
                        };

                        fieldsToDisplay.forEach(field => {
                            if (invoiceData[field] !== undefined) {
                                newEntry[field] = invoiceData[field];
                            }
                        });

                        filteredData.push(newEntry);
                    }
                }
                //sendDataToPHP(filteredData)
                return filteredData;

            }

            function sendDataToPHP(data) {
                data.forEach(item => {
                    $.ajax({
                        url: '../../functions/updateMasterInv.php',
                        type: 'POST',
                        data: {
                            id: '',
                            inv: item.InvoiceNum,
                            grn: '',
                            gin: '',
                            itemCode: item.ItemCode,
                            serial: item.Serial,
                            value: item['Sales Price'],
                            status: item.Type,
                            warranty: 'CHECK MEMO',
                            date: item.Date,
                            memo: item.Memo
                        },
                        success: function(response) {
                            // console.log('Response:', response);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                });
            }

            function displayFilteredData(filteredData) {
                const container = document.getElementById('filteredDataContainer');
                container.innerHTML = ''; // Clear existing content

                for (const invoiceNum in filteredData) {
                    const invoiceData = filteredData[invoiceNum];

                    const table = document.createElement('table');
                    table.classList.add('table', 'table-bordered', 'table-striped');
                    const headerRow = document.createElement('tr');
                    const valueRow = document.createElement('tr');

                    for (const field in invoiceData) {
                        const headerCell = document.createElement('th');
                        headerCell.textContent = field;
                        headerRow.appendChild(headerCell);

                        const valueCell = document.createElement('td');
                        valueCell.textContent = invoiceData[field];
                        valueRow.appendChild(valueCell);
                    }

                    table.appendChild(headerRow);
                    table.appendChild(valueRow);
                    container.appendChild(table);
                }
            }

            function showToast(message) {
                var toastElement = document.createElement('div');
                toastElement.classList.add('toast', 'align-items-center', 'text-bg-success', 'border-0');
                toastElement.setAttribute('role', 'alert');
                toastElement.setAttribute('aria-live', 'assertive');
                toastElement.setAttribute('aria-atomic', 'true');

                var toastBody = document.createElement('div');
                toastBody.classList.add('d-flex');
                var toastMessage = document.createElement('div');
                toastMessage.classList.add('toast-body');
                toastMessage.textContent = message;

                var closeButton = document.createElement('button');
                closeButton.type = 'button';
                closeButton.classList.add('btn-close', 'btn-close-white', 'me-2', 'm-auto');
                closeButton.setAttribute('data-bs-dismiss', 'toast');
                closeButton.setAttribute('aria-label', 'Close');

                toastBody.appendChild(toastMessage);
                toastBody.appendChild(closeButton);
                toastElement.appendChild(toastBody);

                document.body.appendChild(toastElement);

                var toast = new bootstrap.Toast(toastElement);
                toast.show();

                setTimeout(function() {
                    toastElement.remove();
                }, 5000);
            }








            // Get the button element
            const newSaleButton = document.getElementById("newSale");

            // Get the modal element
            const importModal = new bootstrap.Modal(document.getElementById("import"));

            // When the button is clicked, show the modal
            newSaleButton.addEventListener("click", function() {
                importModal.show();
            });

            $(document).ready(function() {
                // Hide the context menu initially
                $('.context-menu').hide();

                // Add event listener for right click on table rows
                $('#dataTable tbody').on('contextmenu', 'tr', function(event) {
                    // Prevent the default context menu from appearing
                    event.preventDefault();

                    // Log the position of the right-click event
                    console.log('Right-click position:', {
                        x: event.pageX,
                        y: event.pageY
                    });

                    // Hide any previously shown context menus
                    $('.context-menu').hide();

                    // Get the IVN value from the clicked row
                    var ivnValue = $(this).find('td:first').text().trim();
                    var gp = $('tr.context-clicked').find('td:nth-child(34)').text().trim();

                    console.log('Selected IVN value:', ivnValue);

                    // Show the context menu at the position of the right-click event
                    $('#contextMenu').css({
                        top: event.pageY + 'px',
                        left: event.pageX + 'px'
                    }).show();

                    // Log the position of the context menu
                    console.log('Context menu position:', $('#contextMenu').offset());

                    // Add a class to the clicked row to track it
                    $(this).addClass('context-clicked');
                });

                // Hide the context menu when clicking outside of it
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('#contextMenu').length && !$(event.target).closest('tr.context-clicked').length) {
                        $('.context-menu').hide();
                        $('tr.context-clicked').removeClass('context-clicked');
                    }
                });

                // Handle context menu item clicks
                $('#contextMenu').on('click', '.context-menu-item', function() {
                    var ivnValue = $('tr.context-clicked').find('td:first').text().trim();
                    var sale = $('tr.context-clicked').find('td:nth-child(2)').text().trim();
                    var gpMonth = $('tr.context-clicked').find('td:nth-child(3)').text().trim();
                    var invDate = $('tr.context-clicked').find('td:nth-child(4)').text().trim();
                    var soldTo = $('tr.context-clicked').find('td:nth-child(5)').text().trim();
                    var endCustomer = $('tr.context-clicked').find('td:nth-child(6)').text().trim();
                    var directPartner = $('tr.context-clicked').find('td:nth-child(7)').text().trim();
                    var salesPerson = $('tr.context-clicked').find('td:nth-child(8)').text().trim();
                    var rep = $('tr.context-clicked').find('td:nth-child(9)').text().trim();
                    var totalInvValue = $('tr.context-clicked').find('td:nth-child(10)').text().trim();
                    var vat = $('tr.context-clicked').find('td:nth-child(11)').text().trim();
                    var nbt = $('tr.context-clicked').find('td:nth-child(12)').text().trim();
                    var salesExcluTax = $('tr.context-clicked').find('td:nth-child(13)').text().trim();
                    var total = $('tr.context-clicked').find('td:nth-child(14)').text().trim();
                    var synology = $('tr.context-clicked').find('td:nth-child(15)').text().trim();
                    var bdcom = $('tr.context-clicked').find('td:nth-child(16)').text().trim();
                    var draytec = $('tr.context-clicked').find('td:nth-child(17)').text().trim();
                    var zyxel = $('tr.context-clicked').find('td:nth-child(18)').text().trim();
                    var hardDrives = $('tr.context-clicked').find('td:nth-child(19)').text().trim();
                    var acronis = $('tr.context-clicked').find('td:nth-child(20)').text().trim();
                    var gajRow = $('tr.context-clicked').find('td:nth-child(21)').text().trim();
                    var network = $('tr.context-clicked').find('td:nth-child(22)').text().trim();
                    var maintainance = $('tr.context-clicked').find('td:nth-child(23)').text().trim();
                    var labour = $('tr.context-clicked').find('td:nth-child(24)').text().trim();
                    var other = $('tr.context-clicked').find('td:nth-child(25)').text().trim();
                    var product = $('tr.context-clicked').find('td:nth-child(26)').text().trim();
                    var Labour4 = $('tr.context-clicked').find('td:nth-child(27)').text().trim();
                    var other5 = $('tr.context-clicked').find('td:nth-child(28)').text().trim();
                    var totalExclud = $('tr.context-clicked').find('td:nth-child(29)').text().trim();
                    var pVat = $('tr.context-clicked').find('td:nth-child(30)').text().trim();
                    var total6 = $('tr.context-clicked').find('td:nth-child(31)').text().trim();
                    var cost = $('tr.context-clicked').find('td:nth-child(32)').text().trim();
                    var sales = $('tr.context-clicked').find('td:nth-child(33)').text().trim();
                    var gp = $('tr.context-clicked').find('td:nth-child(34)').text().trim();
                    var gpp = $('tr.context-clicked').find('td:nth-child(35)').text().trim();
                    var totalAmount = $('tr.context-clicked').find('td:nth-child(36)').text().trim();
                    var paid = $('tr.context-clicked').find('td:nth-child(37)').text().trim();
                    var balance = $('tr.context-clicked').find('td:nth-child(38)').text().trim();
                    var days = $('tr.context-clicked').find('td:nth-child(39)').text().trim();
                    var viable = $('tr.context-clicked').find('td:nth-child(40)').text().trim();
                    var datePaid = $('tr.context-clicked').find('td:nth-child(41)').text().trim();
                    var salesType = $('tr.context-clicked').find('td:nth-child(42)').text().trim();
                    var warrantyEx = $('tr.context-clicked').find('td:nth-child(43)').text().trim();
                    var nextInv = $('tr.context-clicked').find('td:nth-child(44)').text().trim();
                    var contract = $('tr.context-clicked').find('td:nth-child(45)').text().trim();
                    var nextSalesPerson = $('tr.context-clicked').find('td:nth-child(46)').text().trim();
                    var created_at = $('tr.context-clicked').find('td:nth-child(47)').text().trim();
                    var update_at = $('tr.context-clicked').find('td:nth-child(48)').text().trim();

                    // console.log('test vat:', vat);


                    var selectedItem = $(this).text().trim();

                    // Log different messages based on the selected menu item
                    if (selectedItem === 'Edit') {
                        console.log(ivnValue, 'Edit clicked');
                        document.getElementById('ivn').value = ivnValue;
                        //console.log('sale',document.getElementById('sale').value);
                        document.getElementById('sale').value = sale;
                        //console.log('after sale',sale);
                        document.getElementById('gpMonth').value = gpMonth;
                        document.getElementById('invDate').value = invDate;
                        document.getElementById('soldTo').value = soldTo;
                        document.getElementById('endCustomer').value = endCustomer;
                        document.getElementById('directPartner').value = directPartner;
                        document.getElementById('salesPerson').value = salesPerson;
                        document.getElementById('rep').value = rep;
                        document.getElementById('totalInvValue').value = totalInvValue;
                        document.getElementById('vat').value = vat;
                        document.getElementById('nbt').value = nbt;
                        document.getElementById('salesExcluTax').value = salesExcluTax;
                        document.getElementById('total').value = total;
                        document.getElementById('synology').value = synology;
                        document.getElementById('bdcom').value = bdcom;
                        document.getElementById('draytec').value = draytec;
                        document.getElementById('zyxel').value = zyxel;
                        document.getElementById('hardDrives').value = hardDrives;
                        document.getElementById('acronis').value = acronis;
                        document.getElementById('gaj').value = gajRow;
                        document.getElementById('network').value = network;
                        document.getElementById('maintain').value = maintainance;
                        document.getElementById('labour').value = labour;
                        document.getElementById('other').value = other;
                        document.getElementById('product').value = product;
                        document.getElementById('lb4').value = Labour4;
                        document.getElementById('other5').value = other5;
                        document.getElementById('totalExclud').value = totalExclud;
                        document.getElementById('pVat').value = pVat;
                        document.getElementById('total6').value = total6;
                        document.getElementById('cost').value = cost;
                        document.getElementById('sales').value = sales;
                        document.getElementById('gp').value = gp;
                        document.getElementById('gpp').value = gpp;
                        document.getElementById('totalAmount').value = totalAmount;
                        document.getElementById('paid').value = paid;
                        document.getElementById('balance').value = balance;
                        document.getElementById('days').value = days;
                        document.getElementById('viable').value = viable;
                        document.getElementById('datePaid').value = datePaid;
                        document.getElementById('salesType').value = salesType;
                        document.getElementById('warrantyEx').value = warrantyEx;
                        document.getElementById('nextInv').value = nextInv;
                        document.getElementById('contract').value = contract;
                        document.getElementById('nextSalesPerson').value = nextSalesPerson;
                        // document.getElementById('created_at').value = created_at;
                        // document.getElementById('update_at').value = update_at;

                        importModal.show();
                    } else if (selectedItem === 'Delete') {
                        const deleteData = new FormData(); // Corrected from 'new deleteData()' to 'new FormData()'
                        deleteData.append('ivn', ivnValue);
                        deleteData.append('table', 'masterfile');
                        deleteData.append('primaryKey', 'ivn');

                        fetch('../../functions/publicDelete.php', {
                                method: 'POST',
                                body: deleteData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.text(); // Change response type to text
                            })
                            .then(data => {
                                console.log('Response from server:', data); // Log the response
                                alert(data);
                                location.reload();
                            })
                            .catch(error => {
                                console.error('Error inserting data:', error);
                            });

                        console.log(ivnValue, 'Delete clicked');
                    } else {
                        console.log('Unknown action clicked');
                    }

                    // Hide the context menu after a click
                    $('.context-menu').hide();
                    $('tr.context-clicked').removeClass('context-clicked');


                    // Handle different context menu actions based on the clicked item
                    // Example:
                    switch ($(this).data('action')) {
                        case 'markAsInvoiced':
                            // Implement logic for marking as invoiced
                            break;
                        case 'markAsNotInvoiced':
                            // Implement logic for marking as not invoiced
                            break;
                            // Add more cases as needed
                    }
                });
            });


            function logUserInputs() {
                // Get all input elements by their IDs
                const formData = new FormData();
                formData.append('ivn', document.getElementById('ivn').value);
                formData.append('sale', document.getElementById('sale').value);
                formData.append('gpMonth', document.getElementById('gpMonth').value);
                formData.append('invDate', document.getElementById('invDate').value);
                formData.append('soldTo', document.getElementById('soldTo').value);
                formData.append('endCustomer', document.getElementById('endCustomer').value);
                formData.append('directPartner', document.getElementById('directPartner').value);
                formData.append('salesPerson', document.getElementById('salesPerson').value);
                formData.append('rep', document.getElementById('rep').value);
                formData.append('totalInvValue', document.getElementById('totalInvValue').value);
                formData.append('vat', document.getElementById('vat').value);
                formData.append('nbt', document.getElementById('nbt').value);
                formData.append('salesExcluTax', document.getElementById('salesExcluTax').value);
                formData.append('total', document.getElementById('total').value);
                formData.append('synology', document.getElementById('synology').value);
                formData.append('bdcom', document.getElementById('bdcom').value);
                formData.append('draytec', document.getElementById('draytec').value);
                formData.append('zyxel', document.getElementById('zyxel').value);
                formData.append('hardDrives', document.getElementById('hardDrives').value);
                formData.append('acronis', document.getElementById('acronis').value);
                formData.append('gaj', document.getElementById('gaj').value);
                formData.append('network', document.getElementById('network').value);
                formData.append('maintain', document.getElementById('maintain').value);
                formData.append('labour', document.getElementById('labour').value);
                formData.append('other', document.getElementById('other').value);
                formData.append('product', document.getElementById('product').value);
                formData.append('lb4', document.getElementById('lb4').value);
                formData.append('other5', document.getElementById('other5').value);
                formData.append('totalExclud', document.getElementById('totalExclud').value);
                formData.append('pVat', document.getElementById('pVat').value);
                formData.append('total6', document.getElementById('total6').value);
                formData.append('cost', document.getElementById('cost').value);
                formData.append('sales', document.getElementById('sales').value);
                formData.append('gp', document.getElementById('gp').value);
                formData.append('gpp', document.getElementById('gpp').value);
                formData.append('totalAmount', document.getElementById('totalAmount').value);
                formData.append('paid', document.getElementById('paid').value);
                formData.append('balance', document.getElementById('balance').value);
                formData.append('days', document.getElementById('days').value);
                formData.append('viable', document.getElementById('viable').value);
                formData.append('datePaid', document.getElementById('datePaid').value);
                formData.append('salesType', document.getElementById('salesType').value);
                formData.append('warrantyEx', document.getElementById('warrantyEx').value);
                formData.append('nextInv', document.getElementById('nextInv').value);
                formData.append('contract', document.getElementById('contract').value);
                formData.append('nextSalesPerson', document.getElementById('nextSalesPerson').value);


                // Log the values in the console
                // console.log('formData gp:', formData.get('gp'));

                fetch('../../functions/sendMasterFile.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text(); // Change response type to text
                    })
                    .then(data => {
                        console.log('Response from server:', data); // Log the response
                        alert(data);
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error inserting data:', error);
                    });


            }


            flatpickr("#gpMonth", {
                dateFormat: "Y-m",
                defaultDate: "today",
                disableMobile: true,
                onChange: function(selectedDates, dateStr, instance) {
                    console.log(dateStr); // You can perform further actions here
                }
            });

            flatpickr("#invDate", {
                dateFormat: "Y-m-d",
                defaultDate: "today",
                disableMobile: true,
                onChange: function(selectedDates, dateStr, instance) {
                    console.log(dateStr); // You can perform further actions here
                }
            });





            // Define the URL of the PHP script that fetches the data
            const phpScriptUrl = '../../functions/fetchMasterFile.php'; // Replace with the actual path
            const updatePhpScriptUrl = '../../functions/realTimeMasterFileUpdate.php'; // PHP script for updating the database


            // Fetch data from the PHP script
            fetch(phpScriptUrl)
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        let tableData = data.map(row => [
            row.ivn, row.sale, row.gpMonth, row.invDate, row.soldTo, row.endCustomer, row.directPartner,
            row.salesPerson, row.rep, parseFloat(row.totalInvValue), parseFloat(row.vat),
            parseFloat(row.nbt), parseFloat(row.salesExcluTax), parseFloat(row.total),
            parseFloat(row.synology), parseFloat(row.bdcom), parseFloat(row.draytec), 
            parseFloat(row.zyxel), parseFloat(row.hardDrives), parseFloat(row.acronis),
            parseFloat(row.gaj), parseFloat(row.network), parseFloat(row.maintain),
            parseFloat(row.labour), parseFloat(row.other), parseFloat(row.product),
            parseFloat(row.lb4), parseFloat(row.other5), parseFloat(row.totalExclud),
            parseFloat(row.pVat), parseFloat(row.total6), parseFloat(row.cost),
            parseFloat(row.sales), parseFloat(row.gp), row.gpp,
            parseFloat(row.totalAmount), parseFloat(row.paid), parseFloat(row.balance),
            row.days, row.viable, row.datePaid, row.salesType, row.warrantyEx,
            row.nextInv, row.contract, row.nextSalesPerson, row.created_at, row.update_at
        ]);

           tableData.sort((a, b) => new Date(b[3]) - new Date(a[3]));

        const numberRenderer = function (instance, td, row, col, prop, value) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            if (typeof value === 'number') {
                td.innerHTML = value.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                td.style.textAlign = 'right';
            }
        };

                    // Initialize Handsontable
                    const container = document.getElementById('dataTable');

                   

                    const hot = new Handsontable(document.getElementById('dataTable'), {
                        
    data: tableData,
    colHeaders: [
        'IVN', 'Sale', 'GP Month', 'Inv Date', 'Sold To', 'End Customer', 'Direct Partner',
        'Sales Person', 'Rep', 'Total Inv Value', 'VAT', 'NBT', 'Sales Exclu Tax', 'Total',
        'Synology', 'BDCOM', 'Draytec', 'Zyxel', 'Hard Drives', 'Acronis', 'GAJ', 'Network',
        'Maintain', 'Labour', 'Other', 'Product', 'LB4', 'Other5', 'Total Exclud', 'P VAT',
        'Total6', 'Cost', 'Sales', 'GP', 'GPP', 'Total Amount', 'Paid', 'Balance', 'Days',
        'Viable', 'Date Paid', 'Sales Type', 'Warranty Ex', 'Next Inv', 'Contract',
        'Next Sales Person', 'Created At', 'Updated At'
    ],
    columns: Array.from({ length: tableData[0].length }, (v, i) =>
        i >= 9 ? { type: 'numeric', renderer: numberRenderer } : {}
    ),
    rowHeaders: true,
    stretchH: 'all',
    width: '100%',
    height: 540,
    licenseKey: 'non-commercial-and-evaluation',
    // Column Features
    manualColumnResize: true,
    manualColumnMove: true,
    // Row Features
    manualRowResize: true,
    manualRowMove: true,
    dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
    // Selection & Navigation
    contextMenu: {
        items: {
            'row_above': {},
            'row_below': {},
            'separator1': '---------',
            'col_left': {},
            'col_right': {},
            'separator2': '---------',
            'remove_row': {},
            'remove_col': {},
            'separator3': '---------',
            'undo': {},
            'redo': {},
            'separator4': '---------',
            'make_read_only': {},
            'alignment': {},
            'separator5': '---------',
            'hide_column': {},
            'show_column': {}
        }
    },
    // Comments & Custom Borders
    customBorders: true,
    // Copy & Paste
    copyPaste: true,
    copyable: true,
    pasteMode: 'shift_down',
    // Search
    search: {
        queryMethod: 'regex'
    },
    // Performance
    viewportRowRenderingOffset: 70,
    viewportColumnRenderingOffset: 30,
    // State Persistence
    fixedRowsTop: 0,
    filters: true,
    afterChange: function (changes, source) {
        if (source === 'loadData' || !changes) return;

        const updates = []; // Collect updates for batching
        changes.forEach(([row, prop, oldValue, newValue]) => {
            if (newValue !== oldValue) {
                const id = this.getDataAtRowProp(row, '0'); // Adjust based on your ID column
                const columnName = this.getSettings().colHeaders[prop]; // Get the column header
                updates.push({ id, column: columnName.replace(/ /g, ''), newValue });
            }
        });

        // Update the database in one go if there are any changes
        if (updates.length) {
            updateDatabaseBatch(updates);
        }
    }
});

// Debounce search input
let debounceTimeout;
document.getElementById('search').addEventListener('input', function () {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        const searchValue = this.value.toLowerCase().trim();
        const filteredData = tableData.filter(row => {
            if (!searchValue) return true;
            return row.some(value => value && value.toString().toLowerCase().includes(searchValue));
        });
        hot.loadData(filteredData);
    }, 300); // Adjust the delay as needed
});
    })

    function updateDatabaseBatch(updates) {
    // Create an array of promises to handle each update
    const updatePromises = updates.map(update => {
        const { id, column, newValue } = update; // Destructure the update object

        console.log('update', update);
        // Return a promise for each fetch call
        return fetch(updatePhpScriptUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id,
                column,
                value: newValue // Ensure you're using the newValue correctly
            })
        });
    });

    // Wait for all promises to resolve
    Promise.all(updatePromises)
        .then(responses => Promise.all(responses.map(response => response.text())))
        .then(data => {
            console.log('Batch update successful:', data);
        })
        .catch((error) => {
            console.error('Batch update error:', error);
        });
}


                    


               
        </script>



    </div>
    </main>

    <!--   Core JS Files   -->
    <script src="../../../assets/js/core/popper.min.js"></script>
    <script src="../../../assets/js/core/bootstrap.min.js"></script>
    <script src="../../../assets/js/plugins/choices.min.js"></script>
    <script src="../../../assets/js/plugins/photoswipe.min.js"></script>
    <script src="../../../assets/js/plugins/photoswipe-ui-default.min.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
</body>

</html>