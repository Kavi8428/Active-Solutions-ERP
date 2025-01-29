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

<?php


// SQL query to fetch data from the 'active' table
$sql = "SELECT * FROM active";
$result = $conn->query($sql);

// Check if there are any rows in the result
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $name = $row["name"];
        $activeVat = $row["vatNo"];
        $address =  $row["address"];
        $tel = $row["tel"];
        $owner =  $row["owner"];
        $brNo =  $row["brNo"];
    }
} else {
    echo "0 results";
}

// Close the connection
$conn->close();
?>







<?php
// Database connection settings
include '../../connection.php';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['code'])) {
    $id = $_GET['code'];

    // SQL query to retrieve data from the database
    $sqlm = "SELECT * FROM quotation WHERE deal_id = $id";

    $result = $conn->query($sqlm);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $qt_num = $row["deal_id"];
            $partner_name = $row["partner_name"];
            $qt_date = $row["deal_date"];
            $partner_employee = $row["partner_employee"];
            $project_month = $row["project_month"];
            $pmanager = $row["pmanager"];
            $discount = $row["discount"];
            $finalSum = $row["sum"];
            $validity = $row["validity"];
            $comment =  $row["comment"];
            $permition = $row["status"];
            $approve;
            $print;

            //echo $_SESSION["user"] . $pmanager;

            if ($_SESSION["user"] === $pmanager) {

                $approve = 'enabled';
            } else {
                $approve = 'enabled';
            }

            if ($permition === 'approved') {

                $print = 'enabled';
            } else {
                $print = 'enabled';
            }


            $sqlpm = "SELECT email FROM customer_employee WHERE cus_em_name = '$partner_employee'";

            $resultpm = $conn->query($sqlpm);

            if ($resultpm->num_rows > 0) {

                while ($rowpm = $resultpm->fetch_assoc()) {

                    $emailpm = $rowpm["email"];
                }
            }
        }
    }
}
$sql1 = "SELECT * FROM customer WHERE company_name = ?";
$stmt = $conn->prepare($sql1);
$stmt->bind_param("s", $partner_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch and display user information
    while ($row = $result->fetch_assoc()) {
        $cusAddress = $row["address"];
        $vat = $row["vat"];
    }
} else {
    echo "No user information found for user name: $user_name";
}

