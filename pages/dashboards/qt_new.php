<?php include '../../connection.php'; ?>



<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page
    header("Location: ../../index.php ");
    exit();
}

// Get the username from the session
$username = $_SESSION["user"];
?>

<?php

// Query to fetch the last deal_id
$sql = "SELECT deal_id FROM quotation ORDER BY deal_id DESC LIMIT 1";
$result = $conn->query($sql);

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();

    // Get the last deal_id
    $lastDealId = $row['deal_id'];

    // Output the result
} else {
    echo "No results found";
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
        New Quotation
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="../../../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="../../../assets/css/material-dashboard.css?v=3.0.6" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css">

    <script src="../../functions/choices.min.js"></script>
    <style>
        table {

            width: 100%;
            border: 1px black;
        }

        th,
        td {

            text-align: center;
            padding: 4px;
            width: 50px;
        }

        th {
            background-color: #f2f2f2;
        }

        #addRowBtn,
        #removeRowBtn {
            margin-top: 2px;
        }

        input {
            border: none;
            width: 50px;
            height: 35px;
            background: transparent;
            margin-top: 2px;

        }

        label {
            font-weight: bold;
        }

        select {
            border: none;
        }

        textarea {
            border: none;
            width: 100%;
            background: transparent;
        }

        tr {
            align-content: center;
        }

        .select {
            width: 100px;
            background: transparent;
        }

        #itemCodesDropdown {
            width: 120%;
            border: none;
        }

        .select2-selection__arrow {
            height: 50px;
            background: black;
        }

        .form-select {
            /* Add your desired styles here */
            background-color: transparent;
            /* Example background color */
            /* Example text color */
            border: 1px solid #ced4da;
            /* Example border color */
            border-radius: 5px;
            /* Example border radius */
            padding: 0.5rem;
            /* Example padding */
        }

        .form-select option {
            /* Add your desired styles for options here */
            background-color: #e9ecef;
            /* Example background color for options */
            color: #555;
            /* Example text color for options */
            font-weight: bold;
            /* Example font weight for options */
            padding: 1rem 1rem;
        }

        .form-select option:hover {
            background-color: #f3969a;
            /* Example background color on hover */
            color: #ffffff;
            /* Example text color on hover */
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
                    <div class="collapse" id="ProfileNav">
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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                                href="javascript:;">Dashboard</a>
                        </li>
                        <a href="../../pages/dashboards/quotation.php" class="l">
                            <li class="breadcrumb-item text-sm text-dark active" aria-current="page"> / Quotation</li>
                        </a>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">New Qt</h6>
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
                            <input type="text" class="form-control">
                        </div>
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
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
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
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="multisteps-form">
                        <!--progress bar-->
                        <div class="row">
                            <div class="col-12 col-lg-8 ">
                            </div>
                        </div>
                        <!--form panels-->
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <div class="card" style="width: 150%;">
                                    <div class="card-header p-0 position-relative  z-index-2">
                                        <div class="bg-dark shadow-dark border-radius-lg pt-4 pb-3">
                                            <div class="multisteps-form__progress">
                                                <button class="multisteps-form__progress-btn js-active" type="button"
                                                    title="User Info">
                                                    <span>Qt Info</span>
                                                </button>
                                                <button class="multisteps-form__progress-btn" type="button"
                                                    title="Address">Details</button>
                                                <button class="multisteps-form__progress-btn" type="button"
                                                    title="Socials">Comment</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background: transparent; height: 1100px" class="card-body">
                                        <form class="multisteps-form__form needs-validation" novalidate method="POST">
                                            <!--single form panel-->
                                            <div class="multisteps-form__panel border-radius-xl bg-white js-active"
                                                data-animation="FadeIn">
                                                <div class="row">
                                                    <div class="col-9">
                                                    </div>
                                                    <div class="col-3 text-end mb-0">
                                                        AS-Qt-<input id="qtNum" value="<?php echo $lastDealId + 1 ?>">
                                                    </div>
                                                </div>
                                                <div class="multisteps-form__content">
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                                            <div class="input-group input-group-dynamic">
                                                                <label class="form-label">Deal Name</label>
                                                                <input class="multisteps-form__input form-control"
                                                                    type="text" name="deal_name" />
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="input-group input-group-dynamic">

                                                                <input class="multisteps-form__input form-control"
                                                                    type="text" name="deal_date" id="dateInput" />
                                                            </div>
                                                        </div>
                                                        <!--Script for add current date-->

                                                        <script>
                                                            // Function to get the current date in YYYY-MM-DD format
                                                            function getCurrentDate() {
                                                                const now = new Date();
                                                                const year = now.getFullYear();
                                                                const month = String(now.getMonth() + 1).padStart(2,
                                                                    '0'); // January is 0
                                                                const day = String(now.getDate()).padStart(2, '0');
                                                                return `${year}-${month}-${day}`;
                                                            }

                                                            // Autofill the date field and allow editing
                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                const dateInput = document.getElementById(
                                                                    'dateInput');
                                                                if (dateInput) {
                                                                    dateInput.value = getCurrentDate();
                                                                }
                                                            });
                                                        </script>

                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-sm-6">
                                                            <div class="input-group input-group-dynamic">
                                                                <?php
                                                                $sql = "SELECT company_name FROM customer WHERE type='partner'";
                                                                $result = mysqli_query($conn, $sql);
                                                                if ($result) {
                                                                    echo '<select class="form-control" name="partner_name" id="choices-company" required>';
                                                                    echo '<option value="">Select Partner</option>';

                                                                    // Loop through the results and create an option for each company
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        $companyName = $row['company_name'];
                                                                        echo '<option value="' . $companyName . '">&nbsp;' . $companyName . '</option>';
                                                                    }

                                                                    echo '</select>';
                                                                } else {
                                                                    echo 'Error: ' . mysqli_error($conn);
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="input-group input-group-dynamic">
                                                                <label for="choices-cusEmployee" class="form-label"></label>
                                                                <select class="form-control border-0" aria-label="Default select example" name="choices-cusEmployee" id="choices-cusEmployee">
                                                                    <option>SELECT</option>
                                                                    <!-- Add more options here -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            document.getElementById('choices-company').addEventListener('change', function() {
                                                                var selectedValue = this.value.trim(); // Trim the selected value
                                                                console.log('selectedValue', selectedValue);

                                                                fetchEmployee(selectedValue); // Call fetchEmployee with the selectedValue


                                                                function fetchVAT() {
                                                                    // Make a fetch request to fetch the employee name
                                                                    fetch(`../../functions/fetchVatNo.php?company=${selectedValue}`)
                                                                        .then(response => {
                                                                            console.log(response); // Log the entire response object
                                                                            if (!response.ok) {
                                                                                throw new Error('Network response was not ok');
                                                                            }
                                                                            return response.json();
                                                                        })
                                                                        .then(data => {
                                                                            // Log to check the fetched employee name
                                                                            console.log('Fetched fetchVAT:', data);
                                                                            document.getElementById('vat').value = data.vat;

                                                                            if (data.vat == null || data.vat == 0) {
                                                                                document.getElementById('nonVat').checked = true;
                                                                            } else {
                                                                                document.getElementById('checkVat').checked = true;

                                                                            }
                                                                        })
                                                                        .catch(error => console.error('Error fetching employee name:', error));
                                                                }

                                                                // Call the fetchEmployee function
                                                                fetchVAT();

                                                            });

                                                            function fetchEmployee(selectedValue) {
                                                                // Make a fetch request to fetch the customer ID
                                                                fetch(`../../functions/cus_employees.php?company=${selectedValue}`)
                                                                    .then(response => {
                                                                        if (!response.ok) {
                                                                            throw new Error('Network response was not ok');
                                                                        }
                                                                        return response.json();
                                                                    })
                                                                    .then(data => {
                                                                        console.log('data', data);
                                                                        if (data.error) {
                                                                            console.error('Error:', data.error);
                                                                        } else {
                                                                            // Log to check the fetched customer ID
                                                                            console.log('Fetched Customer ID:', data.customer_id);
                                                                            customerID = data.customer_id;
                                                                            fetchEmpId(customerID);
                                                                        }
                                                                    })
                                                                    .catch(error => console.error('Error fetching customer ID:', error));
                                                            }

                                                            function fetchEmpId(customerID) {
                                                                // Make a fetch request to fetch the customer ID
                                                                fetch(`../../functions/cus_emp_junc.php?cusId=${customerID}`)
                                                                    .then(response => {
                                                                        if (!response.ok) {
                                                                            throw new Error('Network response was not ok');
                                                                        }
                                                                        return response.json();
                                                                    })
                                                                    .then(data => {
                                                                        console.log('data', data);
                                                                        if (data.error) {
                                                                            console.error('Error:', data.error);
                                                                        } else {
                                                                            // Log to check the fetched customer IDs
                                                                            console.log('Fetched Cus_em_ids:');
                                                                            data.data.forEach(row => {
                                                                                console.log(row.cus_em_id);
                                                                                // Call fetchEmp for each cus_em_id
                                                                                fetchEmp(row.cus_em_id);
                                                                            });
                                                                        }
                                                                    })
                                                                    .catch(error => {
                                                                        console.error('Error fetching customer ID:', error);
                                                                        // Log the entire response object
                                                                        console.log('Response:', error.response);
                                                                    });
                                                            }

                                                            function fetchEmp(cus_em_id) {

                                                                // Make a fetch request to fetch the employee name
                                                                fetch(`../../functions/fetch_cus_employee.php?empId=${cus_em_id}`)
                                                                    .then(response => {
                                                                        if (!response.ok) {
                                                                            throw new Error('Network response was not ok');
                                                                        }
                                                                        return response.json();
                                                                    })
                                                                    .then(data => {
                                                                        console.log('data testing', data);
                                                                        if (data.error) {
                                                                            console.error('Error:', data.error);
                                                                        } else {
                                                                            // Get the select element
                                                                            const selectElement = document.getElementById("choices-cusEmployee");
                                                                            // Create an option element
                                                                            const option = document.createElement("option");
                                                                            // Set the value and text content of the option
                                                                            option.value = data.cus_em_name; // You can set this to whatever value you need
                                                                            option.textContent = data.cus_em_name; // Use the fetched employee name
                                                                            // Append the option to the select element
                                                                            selectElement.appendChild(option);
                                                                        }
                                                                    })
                                                                    .catch(error => {
                                                                        console.error('Error fetching employee:', error);
                                                                        // Log the entire response object
                                                                        console.log('Response:', error.response);
                                                                    });
                                                            }
                                                        </script>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                                            <div class="input-group input-group-dynamic">
                                                                <select class="form-control" name="project_month"
                                                                    id="choices-month" required>
                                                                    <option><b>&nbsp;Select Project Month</b></option>
                                                                    <option value="Januaru"> &nbsp; January</option>
                                                                    <option value="February">&nbsp; February</option>
                                                                    <option value="March">&nbsp; March</option>
                                                                    <option value="April">&nbsp; April</option>
                                                                    <option value="May">&nbsp; May</option>
                                                                    <option value="June">&nbsp; June</option>
                                                                    <option value="July">&nbsp; July</option>
                                                                    <option value="August">&nbsp; August</option>
                                                                    <option value="September">&nbsp;&nbsp;September
                                                                    </option>
                                                                    <option value="Octomber">&nbsp; Octomber</option>
                                                                    <option value="November">&nbsp; November</option>
                                                                    <option value="December">&nbsp; December</option>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="input-group input-group-dynamic">
                                                                <?php
                                                                $sql = "SELECT company_name FROM customer WHERE type='end_customer'";


                                                                $result = mysqli_query($conn, $sql);

                                                                // Check if the query was successful
                                                                if ($result) {
                                                                    echo '<select class="form-control" name="end_customer" id="choices-endCustomer" required>';
                                                                    echo '<option>Select End-Customer</option>';

                                                                    // Loop through the results and create an option for each company
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        $companyName = $row['company_name'];
                                                                        $vat = $row['vat'];
                                                                        echo '<option value="' . $companyName . '">&nbsp;' . $companyName . '</option>';
                                                                    }

                                                                    echo '</select>';
                                                                } else {
                                                                    echo 'Error: ' . mysqli_error($conn);
                                                                }

                                                                // Close the database connection

                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 mt-3">
                                                                <div class="input-group input-group-dynamic">
                                                                    <label class="form-label">VAT No</label>
                                                                    <input class="multisteps-form__input form-control"
                                                                        type="text" name="vat" id="vat" value="" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mt-3 ps-4 ">
                                                                <div class="input-group">
                                                                    <?php
                                                                    $sql = "SELECT user_name FROM system_user";
                                                                    $result = mysqli_query($conn, $sql);

                                                                    // Check if the query was successful
                                                                    if ($result) {
                                                                        echo '<select class="form-control" name="pmanager" id="choices-pmanager" required>';

                                                                        // Loop through the results and create an option for each user
                                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                                            $userName = $row['user_name'];
                                                                            echo '<option value="' . $userName . '">&nbsp;' . $userName . '</option>';
                                                                        }
                                                                        echo '<option selected>Product Manager</option>';
                                                                        echo '</select>';
                                                                    } else {
                                                                        echo 'Error: ' . mysqli_error($conn);
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-6 ">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <label for="">VAT Value : </label>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="checkVat" id="nonVat" value="0" checked>
                                                                        <label class="form-check-label" for="nonVat">
                                                                            Hide
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-4">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="checkVat" id="checkVat" value="1">
                                                                        <label class="form-check-label" for="checkVat">
                                                                            Show
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <label for="">Discount: </label>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="checkDiscount" id="no" value="0" checked>
                                                                        <label class="form-check-label" for="no">Hide</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="checkDiscount" id="yes" value="1">
                                                                        <label class="form-check-label" for="yes">Show</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="button-row d-flex mt-4">
                                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next"
                                                            type="button" onclick="valication()" title="Next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--single form panel-->
                                            <div class="multisteps-form__panel border-radius-xl bg-white "
                                                data-animation="FadeIn">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <div class="form-check">
                                                            <label class="form-check-label" for="dollar">Today $ Rate : </label>
                                                            <input class="w-25" type="number" id="dollar" name="dollar">

                                                        </div>
                                                    </div>

                                                    <div class="col-3 text-end mb-0">
                                                        AS-Qt-<input id="qtNum" value="<?php echo $lastDealId + 1 ?>">
                                                    </div>
                                                </div>
                                                <div class="multisteps-form__content">
                                                    <div style="font-size: small; background:transparent;">


                                                        <table class="table table-bordered table-sm text-sm bg-transparent" id="dynamic-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Item Code</th>
                                                                    <th>Price Type</th>
                                                                    <th>Price</th>
                                                                    <th>Qty</th>
                                                                    <th>Vat</th>
                                                                    <th>Ttl(Rs.)</th>
                                                                    <th>Check</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="table-body">
                                                                <tr id="row-1">
                                                                    <td>
                                                                        <select class="form-control choices" name="item_code" id="choices-tags-1">
                                                                            <option selected>select</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="price-category">
                                                                        <select style="width:10%;" class="" name="cus_type" id="choices-state-1">
                                                                            <option selected>Select</option>
                                                                            <option value="r">R</option>
                                                                            <option value="lr">LR</option>
                                                                            <option value="rt">RT</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input name="price" class="form-control text-end" type="text" min="1"><input name="cost" id="cost" type="text" hidden>
                                                                    </td>
                                                                    <td style="padding-top: 5px;"><input class=" form-control text-end" name="quantity" type="text" min="1"></td>
                                                                    <td><input name="vat" class="form-control text-end" type="text"></td>
                                                                    <td><input class="form-control text-end" name="total" type="text"><input name="rowGp" id="rowGp" type="number" hidden></td>
                                                                    <td><input name="checking" type="checkbox"></td>
                                                                </tr>
                                                                <tr id="description-row-1" class="description-row">
                                                                    <td colspan="7"><textarea type="text" name="description">
                                                            </textarea><button onclick="addRow()" class="btn " id="addRowBtn" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                                                                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                                            </svg>
                                                                        </button></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td>Discount :</td>
                                                                    <td colspan="4"></td>
                                                                    <td><input style=" width: 70px ;" class="text-end" name="discount" id="discount" type="text" value="0"></td>

                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Sum :</td>
                                                                    <td colspan="4"></td>
                                                                    <td><input style=" width: 70px ; " class="text-end" name="sum" id="sum" type="text" readonly></td>

                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>GP :</td>
                                                                    <td colspan="4"></td>
                                                                    <td><input style=" width: 70px ; " class="text-end" name="gp" id="gp"></label></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <div class="row">
                                                            <div class="col-9"></div>
                                                            <div class="col-3">
                                                                <button onclick="addRow()" class="btn btn-outline-dark" id="addRowBtn" type="button" placeholder="New Row">Add Row
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="button-row d-flex mt-4">
                                                    <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button"
                                                        title="Prev">Prev
                                                    </button>
                                                    <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next"
                                                        type="button" title="Next">Next
                                                    </button>
                                                </div>
                                            </div>
                                            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                            <!--single form panel-->
                                            <div class="multisteps-form__panel border-radius-xl bg-white h-100"
                                                data-animation="FadeIn">
                                                <div class="row">
                                                    <div class="col-9">
                                                    </div>
                                                    <div class="col-3 text-end mb-0">
                                                        AS-Qt-<input id="qtNum" value="<?php echo $lastDealId + 1 ?>">
                                                    </div>

                                                </div>
                                                <div class="multisteps-form__content mt-3">
                                                    <div class="row">
                                                        <div class="col-6 mt-3">

                                                            <div class="input-group input-group-dynamic">
                                                                <label class="form-label">Validity Period</label>
                                                                <input class="multisteps-form__textarea form-control"
                                                                    rows="5"
                                                                    placeholder="Validity Period"
                                                                    name="validity" id="validity"></input>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="input-group input-group-dynamic">
                                                                <label class="form-label">Comment</label>
                                                                <input class="multisteps-form__textarea form-control"
                                                                    rows="5"
                                                                    placeholder="Comment"
                                                                    name="comment"></input>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="button-row d-flex mt-4">
                                                        <div class="col-8">
                                                            <button class="btn bg-gradient-light mb-0 js-btn-prev"
                                                                type="button" title="Prev">Prev
                                                            </button>
                                                        </div>
                                                        <div class="col-2">
                                                            <button class="btn bg-gradient-dark ms-auto mb-0" type="submit" title="Send" name="long_qt" id="submitLongBtn">
                                                                Long Qt
                                                            </button>
                                                        </div>
                                                        <div class="col-2">
                                                            <button class="btn bg-gradient-dark ms-auto mb-0 "
                                                                type="submit" title="Send" name="print_qt" id="submitBtn">Print
                                                                Qt</button>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script src="../../../assets/js/plugins/choices.min.js"></script>

        <script>
            function valication() {
                let deal_name_input = document.getElementsByName("deal_name")[0].value;
                let deal_date = document.getElementsByName("deal_date")[0].value;
                let partner_name = document.getElementsByName("partner_name")[0].value; // Corrected from "deal_date"
                let partner_employee = document.getElementsByName("choices-cusEmployee")[0].value;
                let project_month = document.getElementsByName("project_month")[0].value;
                let vat = document.getElementsByName("vat")[0].value;
                let pmanager = document.getElementsByName("pmanager")[0].value;

                console.log('deal_name_input', deal_name_input);

                if (deal_name_input === '') {
                    alert('The deal name is empty');
                } else if (deal_date === '') {
                    alert('The deal date is empty');
                } else if (partner_name === '') {
                    alert('The partner name is empty');
                } else if (partner_employee === '') {
                    alert('The partner employee is empty');
                } else if (project_month === '') {
                    alert('The project month is empty');
                } else if (pmanager === '') {
                    alert('The product manager is empty');
                }
            }

            // Initialize Choice library for searchable dropdowns
            var choicesTags = new Choices('#choices-tags-1', {
                // Add options as needed
            });
            var choicesState = new Choices('#choices-state-1', {
                // Add options as needed
            });

            function collectGeneralData() {
                // Collect data from the input fields
                var username = "<?php echo $username; ?>";
                var task = "Create Quotation";
                var dealName = document.querySelector('input[name="deal_name"]').value;
                var dealDate = document.querySelector('input[name="deal_date"]').value;
                var partnerName = document.querySelector('select[name="partner_name"]').value;
                var partnerEmployee = document.querySelector('select[name="choices-cusEmployee"]').value;
                var projectMonth = document.querySelector('select[name="project_month"]').value;
                var endCustomer = document.querySelector('select[name="end_customer"]').value;
                var comment = document.querySelector('input[name="comment"]').value; // Change selector here
                var sum = document.querySelector('input[name="sum"]').value; // Change selector here
                var discount = document.querySelector('input[name="discount"]').value; // Change selector here
                var pManager = document.querySelector('select[name="pmanager"]').value;
                var gp = document.querySelector('input[name="gp"]').value; // Change selector here
                var validity = document.querySelector('input[name="validity"]').value; // Change selector here
                var checkDiscount = document.querySelector('[name="checkDiscount"]').checked ? 1 : 0;
                var checkVat = document.querySelector('[name="checkVat"]').checked ? 1 : 0;




                // Create an array with the collected data
                var generalData = {
                    username: username,
                    task: task,
                    dealName: dealName,
                    dealDate: dealDate,
                    partnerName: partnerName,
                    partnerEmployee: partnerEmployee,
                    projectMonth: projectMonth,
                    endCustomer: endCustomer,
                    comment: comment,
                    sum: sum,
                    discount: discount,
                    pManager: pManager,
                    gp: gp,
                    validity: validity,
                    checkDiscount: checkDiscount,
                    checkVat: checkVat
                };

                return generalData;
            }



            function collectData() {
                var tableRows = document.querySelectorAll('#table-body > tr[id^="row-"]');
                var data = [];

                tableRows.forEach(function(row) {
                    var descriptionRow = row.nextElementSibling;
                    var descriptionElement = descriptionRow ? descriptionRow.querySelector('[name="description"]') : null;

                    var rowData = {
                        item_code: row.querySelector('[name="item_code"]').value,
                        description: descriptionElement && descriptionElement.value ? descriptionElement.value : '',
                        quantity: row.querySelector('[name="quantity"]').value,
                        vat: row.querySelector('[name="vat"]').value,
                        price: row.querySelector('[name="price"]').value,
                        total: row.querySelector('[name="total"]').value,
                        checking: row.querySelector('[name="checking"]').checked ? 1 : 0
                    };

                    data.push(rowData);
                });

                console.log('data', data);
                return data;
            }




            document.getElementById('submitLongBtn').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission
                var data = collectData();
                var generalData = collectGeneralData();

                // Merge general data with table data
                var dataToSend = {
                    itemsArray: data,
                    generalData: generalData
                };

                // Use the correct variable 'dataToSend' instead of 'tableData'
                sendDataToPHP(dataToSend.itemsArray, dataToSend.generalData, 'qt_long_view.php');
            });

            document.getElementById('submitBtn').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                if (!validateForm()) {
                    // Display an alert or any other user notification method
                    alert("Please fill in all the required fields before submitting.");
                    return;
                }
                var data = collectData();
                var generalData = collectGeneralData();
                var vatType = getSelectedVATType(); // Assuming a function to get the selected VAT type
                var discount = getSelectedDiscountType();

                // Merge general data with table data
                var dataToSend = {
                    itemsArray: data,
                    generalData: generalData
                };

                // Determine the target PHP file based on VAT type

                var targetPHP;

                if (vatType === '1' && discount === '1') {
                    targetPHP = 'qt_view.php';
                } else if (vatType === '1' && discount === '0') {
                    targetPHP = 'vatNoneDiscount.php';
                } else if (vatType === '0' && discount === '1') {
                    targetPHP = '../../pages/dashboards/qt_noneVat.php';
                } else if (vatType === '0' && discount === '0') {
                    targetPHP = 'nonVatNonDiscount.php';
                } else {
                    targetPHP = 'qt_view.php';
                }


                // Use the correct variable 'dataToSend' instead of 'tableData'
                sendDataToPHP(dataToSend.itemsArray, dataToSend.generalData, targetPHP);
            });

            // Function to get the selected VAT type
            function getSelectedVATType() {
                var vatRadioButtons = document.getElementsByName('checkVat');
                for (var i = 0; i < vatRadioButtons.length; i++) {
                    if (vatRadioButtons[i].checked) {
                        return vatRadioButtons[i].value;
                    }

                }
                // Default to Non-VAT if none is selected
                return '0';
            }

            function getSelectedDiscountType() {
                var discountRadioButtons = document.getElementsByName('checkDiscount');
                for (var i = 0; i < discountRadioButtons.length; i++) {
                    if (discountRadioButtons[i].checked) {
                        return discountRadioButtons[i].value;
                    }
                }
                // Default to Non-VAT if none is selected
                return '0';
            }

            function validateForm() {
                var dateInput = document.getElementById('dateInput');
                var partnerName = document.getElementById('choices-company');
                var partnerEmployee = document.getElementById('choices-cusEmployee');
                var projectMonth = document.getElementById('choices-month');
                var endCustomer = document.getElementById('choices-endCustomer');

                // Check if any of the required fields are empty
                if (!dateInput.value || partnerName.value === 'Select Partner' || partnerEmployee.value === 'Select Partner Employee' || projectMonth.value === '<b>&nbsp;Select Project Month</b>' || endCustomer.value === 'Select End-Customer') {
                    return false; // Return false if any required field is empty
                }

                return true; // All required fields are filled
            }

            function sendDataToPHP(itemsArray, generalData, destination) {
                var jsonData = JSON.stringify({
                    itemsArray: itemsArray,
                    generalData: generalData
                });

                $.ajax({
                    type: 'POST',
                    url: '../../functions/insertQtData.php',
                    data: jsonData,
                    success: function(result) {
                        console.log('Data sent successfully:', result);

                        // Check if the result contains the deal_id
                        if (result) {
                            // Extract the deal_id from the result
                            var dealId = result.trim().replace(/^\[|\]$/g, '');

                            // Show a success message to the user
                            alert('Data sent successfully! Redirecting to ' + destination);

                            // Redirect to the appropriate page with the deal_id after a short delay (e.g., 2 seconds)
                            setTimeout(function() {
                                window.location.href = '../dashboards/' + destination + '?code=' + dealId;
                            }, 10);
                        } else {
                            // Handle the case when deal_id is not available in the result
                            console.log('result', result.trim())
                            alert('Error: deal_id not found in the result.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sending data. Status:', status, 'Response:', xhr.responseText);
                    }
                });

                console.log('Sending data:', jsonData);
            }


            function updateGp() {
                // Get all the total input fields
                var totalFields = document.querySelectorAll('[name="cost"]');
                var totalPrice = document.querySelectorAll('[name="price"]');
                var quantityFields = document.querySelectorAll('[name="quantity"]');
                // Calculate the sum of all total values
                var totalCost = Array.from(totalFields).reduce(function(accumulator, totalField) {
                    return accumulator + (parseFloat(totalField.value) || 0);

                }, 0);

                console.log('totalCost', totalCost);


                var sum1 = Array.from(totalPrice).reduce(function(accumulator, totalPrice) {
                    return accumulator + (parseFloat(totalPrice.value) || 0);
                }, 0);

                console.log('testsum1', sum1);


                // Calculate the sum of all quantity values
                var totalQuantity = Array.from(quantityFields).reduce(function(accumulator, quantityField) {
                    return accumulator + (parseFloat(quantityField.value) || 0);
                }, 0);

                console.log('totalQuantity', totalQuantity);


                totalCost = totalCost * totalQuantity;
                sum1 = sum1 * totalQuantity;

                // Calculate GP based on quantity
                var gp = sum1 - totalCost;
                var gpWithQuantity = gp;

                // Update the sum input field and display the GP with quantity

                // Log to check the calculated sum and GP with quantity
                console.log('Calculated Gp:', gp);
                console.log('Calculated Gp with Quantity:', gpWithQuantity);
            }








            function updateSum() {
                // Get all the total input fields
                var totalFields = document.querySelectorAll('[name="total"]');

                // Calculate the sum of all total values
                var sum = Array.from(totalFields).reduce(function(accumulator, totalField) {
                    return accumulator + (parseFloat(totalField.value) || 0);
                }, 0);

                var rowGpFields = document.querySelectorAll('[name="rowGp"]');

                // Calculate the sum of all total values
                var rowGpValue = Array.from(rowGpFields).reduce(function(accumulator, rowGpFields) {
                    return accumulator + (parseFloat(rowGpFields.value) || 0);
                }, 0);

                // Update the sum input field
                document.getElementById('sum').value = sum.toFixed(2);

                document.getElementById('gp').value = rowGpValue.toFixed(2);

                // Log to check the calculated sum
                console.log('Calculated Sum:', sum);
                console.log('Calculated rowGpValue:', rowGpValue);



                document.getElementById('gp').value = rowGpValue.toFixed(2);


            }




            // Add event listeners for real-time sum update
            document.getElementById('table-body').addEventListener('change', function(event) {
                if (event.target && event.target.name === 'total') {
                    updateSum();
                }
            });






            function updateGp() {
                // Get all the total input fields
                var totalFields = document.querySelectorAll('[name="total"]');

                // Calculate the sum of all total values
                var sum = Array.from(totalFields).reduce(function(accumulator, totalField) {
                    return accumulator + (parseFloat(totalField.value) || 0);
                }, 0);

                var rowGpFields = document.querySelectorAll('[name="rowGp"]');

                // Calculate the sum of all total values
                var rowGpValue = Array.from(rowGpFields).reduce(function(accumulator, rowGpFields) {
                    return accumulator + (parseFloat(rowGpFields.value) || 0);
                }, 0);

                // Update the sum input field
                document.getElementById('sum').value = sum.toFixed(2);

                document.getElementById('gp').value = rowGpValue.toFixed(2);

                // Log to check the calculated sum
                console.log('Calculated Sum:', sum);
                console.log('Calculated rowGpValue:', rowGpValue);

                var discountInput = document.getElementById('discount');
                discountInput.addEventListener('input', function() {
                    // Display user input in real-time
                    discount = discountInput.value;
                    console.log('User input:', discount);
                    updateGp();
                    // You can use the value here as needed
                });
                discountedRowGpValue = rowGpValue - discount;

                document.getElementById('gp').value = discountedRowGpValue.toFixed(2);

            }


            var discountInput = document.getElementById('discount');
            discountInput.addEventListener('input', function() {
                // Display user input in real-time
                discount = discountInput.value;
                console.log('User input:', discount);
                updateGp();
                // You can use the value here as needed
            });

            // Add event listeners for real-time sum update
            document.getElementById('table-body').addEventListener('change', function(event) {
                if (event.target && event.target.name === 'total') {
                    updateGp();
                }
            });










            function updateTotal(event) {
                // Get the current row
                var currentRow = event.target.closest('tr');

                // Get the price and quantity values
                var price = parseFloat(currentRow.querySelector('[name="price"]').value) || 0;
                var quantity = parseInt(currentRow.querySelector('[name="quantity"]').value) || 0;
                var vat = parseInt(currentRow.querySelector('[name="vat"]').value) || 0;
                var cost = parseInt(currentRow.querySelector('[name="cost"]').value) || 0;
                var rowGp = parseInt(currentRow.querySelector('[name="rowGp"]').value) || 0;


                qtPrice = price * quantity;
                console.log('qtPrice', qtPrice);
                var finalCost = cost * quantity;
                var vatPercentage = vat / 100;
                var finalVat = price * vatPercentage;
                var finalPrice = price + finalVat;
                console.log('finalPrice', finalPrice);
                console.log("finalCost", finalCost);
                // Calculate the total
                var total = finalPrice * quantity;

                // Update the total input field
                currentRow.querySelector('[name="total"]').value = total.toFixed(2);
                var rowGp = qtPrice - finalCost;
                currentRow.querySelector('[name="rowGp"]').value = rowGp.toFixed(2);
                // Log to check the calculated total
                console.log('Calculated Total:', total);
                console.log("rowGp", rowGp);
                updateSum()

            }

            document.getElementById('table-body').addEventListener('change', function(event) {
                if (event.target) {
                    if (event.target.name === 'item_code') {
                        updateDescription(event);
                    } else if (event.target.name === 'cus_type') {} else if (event.target.name === 'price' || event.target.name === 'quantity' || event.target.name === 'vat') {
                        updateTotal(event);

                    }
                }
            });





            async function updateDescription(event) {
                // Get the selected item code
                var currentRow = event.target.closest('tr');
                var itemCode = encodeURIComponent(currentRow.querySelector('[name="item_code"]').value);
                var cusType = currentRow.querySelector('[name="cus_type"]').value;

                // Log to check the selected item code and cus_type
                console.log('Item Code:', itemCode);
                console.log('Cus Type:', cusType);

                // Find the description row based on the current row
                var descriptionRow = currentRow.nextElementSibling; // Assuming the description row always follows the item_code row

                // Log to check if the description row is found
                console.log('Description Row:', descriptionRow);

                // Check if the description row is found
                if (descriptionRow) {
                    try {
                        // Find the description textarea within the description row
                        var descriptionTextarea = descriptionRow.querySelector('[name="description"]');

                        // Log to check if the description textarea is found
                        console.log('Description Textarea:', descriptionTextarea);

                        // Check if the description textarea is found
                        if (descriptionTextarea) {
                            // Make an AJAX request to fetch the description
                            const response = await fetch(`../../functions/fetch_description.php?code=${itemCode}`);
                            const description = await response.text();

                            // Update the description textarea field
                            descriptionTextarea.value = description.trim();

                            // Log to check the fetched description
                            console.log('Fetched Description:', description);
                        } else {
                            console.error('Error: Description textarea not found in the description row.');
                        }
                    } catch (error) {
                        console.error('Error updating description:', error);
                    }
                } else {
                    console.error('Error: Description row not found.');
                }
            }






            // For dynamically added rows
            document.getElementById('table-body').addEventListener('change', function(event) {
                if (event.target && event.target.name === 'item_code') {
                    updateDescription(event);
                    updateSum();
                }
            });

            // Add event listeners for real-time description update
            document.querySelector('#choices-tags-1').addEventListener('change', updateDescription);

            document.getElementById('table-body').addEventListener('change', function(event) {
                if (event.target && event.target.name === 'item_code') {
                    updateDescription(event);
                    updateSum();
                }
            });



            function updatePrice(event) {
                // Get the selected item code and cus_type
                var currentRow = event.target.closest('tr');
                var itemCodeInput = currentRow.querySelector('[name="item_code"]');
                var itemCode = encodeURIComponent(itemCodeInput.value);
                var priceInput = currentRow.querySelector('[name="price"]');
                var costInput = currentRow.querySelector('[name="cost"]');
                var cusType = currentRow.querySelector('[name="cus_type"]').value;
                var dollarInput = document.getElementById('dollar').value;

                // Log to check the selected item code and cus_type
                console.log('Item Code:', itemCode);
                console.log('Cus Type:', cusType);

                // Check if the change is triggered by the user
                if (event.target.classList.contains('user-changed-price') || event.target.classList.contains('user-changed-cost')) {
                    // Do not fetch price and cost if the change is manual
                    return;
                }

                // Fetch the dollar rate
                fetch('https://api.exchangerate-api.com/v4/latest/USD')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const dollarRate = data.rates['LKR']; // Access the rate for Sri Lankan Rupees

                        // Log to check the fetched dollar rate
                        console.log('Fetched Dollar Rate:', dollarRate);



                        // Fetch the brand
                        return fetch(`../../functions/productBrand.php?code=${itemCode}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(fetchData => {
                                // Log to check the fetched brand
                                // console.log('Fetched Brand:', fetchData.brand);

                                // Make an AJAX request to fetch the price and cost
                                return fetch(`../../functions/fetch_price.php?code=${itemCode}&category=${cusType}`)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        // Log to check the fetched price and cost
                                        // console.log('Fetched Price:', data.price);
                                        // console.log('Fetched Cost:', data.cost);

                                        if (fetchData.brand === "Acronis" && priceInput && parseFloat(priceInput.value) !== parseFloat(data.price)) {
                                            if (dollarInput > 0) {
                                                priceInput.value = parseFloat(data.price) * dollarInput;
                                                // Log to check the updated price
                                                console.log('Updated Price:', data.price);
                                            } else {
                                                // Highlight the dollar input field
                                                document.getElementById('dollar').style.border = "2px solid red";
                                                document.getElementById('dollar').style.backgroundColor = "lightyellow";


                                                // Create a tooltip element
                                                var tooltip = document.createElement('span');
                                                tooltip.innerText = "please enter dollar rate";
                                                tooltip.style.position = 'absolute';
                                                tooltip.style.top = '-20px'; // Adjust as needed
                                                tooltip.style.left = '0';
                                                tooltip.style.backgroundColor = 'white';
                                                tooltip.style.padding = '1px';
                                                tooltip.style.border = '0px';
                                                tooltip.style.borderRadius = '0px';
                                                tooltip.style.zIndex = '999';

                                                // Append the tooltip to the input field's parent element
                                                document.getElementById('dollar').parentNode.appendChild(tooltip);

                                                // Set event listener to remove the tooltip when clicking outside
                                                document.addEventListener('click', function(event) {
                                                    if (!document.getElementById('dollar').contains(event.target)) {
                                                        tooltip.remove();
                                                    }
                                                });
                                                // Set dollarRate as a hint
                                                document.getElementById('dollar').placeholder = dollarRate;
                                            }
                                        } else {
                                            priceInput.value = data.price;
                                            // Log to check the updated price
                                            console.log('Updated Price:', data.price);
                                        }

                                        if (fetchData.brand === "Acronis" && costInput && parseFloat(costInput.value) !== parseFloat(data.cost)) {
                                            costInput.value = parseFloat(data.cost) * dollarInput;
                                            // Log to check the updated cost
                                            console.log('Updated Cost:', data.cost);
                                        } else {
                                            costInput.value = data.cost;
                                            // Log to check the updated cost
                                            console.log('Updated Cost:', data.cost);
                                        }
                                    });
                            });
                    })
                    .catch(error => console.error('Error:', error));
            }



            // Add an input event listener to set the 'user-changed-price' class when the user manually changes the price
            document.getElementById('table-body').addEventListener('input', function(event) {
                if (event.target && event.target.name === 'price') {
                    // Add the class to indicate that the change is triggered by the user
                    event.target.classList.add('user-changed-price');
                }
            });

            // Add an input event listener to reset the 'user-changed-price' class when the user manually changes the price and clicks elsewhere
            document.getElementById('table-body').addEventListener('focusout', function(event) {
                if (event.target && event.target.name === 'price') {
                    // Remove the class when the user clicks elsewhere after manually changing the price
                    event.target.classList.remove('user-changed-price');
                }
            });


            // Add event listeners for real-time description update
            document.querySelector('#choices-tags-1').addEventListener('change', updateDescription);
            document.querySelector('#choices-state-1').addEventListener('change', updatePrice);


            function fetchItemCodes() {
                return fetch('../../functions/fetch_item_codes.php')
                    .then(response => response.json())
                    .catch(error => {
                        console.error('Error fetching item codes:', error);
                        throw error;
                    });
                updatePrice(event)
            }

            // Call the fetchItemCodes function to populate the dropdown initially
            fetchItemCodes().then(itemCodes => {
                // Update the initial row's Choices dropdown with fetched item codes
                console.log('itemCodes:', itemCodes)
                choicesTags.setChoices(itemCodes.map(itemCode => ({
                    value: itemCode,
                    label: itemCode
                })), 'value', 'label', true);
                updateSum();
            });


            async function addRow() {
                var tableBody = document.getElementById('table-body');
                var rowCount = tableBody.children.length / 2 + 1; // Divide by 2 as there are two rows per entry

                // Fetch item codes for the new row
                try {
                    const itemCodes = await fetchItemCodes();

                    // Create item_code row
                    var itemCodeRow = createItemCodeRow(rowCount);
                    tableBody.appendChild(itemCodeRow);

                    // Initialize Choice library for searchable dropdowns in the new row
                    var choicesTagsNew = new Choices(`#choices-tags-${rowCount}`, {
                        choices: itemCodes.map(itemCode => ({
                            value: itemCode,
                            label: itemCode
                        })),
                    });

                    // Add event listeners for real-time logging and price update in the new row
                    document.querySelectorAll(`#row-${rowCount} input, #row-${rowCount} select`).forEach(function(element) {
                        element.addEventListener('change', function() {
                            console.log(element.name + ' changed to: ' + element.value);
                            if (element.name === 'item_code') {
                                updateDescription({
                                    target: element
                                }); // Call the updateDescription function when item_code changes
                            } else if (element.name === 'cus_type') {
                                updatePrice({
                                    target: element
                                }); // Call the updatePrice function when cus_type changes
                            }
                        });
                    });

                    // Create description row with a slight delay
                    setTimeout(function() {
                        var descriptionRow = createDescriptionRow(rowCount);
                        tableBody.appendChild(descriptionRow);
                        console.log('descriptionRow');

                        new Choices(`#choices-state-${rowCount}`, {
                            // Add options as needed
                        });
                    }, 100);

                } catch (error) {
                    console.error('Error adding new row:', error);
                }
            }


            function createItemCodeRow(rowCount) {
                var newRow = document.createElement('tr');
                newRow.id = `row-${rowCount}`;

                newRow.innerHTML = `
            <td>
                <select class="form-control choices" name="item_code" id="choices-tags-${rowCount}"><option selected>select</option></select>
            </td>
            <td class="price-category">
                <select style="width:150px;" class="select" name="cus_type" id="choices-state-${rowCount}">
                    <option>Select</option>
                    <option value="r">R</option>
                    <option value="lr">LR</option>
                    <option value="rt">RT</option>
                </select>
            </td>
            <td><input name="price" type="text"><input name="cost" id="cost" type="text" hidden ></td>
            <td style="padding-top: 5px;"><input class="text-center" style="margin: 2px;" name="quantity" type="number" min="1"></td>
            <td><input name="vat" type="text"></td>
            <td><input style=" width: 70px ;" name="total" type="text"><input name="rowGp" id="rowGp" type="text" hidden ></td>
            <td><input name="checking" type="checkbox"></td>
        `;

                return newRow;
            }
            document.getElementById('table-body').addEventListener('change', function(event) {
                if (event.target && (event.target.name === 'cost' || event.target.name === 'quantity')) {
                    updateSum();
                }
            });

            // Updated createDescriptionRow function
            function createDescriptionRow(rowCount) {
                var newRow = document.createElement('tr');
                newRow.id = `description-row-${rowCount}`;
                newRow.classList.add('description-row'); // Add the description-row class

                newRow.innerHTML = `
        <td colspan="7"><textarea type="text" name="description" id="description-row-${rowCount}"></textarea><button class="btn" onclick="removeRow('row-${rowCount}')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-square" viewBox="0 0 16 16">
                                                                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                                                    <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"/>
                                                                    </svg></button></td>
    `;

                return newRow;
                updateSum();
            }




            function removeRow(rowId) {
                var tableBody = document.getElementById('table-body');
                var rowToRemove = document.getElementById(rowId);
                var nextRow = rowToRemove.nextElementSibling;

                // Get the total value of the row being removed
                var totalValue = parseFloat(rowToRemove.querySelector('[name="total"]').value) || 0;

                // Remove both rows (item_code and description)
                tableBody.removeChild(rowToRemove);
                tableBody.removeChild(nextRow);

                // Update the sum by subtracting the total value of the removed row
                var currentSum = parseFloat(document.getElementById('sum').value) || 0;
                var newSum = currentSum - totalValue;

                // Update the sum input field
                document.getElementById('sum').value = newSum.toFixed(2);

                // Log to check the updated sum
                console.log('Updated Sum after removal:', newSum);
                updateSum();
            }


            // Add event listeners for real-time logging
            document.querySelectorAll('#dynamic-table input, #dynamic-table select').forEach(function(element) {
                element.addEventListener('change', function() {
                    console.log(element.name + ' changed to: ' + element.value);

                });
            });
        </script>



        <footer class="footer py-4  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                                document.write(new Date().getFullYear())
                            </script>,

                            <a href="https://www.activelk.com" class="font-weight-bold" target="_blank">ACTIVE
                                SOLUTIONS</a>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://www.activelk.com" class="nav-link text-muted" target="_blank">Active
                                    Solutions </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.activelk.com" class="nav-link text-muted" target="_blank">About
                                    Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.activelk.com" class="nav-link text-muted" target="_blank">Blog</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        </div>
    </main>
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
    <script src="../../../assets/js/core/popper.min.js"></script>
    <script src="../../../assets/js/core/bootstrap.min.js"></script>
    <script src="../../../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../../../assets/js/plugins/choices.min.js"></script>
    <script src="../../../assets/js/plugins/multistep-form.js"></script>
    <script>
        if (document.getElementById('choices-state')) {
            var element = document.getElementById('choices-state');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-company')) {
            var element = document.getElementById('choices-company');
            const example = new Choices(element, {
                searchEnabled: true
            });
        };
        if (document.getElementById('choices-pmanager')) {
            var element = document.getElementById('choices-pmanager');
            const example = new Choices(element, {
                searchEnabled: true
            });
        };
        if (document.getElementById('choices-month')) {
            var element = document.getElementById('choices-month');
            const example = new Choices(element, {
                searchEnabled: true
            });
        };
        if (document.getElementById('choices-item_code')) {
            var element = document.getElementById('choices-item');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price1')) {
            var element = document.getElementById('choices-price1');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price2')) {
            var element = document.getElementById('choices-price2');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price3')) {
            var element = document.getElementById('choices-price3');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price4')) {
            var element = document.getElementById('choices-price4');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price5')) {
            var element = document.getElementById('choices-price5');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price6')) {
            var element = document.getElementById('choices-price6');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price7')) {
            var element = document.getElementById('choices-price7');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price8')) {
            var element = document.getElementById('choices-price8');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price9')) {
            var element = document.getElementById('choices-price9');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-price10')) {
            var element = document.getElementById('choices-price10');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };
        if (document.getElementById('choices-endCustomer')) {
            var element = document.getElementById('choices-endCustomer');
            const example = new Choices(element, {
                searchEnabled: true
            });
        };
        if (document.getElementById('choices-cemployee')) {
            var element = document.getElementById('choices-cemployee');
            const example = new Choices(element, {
                searchEnabled: false
            });
        };

        if (document.getElementById('choices-tags')) {
            var tags = document.getElementById('choices-tags');
        }
    </script>
    <!-- Kanban scripts -->
    <script src="../../../assets/js/plugins/dragula/dragula.min.js"></script>
    <script src="../../../assets/js/plugins/jkanban/jkanban.js"></script>
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
    <script src="../../../assets/js/material-dashboard.min.js?v=3.0.6"></script>
</body>

</html>