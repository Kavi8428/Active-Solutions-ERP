<?php include '../../connection.php';?>



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


<?php
if (isset($_GET['code'])) {
    $id = $_GET['code'];

    // Query to fetch data from the database based on the redirection code
    $sql = "SELECT * FROM quotation WHERE deal_id = '$id'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row0 = mysqli_fetch_assoc($result);

        // Populate the HTML fields with fetched data
     
           $dealName=   $row0['deal_name'] ;
           $deal_date  =$row0['deal_date'];
           $partnerName= $row0['partner_name'];
           $partnerEmployee = $row0['partner_employee'];
           $projectMonth = $row0['project_month'];
           $endCustomer= $row0['end_customer'];
           $comment= $row0['comment'];
           $sum= $row0['sum'];
           $pManager =  $row0['pmanager'];
           $gp= $row0['gp'];
           $validity= $row0['validity'];
           $vat = $row0['vated'];
           $discount = $row0['discounted'];

          
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    echo 'Redirection code not provided in the URL.';
}

// Fetch data from the database based on the redirection code


?>















<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../../assets/img/logo (2).png">
    <title>
        Edit Quotation
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

    <script src="../../functions/choices.min.js"></script>
    <style>
        table {
            
            width: 50%;
            border: 1px black;
        }

        th, td {
            
            text-align: center;
            padding: 2px ;
            width: 50px;
        }

        th {
            background-color: #f2f2f2;
        }

        #addRowBtn, #removeRowBtn {
            margin-top: 2px;
        }
        input{
            border: none;
            width: 50px;
            height: 35px;
            background: transparent;
            margin-top: 2px;

        }
        select{
            border: none;
        }
        textarea{
            border: none;
            width: 100%;
            background: transparent;
        }
        tr{
            align-content: center;
        }
        .select{
            width: 50px;
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
                                href="javascript:;">Dashboard / </a>
                        </li>
                        <a href="../../pages/dashboards/quotation.php"><li class="breadcrumb-item text-sm text-dark active" aria-current="page"> Quotation</li></a>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Edit Qt</h6>
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
                    <div class="multisteps-form mb-9">
                        <!--progress bar-->
                        <div class="row">
                            <div class="col-12 col-lg-8 mx-auto my-5">
                            </div>
                        </div>
                        <!--form panels-->
                        <div class="row">
                            <div class="col-12 col-lg-8 m-auto">
                                <div class="card">
                                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
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
                                        <form class="multisteps-form__form" method="POST">
                                            <!--single form panel-->
                                            <div class="multisteps-form__panel border-radius-xl bg-white js-active"
                                                data-animation="FadeIn">
                                                <div class="row">
                                                    <div class="col-9">
                                                    </div>
                                                    <div class="col-3 text-end">
                                                        AS-Qt-<input value="<?php echo $id?>" disabled >
                                                    </div>
                                                </div>
                                                <p class="mb-0 text-sm"></p>
                                                <div class="multisteps-form__content">
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                                            <div class="input-group input-group-dynamic">
                                                                <label class="form-label">Deal Name</label>
                                                                <input class="multisteps-form__input form-control"
                                                                    type="text" name="deal_name" value="<?php echo $dealName?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="input-group input-group-dynamic">

                                                                <input class="multisteps-form__input form-control"
                                                                    type="date" name="deal_date" id="dateInput"  value="<?php echo $deal_date?>" />
                                                            </div>
                                                        </div>
                                                        <!--Script for add current date-->

                                                      

                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-sm-6">
                                                            <div class="input-group input-group-dynamic">
                                                                <?php
                                                                    $sql = "SELECT company_name FROM customer WHERE type='partner'";
                                                                    $result = mysqli_query($conn, $sql);
                                                                    if ($result) {
                                                                        echo '<select class="form-control" name="partner_name" id="choices-company" required>';
                                                                        echo '<option>'.$partnerName.'</option>';

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
                                                            <select class="form-control" name="CusEmp" id="cusEmp" required>
                                                            </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                                            <div class="input-group input-group-dynamic">
                                                                <select class="form-control" name="project_month"
                                                                    id="choices-month" required>
                                                                    <option><b><?php echo $projectMonth;?> </b></option>
                                                                    <option value="Januaru"> &nbsp; January</option>
                                                                    <option value="February">&nbsp; February</option>
                                                                    <option value="March">&nbsp; March</option>
                                                                    <option value="April">&nbsp; April</option>
                                                                    <option value="May">&nbsp; May</option>
                                                                    <option value="June">&nbsp; June</option>
                                                                    <option value="July">&nbsp; July</option>
                                                                    <option value="August">&nbsp; August</option>
                                                                    <option value="September">&nbsp;&nbsp;September</option>
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
                                                              //echo '<option  value="'.$endCustomer.'">'.$endCustomer.'</option>';
                                                              echo '<option>select</option>';


                                                              // Loop through the results and create an option for each company
                                                              while ($row = mysqli_fetch_assoc($result)) {
                                                                  $companyName = $row['company_name'];
                                                                  if ($companyName==$endCustomer){$selected ='selected';}
                                                                  echo '<option '.$selected.' value="' . $companyName . '">&nbsp;' . $companyName . '</option>';
                                                              }

                                                              echo '</select>';
                                                          } else {
                                                              echo 'Error: ' . mysqli_error($conn);
                                                          }

                                                          // Close the database connection
                                                          
                                                        ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row mt-3">
                                                            <div class="col-6 mt-1">
                                                                <div class="input-group input-group-dynamic">
                                                                    <label class="form-label">VAT No</label>
                                                                    <input class="multisteps-form__input form-control"
                                                                        type="text" name="vat" id="vat" value="" disabled />
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-group input-group-dynamic">
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
                                                                        echo '<option selected>'.$pManager.'</option>';
                                                                        echo '</select>';
                                                                    } else {
                                                                        echo 'Error: ' . mysqli_error($conn);
                                                                    }

                                                                    // Close the database connection
                                                                    mysqli_close($conn);
                                                                ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <div class="row mt-3">
                                                        <div class="col-8 mt-3">
                                                                <div class="row">
                                                                    <div class="col-3">
                                                                        <label for="">VAT Value : </label>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="checkVat" id="nonVat" value="0" <?php if ($vat == 1 || $vat == '' || $vat == 'null'){echo 'checked';} ?> >
                                                                            <label class="form-check-label" for="nonVat">
                                                                                Hide
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="checkVat" id="checkVat" value="1" <?php if ($vat == 0){echo 'checked';} ?> >
                                                                            <label class="form-check-label" for="checkVat">
                                                                            Show
                                                                            </label>
                                                                        </div>    
                                                                    </div> 
                                                                </div>
                                                        </div>   
                                                    </div>
                                                    <div class="row mt-3">
                                                    <div class="col-8 mt-3">
                                                            <div class="row">
                                                            <div class="col-3">
                                                                <label for="">Discount</label>
                                                            </div>
                                                                <div class="col-5">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="checkDiscount" id="no" value="0" <?php if ($discount == 1){echo 'checked';} ?>  >
                                                                        <label class="form-check-label" for="no">Hide</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="checkDiscount" id="yes" value="1" <?php if ($discount == 0){echo 'checked';} ?> >
                                                                        <label class="form-check-label" for="yes">Show</label>
                                                                    </div>    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>


    // Function to log input changes
    function logInputChange(event) {
        // Get the input element that triggered the event
        const inputElement = event.target;
        // Get the type of the input field
        const inputType = inputElement.type;
        // Initialize variable to hold the new value
        let newValue;

        // Check the type of input field
        if (inputType === 'radio' || inputType === 'checkbox') {
            // For radio buttons and checkboxes, get the new value directly
            newValue = inputElement.checked;
        } else if (inputType === 'select-one') {
            // For select menus, get the new value from the selected option
            newValue = inputElement.options[inputElement.selectedIndex].value;
        } else {
            // For other input types (text, date, etc.), get the new value from the input field value
            newValue = inputElement.value;
        }

        // Get the name of the input field
        const inputName = inputElement.name;
        // Log the input change with relevant name
        console.log(`Input field "${inputName}" changed to: ${newValue}`);
       

        sendSelectedOptionToPHP(newValue)
    }

 // Get the select elements