$user_name = $pmanager;
$sql1 = "SELECT * FROM system_user WHERE user_name = ?";
$stmt = $conn->prepare($sql1);
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch and display user information
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $user_name = $row["user_name"];
        $full_name = $row["full_name"];
        $user_level = $row["user_level"];
        $position = $row["position"];
        $address = $row["address"];
        $category = $row["category"];
        $create_date = $row["create_date"];
        $email = $row["email"];
        $notes = $row["notes"];
        $tel = $row["tel"];
    }
} else {
    echo "No user information found for user name: $user_name";
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
        QT DETAILS
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

    <style type="style/css">
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

        
    </style>

</head>

<body class="g-sidenav-show  bg-gray-200">
    <input type="text" name="p_employee" id="p_employee" value="<?php echo $emailpm ?>" hidden>

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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page"> <a href="../../pages/dashboards/quotation.php">Quotation</a> </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Qt Page</h6>
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
                            <a href="../../../pages/authentication/signin/illustration.html"
                                class="nav-link text-body p-0 position-relative" target="_blank">
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
        <div style="font-size: 5px ;" class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Quotation Details</h5>
                            <form method="POST" style="color:black;">
                                <div id="pdfCard">
                                    <div class="row" style="margin:10px; font-size: small; ">
                                        <div id="letterhead" class="row">
                                            <div class="col-6">
                                                <img class="w-40 border-radius-lg shadow-lg"
                                                    src="../../assets/img/activeLogo.png" alt="chair">
                                                <div class="my-gallery d-flex" itemscope
                                                    itemtype="http://schema.org/ImageGallery">
                                                </div>
                                            </div>
                                            <span style="text-align: right; font-size: small; color:black; " class="col-6 ">
                                                32/2-2/1 Nandimithra Place, Colombo 06,Sri Lanka.<br>
                                                Phone: 011 741 5200 / <input style="border:none ; width: 72px; " value="<?php echo $tel ?>"> <br>
                                                E-mail: sales@activelk.com <br>
                                                VAT No : <?php echo $activeVat ?>
                                            </span>

                                        </div>
                                        <div class="row">
                                            <div class="col-10">
                                                <input style=" font-size: 13px; border: none;" class=" w-100" type="text"
                                                    value=" <?php echo $partner_name ?>">
                                            </div>
                                            <div style="align-content: right; ; " class="col-2 mt-2 ">
                                                <input style="border: none; color:black; " class="input text-center" type="text"
                                                    value="<?php echo $qt_date; ?>       ">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-10">
                                                <p style="font-size: 13px; color:black; font-weight: 400; line-height: 1.7; " class=" w-100 m-1" type="text">
                                                    <?php echo $cusAddress ?><br>
                                                    VAT No : <?php echo $vat ?>
                                                </p>
                                            </div>
                                            <div style="align-content: right;" class="col-2 ">
                                                <input style="border: none; color:black; " class="input text-center " type="text"
                                                    value="<?php echo 'AS-QT-' . $qt_num; ?>     ">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12 ">
                                                <label style="color: black;"> Dear Sir/Madam,</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <input style="border: none; text-align : Center ; font-weight: bold;; "
                                                    class=" w-100 text-decoration-underline" type="text"
                                                    value="QUOTATION FOR NAS CONFIGURATION ">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <label style="color: black;"> Further to your inquiry on the above, we are pleased to forward
                                                    our quotation for the requested products. </label>
                                            </div>
                                        </div>

                                        <div style="background: transparent ;  border-radius : 1% ;" class="row mt-1">
                                            <section id="mainTable">
                                                <div class="table-container col-12">
                                                    <table id="mainTable" class=" col-12">
                                                        <thead>
                                                            <tr style="background-color: rgb(33,37,41); color: white; " class="row p-1 ">
                                                                <th class="col-2 text-start ">MODEL</th>
                                                                <th class="col-4 text-start">DESCRIPTION</th>
                                                                <th class="col-2 text-center">RATE</th>
                                                                <th class="col-1 text-center">QTY</th>
                                                                <th class="col-2 text-center">AMOUNT</th>
                                                                <th class="col-1 text-center">VAT</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <!-- Add more rows as needed -->
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="row" id="subtotalArea">
                                                                <td class="col-6 pt-2">Comments or Notes</td>
                                                                <td class="col-2 pt-2 text-start border-start border-end border-bottom table-footer">SUBTOTAL</td>
                                                                <td class="col-1 pt-2 text-start border-end border-bottom"></td>
                                                                <td id="subTotal" class="col-2 pt-2 text-end border-end border-bottom table-footer"></td>
                                                                <td class="col-1 text-start ps-2 pt-2"></td>
                                                            </tr>
                                                            <tr class="row" id="discountArea">
                                                                <td class="col-6 pt-2"></td>
                                                                <td class="col-2 pt-2 text-start border-start border-end border-bottom">DISCOUNT</td>
                                                                <td class="col-1 pt-2 text-start border-end border-bottom"></td>
                                                                <td class="col-2 pt-2 text-end border-end border-bottom"></td>
                                                                <td class="col-1 text-start ps-2 pt-2"></td>
                                                            </tr>
                                                            <tr class="row" id="vatArea">
                                                                <td class="col-6 pt-2"></td>
                                                                <td class="col-2 pt-2 text-start border-start border-end border-bottom">VAT</td>
                                                                <td class="col-1 pt-2 text-start border-end border-bottom"></td>
                                                                <td id="vat" class="col-2 pt-2 text-end border-end border-bottom"></td>
                                                                <td class="col-1 text-start ps-2 pt-2"></td>
                                                            </tr>
                                                            <tr class="row" id="totalArea">
                                                                <td class="col-6 pt-2"></td>
                                                                <td class="col-2 pt-2 text-start border-start border-end border-bottom table-footer">TOTAL</td>
                                                                <td class="col-1 pt-2 text-start border-end border-bottom"></td>
                                                                <td id="grandTotal" class="col-2 pt-2 text-end border-end border-bottom table-footer"></td>
                                                                <td class="col-1 text-start ps-2 pt-2"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </section>
                                        </div>
                                        <div
                                            <?php if ($result->num_rows >= 4) {
                                                echo 'style="page-break-before: always;"';
                                            } ?> class="page-break">
                                            <div class="grouped-content">
                                                <div class="row mt-3 ">
                                                    <b><u style="color: black;">Terms & Conditions:</u></b> <br>
                                                    <p style="font-size: 13px; color:black; font-weight: 400; line-height: 1.7; ">
                                                        &diams; Payment : - All payments should be made by cheque drawn in favoure of “Active Solutions”.</br>
                                                        &diams; Validity : - <?php echo $validity; ?> Days from the date of quotation. Prices are based on present exchange Dollar Rate. Prices are subjected to change on Dollar fluctuation.<br>
                                                        &diams; Warranty : - As specified in the quote. Active Solutions will provide Support after expiry of a warranty at a minimal warranty and charge.<br>
                                                        &diams; Delivery : - Within 3 weeks from date of order. Unless specifically mentioned.</br>
                                                        We trust our offer will be acceptable to you. If you require any further clarification or information, please do not hesitate to contact me on <?php echo $tel ?> or e-mail: <?php echo $email ?>.<br><br>
                                                        Thank you.<br>
                                                        <?php echo $full_name ?></br>
                                                        <?php echo $position ?></br>
                                                        <?php echo $tel . ' / ' . $email ?></br>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3"></div>
                                    <div class="col-3">
                                        <button class="btn bg-dark " id="buttonPDF" type="button" name="generate_pdf"><i
                                                class="fa fa-download" aria-hidden="true"></i> &nbsp; <label>PDF</label></button>

                                        <?php
                                        echo '<a href="../../pages/dashboards/qt_edit.php?code=' . $id . '" class="btn btn-dark" >
                                     <i class="material-icons text-secondary position-relative text-lg">drive_file_rename_outline</i> <label>EDIT</label>
                                     </a>';

                                        ?>

                                    </div>
                                    <div class="col-3"> <button class="btn bg-dark" type="submit" onclick="sendEmail()" name="send_email" <?php echo $print ?>><i class="fa fa-envelope" aria-hidden="true"></i> &nbsp; <label>Email</label></button></div>
                                    <div class="col-3"> <button class="btn bg-dark" onclick="approve()" type="button" <?php echo $approve ?> hidden><i class="fa fa-android " aria-hidden="true"></i> &nbsp; <label>Approve</label></button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <script src="../../assets/js/html2pdf.bundle.min.js"></script>
                    <script>
                        const urlParams = new URLSearchParams(window.location.search);
                        const dealId = urlParams.get('code');
                        let fetchQuotes;

                        // Self-invoked async function to handle the data fetch and population
                        (async () => {
                            try {
                                // Fetch both quotations and quote products in parallel
                                const [quoteProductsResponse, quotationsResponse] = await Promise.all([
                                    fetch('../../functions/fetchQuoteProducts.php'),
                                    fetch('../../functions/fetchQuotations.php')
                                ]);

                                // Check for errors in both fetch responses
                                if (!quoteProductsResponse.ok) {
                                    throw new Error('Error occurred while fetching quote products data');
                                }
                                if (!quotationsResponse.ok) {
                                    throw new Error('Error occurred while fetching quotations data');
                                }

                                // Parse both responses
                                const quoteProductsData = await quoteProductsResponse.json();
                                const quotationsData = await quotationsResponse.json();

                                // Filter data for quote products and quotations based on dealId
                                const filteredDataCheck1 = quoteProductsData.filter(item => item.deal_id === dealId && item.checking == 1);
                                const filteredDataCheck0 = quoteProductsData.filter(item => item.deal_id === dealId && item.checking == 0);

                                // Set the fetchQuotes variable with filtered data
                                fetchQuotes = quotationsData.filter(item => item.deal_id === dealId);

                                // Call populateTable with the filtered data
                                populateTable(filteredDataCheck1, filteredDataCheck0);

                            } catch (error) {
                                console.error('Error occurred during fetch operations:', error);
                            }
                        })();

                        // Function to populate the table
                        function populateTable(filteredData, filteredDataCheck0) {
                            if (!filteredData || filteredData.length === 0) {
                                console.log('No data found for the given deal ID');
                                return;
                            }

                            // Target the table body and footer cells
                            const tableBody = document.querySelector('#mainTable tbody');
                            const subTotalElement = document.getElementById('subTotal');
                            const vatElement = document.getElementById('vat');
                            const grandTotalElement = document.getElementById('grandTotal');

                            // Clear any existing rows
                            tableBody.innerHTML = '';

                            // Initialize the subtotal and total VAT
                            let subTotal = 0;
                            let totalVAT = 0;

                            // Collect chk0Discription values
                            let chk0Discription = '';
                            let chk0rate = 0;
                            filteredDataCheck0.forEach(chk0Item => {
                                chk0Discription += chk0Item.description + '\n';
                                chk0rate += parseFloat(chk0Item.price);
                            });

                            // Use fetchQuotes instead of filteredQuotationData



                            const filteredQuotation1Data = fetchQuotes.filter(item => item.deal_id === dealId && item.discounted == 1);
                            const discount1Value = filteredQuotation1Data.reduce((sum, item) => sum + parseFloat(item.discounted), 0);

                            const filteredQuotation0Data = fetchQuotes.filter(item => item.deal_id === dealId && item.discounted == 0);
                            const discount0Value = filteredQuotation0Data.reduce((sum, item) => sum + parseFloat(item.discounted), 0);

                            // Flag to indicate the first row for each quotation
                            let isFirstRow = true;

                            // Iterate through filteredData and create rows dynamically
                            filteredData.forEach(dataItem => {

                                const hasDiscount = fetchQuotes.some(item => item.discount === 1);
                                const hasVat = fetchQuotes.some(item => item.vated === 1);
                                const rate = isFirstRow ? `${parseFloat(dataItem.price) + chk0rate}` : dataItem.price;



                                if (hasDiscount && hasVat) {

                                    
                                    console.log('Show discount & vat');
                                    document.getElementById('discountArea').hidden = false;
                                    document.getElementById('vatArea').hidden = false;


                                } else if (!hasDiscount && hasVat) {

                                    //console.log('hide the discount and show vat');

                                    document.getElementById('discountArea').hidden = true;
                                    document.getElementById('vatArea').hidden = false;

                                    // Calculate the amount for each item
                                    const amount = rate * dataItem.quantity;
                                    const itemVAT = (amount * (dataItem.vat / 100));

                                    // Accumulate the subtotal
                                    subTotal += amount;
                                    // Calculate VAT for each item and accumulate the total VAT
                                    totalVAT += itemVAT;


                                } else if (!hasDiscount && !hasVat) {


                                    console.log('Hide discount & vat areas then add the values to items');
                                    document.getElementById('discountArea').hidden = true;
                                    document.getElementById('vatArea').hidden = true;

                                    // Calculate the amount for each item
                                    const amount = rate * dataItem.quantity;
                                    const itemVAT = (amount * (dataItem.vat / 100));
                                    // Accumulate the subtotal
                                    subTotal += amount;
                                    // Calculate VAT for each item and accumulate the total VAT
                                    totalVAT += itemVAT;


                                } else if (hasDiscount && !hasVat) {


                                    console.log('show discount area but hide the vat area');
                                    document.getElementById('discountArea').hidden = false;
                                    document.getElementById('vatArea').hidden = true;
                                }



                                // Create a new row element
                                const newRow = document.createElement('tr');
                                newRow.classList.add('row', 'border-start', 'border-end', 'border-bottom');
                                // Prepare the description: include `chk0Discription` only for the first row
                                const description = isFirstRow ? `${dataItem.description}\n${chk0Discription}` : dataItem.description;
                                // Insert cells with the respective values
                                newRow.innerHTML = `
                                    <td class="col-2 pt-1 text-start border-start border-end border-bottom">${dataItem.item_code}</td>
                                    <td class="col-4 pt-1 text-start border-start border-end border-bottom">${description}</td>
                                    <td class="col-2 pt-1 text-end border-start border-end border-bottom">${formatCurrency(rate)}</td>
                                    <td class="col-1 pt-1 text-center border-start border-end border-bottom">${dataItem.quantity}</td>
                                    <td class="col-2 pt-1 text-end border-start border-end border-bottom">${formatCurrency(amount)}</td>
                                    <td class="col-1 pt-1 text-center border-start border-end border-bottom">${dataItem.vat}%</td>
                                `;


                                // Append the newly created row to the table body
                                tableBody.appendChild(newRow);

                                // After adding the first row, set isFirstRow to false
                                isFirstRow = false;
                            });

                            // Calculate grand total
                            const grandTotal = subTotal + totalVAT;

                            // Display the calculated values in the respective table footer cells
                            subTotalElement.textContent = formatCurrency(subTotal);
                            vatElement.textContent = formatCurrency(totalVAT);
                            grandTotalElement.textContent = formatCurrency(grandTotal);
                        }





                        // Format currency without displaying any currency symbol
                        function formatCurrency(value) {
                            return parseFloat(value).toLocaleString('en-US', {
                                style: 'decimal',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }


                        const user = '<?php echo $_SESSION["user"]; ?>';
                        //console.log('user',user);
                        function notification() {
                            fetch('../../functions/fetchQuotations.php')
                                .then(response => response.json())
                                .then(data => {
                                    // Filter data based on the user
                                    const filteredData = data.filter(item => item.pmanager === user && item.status != 'approved');
                                    //  console.log('filteredData', filteredData);
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

                                            window.location.href = `./qt_view.php?code=${clickedDealId}`;
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


                        document.addEventListener("DOMContentLoaded", function() {
                            var rowCount = document.querySelectorAll('.row.border-1.border-dark').length;
                            //console.log("Number of rows in the table: " + rowCount);
                        });
                        document.addEventListener('DOMContentLoaded', function() {


                            function getParameterByName(name, url) {
                                if (!url) url = window.location.href;
                                name = name.replace(/[\[\]]/g, "\\$&");
                                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                                    results = regex.exec(url);
                                console.log('results', results)
                                if (!results) return null;
                                if (!results[2]) return '';
                                return decodeURIComponent(results[2].replace(/\+/g, ' '));
                            }


                            var buttonPDF = document.getElementById('buttonPDF');
                            buttonPDF.addEventListener('click', sendValuesToPHP);

                            function sendValuesToPHP() {
                                // Get the VAT and grand total values
                                var vatValue = extractNumericValue(vatInput.value);
                                var grandTotalValue = extractNumericValue(grandTotalInput.value);

                                // Get the code from the URL parameter
                                var codeFromURL = getParameterByName('code');
                                console.log('codeFromURL', codeFromURL);

                                // Create a data object with the values
                                var data = {
                                    vat: vatValue,
                                    grandTotal: grandTotalValue,
                                    code: codeFromURL
                                };

                                // Perform an AJAX request
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', '../../functions/updateQt.php', true);
                                xhr.setRequestHeader('Content-Type', 'application/json');

                                // Convert the data object to JSON and send it
                                xhr.send(JSON.stringify(data));

                                // Handle the response
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState == 4 && xhr.status == 200) {
                                        console.log('Response from PHP script:', xhr.responseText);
                                        // Handle the response from the PHP script if needed
                                    }
                                };
                            }


                            // Get the total input element

                            // Get the vatPercentage input element
                            var vatPercentageInput = document.getElementById('vatPercentage');

                            // Get the vat input element
                            var vatInput = document.getElementById('vat');

                            // Get the grandTotal input element
                            var grandTotalInput = document.getElementById('grandTotal');

                            // Set initial total value


                            // Add input event listener to update VAT value in real-time

                            function updateVAT() {
                                // Get numeric values
                                var vatPercentageValue = parseFloat(vatPercentageInput.value) || 0;

                                // Calculate VAT value without rounding
                                var vatValue = totalValue * (vatPercentageValue / 100);

                                vatInput.value = vatValue.toLocaleString('en-US', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }) + ' /=';

                                // Update grand total
                                updateGrandTotal();
                            }

                            function updateGrandTotal() {
                                var vatValue = extractNumericValue(vatInput.value);
                                var grandTotalValue = totalValue + vatValue;

                                grandTotalInput.value = grandTotalValue.toLocaleString('en-US', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }) + ' /=';
                            }

                            // Function to extract numeric value from a string
                            function extractNumericValue(str) {
                                return parseFloat(str.replace(/[^\d.]/g, ''));
                            }
                        });

                        function validateInput() {
                            // Get the input element
                            var inputElement = document.getElementById('vatPercentage');

                            // Remove non-numeric characters
                            var numericValue = inputElement.value.replace(/[^0-9]/g, '');

                            // Update the input value
                            inputElement.value = numericValue + '%';
                        }
                    </script>
                    <script>
                        function approve() {
                            var urlParams = new URLSearchParams(window.location.search);
                            var dealId = urlParams.get('code');
                            var approve_value = 'approved';

                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "../../functions/updateApprove.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    // Response from the server
                                    console.log(xhr.responseText);
                                    location.reload();

                                }
                            };
                            var data = "deal_id=" + encodeURIComponent(dealId) + "&approve_value=" + encodeURIComponent(approve_value);
                            xhr.send(data);
                        }

                        function sendEmail() {
                            var emailAddress = document.getElementById('p_employee').value;
                            var subject = "QUOTATION FOR NAS CONFIGURATION ";
                            var body = "";

                            var mailtoLink = "mailto:" + encodeURIComponent(emailAddress) +
                                "?subject=" + encodeURIComponent(subject) +
                                "&body=" + encodeURIComponent(body);

                            // Open the mail client
                            window.location.href = mailtoLink;
                        }
                        document.getElementById('buttonPDF').onclick = function() {
                            var element = document.getElementById('pdfCard');

                            var opt = {
                                margin: 2,
                                filename: 'Active_Quotation.pdf',
                                image: {
                                    type: 'jpeg',
                                    quality: 0.9
                                }, // Change type to 'jpeg' for better compression
                                html2canvas: {
                                    scale: 3
                                }, // You may adjust the scale as needed
                                jsPDF: {
                                    unit: 'mm',
                                    format: 'a4',
                                    orientation: 'portrait',
                                    fontSize: 1,
                                    lineHeight: 1,
                                    pageBreak: {
                                        auto: true,
                                        before: '.page-break',
                                        after: '.page-break'
                                    },
                                    compression: {
                                        enabled: true, // Enable compression
                                        compress: 'MEDIUM' // Adjust compression level
                                    }
                                }
                            };

                            html2pdf().from(element).set(opt).save();
                        }
                    </script>



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

                            <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">ACTIVE
                                SOLUTIONS</a>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com" class="nav-link text-muted"
                                    target="_blank">Active Solutions</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted"
                                    target="_blank">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/blog" class="nav-link text-muted"
                                    target="_blank">Blog</a>
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
    <script src="../../../assets/js/plugins/photoswipe.min.js"></script>
    <script src="../../../assets/js/plugins/photoswipe-ui-default.min.js"></script>
    <script>
        // Products gallery

        var initPhotoSwipeFromDOM = function(gallerySelector) {

            // parse slide data (url, title, size ...) from DOM elements
            // (children of gallerySelector)
            var parseThumbnailElements = function(el) {
                var thumbElements = el.childNodes,
                    numNodes = thumbElements.length,
                    items = [],
                    figureEl,
                    linkEl,
                    size,
                    item;

                for (var i = 0; i < numNodes; i++) {

                    figureEl = thumbElements[i]; // <figure> element
                    // include only element nodes
                    if (figureEl.nodeType !== 1) {
                        continue;
                    }

                    linkEl = figureEl.children[0]; // <a> element

                    size = linkEl.getAttribute('data-size').split('x');

                    // create slide object
                    item = {
                        src: linkEl.getAttribute('href'),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10)
                    };

                    if (figureEl.children.length > 1) {
                        // <figcaption> content
                        item.title = figureEl.children[1].innerHTML;
                    }

                    if (linkEl.children.length > 0) {
                        // <img> thumbnail element, retrieving thumbnail url
                        item.msrc = linkEl.children[0].getAttribute('src');
                    }

                    item.el = figureEl; // save link to element for getThumbBoundsFn
                    items.push(item);
                }

                return items;
            };

            // find nearest parent element
            var closest = function closest(el, fn) {
                return el && (fn(el) ? el : closest(el.parentNode, fn));
            };

            // triggers when user clicks on thumbnail
            var onThumbnailsClick = function(e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;

                var eTarget = e.target || e.srcElement;

                // find root element of slide
                var clickedListItem = closest(eTarget, function(el) {
                    return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
                });

                if (!clickedListItem) {
                    return;
                }

                // find index of clicked item by looping through all child nodes
                // alternatively, you may define index via data- attribute
                var clickedGallery = clickedListItem.parentNode,
                    childNodes = clickedListItem.parentNode.childNodes,
                    numChildNodes = childNodes.length,
                    nodeIndex = 0,
                    index;

                for (var i = 0; i < numChildNodes; i++) {
                    if (childNodes[i].nodeType !== 1) {
                        continue;
                    }

                    if (childNodes[i] === clickedListItem) {
                        index = nodeIndex;
                        break;
                    }
                    nodeIndex++;
                }



                if (index >= 0) {
                    // open PhotoSwipe if valid index found
                    openPhotoSwipe(index, clickedGallery);
                }
                return false;
            };

            // parse picture index and gallery index from URL (#&pid=1&gid=2)
            var photoswipeParseHash = function() {
                var hash = window.location.hash.substring(1),
                    params = {};

                if (hash.length < 5) {
                    return params;
                }

                var vars = hash.split('&');
                for (var i = 0; i < vars.length; i++) {
                    if (!vars[i]) {
                        continue;
                    }
                    var pair = vars[i].split('=');
                    if (pair.length < 2) {
                        continue;
                    }
                    params[pair[0]] = pair[1];
                }

                if (params.gid) {
                    params.gid = parseInt(params.gid, 10);
                }

                return params;
            };

            var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
                var pswpElement = document.querySelectorAll('.pswp')[0],
                    gallery,
                    options,
                    items;

                items = parseThumbnailElements(galleryElement);

                // define options (if needed)
                options = {

                    // define gallery index (for URL)
                    galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                    getThumbBoundsFn: function(index) {
                        // See Options -> getThumbBoundsFn section of documentation for more info
                        var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                            pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                            rect = thumbnail.getBoundingClientRect();

                        return {
                            x: rect.left,
                            y: rect.top + pageYScroll,
                            w: rect.width
                        };
                    }

                };

                // PhotoSwipe opened from URL
                if (fromURL) {
                    if (options.galleryPIDs) {
                        // parse real index when custom PIDs are used
                        // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                        for (var j = 0; j < items.length; j++) {
                            if (items[j].pid == index) {
                                options.index = j;
                                break;
                            }
                        }
                    } else {
                        // in URL indexes start from 1
                        options.index = parseInt(index, 10) - 1;
                    }
                } else {
                    options.index = parseInt(index, 10);
                }

                // exit if index not found
                if (isNaN(options.index)) {
                    return;
                }

                if (disableAnimation) {
                    options.showAnimationDuration = 0;
                }

                // Pass data to PhotoSwipe and initialize it
                gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.init();
            };

            // loop through all gallery elements and bind events
            var galleryElements = document.querySelectorAll(gallerySelector);

            for (var i = 0, l = galleryElements.length; i < l; i++) {
                galleryElements[i].setAttribute('data-pswp-uid', i + 1);
                galleryElements[i].onclick = onThumbnailsClick;
            }

            // Parse URL and open gallery if it contains #&pid=3&gid=1
            var hashData = photoswipeParseHash();
            if (hashData.pid && hashData.gid) {
                openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
            }
        };

        // execute above function
        initPhotoSwipeFromDOM('.my-gallery');
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