<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page
    header("Location: ../../index.php ");
    exit();
}

?>

<!DOCTYPE html>

<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
<link rel="icon" type="image/png" href="./assets/img/logo (2).png">
<title>
    Dashboard
</title>
<!--     Fonts and icons     -->
<link rel="stylesheet" type="text/css"
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

<!-- Nucleo Icons -->
<link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="./assets/css/nucleo-svg.css" rel="stylesheet" />

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- CSS Files -->
<link id="pagestyle" href="./assets/css/material-dashboard.css?v=3.0.6" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- FullCalendar CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Moment.js (Required for FullCalendar) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<!-- FullCalendar JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">



<style>
    .search-results-dropdown {
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
    }

    .search-results-dropdown li {
        cursor: pointer;
        padding: 5px;
    }

    .search-results-dropdown li:hover {
        background-color: #ccc;
    }

    .search-results-dropdown {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    #search-results {
        position: absolute;
        top: 80%;
        left: 20%;
        width: 80%;
        background-color: transparent;
        border: none;
        padding: 10px;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        /* semi-transparent background */
        z-index: 9999;
        /* ensure it is on top of other elements */
        display: flex;
        justify-content: center;
        align-items: center;
    }


    /* HTML: <div class="loader"></div> */
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
</style>

</head>
<div id="loadingScreen" class="loading-overlay">
    <div class="loader"></div>

    <div class="loading-text">Loading...</div>
</div>