const selectCompany = document.getElementById('choices-company');
const selectCusEmp = document.getElementById('cusEmp');

// Add change event listeners to select elements
selectCompany.addEventListener('change', logInputChange);
//selectCusEmp.addEventListener('change', logInputChange);
    // Function to send selected option to PHP script and log response
    function sendSelectedOptionToPHP(newValue) {
        
        // Create a new XMLHttpRequest object
        const xhr = new XMLHttpRequest();
        // Define the PHP script URL
        const url = '../../functions/cus_employees.php?company=' + encodeURIComponent(newValue);
        
        // Configure the AJAX request
        xhr.open('GET', url, true);
        
        // Define the function to handle the response
        xhr.onload = function() {
            // Check if the request was successful
            if (xhr.status >= 200 && xhr.status < 300) {
                // Parse the JSON response
                const response = JSON.parse(xhr.responseText);
                // Log the response in the console
                console.log('Response from PHP script:');
                console.log(response);
                fetchEmpId(response.customer_id)
            } else {
                // Log an error message if the request was not successful
                console.error('Request failed with status:', xhr.status);
            }
        };
        
        // Define the function to handle network errors
        xhr.onerror = function() {
            console.error('Request failed');
        };
        
        // Send the AJAX request
        xhr.send();
    }

    function fetchEmpId(cusId) {
        console.log('cusId',cusId)
        // Create a new XMLHttpRequest object
        const xhr = new XMLHttpRequest();
        // Define the PHP script URL
        const url = '../../functions/cus_emp_junc.php?cusId=' + encodeURIComponent(cusId);
        
        // Configure the AJAX request
        xhr.open('GET', url, true);
        
        // Define the function to handle the response
        xhr.onload = function() {
    // Check if the request was successful
    if (xhr.status >= 200 && xhr.status < 300) {
        // Parse the JSON response
        const response = JSON.parse(xhr.responseText);
        
        // Check if the 'data' array exists in the response
        if (response && response.data && Array.isArray(response.data)) {
            // Iterate over the 'data' array
            response.data.forEach(function(item) {
                // Check if 'cus_em_id' property exists in each item
                if (item.cus_em_id) {
                    // Print the 'cus_em_id' property
                    console.log('cus_em_id:', item.cus_em_id);
                    fetchEmName(item.cus_em_id)
                } else {
                    console.error('cus_em_id property not found in item:', item);
                }
            });
        } else {
            console.error('Data array not found in response:', response);
        }
    } else {
        // Log an error message if the request was not successful
        console.error('Request failed with status:', xhr.status);
    }
};

        
        // Define the function to handle network errors
        xhr.onerror = function() {
            console.error('Request failed');
        };
        
        // Send the AJAX request
        xhr.send();
    }

