<!--
=========================================================
* Material Dashboard 2 PRO - v3.0.6
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard-pro
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->

<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
  // Redirect to the login page
  header("Location: ../../index.php ");
  exit();
}

// The rest of your dashboard.php code here
?>

<!DOCTYPE html>

<html lang="en">

<head>

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
    </style>

</head>


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
                            <li class="nav-item" >
                            <a  href="../../pages/crm/crmDashboard.php" class="nav-link text-white "
                                    role="button" >
                                    <i class="material-icons">schedule</i>
                                    <span class="nav-link-text ms-2 ps-1">CRM</span>
                                </a>
                            </li>

                <li class="nav-item mt-3">
                    <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">PAGES</h6>
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
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#ecommerceExamples" class="nav-link text-white "
                        aria-controls="ecommerceExamples" role="button" aria-expanded="false">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">shopping_basket</i>
                        <span class="nav-link-text ms-2 ps-1">Orders</span>
                    </a>
                    <div class="collapse " id="ecommerceExamples">
                        <ul class="nav ">



                            <!--nbb    -->



                            <li class="nav-item ">
                                <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                    href="#ordersExample">
                                    <span class="sidenav-mini-icon"> O </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Orders <b class="caret"></b></span>
                                </a>

                                <div class="collapse " id="ordersExample">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">

                                            <a class="nav-link text-white " href="pages/ecommerce/orders/list.php">
                                                <span class="sidenav-mini-icon"> O </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Order List </span>
                                            </a>

                                        </li>

                                        <li class="nav-item">

                                            <a class="nav-link text-white " href="pages/ecommerce/orders/details.php">
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
                    <a class="nav-link text-white" href="log.php" target="_blank">
                        <i
                            class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">receipt_long</i>
                        <span class="nav-link-text ms-2 ps-1">log</span>
                    </a>
                </li>
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
                            <input type="text" class="form-control" id="search-input">
                        </div><br>
                        <div id="search-results"></div>
                    </div>

                    <script>
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
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item">
                            <a href="pages/pages/profile/overview.php" class="nav-link text-body p-0 position-relative"
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
                            </a>
                            </i>
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
                                <span class="small" id="count" ></span>
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
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12 position-relative z-index-2">
                    <div class="card card-plain mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column h-100">
                                        <h2 class="font-weight-bolder mb-0">General Statistics</h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="card  mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div
                                        class="icon icon-lg icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">weekend</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Previous Month</p>
                                        <h4 class="mb-0">281</h4>
                                    </div>
                                </div>

                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3">
                                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55%
                                        </span>than last month</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="card  mb-2">
                                <div class="card-header p-3 pt-2 bg-transparent">
                                    <div
                                        class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">store</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize ">Last Month</p>
                                        <h4 class="mb-0 ">34k</h4>
                                    </div>
                                </div>

                                <hr class="horizontal my-0 dark">
                                <div class="card-footer p-3">
                                    <p class="mb-0 "><span class="text-success text-sm font-weight-bolder">+1%
                                        </span>than previous month</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card ">
                                <div class="card-header p-3 pt-2 bg-transparent">
                                    <div
                                        class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">person_add</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize ">This Month</p>
                                        <h4 class="mb-0 ">+91</h4>
                                    </div>
                                </div>

                                <hr class="horizontal my-0 dark">
                                <div class="card-footer p-3">
                                    <p class="mb-0 ">Just updated</p>
                                </div>
                            </div>

                        </div>
                        <div class="col-3">
                            <div class="card ">
                                <div class="card-header p-3 pt-2 bg-transparent">
                                    <div
                                        class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">person_add</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize ">This year</p>
                                        <h4 class="mb-0 ">+91</h4>
                                    </div>
                                </div>

                                <hr class="horizontal my-0 dark">
                                <div class="card-footer p-3">
                                    <p class="mb-0 ">Just updated</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-10">
                            <div class="card mb-4 ">
                                <div class="d-flex">
                                    <div
                                        class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-xl mt-n3 ms-4">
                                        <i class="material-icons opacity-10" aria-hidden="true">language</i>
                                    </div>
                                    <h6 class="mt-3 mb-2 ms-3 ">Sales by Country</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-7">
                                            <div class="table-responsive">
                                                <table class="table align-items-center ">
                                                    <tbody>
                                                        <tr>
                                                            <td class="w-30">
                                                                <div class="d-flex px-2 py-1 align-items-center">
                                                                    <div>
                                                                        <img src="./assets/img/icons/flags/US.png"
                                                                            alt="Country flag">
                                                                    </div>
                                                                    <div class="ms-4">
                                                                        <p class="text-xs font-weight-bold mb-0 ">
                                                                            Country:</p>
                                                                        <h6 class="text-sm font-weight-normal mb-0 ">
                                                                            United States</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Sales:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">2500
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Value:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">
                                                                        $230,900</h6>
                                                                </div>
                                                            </td>
                                                            <td class="align-middle text-sm">
                                                                <div class="col text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Bounce:
                                                                    </p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">29.9%
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="w-30">
                                                                <div class="d-flex px-2 py-1 align-items-center">
                                                                    <div>
                                                                        <img src="./assets/img/icons/flags/DE.png"
                                                                            alt="Country flag">
                                                                    </div>
                                                                    <div class="ms-4">
                                                                        <p class="text-xs font-weight-bold mb-0 ">
                                                                            Country:</p>
                                                                        <h6 class="text-sm font-weight-normal mb-0 ">
                                                                            Germany</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Sales:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">3.900
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Value:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">
                                                                        $440,000</h6>
                                                                </div>
                                                            </td>
                                                            <td class="align-middle text-sm">
                                                                <div class="col text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Bounce:
                                                                    </p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">40.22%
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="w-30">
                                                                <div class="d-flex px-2 py-1 align-items-center">
                                                                    <div>
                                                                        <img src="./assets/img/icons/flags/GB.png"
                                                                            alt="Country flag">
                                                                    </div>
                                                                    <div class="ms-4">
                                                                        <p class="text-xs font-weight-bold mb-0 ">
                                                                            Country:</p>
                                                                        <h6 class="text-sm font-weight-normal mb-0 ">
                                                                            Great Britain</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Sales:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">1.400
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Value:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">
                                                                        $190,700</h6>
                                                                </div>
                                                            </td>
                                                            <td class="align-middle text-sm">
                                                                <div class="col text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Bounce:
                                                                    </p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">23.44%
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="w-30">
                                                                <div class="d-flex px-2 py-1 align-items-center">
                                                                    <div>
                                                                        <img src="./assets/img/icons/flags/BR.png"
                                                                            alt="Country flag">
                                                                    </div>
                                                                    <div class="ms-4">
                                                                        <p class="text-xs font-weight-bold mb-0 ">
                                                                            Country:</p>
                                                                        <h6 class="text-sm font-weight-normal mb-0 ">
                                                                            Brasil</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Sales:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">562
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Value:</p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">
                                                                        $143,960</h6>
                                                                </div>
                                                            </td>
                                                            <td class="align-middle text-sm">
                                                                <div class="col text-center">
                                                                    <p class="text-xs font-weight-bold mb-0 ">Bounce:
                                                                    </p>
                                                                    <h6 class="text-sm font-weight-normal mb-0 ">32.14%
                                                                    </h6>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-5">
                                            <div id="map" class="mt-0 mt-lg-n4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="globe" class="position-absolute end-0 top-10 mt-sm-3 mt-7 me-lg-7">
                        <canvas width="700" height="600"
                            class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
                    </div>
                </div>
            </div>
            <footer class="footer py-4  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © <script>
                                document.write(new Date().getFullYear())
                                </script>,
                                <i class="fa fa-heart"></i>
                                <a href="https://www.activelk.com" class="font-weight-bold" target="_blank">ACTIVE
                                    SOLUTIONS</a>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://activelk.com/" class="nav-link text-muted" target="_blank">Active
                                        Solutions</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://activelk.com/" class="nav-link text-muted" target="_blank">About
                                        Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://activelk.com/" class="nav-link text-muted" target="_blank">Blog</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>
    <script>
        const user = '<?php echo $_SESSION["user"]; ?>';
        //console.log('user',user);
        function notification() {
        fetch('./functions/fetchQuotations.php')
            .then(response => response.json())
            .then(data => {
            // Filter data based on the user
            const filteredData = data.filter(item => item.pmanager === user && item.status != 'approved');
            console.log('filteredData',filteredData);
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