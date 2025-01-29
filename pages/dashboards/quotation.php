<?php include '../../connection.php';?>



<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
  // Redirect to the login page
  header("Location: ../../../index.php");
  exit();
}

// Database connection settings

include '../../connection.php';

$backupFolderPath = '../../assets/backup';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function logEvent($user, $task, $other, $severity) {
    global $conn;

    // Prepare and bind the SQL statement
    $logSql = "INSERT INTO log (user, task, date_time, other, serverity) VALUES (?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($logSql);
    $stmt->bind_param('sssi', $user, $task, $other, $severity);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();}

// Check if the "delete" parameter is present in the URL and if "id" is set
if (isset($_GET['delete']) && isset($_GET['code'])) {
    $id = $_GET['code'];

    // Sanitize the ID to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // SQL query to select the data to be backed up
    $backupSql = "SELECT * FROM quotation WHERE deal_id = '$id'";
    $backupResult = $conn->query($backupSql);

    if ($backupResult->num_rows > 0) {
        $backupData = $backupResult->fetch_assoc();
        logEvent($_SESSION['user'], 'Delete Quotation', 'Qt Number: ' . $id, 1);


        // Create a backup file in the "backup" folder with .doc extension
        $backupFileName = $backupFolderPath . '/' . $id . ".doc";

        // Check if the folder is writable
        if (is_writable($backupFolderPath)) {
            $backupFile = fopen($backupFileName, "w");

            if ($backupFile) {
                // Write data to the backup file
                foreach ($backupData as $key => $value) {
                    fwrite($backupFile, "$key: $value\r\n");
                }

                fclose($backupFile);

                // SQL query to delete data from the database based on the "id" parameter
                $deleteSql = "DELETE FROM quotation WHERE deal_id = '$id'";

                if ($conn->query($deleteSql) === TRUE) {
                    // Deletion successful
                    // Redirect to a success page or display a success message
                    $successMessage = "Data deleted successfully.";

                    // Redirect to a success page or display a success message
                    header('Location:../dashboards/quotation.php?success=' . urlencode($successMessage));
                    exit();
                } else {
                    // Handle the case where deletion failed
                    echo "Error: " . $deleteSql . "<br>" . $conn->error;
                }
            } else {
                echo "<script>alert('Error creating backup file')</script>";
            }
        } else {
            echo "<script>alert('Backup folder is not writable')</script>";

        }
    } else {
        echo "<script>alert('No data found for backup')</script>";
    }
}


// SQL query to retrieve data from the database
$sql = "SELECT * FROM quotation";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
} else {
    
    echo "<script>alert('No data found')</script>";

}


// Close the database connection
$conn->close();
?>




<!-- Display data here as needed -->

<!-- Add a button for deleting data with confirmation -->






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../assets/img/logo (2).png">
    <title>
        QUOTATION
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script type="script" src="../../functions/notification.js"></script>



    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.0.6" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">