var cus_em_name = "<?php echo $partnerEmployee; ?>";
console.log('cus_em_name',cus_em_name);

populateSelectWithOptions([cus_em_name])

// Define a function to populate the select element with cus_em_name options
function populateSelectWithOptions(cus_em_names) {

    // Select the <select> element
    const selectElement = document.getElementById('cusEmp');

    // Iterate over cus_em_names and create an option element for each
    cus_em_names.forEach(function(cus_em_name) {
        // Create an <option> element
        const option = document.createElement('option');
        // Set the value of the option to cus_em_name
        option.value = cus_em_name;
        
        // Set the text of the option to cus_em_name
        option.textContent = cus_em_name;
        // Append the option to the select element
        selectElement.appendChild(option);
        // Log the appended option for debugging
        console.log('Appended option:', option);
    });
}

// Function to fetch employee name using AJAX
// Function to fetch employee name using AJAX
function fetchEmName(empId) {
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    // Define the PHP script URL
    const url = '../../functions/fetch_cus_employee.php?empId=' + encodeURIComponent(empId);
    
    // Configure the AJAX request
    xhr.open('GET', url, true);
    
    // Define the function to handle the response
    xhr.onload = function() {
        // Check if the request was successful
        if (xhr.status >= 200 && xhr.status < 300) {
            // Parse the JSON response
            const response = JSON.parse(xhr.responseText);
            // Log the response in the console
            console.log('Received cus_em_names:', response.cus_em_name);

            // Initialize an array to store all employee names
            const allCusEmNames = [];

            // Check if response.cus_em_name is an array
            if (Array.isArray(response.cus_em_name)) {
                // Concatenate all received employee names into one array
                allCusEmNames.push(...response.cus_em_name);
            } else if (typeof response.cus_em_name === 'string') {
                // If response.cus_em_name is a string, push it directly to the array
                allCusEmNames.push(response.cus_em_name);
            }

            // Log the processed array
            console.log('Processed cus_em_names:', allCusEmNames);

            // Call the function to populate the select element with all received employee names
            populateSelectWithOptions(allCusEmNames);
        } else {
            // Log an error message if the request was not successful
            console.error('Request failed with status:', xhr.status);
        }
    };
    
    // Define the function to handle network errors
    xhr.onerror = function() {
        console.error('Request failed');
    };
    
    // Send the AJAX request
    xhr.send();
}


</script>


                                                    <div class="button-row d-flex mt-4">
                                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next"
                                                            type="button" title="Next">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--single form panel-->
                                            <div  class="multisteps-form__panel border-radius-xl bg-white "
                                                data-animation="FadeIn">
                                                <div class="row">
                                                    <div class="col-9">
                                                    <h5 class="font-weight-bold mb-0 container ">Products</h5>
                                                    </div>
                                                    <div class="col-3 text-end">
                                                        AS-Qt-<input value="<?php echo $id?>" disabled>
                                                    </div>
                                                </div>
                                                <div   class="multisteps-form__content">
                                                    <div style="font-size: small; background:transparent;">
      
    <?php
include '../../connection.php';

if (isset($_GET['code'])) {
    $redirection_code = $_GET['code'];
    $sql = "SELECT * FROM quotation_products WHERE deal_id = '$redirection_code'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    } else {
        echo "No rows found";
    }
}



?>

<table class="table table-bordered bg-transparent " id="dynamic-table">
    <thead>
    <tr>
        <th>Item Code</th>
        <th>Cus_Type</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Vat</th>
        <th>Total(RS./=)</th>
        <th>Check</th>
    </tr>
    </thead>

    <tbody id="table-body">
    <?php
    if ($result->num_rows > 0) {
        $rowIndex = 0;
        while ($row = $result->fetch_assoc()) {

            $itemCode = $row['item_code'];
            $price = $row['price'];
            $qt = $row['quantity'];
            $qtPrice = $price * $qt;
            $cost = 0; // Initialize $cost
            $rowCost = 0; // Initialize $rowCost

            if (isset($itemCode)) {
                $redirection_code = $itemCode;
                $costSql = "SELECT * FROM pricing WHERE item_code = '$redirection_code'";
                $costResult = $conn->query($costSql);
            
                if ($costResult) { // Check if query execution was successful
                    if ($costResult->num_rows > 0) {
                        while ($costRow = $costResult->fetch_assoc()) {
                            $cost = $costRow['cost'];
                            $rowCost = $qtPrice - $cost;
                        }
                    } else {
                        echo "<script>alert('The cost is removed or not available. Calculation may not be correct ')</script>";
                    }
                } else {
                    echo "Error executing query: " . $conn->error; // Print error message if query execution fails
                }
            }
            

            echo '<tr data-row-index="'.$rowIndex.'">';
            echo '<td>
                    <select name="itemCode[]" id="itemCode' . $rowIndex . '" class="choices-select">
                        <option>' . $row['item_code'] . '</option>
                    </select> 
                </td>';
            echo '<td>
                    <select name="cus-type[]" id="cus_type' . $rowIndex . '" class="choices-cusType">
                        <option>R</option>
                        <option>LR</option>
                        <option>RT</option>
                    </select>
                </td>';
            echo '<td><input  style=" width: 70px ;" name="price[]" type="text" value="' .$row['price'] . '"><input name="cost[]" id="cost" type="text" value="'.$cost.'" hidden ></td>';
            echo '<td style="padding-top: 5px;"><input class="text-center" style="margin: 2px; width=:10px;" name="quantity[]" type="number" min="1" value="' . $row['quantity'] . '"></td>';
            echo '<td><input class="text-center" name="vat[]" type="text" value="' . $row['vat'] . '"></td>';
            echo '<td><input  style=" width: 70px ;" name="total[]" type="text" value="' . $row['total']. '"><input name="rowGp[]" id="rowGp" type="text"  value = "'.$rowCost.'" hidden ></td>';
            echo '<td><input name="checking[]" type="checkbox" ' . ($row['checking'] ? 'checked' : '') . '></td>';
            echo '</tr>';
            echo '<tr data-row-index="'.$rowIndex.'">';
            echo '<td colspan="7">';
            echo '<textarea id="description' . $rowIndex . '" name="description[]">' . $row['description'] . '</textarea>';
            echo '<button onclick="removeRow('.$rowIndex.')" class="btn " id="addRowBtn" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
          </svg></button>';
            echo '</td>';
            echo '</tr>';
            $rowIndex++;

        }
    } else {
        echo "No rows found";
    }
    ?>
