<?php include '../../connection.php'; ?>



<?php
session_start(); // Start a session

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page or display an error message
    header("Location: ../../index.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../assets/img/logo (2).png">
    <title>
        GRN
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.0.6" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.0.1/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.0.1/dist/handsontable.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./css/grn.css">
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader {
            height: 15px;
            aspect-ratio: 4;
            --_g: no-repeat radial-gradient(farthest-side, #000 90%, #0000);
            background:
                var(--_g) left,
                var(--_g) right;
            background-size: 25% 100%;
            display: flex;
        }

        .loader:before {
            content: "";
            flex: 1;
            background: inherit;
            animation: l50 2s infinite;
        }

        @keyframes l50 {
            0% {
                transform: translate(37.5%) rotate(0)
            }

            16.67% {
                transform: translate(37.5%) rotate(90deg)
            }

            33.33% {
                transform: translate(-37.5%) rotate(90deg)
            }

            50% {
                transform: translate(-37.5%) rotate(180deg)
            }

            66.67% {
                transform: translate(-37.5%) rotate(270deg)
            }

            83.33% {
                transform: translate(37.5%) rotate(270deg)
            }

            100% {
                transform: translate(37.5%) rotate(360deg)
            }
        }

        .tagInput {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            min-height: 24px;
            padding: 2px;
            width: 100%;
        }

        .tag {
            background: transparent;
            border-radius: 3px;
            padding: 2px 6px;
            margin: 1px;
            display: inline-flex;
            align-items: center;
            max-width: calc(100% - 4px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tag .remove {
            margin-left: 4px;
            cursor: pointer;
            color: #666;
            font-weight: bold;
            flex-shrink: 0;
        }

        .tagInput input {
            border: none;
            outline: none;
            padding: 2px;
            margin: 1px;
            width: 80px;
            min-width: 80px;
            max-width: 100%;
        }

        .handsontable td {
            white-space: normal;
            height: 32px;
            min-height: 32px;
        }

        #example {
            overflow: hidden;
            margin: 20px;
        }

        .handsontable .htDimmed {
            color: inherit !important;
        }

        .tagInput {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding: 5px;
            background-color: transparent;
            border: none;
            border-radius: 4px;
        }

        .tag {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
        }

        .tag .remove {
            margin-left: 5px;
            cursor: pointer;
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>

<body class="g-sidenav-show  bg-gray-200">

    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="../../../dashboard.php " target="_blank">
                <img src="../../../assets/img/logo (2).png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold text-white">ACTIVE SOLUTIONS</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item mb-2 mt-0">
                    <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-white"
                        aria-controls="ProfileNav" role="button" aria-expanded="false">
                        <img src="../../assets/img/dp.png " class="avatar">
                        <span class="nav-link-text ms-2 ps-1"><?php echo $_SESSION["user_name"]; ?></span>
                    </a>
                    <div class="collapse" id="ProfileNav" style="">
                        <ul class="nav ">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../pages/profile/overview.php">
                                    <span class="sidenav-mini-icon"> MP </span>
                                    <span class="sidenav-normal  ms-3  ps-1"> My Profile </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white " href="../pages/profile/settings.php">
                                    <span class="sidenav-mini-icon"> S </span>
                                    <span class="sidenav-normal  ms-3  ps-1"> Settings </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white " href="../authentication/signin/basic.php">
                                    <span class="sidenav-mini-icon"> L </span>
                                    <span class="sidenav-normal  ms-3  ps-1"> Logout </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <hr class="horizontal light mt-0">
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link text-white "
                        aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                        <i class="material-icons-round opacity-10">dashboard</i>
                        <span class="nav-link-text ms-2 ps-1">Dashboards</span>
                    </a>
                    <div class="collapse " id="dashboardsExamples">
                        <ul class="nav ">
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../dashboards/quotation.php">
                                    <span class="sidenav-mini-icon"> Q </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Quotation </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../dashboards/inventory.php">
                                    <span class="sidenav-mini-icon"> S </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Inventory </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../dashboards/invoice.php">
                                    <span class="sidenav-mini-icon"> I </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Invoicing </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../dashboards/report.php">
                                    <span class="sidenav-mini-icon"> R </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Reports </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../dashboards/warranty.php">
                                    <span class="sidenav-mini-icon"> W </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Warrantylookup </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">PAGES</h6>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#pagesExamples" class="nav-link text-white "
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">image</i>
                        <span class="nav-link-text ms-2 ps-1">Setup
                        </span>
                    </a>
                    <div class="collapse " id="pagesExamples">
                        <ul class="nav ">
                            <li class="nav-item ">
                                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                    href="#usersExample">
                                    <span class="sidenav-mini-icon"> U </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Users <b class="caret"></b></span>
                                </a>
                                <div class="collapse " id="usersExample">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../dashboards/user_details.php">
                                                <span class="sidenav-mini-icon"> U </span>
                                                <span class="sidenav-normal  ms-2  ps-1">User List</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../pages/users/new-user.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New User </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                    href="#accountExample">
                                    <span class="sidenav-mini-icon"> C </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Customer <b class="caret"></b></span>
                                </a>
                                <div class="collapse " id="accountExample">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../dashboards/customer_data.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Customer Details</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../pages/account/newClient.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New Customer</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link text-white "
                                                href="../pages/account/customerEmployeeDetails.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Customer Employee Details
                                                </span>
                                            </a>

                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link text-white "
                                                href="../pages/account/newClientEmployee.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New Customer Employee </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                    href="#productsExample">
                                    <span class="sidenav-mini-icon"> P </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Products <b class="caret"></b></span>
                                </a>
                                <div class="collapse " id="productsExample">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../ecommerce/products/products-list.php">
                                                <span class="sidenav-mini-icon"> P </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Products List </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../ecommerce/products/new-product.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New Product </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                    href="#componentsExamples">
                                    <span class="sidenav-mini-icon"> A </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Add <b class="caret"></b></span>
                                </a>
                                <div class="collapse " id="componentsExamples">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../dashboards/category.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Category </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../dashboards/brand.php">
                                                <span class="sidenav-mini-icon"> B </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Brand </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../dashboards/price_view.php">
                                                <span class="sidenav-mini-icon"> P </span>
                                                <span class="sidenav-normal  ms-2  ps-1">Price </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#ecommerceExamples" class="nav-link text-white "
                        aria-controls="ecommerceExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">shopping_basket</i>

                        <span class="nav-link-text ms-2 ps-1">Stock</span>
                    </a>
                    <div class="collapse " id="ecommerceExamples">
                        <ul class="nav ">

                            <li class="nav-item ">
                                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                    href="#ordersExample">
                                    <span class="sidenav-mini-icon"> O </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Orders <b class="caret"></b></span>
                                </a>
                                <div class="collapse " id="ordersExample">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../ecommerce/orders/list.php">
                                                <span class="sidenav-mini-icon"> O </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Order List </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../ecommerce/orders/details.php">
                                                <span class="sidenav-mini-icon"> O </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Order Details </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                <li class="nav-item ">
                    <a class="nav-link text-white" href="../../log.php" target="_blank">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">receipt_long</i>
                        <span class="nav-link-text ms-2 ps-1">log</span>
                    </a>
                </li>
                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <div id="loadingScreen" class="loading-overlay">
            <div class="loader"></div>

            <div class="loading-text">Loading...</div>
        </div>

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky"
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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">grn</li>
                    </ol>
                </nav>

                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-centern w-25  ">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <select type="search" id="search">
                            <option selected>Search Invoice Number</option>
                        </select>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item">
                            <a href="../pages/profile/overview.php" class="nav-link text-body p-0 position-relative"
                                target="_blank">
                                <i class="material-icons me-sm-1">
                                    account_circle
                                </i>
                            </a>
                        </li>

                        <li class="nav-item px-3">
                            <a href="javascript:;" class="nav-link text-body p-0">
                                <i class="material-icons fixed-plugin-button-nav cursor-pointer">
                                    settings
                                </i>
                            </a>
                        </li>
                        <li class="nav-item dropdown pe-2">
                            <a href="javascript:;" class="nav-link text-body p-0 position-relative"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons cursor-pointer">
                                    notifications
                                </i>
                                <span
                                    class="position-absolute top-5 start-100 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2">
                                    <span class="small">11</span>
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n4"
                                aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex align-items-center py-1">
                                            <span class="material-icons">email</span>
                                            <div class="ms-2">
                                                <h6 class="text-sm font-weight-normal my-auto">
                                                    Check new messages
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex align-items-center py-1">
                                            <span class="material-icons">podcasts</span>
                                            <div class="ms-2">
                                                <h6 class="text-sm font-weight-normal my-auto">
                                                    Manage podcast session
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex align-items-center py-1">
                                            <span class="material-icons">shopping_cart</span>
                                            <div class="ms-2">
                                                <h6 class="text-sm font-weight-normal my-auto">
                                                    Payment successfully completed
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <!-- End Navbar -->
        <div class="container-fluid">
            <div class="card">

                <section class="m-2">

                    <div class="row">
                        <div class="col-1">
                            <label>Grn No</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="id" disabled>
                        </div>
                        <div class="col-1">
                            <label>Inv No</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="invNo" required>
                        </div>
                        <div class="col-1">
                            <label>Objective:</label>
                        </div>
                        <div class="col-3">
                            <div class="select-container">
                                <select id="object" class="select2-first">
                                    <option value="newStock">NEW STOCK</option>
                                    <option value="warrantyIn">WARRANTY-IN</option>
                                    <option value="rmaIn">RMA-IN</option>
                                    <option value="return">RETURN</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            <label>SUPPLIER</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="supplier">
                        </div>
                        <div class="col-1">
                            <label>LOGISTIC</label>
                        </div>
                        <div class="col-3">
                            <select id="method">
                                <option selected value="shipping">SHIPPING</option>
                                <option value="air">AIR</option>
                                <option value="courrier">COURRIER</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <label>DATE</label>
                        </div>
                        <div class="col-3">
                            <input type="date" id="date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="invTable">

                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div id="toast"></div>
            <!--   Core JS Files   -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
            <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

            <script src="./js/grn.js"></script>

        </div>
    </main>
    <div class="fixed-plugin">
        <button id="upload" onclick="sendData()" class="fixed-plugin-button text-dark bg-dark position-fixed px-3 py-2">
            <i class="material-icons text-light py-2">upload</i>
        </button>
    </div>
    </div>

</body>

</html>