<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .context-menu {
            display: none; /* Hide the context menu initially */
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 8px 0;
            z-index: 9999;
        }

        /* Other styles remain unchanged */

        .context-menu-item {
            padding: 8px 16px;
            cursor: pointer;
        }

        .context-menu-item:hover {
            background-color: #f8f9fa;
        }
        .Toastify {
            z-index: 9999 !important;
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
    <main class="main-content  position-relative max-height-vh-100 h-100 border-radius-lg ">
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Quotation</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Qt List</h6>
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
                                    <span class="small" id="count" ></span>
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end  me-sm-n4"
                                aria-labelledby="dropdownMenuButton">
                            <!--    <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex align-items-center py-1">
                                            <span class="material-icons">email</span>
                                            <div class="ms-2">
                                                <h6 class="text-sm font-weight-normal my-auto">
                                                    Need your Approval
                                                </h6>
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <td>Deal Id_0</td>
                                                        <td>Deal Name_0</td>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex align-items-center py-1">
                                            <span class="material-icons">email</span>
                                            <div class="ms-2">
                                                <h6 class="text-sm font-weight-normal my-auto">
                                                    Need your Approval
                                                </h6>
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <td>Deal Id_1</td>
                                                        <td>Deal Name_0</td>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </a>
                                </li>-->
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div style="height: 30px;" class="row">
                <div class="col-10">
                    <h5 class="mb-0">Quotation List</h5>
                </div>
                <div class="col-2">
                    <a href="qt_new.php">
                        <button class="btn btn-dark">New Qt</button>
                    </a>

                </div>

            </div>
        </div>
        <div class="row mt-4" > 
            <div class="col-12" >
                <div class="card" >
                    <!-- Card header -->

                    <div class="table-responsive">
                        <?php
                        // Database connection settings
                        include '../../connection.php';

                        // Check the connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // SQL query to retrieve data from the database
                        $sql = "SELECT * FROM quotation";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo '<table class="table table-sm" id="datatable-search">';
                            echo '<thead class="thead-light">';
                            echo '<tr>';
                            echo '<th>Qt Num</th>';
                            echo '<th>Deal Name</th>';
                            echo '<th>Date</th>';
                            echo '<th>Project Month</th>';
                            echo '<th>Partner</th>';
                            echo '<th>Partner-Employee</th>';
                            echo '<th>Project-Manager</th>';
                            echo '<th>End Customer</th>';
                            echo '<th>Invoiced</th>';
                            echo '<th>Sales Value</th>';
                            echo '<th>GP</th>';
                            echo '<th>Comments</th>';
                            echo '<th class="text-center" >Action</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($row = $result->fetch_assoc()) {
                                $invoced = '' ;
                                if ( $row["invoiced"] == 1){
                                    $invoced = 'Invoiced';
                                }
                                else{
                                    $invoced = 'Not Invoiced';

                                }
                                echo '<tr  oncontextmenu="showContextMenu(event)" >';
                                echo '<tr oncontextmenu="showContextMenu(event, \'' . $row["deal_id"] . '\')" >';
                                echo '<td class="text-sm font-weight-normal">' . $row["deal_id"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["deal_name"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["deal_date"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["project_month"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["partner_name"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["partner_employee"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["pmanager"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["end_customer"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $invoced . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["sum"] . '</td>';
                                echo '<td class="text-sm font-weight-normal">' . $row["gp"] . '</td>';
                            // echo '<td class="text-sm font-weight-normal">' . $row["gross_profit"] . '</td>';
                                echo '<td class="text-sm font-weight-normal"> <textaria class=" textaria??">'.$row["comment"].'</textaria></td>';
                                
                                echo '<td class="text-sm font-weight-normal">
                                <a href="../dashboards/qt_edit.php?code=' . $row["deal_id"] . '" class="btn">
                                <i class="material-icons text-secondary position-relative text-lg">drive_file_rename_outline</i>
                                </a>' . 
                                '<a href="?code=' . $row["deal_id"] . '&delete=true" onclick="return confirm(\'Are you sure you want to delete this data?\');">
                                <i class="material-icons text-secondary position-relative text-lg">delete</i>
                                </a>'.'
                                <a href="../dashboards/qt_view.php?code='. $row["deal_id"].'" class="btn">
                                <i class="material-icons text-secondary position-relative text-lg">visibility</i>
                            </a>
                            
                                </td>';
                            
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo "No data found";
                        } 

                        // Close the database connection
                        $conn->close();
                        ?>
                        <div class="context-menu">
                            <div class="context-menu-item"><i class="fa fa-check"></i> &nbsp;Mark as Invoiced</div>
                            <div class="context-menu-item"><i class="fa fa-check"></i> &nbsp;Mark as Not Invoiced</div>
                            <div class="context-menu-item"><i class="fa fa-list "></i> &nbsp;BOM</div>
                            <div class="context-menu-item"> <i class="fa fa-eye"></i> &nbsp;View</div>
                            <div class="context-menu-item">  <i class="fa fa-pencil"></i> &nbsp;Edit</div>
                            <div class="context-menu-item"><i class="fa fa-trash "></i> &nbsp;Delete</div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

        <script>


// Assuming you have a valid PHP session variable named 'user'
const user = '<?php echo $_SESSION["user"]; ?>';

//console.log('user',user);

function notification() {
  fetch('../../functions/fetchQuotations.php')
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



// Add event listener for right-click to show the context menu
document.documentElement.addEventListener('contextmenu', function(event) {
    event.preventDefault(); // Prevent the default context menu from appearing
    const tr = event.target.closest('tr[data-deal-id]'); // Find the closest ancestor tr with data-deal-id attribute
    if (tr) {
        const dealId = tr.getAttribute('data-deal-id');
        showContextMenu(event, dealId);
        console.log("Deeeal Id", dealId);
    }
});


function showContextMenu(event, dealId) {
    const contextMenu = document.querySelector('.context-menu');
    contextMenu.style.top = event.clientY + 'px';
    contextMenu.style.left = event.clientX + 'px';
    contextMenu.style.display = 'block';

    // Handle context menu item clicks
    const contextMenuItems = document.querySelectorAll('.context-menu-item');
    contextMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            const selectedItem = this.textContent.trim();
            console.log('Selected menu item:', selectedItem);
            console.log('Deal ID:', dealId);
            
            // Redirect based on the selected menu item
            switch(selectedItem) {
                case 'Mark as Invoiced':
                    markAsInvoiced(dealId);
                    break;
                case 'Mark as Not Invoiced':
                    markAsNotInvoiced(dealId);
                    break;
                case 'BOM':
                    window.location.href = '../dashboards/bom.php?code=' + dealId;
                    break;
                case 'View':
                    window.location.href = '../dashboards/qt_view.php?code=' + dealId;
                    break;
                case 'Edit':
                    window.location.href = '../dashboards/qt_edit.php?code=' + dealId;
                    break;
                case 'Delete':
                    if (confirm('Are you sure you want to delete this data?')) {
                        // Send AJAX request to the PHP code for deletion
                        deleteDeal(dealId);
                    }
                    break;
                default:
                    // Handle default case
                    break;
            }
        });
    });

    // Hide the context menu when clicking outside of it
    document.addEventListener('click', function(event) {
        if (!contextMenu.contains(event.target)) {
            contextMenu.style.display = 'none';
        }
    });
}

// Other functions remain unchanged...

function markAsNotInvoiced(dealId) {
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Define the PHP script URL and request method
    const url = '../../functions/markAsNotInvoiced.php';
    const params = 'deal_id=' + dealId; // Parameters to send
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Define the function to handle the AJAX response
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Display SweetAlert notification with custom emoji and message
                Swal.fire({
                    icon: 'success',
                    title: 'Marked as Not Invoiced',
                    html: '<span style="font-size: 40px">&#128118;</span>', // Child face emoji
                    showConfirmButton: false,
                    background: '#ffc107', // Custom background color
                    customClass: {
                        popup: 'swal-popup', // Custom class for the popup
                        title: 'swal-title', // Custom class for the title
                    },
                    timer: 3000
                });
            } else {
                // Handle errors if any
                console.error('Error sending Mark as Not Invoiced request.');
            }
        }
    };

    // Send the AJAX request with the parameters
    xhr.send(params);
}




function markAsInvoiced(dealId) {
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Define the PHP script URL and request method
    const url = '../../functions/markAsInvoiced.php';
    const params = 'deal_id=' + dealId; // Parameters to send
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Define the function to handle the AJAX response
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Display toast notification
                Swal.fire({
                    icon: 'success',
                    title: 'Marked as Invoiced',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                // Handle errors if any
                console.error('Error sending Mark as Invoiced request.');
            }
        }
    };

    // Send the AJAX request with the parameters
    xhr.send(params);
}

function deleteDeal(dealId) {
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Define the PHP script URL and request method
    const url = window.location.pathname + '?delete=true&code=' + dealId;
    xhr.open('GET', url, true);

    // Define the function to handle the AJAX response
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Handle the response here if needed
                console.log('Deletion request sent successfully.');
            } else {
                // Handle errors if any
                console.error('Error sending deletion request.');
            }
        }
    };

    // Send the AJAX request
    xhr.send();
}

document.addEventListener('click', function(event) {
    const contextMenu = document.querySelector('.context-menu');
    contextMenu.style.display = 'none';
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
                            <i class="fa fa-heart"></i>
                            <a href="https://www.activelk.com.com" class="font-weight-bold" target="_blank">ACTIVE
                                SOLUTIONS</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://www.activelk.com" class="nav-link text-muted"
                                    target="_blank">activelk</a>
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
    <script src="../../assets/js/core/popper.min.js"></script>
    <script src="../../assets/js/core/bootstrap.min.js"></script>
    <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../../assets/js/plugins/datatables.js"></script>
    <!-- Kanban scripts -->
    <script src="../../assets/js/plugins/dragula/dragula.min.js"></script>
    <script src="../../assets/js/plugins/jkanban/jkanban.js"></script>
    <script>
    const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
        searchable: true,
        fixedHeight: true
    });
    </script>
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
    <script src="../../assets/js/material-dashboard.min.js?v=3.0.6"></script>
</body>

</html>