</tbody>

    <tfoot>
        <tr>
            <td>Discount :</td>
            <td colspan="4"></td>
            <td><input  style=" width: 70px ;" name="discount" id="discount" type="text" value="<?php echo $row0['discount']?>"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Sum :</td>
            <td colspan="4"></td>
            <td><input  style=" width: 70px ;" name="sum" id="sum" type="text" value="<?php echo $row0['sum']?>"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>GP :</td>
            <td colspan="4"></td>
            <td><input  style=" width: 70px ;" id="gp" name="gp" value="<?php echo $gp;?>" ></td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>


    let priceInputs, selectCustType, selectElements, quantityInputs, totalInputs;
    document.addEventListener('DOMContentLoaded', function () {

    function collectGeneralData() {
    // Collect data from the input fields
    var username = "<?php echo $username; ?>";
    var task = "Edit Quotation";
    var dealName = document.querySelector('input[name="deal_name"]').value;
    var dealDate = document.querySelector('input[name="deal_date"]').value;
    var partnerName = document.querySelector('select[name="partner_name"]').value;
    var partnerEmployee = document.querySelector('select[name="CusEmp"]').value;
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
        username:username,
        task:task,
        dealName: dealName,
        dealDate: dealDate,
        partnerName: partnerName,
        partnerEmployee: partnerEmployee,
        projectMonth: projectMonth,
        endCustomer: endCustomer,
        comment: comment,
        sum: sum,
        discount:discount,
        pManager: pManager,
        gp: gp,
        validity:validity,
        checkDiscount:checkDiscount,
        checkVat:checkVat
    };

    return generalData;
    console.log(generalData);
}


function collectData() {
    var tableRows = document.querySelectorAll('#table-body > tr[data-row-index]');
    var data = [];

    // Event listener for description input
    $(document).ready(function () {
        $('textarea[id^="description"]').on('input', function () {
            var rowIndex = $(this).closest('tr').data('row-index');
            var inputValue = $(this).val();
            console.log('Description Input for Row ' + rowIndex + ': ', inputValue);
        });
    });

    tableRows.forEach(function (row) {
        var itemCodeInput = row.querySelector('[name="itemCode[]"]');
        var quantityInput = row.querySelector('[name="quantity[]"]');
        var vatInput = row.querySelector('[name="vat[]"]');
        var priceInput = row.querySelector('[name="price[]"]');
        var totalInput = row.querySelector('[name="total[]"]');
        var checkingInput = row.querySelector('[name="checking[]"]');
        var rowIndex = row.dataset.rowIndex;

        // Check if the row contains valid data
        if (itemCodeInput && quantityInput && vatInput && priceInput && totalInput) {
            var descriptionRow = row.nextElementSibling;
            var descriptionElement = descriptionRow ? descriptionRow.querySelector('textarea[id^="description"]') : null;

            var rowData = {
                item_code: itemCodeInput.value,
                description: descriptionElement ? descriptionElement.value : '', // Use descriptionElement.value
                quantity: quantityInput.value,
                vat: vatInput.value,
                price: priceInput.value,
                total: totalInput.value,
                checking: checkingInput.checked ? 1 : 0
            };

            data.push(rowData);
        }
    });

    console.log('data', data);
    return data;
}





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
document.getElementById('submitLongBtn').addEventListener('click', function (event) {
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

document.getElementById('submitBtn').addEventListener('click', function (event) {
    event.preventDefault(); // Prevent the default form submission
    var data = collectData();
    var generalData = collectGeneralData();
    var vatType = getSelectedVATType(); // Assuming a function to get the selected VAT type
    var discount =  getSelectedDiscountType() 


    // Merge general data with table data
    var dataToSend = {
        itemsArray: data,
        generalData: generalData
    };

    var targetPHP;

if (vatType === '1' && discount === '1') {
    targetPHP = 'qt_view.php';
} else if (vatType === '1' && discount === '0') {
    targetPHP = 'qt_view.php';
} else if (vatType === '0' && discount === '1') {
    targetPHP = 'qt_view.php';
} else if (vatType === '0' && discount === '0') {
    targetPHP = 'qt_view.php';
}
else{
    targetPHP = 'qt_view.php';
}

    // Use the correct variable 'dataToSend' instead of 'tableData'
    sendDataToPHP(dataToSend.itemsArray, dataToSend.generalData, targetPHP);
});

function sendDataToPHP(itemsArray, generalData, destination) {
    var jsonData = JSON.stringify({
        itemsArray: itemsArray,
        generalData: generalData
    });
    console.log('jsonData',jsonData)

    $.ajax({
        type: 'POST',
        url: '../../functions/insertQtData.php',
        data: jsonData,
        success: function (result) {
            console.log('Data sent successfully:', result);

            // Check if the result contains the deal_id
            if (result) {
                // Extract the deal_id from the result
                var dealId = result.trim().replace(/^\[|\]$/g, '');

                // Show a success message to the user
                alert('Data sent successfully! Redirecting to ' + destination);

                // Redirect to the appropriate page with the deal_id after a short delay (e.g., 2 seconds)
                setTimeout(function () {
                    window.location.href = '../dashboards/' + destination + '?code=' + dealId;
                }, 10);
            } else {
                // Handle the case when deal_id is not available in the result
                console.log('result', result.trim())
                alert('Error: deal_id not found in the result.');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error sending data. Status:', status, 'Response:', xhr.responseText);
        }
    });

    console.log('Sending data:', jsonData);
}






    
  // Get all select elements with class "choices-select"
  priceInputs = document.querySelectorAll('input[name="price[]"]');
  vatInputs = document.querySelectorAll('input[name="vat[]"]');
  selectCustType = document.querySelectorAll('.choices-cusType');
  selectElements = document.querySelectorAll('.choices-select');
  quantityInputs = document.querySelectorAll('input[name="quantity[]"]');
  totalInputs = document.querySelectorAll('input[name="total[]"]');
  sumInput = document.getElementById('sum');

  priceInputs.forEach((priceInput, index) => {
    priceInput.addEventListener('input', function () {
      updateTotal(index);
    });
  });
  // Add event listener to each select element for item code
  selectElements.forEach((select, index) => {
    select.addEventListener('change', function () {
      const selectedItemCode = this.value;
      const selectedCustType = selectCustType[index].value;
      console.log('Selected Item Code:', selectedItemCode);
      console.log('Selected Cust Type:', selectedCustType);

      // Call the function to fetch and log the price
      fetchPrice(selectedItemCode, selectedCustType, index);
      updateTotal(index);
    });
  });

  // Add event listener to each select element for customer type
  selectCustType.forEach((select, index) => {
    select.addEventListener('change', function () {
      const selectedItemCode = selectElements[index].value;
      const selectedCustType = this.value;
      console.log('Selected Item Code:', selectedItemCode);
      console.log('Selected Cust Type:', selectedCustType);

      // Call the function to fetch and log the price
      fetchPrice(selectedItemCode, selectedCustType, index);
      updateTotal(index);
    });
  });
  quantityInputs.forEach(function (quantityInput, index) {
  quantityInput.addEventListener('input', function () {
    const quantityValue = quantityInput.value;
    console.log(`Input for select #${index}: Quantity - ${quantityValue}`);

    // Call the updateTotal function when the quantity changes
    updateTotal(index);

    // Call updateSum to recalculate and update the sum
    updateSum();
    
  });
});

priceInputs.forEach(function (priceInput, index) {
  priceInput.addEventListener('input', function () {
   var priceValue= priceInput.value;
    // Call the updateTotal function when the price changes
    console.log("priceValue",priceValue);
    updateTotal(index);

    // Call updateSum to recalculate and update the sum
    updateSum();
    
  });
});

vatInputs.forEach(function (vatInputs, index) {
    vatInputs.addEventListener('input', function () {
    // Call the updateTotal function when the price changes
    updateTotal(index);
    updateSum();
   
  });
});


  });

function removeRow(index) {
  // Get the rows with the specified index and remove them
  const rowsToRemove = document.querySelectorAll(`[data-row-index="${index}"]`);
  rowsToRemove.forEach(row => row.remove());
  updateSum();

  
}

function updateTotal(index) {
  const price = parseFloat(priceInputs[index].value) || 0;
  const quantity = parseInt(quantityInputs[index].value, 10) || 0;
  const vat = parseFloat(vatInputs[index].value) || 0;
console.log("price look like :" ,price)
vatPercentage = vat/100;
vatPrice = vatPercentage * price;
finalPrice= vatPrice+price;

  const total = (finalPrice * quantity).toFixed(2); // Adjust to the desired decimal places
  


  // Update the value of the total input field
  totalInputs[index].value = total;

  // Log the updated total to the console
  console.log(`Updated Total for select #${index}: ${total}`);
  updateSum();
  

}

    function updateSum(discount) {
    const allTotalInputs = document.querySelectorAll('input[name="total[]"]');
    const sum = Array.from(allTotalInputs).reduce((acc, input) => {
      const value = parseFloat(input.value) || 0;
      return acc + value;
    }, 0);

    const totalRowGp = document.querySelectorAll('input[name="rowGp[]"]');
    const rowGp = Array.from(totalRowGp).reduce((acc, input) => {
      const value = parseFloat(input.value) || 0;
      return acc + value;
    }, 0);

    // Calculate the updated sum after decreasing the discount
    const updatedSum = (sum).toFixed(2);
    const updatedGp = ((rowGp).toFixed(2) - discount )|| (rowGp).toFixed(2);


    // Display the updated sum in the desired location (replace 'sum' with the actual ID or selector)
    document.getElementById('sum').value = updatedSum;
    document.getElementById('gp').value = updatedGp;


    console.log(`Updated Sum: ${updatedSum}`);
    console.log(`updatedGp : ${updatedGp}`);
    console.log(`updatedGp : ${updatedGp}`);

  }  

  // Event listener for the discount input
  document.getElementById('discount').addEventListener('input', function () {
    // Catch the input in real-time and store it in the calDisc variable
    const calDisc = parseFloat(this.value) || 0;
console.log("Discount",calDisc);
    // Call the updateSum function with the decreased discount value
    updateSum(calDisc);
  });

  function fetchDescription(itemCode, index) {
    
  console.log(`Fetching description for: ${itemCode}, index: ${index}`);

  // Replace 'your_php_description_script_url' with the actual URL of your PHP script
  const phpDescriptionScriptUrl = '../../functions/fetch_description.php';
  const encodedItemCode = encodeURIComponent(itemCode);
  const descriptionTextarea = document.getElementById(`description${index}`);

  // Log the descriptionTextarea and index values
  console.log('descriptionTextarea:', descriptionTextarea);
  console.log('index:', index);

  // Make a fetch request to the PHP script with the selected item code
  fetch(`${phpDescriptionScriptUrl}?code=${encodedItemCode}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.statusText}`);
      }
      return response.text(); // Parse the response as plain text
    })
    .then(responseText => {
      // Log the fetched description to the console
      console.log(`Fetched Description for select #${index}: ${responseText}`);

      // Update the textarea value with the fetched description
      if (descriptionTextarea) {
        descriptionTextarea.value = responseText.trim()+' Years';
      } else {
        console.error(`Textarea with ID 'description${index}' not found.`);
      }
    })
    .catch(error => {
      console.error('Error fetching description:', error);
    });
}