<body class="g-sidenav-show  bg-gray-100">

    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
        id="sidenav-main">

        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="dashboard.php" target="_blank">
                <img src="assets/img/logo (2).png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold text-white">ACTIVE SOLUTIONS</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">


                <li class="nav-item mb-2 mt-0">
                    <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-white"
                        aria-controls="ProfileNav" role="button" aria-expanded="false">
                        <img src="./assets/img/dp.png" class="avatar">
                        <span class="nav-link-text ms-2 ps-1"><?php echo $_SESSION["user_name"]; ?></span>
                    </a>


                    <div class="collapse" id="ProfileNav" style="">
                        <ul class="nav ">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="./pages/pages/profile/overview.php">
                                    <span class="sidenav-mini-icon"> MP </span>
                                    <span class="sidenav-normal  ms-3  ps-1"> My Profile </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white " href="./pages/pages/account/settings.php">
                                    <span class="sidenav-mini-icon"> S </span>
                                    <span class="sidenav-normal  ms-3  ps-1"> Settings </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white " href="./pages/authentication/signin/basic.php">
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
                                <a class="nav-link text-white " href="pages/dashboards/quotation.php">
                                    <span class="sidenav-mini-icon"> Q </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Quotation </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                    href="#inventory">
                                    <span class="sidenav-mini-icon"> I </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Inventory <b class="caret"></b></span>
                                </a>
                                <div class="collapse " id="inventory">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link text-white"
                                                href="./pages/dashboards/grn.php">
                                                <span class="sidenav-mini-icon"> G </span>
                                                <span class="sidenav-normal  ms-2  ps-1">GRN</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="./pages/dashboards/gin.php">
                                                <span class="sidenav-mini-icon"> S </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> GIN</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="./pages/dashboards/inventory.php">
                                                <span class="sidenav-mini-icon"> S </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Stock</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="pages/dashboards/invoice.php">
                                    <span class="sidenav-mini-icon"> I </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Invoicing </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../../pages/dashboards/warranty.php">
                                    <span class="sidenav-mini-icon"> W </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Warrantylookup </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#reports" class="nav-link text-white "
                                    aria-controls="reports" role="button" aria-expanded="false">
                                    <span class="sidenav-mini-icon"> R </span>
                                    <span class="nav-link-text ms-2 ps-1">Reports</span>
                                </a>
                                <div class="collapse " id="reports">
                                    <ul class="nav ">
                                        <li class="nav-item ">
                                            <a class="nav-link text-white " href="../../pages/dashboards/report.php">
                                                <span class="sidenav-mini-icon"> Q </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> GPQT Reports </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-link text-white " href="../../pages/dashboards/masterReport.php">
                                                <span class="sidenav-mini-icon"> MR </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Master Report </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../../pages/dashboards/masterFile.php">
                                    <span class="sidenav-mini-icon"> MF </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Master File </span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </li>
                <li class="nav-item">
                    <a href="../../pages/crm/crmDashboard.php" class="nav-link text-white "
                        role="button">
                        <i class="material-icons">view_timeline</i>
                        <span class="nav-link-text ms-2 ps-1">CRM</span>
                    </a>
                </li>
                <li id="adanced_reports" class="nav-item">
                    <a data-bs-toggle="collapse" href="#advancedReports" class="nav-link text-white" aria-controls="advancedReports" role="button" aria-expanded="false">
                        <i class="material-icons">description</i>
                         <span class="nav-link-text ms-2 ps-1">Advanced Reports</span>
                    </a>
                </li>
                <div class="collapse" id="advancedReports">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../pages/dashboards/masterInvoice.php">
                                <span class="sidenav-mini-icon"> MI </span>
                                <span class="sidenav-normal ms-2 ps-1"> Master Invoice </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../../pages/dashboards/monthlySalesReport.php">
                                <span class="sidenav-mini-icon"> DL </span>
                                <span class="sidenav-normal ms-2 ps-1"> Sales Reports </span>
                            </a>
                        </li>
                    </ul>
                </div>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">UTILITIES</h6>
                </li>


                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#pagesExamples" class="nav-link text-white "
                        aria-controls="pagesExamples" role="button" aria-expanded="false">
                        <i class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">image</i>
                        <span class="nav-link-text ms-2 ps-1">Setup</span>
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
                                            <a class="nav-link text-white "
                                                href="../../pages/dashboards/user_details.php">
                                                <span class="sidenav-mini-icon"> U </span>
                                                <span class="sidenav-normal  ms-2  ps-1">User List</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link text-white" href="pages/pages/users/new-user.php">
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
                                            <a class="nav-link text-white "
                                                href="../../pages/dashboards/customer_data.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Customer Details</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../../pages/pages/account/newClient.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New Customer</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../../pages/dashboard/customer_data.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Customer Employee List </span>
                                            </a>

                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link text-white "
                                                href="../../pages/pages/account/newClientEmployee.php">
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
                                                href="pages/ecommerce/products/new-product.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New Product </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="pages/ecommerce/products/products-list.php">
                                                <span class="sidenav-mini-icon"> P </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Products List </span>
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
                                            <a class="nav-link text-white " href="../../pages/dashboards/category.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Category </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../../pages/dashboards/brand.php">
                                                <span class="sidenav-mini-icon"> B </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Brand </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white "
                                                href="../../pages/dashboards/price_view.php">
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
                <li class="nav-item ">
                    <a class="nav-link text-white" href="log.php" target="_blank">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">receipt_long</i>
                        <span class="nav-link-text ms-2 ps-1">log</span>
                    </a>
                </li>
              
            </ul>
        </div>
    </aside>
    <main class="main-content border-radius-lg ">
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">index</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Dashboard</h6>

                </nav>
                <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </div>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                            <label class="form-label">Search here</label>
                            <input type="text" class="form-control " id="search-input">
                        </div><br>
                        <div id="search-results"></div>
                    </div>
                </div>
                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </li>

                    <li class="nav-item dropdown pe-2">
                        <a href="javascript:;" class="nav-link text-body p-0 position-relative"
                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons cursor-pointer">
                                notifications
                            </i>
                        </a href="pages/pages/profile/notification/notification.php">
                        <span
                            class="position-absolute top-5 start-100 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2">
                            <span class="small" id="count"></span>
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n4"
                            aria-labelledby="dropdownMenuButton">
                        </ul>
                    </li>
                </ul>
            </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid ">
            <div class="row w-100">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card w-100 ">
                        <div class="card-body">
                            <div id="marketingQuote"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Include FullCalendar CSS and JS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
        <script src="./components/daily-quote-component.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const loadingScreen = document.getElementById('loadingScreen')
                loadingScreen.style.display = 'flex'
                setTimeout(() => {
                    loadingScreen.style.display = 'none'
                }, 1000)

                const options = {
                    showDate: true,
                    dateFormat: 'en-US',
                    backgroundImages: [
                        'https://img.freepik.com/free-photo/cloud-computing-backgrounds-dull-gray-blank_1134-1368.jpg',
                        'https://img.freepik.com/free-photo/man-jump-through-gaps-hills_1150-19693.jpg',
                        'https://unsplash.com/photos/6MePtA9EVDA/download?force=true',
                        'https://unsplash.com/photos/3Mhgvrk4tjM/download?force=true',
                        'https://img.freepik.com/free-photo/training-achieve-objectives_1134-420.jpg',
                        'https://img.freepik.com/premium-photo/men-first-award-podium-gold_1150-887.jpg',
                        'https://img.freepik.com/free-photo/happy-successful-businessman_53876-89038.jpg'
                    ],
                    defaultBackground: 'https://img.freepik.com/free-photo/cloud-computing-backgrounds-dull-gray-blank_1134-1368.jpg'
                };

                try {
                    const loadedImages = await preloadImages(options.backgroundImages);
                    const randomImage = loadedImages[Math.floor(Math.random() * loadedImages.length)];
                    // document.body.style.backgroundImage = `url('${randomImage}')`;
                } catch (error) {
                    console.error("Error loading images:", error);
                    document.body.style.backgroundImage = `url('${options.defaultBackground}')`;
                }

                const marketingQuote = new MarketingQuote('marketingQuote', options);
            });

            /**
             * Preloads images and returns an array of successfully loaded images.
             * @param {string[]} imageUrls - List of image URLs to preload.
             * @returns {Promise<string[]>} - Promise that resolves to an array of successfully loaded image URLs.
             */
            async function preloadImages(imageUrls) {
                const loadImage = (url) => new Promise((resolve, reject) => {
                    const img = new Image();
                    img.src = url;
                    img.onload = () => resolve(url);
                    img.onerror = () => reject(`Failed to load image: ${url}`);
                });

                const results = await Promise.allSettled(imageUrls.map(loadImage));
                return results
                    .filter(result => result.status === 'fulfilled')
                    .map(result => result.value);
            }





            $(document).ready(function() {
                $('#search-input').on('input', function() {
                    var searchTerm = $(this).val();
                    console.log(searchTerm)
                    if (searchTerm.length > 0) {
                        $.ajax({
                            method: 'POST',
                            url: 'functions/search.php',
                            data: {
                                search: searchTerm
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                var results = $('#search-results');
                                results.empty();
                                if (data.length > 0) {
                                    var dropdown = $('<ul class="search-results-dropdown"></ul>');
                                    data.forEach(function(item) {
                                        var listItem = $('<li>' + item.item_code + '</li>');
                                        listItem.click(function() {
                                            redirectToPage(item);
                                        });
                                        dropdown.append(listItem);
                                    });
                                    results.append(dropdown);
                                } else {
                                    results.html('<p style="color:red;background:white;border:1px;border-radius:8px">No results found.</p>');
                                }
                            },
                            error: function() {
                                console.log('Error in AJAX request.');
                            }
                        });
                    } else {
                        $('#search-results').empty();
                    }
                });



                function redirectToPage(item) {
                    // Check the source and redirect accordingly
                    if (item.source === 'product') {
                        if (item.product_category === 'NAS') {
                            window.location.href = './pages/ecommerce/products/product-synology-page.php?item_code=' + encodeURIComponent(item.item_code);
                        } else {
                            window.location.href = './pages/ecommerce/products/product-page.php?item_code=' + encodeURIComponent(item.item_code);
                        }
                    } else if (item.source === 'product_synology') {
                        // Customize the redirection for the product_synology table
                        window.location.href = './pages/ecommerce/products/product-synology-page.php?item_code=' + encodeURIComponent(item.item_code);

                    } else {
                        // Handle other sources or redirect to a default page
                        console.log('Redirecting to default page');
                    }
                }

            });
        </script>
        <script>
            $(document).ready(function() {
                // Initialize FullCalendar
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    height: 450,
                    editable: true,
                    events: [{
                            title: 'New Year\'s Day',
                            start: '2023-01-01',
                            backgroundColor: 'red'
                        },
                        {
                            title: 'Christmas Day',
                            start: '2023-12-25',
                            backgroundColor: 'green'
                        }
                    ]
                });

                // Update the current time every second
                function updateTime() {
                    $('#current-time').text(moment().format('LTS'));
                }
                setInterval(updateTime, 1000);
                updateTime();
            });
        </script>
    </main>
    <script>
        const user = '<?php echo $_SESSION["user"]; ?>';
        const category = '<?php echo $_SESSION["user_level"]; ?>';

        if (category == 'manager' || category == 'admin') {
            document.getElementById('adanced_reports').style.display = 'block';
        } else {
            document.getElementById('adanced_reports').style.display = 'none';
        }
        //console.log('user',user);
        function notification() {
            fetch('./functions/fetchQuotations.php')
                .then(response => response.json())
                .then(data => {
                    // Filter data based on the user
                    const filteredData = data.filter(item => item.pmanager === user && item.status != 'approved');
                    console.log('filteredData', filteredData);
                    // Update the count in the HTML element
                    const countElement = document.getElementById('count');
                    countElement.textContent = filteredData.length;

                    // Clear existing notifications
                    const dropdownMenu = document.querySelector('.dropdown-menu', 'w-100');
                    dropdownMenu.innerHTML = '';

                    // Create and append new notifications
                    filteredData.forEach((item, index) => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('mb-1');

                        const anchor = document.createElement('a');
                        anchor.classList.add('dropdown-item', 'border-radius-md');
                        anchor.href = 'javascript:;';
                        anchor.dataset.dealId = item.deal_id; // Store the deal_id as a data attribute

                        // Add a click event listener to the anchor
                        anchor.addEventListener('click', () => {
                            const clickedDealId = anchor.dataset.dealId;
                            console.log(`User clicked on deal_id: ${clickedDealId}`);

                            window.location.href = `./pages/dashboards/qt_view.php?code=${clickedDealId}`;
                        });

                        const div = document.createElement('div');
                        div.classList.add('d-flex', 'align-items-center', 'py-1');

                        const spanIcon = document.createElement('span');
                        spanIcon.classList.add('material-icons');
                        spanIcon.textContent = 'email';

                        const divContent = document.createElement('div');
                        divContent.classList.add('ms-1');

                        const header = document.createElement('h6');
                        header.classList.add('text-sm', 'font-weight-normal', 'my-auto');
                        header.textContent = 'Need your Approval';

                        const table = document.createElement('table');
                        table.classList.add('table', 'table-borderless');

                        const thead = document.createElement('thead');
                        const tr = document.createElement('tr');
                        const tdDealId = document.createElement('td');
                        tdDealId.textContent = item.deal_id;
                        const tdDealName = document.createElement('td');
                        tdDealName.textContent = item.deal_name;

                        // Append elements
                        thead.appendChild(tr);
                        tr.appendChild(tdDealId);
                        tr.appendChild(tdDealName);
                        table.appendChild(thead);
                        divContent.appendChild(header);
                        divContent.appendChild(table);
                        div.appendChild(spanIcon);
                        div.appendChild(divContent);
                        anchor.appendChild(div);
                        listItem.appendChild(anchor);
                        dropdownMenu.appendChild(listItem);

                    });
                    if (filteredData.length > 4) {
                        dropdownMenu.style.maxHeight = '500px'; // Adjust the height as needed
                        dropdownMenu.style.overflowY = 'auto';
                    }

                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Call the notification function
        notification();
    </script>
    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="material-icons py-2">settings</i>
        </a>
        <div class="card shadow-lg">
            <div class="card-header pb-0 pt-3">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Active Solutions Configurator</h5>
                    <p>See our dashboard options.</p>
                </div>
                <div class="float-end mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="material-icons">clear</i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr class="horizontal dark my-1">
            <div class="card-body pt-sm-3 pt-0">
                <!-- Sidebar Backgrounds -->
                <div>
                    <h6 class="mb-0">Sidebar Colors</h6>
                </div>
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-dark" data-color="dark"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger"
                            onclick="sidebarColor(this)"></span>
                    </div>
                </a>

                <!-- Sidenav Type -->

                <div class="mt-3">
                    <h6 class="mb-0">Sidenav Type</h6>
                    <p class="text-sm">Choose between 2 different sidenav types.</p>
                </div>

                <div class="d-flex">
                    <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark"
                        onclick="sidebarType(this)">Dark</button>
                    <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent"
                        onclick="sidebarType(this)">Transparent</button>
                    <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white"
                        onclick="sidebarType(this)">White</button>
                </div>

                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>



                <!-- Navbar Fixed -->

                <div class="mt-3 d-flex">
                    <h6 class="mb-0">Navbar Fixed</h6>
                    <div class="form-check form-switch ps-0 ms-auto my-auto">
                        <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed"
                            onclick="navbarFixed(this)">
                    </div>
                </div>



                <hr class="horizontal dark my-3">
                <div class="mt-2 d-flex">
                    <h6 class="mb-0">Sidenav Mini</h6>
                    <div class="form-check form-switch ps-0 ms-auto my-auto">
                        <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarMinimize"
                            onclick="navbarMinimize(this)">
                    </div>
                </div>


                <hr class="horizontal dark my-3">
                <div class="mt-2 d-flex">
                    <h6 class="mb-0">Light / Dark</h6>
                    <div class="form-check form-switch ps-0 ms-auto my-auto">
                        <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version"
                            onclick="darkMode(this)">
                    </div>
                </div>
                <hr class="horizontal dark my-sm-4">




                <div class="w-100 text-center">
                    <a class="github-button" href="../../dashboard.php" data-icon="octicon-star" data-size="large"
                        data-show-count="true"
                        aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
                    <h6 class="mt-3">Have a nice day!!</h6>


                </div>
            </div>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>
    <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>

    <!-- Kanban scripts -->
    <script src="./assets/js/plugins/dragula/dragula.min.js"></script>
    <script src="./assets/js/plugins/jkanban/jkanban.js"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>


    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="./assets/js/material-dashboard.min.js?v=3.0.6"></script>
</body>

</html>