function fetchPrice(itemCode, custType, index) {
    console.log('Fetching price for:', itemCode, custType);

    if (!itemCode || !custType) {
        console.error('Invalid item code or customer type.');
        return;
    }

    const phpPriceScriptUrl = '../../functions/fetch_price.php';
    const encodedItemCode = encodeURIComponent(itemCode);
    const priceInput = document.querySelectorAll('input[name="price[]"]')[index];
    const costInput = document.querySelectorAll('input[name="cost[]"]')[index];
    let brandData;
    let dollarRate;

    fetch('https://api.exchangerate-api.com/v4/latest/USD')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            dollarRate = data.rates['LKR'];
            console.log('Fetched Dollar Rate:', dollarRate);
            return fetch(`../../functions/productBrand.php?code=${encodedItemCode}`);
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            brandData = data;
            console.log('Fetched Brand:', brandData.brand);
            return fetch(`${phpPriceScriptUrl}?code=${encodedItemCode}&category=${custType}`);
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.text(); // Parse the response as text
        })
        .then(textData => {
            try {
                const jsonData = JSON.parse(textData); // Try parsing the response as JSON
                const fetchedPrice = jsonData.price;
                const fetchedCost = jsonData.cost;
                if (brandData.brand === "Acronis") {
                    priceInput.value = fetchedPrice * dollarRate;
                    costInput.value = fetchedCost * dollarRate;
                    console.log('Brand is Acronis, Price and Cost multiplied by Dollar Rate');
                } else {
                    priceInput.value = fetchedPrice;
                    costInput.value = fetchedCost;
                    console.log('Brand is not Acronis, Displaying fetched Price and Cost');
                }
                console.log(`Fetched Price for select #${index}: ${fetchedPrice}`);
                console.log(`Fetched Cost for select #${index}: ${fetchedCost}`);
            } catch (error) {
                console.error('Error parsing JSON response:', error);
            }
        })
        .catch(error => {
            console.error('Error fetching price:', error);
        });
}

    function fetchItemCodes() {
        // Replace 'your_php_script_url' with the actual URL of your PHP script
        const phpScriptUrl = '../../functions/fetch_item_codes.php';

        // Make a fetch request to the PHP script
        return fetch(phpScriptUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.statusText}`);
                }
                return response.json(); // Parse the JSON data
            })
            .catch(error => {
                console.error('Error fetching item codes:', error);
                throw error;
            });
    }

    // Call the function to fetch item codes and initialize Choices.js
    fetchItemCodes().then(itemCodes => {
        // Log the item codes to the console
        console.log('Item Codes:', itemCodes);

        // Initialize Choices.js for all dropdowns with the class 'choices-select'
        var selector = document.querySelectorAll('.choices-cusType');
        selector.forEach(function (select) {
            new Choices(select, {
                searchEnabled: false,
                itemSelectText: 'Click to select',
              
            });
        });
        var selects = document.querySelectorAll('.choices-select');
        selects.forEach(function (select) {
            new Choices(select, {
                searchEnabled: true,
                itemSelectText: 'Click to select',
                choices: itemCodes.map(itemCode => ({
                    value: itemCode,
                    label: itemCode,
                })),
            });
        });
        
    }
    
    );




    
let rowIndex = 3; // Set the initial row index
let sum = 0;

function addRow() {
  // Get the table body
  const tableBody = document.getElementById('table-body');

  // Generate HTML for the new rows
  const newRowHTML = `
    <tr data-row-index="${rowIndex}">
        <td>
            <select name="itemCode[]" id="itemCode${rowIndex}" class="choices-select">
            </select>
        </td>
        <td>
            <select name="cus-type[]" id="cus_type${rowIndex}" class="choices-cusType">
                <option selected >Selec</option>
                <option>R</option>
                <option>LR</option>
                <option>RT</option>
            </select>
        </td>
        <td><input name="price[]" type="text" value=""><input name="cost[]" type="text" value="" hidden ></td>
        <td style="padding-top: 5px;"><input class="text-center w-50 " style="margin: 2px;" name="quantity[]" type="number" min="1" value=""></td>
        <td><input name="vat[]" type="text" value=""></td>
        <td><input name="total[]" type="text" value=""><input name="rowGp[]" id="rowGp" type="text"  value = "" hidden ></td>
        <td><input name="checking[]" type="checkbox"></td>
        
    </tr>
    <tr data-row-index="${rowIndex}">
        <td colspan="7">
            <textarea name="description[]" id ="description${rowIndex}"></textarea><button onclick="removeRow(${rowIndex})" class="btn" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>
            </button>
        </td>
    </tr>
  `;

  // Append the new rows to the table
  tableBody.insertAdjacentHTML('beforeend', newRowHTML);

  // Increment the row index for uniqueness
  rowIndex++;

  // Fetch item codes and initialize Choices.js for the new dropdowns
  fetchItemCodes().then(itemCodes => {
  const selectsItemCode = document.querySelectorAll('.choices-select');
  const selectsCusType = document.querySelectorAll('.choices-cusType');

  selectsItemCode.forEach(function (select, index) {
    const choicesInstance = new Choices(select, {
      searchEnabled: true,
      itemSelectText: 'Click to select',
      choices: itemCodes.map(itemCode => ({
        value: itemCode,
        label: itemCode,
      })),
    });

    // Add event listener to log selected item code and fetch price
    select.addEventListener('change', function () {
      const selectedItemObject = choicesInstance.getValue();
      const selectedItemCode = selectedItemObject ? selectedItemObject.value : null;

      // Get the selected customer type directly without using Choices.js
      const selectedCusTypeObject = selectsCusType[index].value;
      const selectedCusType = selectedCusTypeObject ? selectedCusTypeObject : null;

      console.log('Selected Item Code:', selectedItemCode);
      console.log('Selected Cus Type:', selectedCusType);

      // Call the function to fetch and display the price
      fetchPrice(selectedItemCode, selectedCusType, index);
      fetchDescription(selectedItemCode, index);

    });
  });

  selectsCusType.forEach(function (select, index) {
    const choicesInstance = new Choices(select, {
      searchEnabled: false,
      itemSelectText: 'Click to select',
    });

    // Add event listener to log selected cus Type
    select.addEventListener('change', function (event) {
      // Get the selected customer type directly from the event object
      const selectedCusType = event.target.value;
      console.log('Selected Cus Type:', selectedCusType);

      // Call the function to fetch and display the price
      const selectedItemObject = selectsItemCode[index].value;
      const selectedItemCode = selectedItemObject ? selectedItemObject : null;
      fetchPrice(selectedItemCode, selectedCusType, index);
    });
  });

  function fetchDescription(itemCode, index) {
    index += 1;
  console.log(`Fetching description for: ${itemCode}, index: ${index}`);

  // Replace 'your_php_description_script_url' with the actual URL of your PHP script
  const phpDescriptionScriptUrl = '../../functions/fetch_description.php';
  const encodedItemCode = encodeURIComponent(itemCode);
  const descriptionTextarea = document.getElementById(`description${index}`);

  // Log the descriptionTextarea and index values
  console.log('descriptionTextarea:', descriptionTextarea);
  console.log('index:', index);

  // Make a fetch request to the PHP script with the selected item code
  fetch(`${phpDescriptionScriptUrl}?code=${encodedItemCode}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.statusText}`);
      }
      return response.text(); // Parse the response as plain text
    })
    .then(responseText => {
      // Log the fetched description to the console
      console.log(`Fetched Description for select #${index}: ${responseText}`);

      // Update the textarea value with the fetched description
      if (descriptionTextarea) {
        descriptionTextarea.value = responseText.trim();
      } else {
        console.error(`Textarea with ID 'description${index}' not found.`);
      }
    })
    .catch(error => {
      console.error('Error fetching description:', error);
    });
}


function fetchPrice(itemCode, custType, index) {
    console.log('Fetching price for:', itemCode, custType);

    if (!itemCode || !custType) {
        console.error('Invalid item code or customer type.');
        return;
    }

    // Replace 'your_php_price_script_url' with the actual URL of your PHP script
    const phpPriceScriptUrl = '../../functions/fetch_price.php';
    const encodedItemCode = encodeURIComponent(itemCode);
    const priceInput = document.querySelectorAll('input[name="price[]"]')[index];
    const costInput = document.querySelectorAll('input[name="cost[]"]')[index];
    let brandData; // Define brandData variable outside the fetch block
    let dollarRate; // Define dollarRate variable in the outer scope

    // Fetch the dollar rate
    fetch('https://api.exchangerate-api.com/v4/latest/USD')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            dollarRate = data.rates['LKR']; // Access the rate for Sri Lankan Rupees

            // Log to check the fetched dollar rate
            console.log('Fetched Dollar Rate:', dollarRate);

            // Fetch the brand
            return fetch(`../../functions/productBrand.php?code=${encodedItemCode}`);
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            brandData = data; // Store the fetched brand data in brandData variable

            // Log to check the fetched brand
            console.log('Fetched Brand:', brandData.brand);

            // Make a fetch request to the PHP script with the selected item code and customer type
            return fetch(`${phpPriceScriptUrl}?code=${encodedItemCode}&category=${custType}`);
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.json(); // Parse the response as JSON
        })
        .then(jsonData => {
            // Assuming the JSON response is an object with "price" and "cost" properties
            const fetchedPrice = jsonData.price;
            const fetchedCost = jsonData.cost;

            // If the brand is "Acronis", update the priceInput and costInput values with the fetched values multiplied by the dollar rate
            if (brandData.brand === "Acronis") {
                priceInput.value = fetchedPrice * dollarRate;
                costInput.value = fetchedCost * dollarRate;
                console.log('Brand is Acronis, Price and Cost multiplied by Dollar Rate');
            } else {
                // If the brand is not "Acronis", display the fetched price and cost without applying the dollar rate
                priceInput.value = fetchedPrice;
                costInput.value = fetchedCost;
                console.log('Brand is not Acronis, Displaying fetched Price and Cost');
            }

            // Log the fetched price and cost to the console separately
            console.log(`Fetched Price for select #${index}: ${fetchedPrice}`);
            console.log(`Fetched Cost for select #${index}: ${fetchedCost}`);
        })
        .catch(error => {
            console.error('Error fetching price:', error);
        });
}

});


const quantityInputs = document.querySelectorAll('input[name="quantity[]"]');
const priceInputs = document.querySelectorAll('input[name="price[]"]');
const costInputs = document.querySelectorAll('input[name="cost[]"]');
const totalInputs = document.querySelectorAll('input[name="total[]"]');
const totalRowGp = document.querySelectorAll('input[name="rowGp[]"]');
const vatInputs = document.querySelectorAll('input[name="vat[]"]');


quantityInputs.forEach(function (quantityInput, index) {
  quantityInput.addEventListener('input', function () {
    const quantityValue = quantityInput.value;
    console.log(`Input for select #${index}: Quantity - ${quantityValue}`);

    // Call the updateTotal function when the quantity changes
    updateTotal(index);
  });
});

priceInputs.forEach(function (priceInput, index) {
  priceInput.addEventListener('input', function () {
    // Call the updateTotal function when the price changes
    updateTotal(index);
  });
});

costInputs.forEach(function (costInput, index) {
    costInput.addEventListener('input', function () {
    // Call the updateTotal function when the price changes
    updateTotal(index);
  });
});

vatInputs.forEach(function (vatInputs, index) {
    vatInputs.addEventListener('input', function () {
    // Call the updateTotal function when the price changes
    updateTotal(index);
  });
});

function updateTotal(index) {
  const cost = parseFloat(costInputs[index].value) || 0;
  const price = parseFloat(priceInputs[index].value) || 0;
  const quantity = parseInt(quantityInputs[index].value, 10) || 0;
  const vat = parseFloat(vatInputs[index].value) || 0;

vatPercentage = vat/100;
vatPrice = price*vatPercentage;
finalPrice = price+vatPrice;
rowGp =(price-cost)*quantity;


  const total = (finalPrice * quantity).toFixed(2); // Adjust to the desired decimal places

  // Update the value of the total input field
  totalRowGp[index].value = rowGp;
  totalInputs[index].value = total;


  // Log the updated total to the console
  console.log(`Updated Total for select #${index}: ${rowGp}`);
  updateSum();
}


// Call updateTotal initially for all rows
for (let i = 0; i < totalInputs.length; i++) {
  updateTotal(i);
}
updateSum();

}







</script>

<?php
$conn->close();
?>


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
                                                    <div class="col-3 text-end">
                                                        AS-Qt-<input value="<?php echo $id?>" disabled >
                                                    </div>
                                                </div>
                                                <div class="multisteps-form__content mt-3">
                                                <div class="row">
                                                    <div class="col-4">
                                                        
                                                        <div class="input-group input-group-dynamic">
                                                        <label class="form-label">Validity Period</label>
                                                            <input class="multisteps-form__textarea form-control"
                                                                rows="5"
                                                                placeholder="Validity Period"
                                                                name="validity" id="validity" value="<?php echo $validity?>" ></input>
                                                        </div>
                                                    </div>

                                                </div>
                                                
                                                    <div class="row">
                                                        <div class="col-12 mt-4">
                                                            <div class="input-group input-group-dynamic">
                                                            <label class="form-label">Comment</label>
                                                                <input class="multisteps-form__textarea form-control"
                                                                    rows="5"
                                                                    placeholder="Comment"
                                                                    name="comment"  value = "<?php echo $comment?>"></input>
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
                                                                type="submit" title="Send" name="print_qt" id="submitBtn" >Print
                                                                Qt</button>
                                                        </div>


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
        </div>
        
        <script src="../../../assets/js/plugins/choices.min.js"></script>

                                            

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
            searchEnabled: true
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
    if (document.getElementById('choices-cusEmployee')) {
        var element = document.getElementById('choices-cusEmployee');
        const example = new Choices(element, {
            searchEnabled: true
        });
    };
    if (document.getElementById('choices-item_code')) {
        var element = document.getElementById('choices-item_code');
        const example = new Choices(element, {
            searchEnabled: true
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
            searchEnabled: true
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