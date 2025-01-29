<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page
    header("Location: ../../../index.php ");
    exit();
}

// The rest of your dashboard.php code here
?>


<?php
include '../../../connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}






if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $itemCode = $_POST["item_code"];
    $manufacturer = $_POST["manufacturer"];
    $shortDescription = isset($_POST["short_desc"]) ? $_POST["short_desc"] : "";
    $longDescription = isset($_POST["long_desc"]) ? $_POST["long_desc"] : "";
    $category = 'NAS';
    $brand = $_POST["choices-category"];
    $warrenty = $_POST["warrenty"];
    
    

    // Specify the directory where you want to save the uploaded images
    $targetDir = "../../../assets/product_image/";

    // Create the target directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Check if image 1 was uploaded
    if (!empty($_FILES["img1"]["name"])) {
        $img1 = uniqid() . "_" . $_FILES["img1"]["name"];
        $img1Path = $targetDir . $img1; // Store the image path
        if (move_uploaded_file($_FILES["img1"]["tmp_name"], $img1Path)) {
            // Image 1 uploaded successfully
        } else {
            echo "Error uploading image 1.";
        }
    }

    // Check if image 2 was uploaded
    if (!empty($_FILES["img2"]["name"])) {
        $img2 = uniqid() . "_" . $_FILES["img2"]["name"];
        $img2Path = $targetDir . $img2; // Store the image path
        if (move_uploaded_file($_FILES["img2"]["tmp_name"], $img2Path)) {
            // Image 2 uploaded successfully
        } else {
            echo "Error uploading image 2.";
        }
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO product (item_code, manufacturer, short_des, long_des, category, image1, image2, brand,warrenty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters (including image paths) to the SQL statement
    $stmt->bind_param("sssssssss", $itemCode, $manufacturer, $shortDescription, $longDescription, $category, $img1Path, $img2Path, $brand, $warrenty);

    try {
        if ($stmt->execute()) {

            $mergeSql = "INSERT INTO merged_table (item_code) VALUES (?)";
            $mergeStmt = $conn->prepare($mergeSql);
        
           
        
                $mergeStmt->bind_param('s', $itemCode);
                
                if ($mergeStmt->execute()) {
                    $mergeStmt->close();
                    echo '<script>alert("Product Added to merged Successfully")</script>';
                }
                else{
                    echo '<script>alert("Product not addded to merged Successfully")</script>';

                }






            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $warranty = $_POST["warrenty"];
                // Additional fields
                $MaximumLocalUserAccounts = $_POST["MaximumLocalUserAccounts"];
                $RJ_45_1GbE = $_POST["RJ_45_1GbE"];
                $RJ_45_10GbE = $_POST["RJ_45_10GbE"];
                $RJ_45_25GbEt = $_POST["RJ_45_25GbEt"];
                $usbPort = $_POST["usbPort"];
                $usbCopy = $_POST["usbCopy"];
                $eSataPort = $_POST["eSataPort"];
                $highAvailabilty = $_POST["highAvailabilty"];
                $maximumConcurrentConnection = $_POST["maximumConcurrentConnection"];
                $maximumConcurrentConnectionWithRam = $_POST["maximumConcurrentConnectionWithRam"];
                $PoolSupport = $_POST["PoolSupport"];
                $readWriteCashe = $_POST["readWriteCashe"];
                $sysLogEventPerSecond = $_POST["sysLogEventPerSecond"];
                $maximumIpCam = $_POST["maximumIpCam"];
                
                // Additional fields from ALTER TABLE statement
                $cpuModel = $_POST["cpuModel"];
                $cpuQt = $_POST["cpuQt"];
                $cpuFrequency = $_POST["cpuFrequency"];
                $systemMemory = $_POST["systemMemory"];
                $cpuArchitecture = $_POST["cpuArchitecture"];
                $totalMemorySize = $_POST["totalMemorySlot"];
                $memoryModule = $_POST["memoryModule"];
                $maximumMemoryCapacity = $_POST["maximumMemoryCapacity"];
                $driveBays = $_POST["driveBays"];
                $maximumExpansionUnits = $_POST["maximumExpansionUnits"];
                $driveSlots = $_POST["driveSlots"];
                $maxBayWithExpanUnit = $_POST["maxBayWithExpanUnit"];
                
                // Prepare and execute the SQL INSERT statement
                $sql = "INSERT INTO product_synology (item_code,warrenty, MaximumLocalUserAccounts, RJ_45_1GbE, RJ_45_10GbE, RJ_45_25GbEt, usbPort, usbCopy, eSataPort, highAvailabilty, maximumConcurrentConnection, maximumConcurrentConnectionWithRam, PoolSupport, readWriteCashe, sysLogEventPerSecond, maximumIpCam, cpuModel, cpuQt, cpuFrequency, systemMemory, cpuArchitecture, totalMemorySlot, memoryModule, maximumMemoryCapacity, driveBays, maximumExpansionUnits, driveSlots, maxBayWithExpanUnit) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssiiississsssssssssiiiisssi",$itemCode,$warranty, $MaximumLocalUserAccounts, $RJ_45_1GbE, $RJ_45_10GbE, $RJ_45_25GbEt, $usbPort, $usbCopy, $eSataPort, $highAvailabilty, $maximumConcurrentConnection, $maximumConcurrentConnectionWithRam,  $PoolSupport, $readWriteCashe, $sysLogEventPerSecond, $maximumIpCam, $cpuModel, $cpuQt, $cpuFrequency, $systemMemory, $cpuArchitecture, $totalMemorySize, $memoryModule, $maximumMemoryCapacity, $driveBays, $maximumExpansionUnits, $driveSlots, $maxBayWithExpanUnit);
            
                if ($stmt->execute()) {
                    $connection = $conn;

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form was submitted

    // Get user-selected data
    $in_ext4 = isset($_POST["in_ext4"]) ? $_POST["in_ext4"] : "";
    $in_btrfs = isset($_POST["in_btrfs"]) ? $_POST["in_btrfs"] : "";
    $fat = isset($_POST["fat"]) ? $_POST["fat"] : "";
    $btrfs = isset($_POST["btrfs"]) ? $_POST["btrfs"] : "";
    $ext3 = isset($_POST["ext3"]) ? $_POST["ext3"] : "";
    $ext4 = isset($_POST["ext4"]) ? $_POST["ext4"] : "";
    $ntfs = isset($_POST["ntfs"]) ? $_POST["ntfs"] : "";
    $hsf = isset($_POST["hfs"]) ? $_POST["hfs"] : "";

    // Prepare and execute the SQL statement to insert data
    $sql = "INSERT INTO internal_drive (item_code,ext4, btrfs) VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($connection, $sql);

    // Check for a successful preparation
    if ($stmt) {
        // Bind the values
        mysqli_stmt_bind_param($stmt, "sss",$itemCode, $in_ext4, $in_btrfs);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Data inserted successfully.";
        } else {
            echo "Error executing the SQL statement: " . mysqli_error($connection);
        }

        // Close the statement
        mysqli_stmt_close($stmt);


        
}}
                } else {
                    echo "Error: " . $stmt->error;
                }
            }
            $connection = $conn;

    $sql0 = "INSERT INTO external_drive (item_code, fat, btrfs, ext3, ext4, ntfs, hfs) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
        $stmt0 = mysqli_prepare($connection, $sql0);
    
        // Check for a successful preparation
        if ($stmt0) {
            // Bind the values
            mysqli_stmt_bind_param($stmt0, "sssssss",$itemCode, $fat, $btrfs, $ext3, $ext4, $ntfs, $hsf);
    
            // Execute the statement
            if (mysqli_stmt_execute($stmt0)) {
                $logSql = "INSERT INTO log (user, task, date_time, other, serverity) VALUES (?, ?, NOW(), ?, ?)";
                $logStmt = $conn->prepare($logSql);
        
                $user = $_SESSION['user'];
                $task = 'Add Product';
                $other = ' Item_code : ' . $itemCode ;
                $severity = 2; // 1 for informational, adjust as needed
        
                $logStmt->bind_param('sssi', $user, $task, $other, $severity);
                $logStmt->execute();
                $logStmt->close();
            } else {
                echo "Error executing the SQL statement: " . mysqli_error($connection);
            }
    
            // Close the statement
            mysqli_stmt_close($stmt0);
    
        } else {
            echo "Error preparing the SQL statement: " . mysqli_error($connection);
        }
            echo '<script>alert("Product Added Successfully")</script>';
            header("Location: ../products/products-list.php ");
            exit();






        } else {
            echo '<script>alert("This product number already exists. Try another one.")</script>';
        }
    } catch (mysqli_sql_exception $e) {
        // Handle any other database errors
        echo "Error: " . $e->getMessage();
    }
    
}

    // Close the database connection
    
    $conn->close();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../../assets/img/logo (2).png">
    <title>
        New Product
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

<body class="g-sidenav-show  bg-gray-200">
    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
        id="sidenav-main">

        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="../../../dashboard.php" target="_blank">
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
                        <img src="../../../assets/img/dp.png" class="avatar">
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
                                <a class="nav-link text-white " href="../../dashboards/quotation.php">
                                    <span class="sidenav-mini-icon"> Q </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Quotation </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../../dashboards/inventory.php">
                                    <span class="sidenav-mini-icon"> S </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Inventory </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../../dashboards/invoice.php">
                                    <span class="sidenav-mini-icon"> I </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Invoicing </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../../dashboards/report.php">
                                    <span class="sidenav-mini-icon"> R </span>
                                    <span class="sidenav-normal  ms-2  ps-1"> Reports </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white " href="../../dashboards/warranty.php">
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

                                            <a class="nav-link text-white " href="../../dashboards/user_details.php">
                                                <span class="sidenav-mini-icon"> U </span>
                                                <span class="sidenav-normal  ms-2  ps-1">User List</span>
                                            </a>

                                        </li>

                                        <li class="nav-item">

                                            <a class="nav-link text-white " href="../../pages/users/new-user.php">
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
                                                href="../../pages/account/clientDetails.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Customer Details</span>
                                            </a>

                                        </li>

                                        <li class="nav-item">

                                            <a class="nav-link text-white " href="../../pages/account/newClient.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New Customer</span>
                                            </a>

                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link text-white "
                                                href="../../pages/account/customerEmployeeDetails.php">
                                                <span class="sidenav-mini-icon"> C </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Customer Employee List </span>
                                            </a>

                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link text-white "
                                                href="../../pages/account/newClientEmployee.php">
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
                                            <a class="nav-link text-white " href="../products/new-product.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> New Product </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="products-list.php">
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
                                            <a class="nav-link text-white " href="../../dashboards/category.php">
                                                <span class="sidenav-mini-icon"> N </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Category </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../../dashboards/brand.php">
                                                <span class="sidenav-mini-icon"> P </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Brand </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../../dashboards/price_view.php">
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
                                            <a class="nav-link text-white " href="../orders/list.php">
                                                <span class="sidenav-mini-icon"> O </span>
                                                <span class="sidenav-normal  ms-2  ps-1"> Order List </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white " href="../orders/details.php">
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
                    <a class="nav-link text-white" href="../../../log.php" target="_blank">
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page"> <a href="../../ecommerce/products/products-list.php" >Products</a> </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Synology</h6>
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
        if (searchTerm.length > 0) {
            $.ajax({
                method: 'POST',
                url: '../../../functions/search.php',
                data: {
                    search: searchTerm
                },
                dataType: 'json',
                success: function(data) {
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
            var encodedItemCode = encodeURIComponent(item.item_code);
            window.location.href = 'product-page.php?item_code=' + encodedItemCode;
        } else {
            // Handle other sources or redirect to a default page
            var encodedItemCode = encodeURIComponent(item.item_code);
            window.location.href = 'product-page.php?item_code=' + encodedItemCode;
            console.log('Redirecting to default page');
        }
    }
});

  

                    </script>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item">
                            <a href="../../pages/profile/overview.php" class="nav-link text-body p-0 position-relative"
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
            <div class="row min-vh-80">
                <div class="col-lg-8 col-md-10 col-12 m-auto">
                    <h3 class="mt-3 mb-0 text-center">Add new Product</h3>
                    <div class="card">
                        <div class="card-header p-0 position-relative mt-n5 mx-3 z-index-2">
                            <div class="bg-dark  shadow-dark border-radius-lg pt-4 pb-3">
                                <div class="multisteps-form__progress">
                                    <button class="multisteps-form__progress-btn js-active" type="button"
                                        title="Product Info">
                                        <span>1. Product Info</span>
                                    </button>
                                    <button class="multisteps-form__progress-btn" type="button" title="Media">2.
                                        Description</button>
                                    <button class="multisteps-form__progress-btn" type="button" title="Socials">3.
                                        Info</button>

                                    <button class="multisteps-form__progress-btn" type="button" title="Pricing">4.
                                        Info</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="multisteps-form__form" method="POST" enctype="multipart/form-data">
                                <!--single form panel-->
                                <div class="multisteps-form__panel pt-3 border-radius-xl bg-white js-active"
                                    data-animation="FadeIn">
                                    <h5 class="font-weight-bolder">Product Information</h5>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-6">
                                                <div class="input-group input-group-dynamic">
                                                    <label for="exampleFormControlInput1" class="form-label">Item
                                                        Number</label>
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="item_code" />
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                                <div class="input-group input-group-dynamic">
                                                    <label for="exampleFormControlInput1"
                                                        class="form-label">Manufacturer</label>
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="manufacturer" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="mt-3">Short Description</label>

                                                <div class="multisteps-form__content">
                                                    <div class="row mt-3">
                                                        <textarea name="short_desc"
                                                            style="width: 650px; height: 140px; padding: 10px; font-size: 16px; border: 1px solid #ccc;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-sm-3 mt-5">
                                                <label class="form-control ms-0">Category</label>
                                                <label class="form-control ms-0">NAS</label>
                                                
                                                <label class="form-control ms-0">Brand</label>
                                                <?php 
                                                include '../../../connection.php';
                                                 include_once '../../../functions/brand.php';
                                               

                                                 brand($conn, 'choices-category', 'choices-category', true);
                                                ?>

                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <label class="form-control mb-0">Product images</label>
                                                <input type="file" name="img1"
                                                    placeholder="Click to upload image 1"><br>
                                                <input type="file" name="img2"
                                                    placeholder="Click to upload image 2"><br>
                                            </div>
                                            <div class="col-6 mt-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label for="exampleFormControlInput1"
                                                        class="form-label">Warrenty</label>
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="warrenty" />
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row mt-1">
                                            <div class="button-row d-flex mt-7">

                                                <div class="button-row d-flex mt-1 ms-auto">
                                                    <a href="../products/bdcom.php" class="btn bg-gradient-dark  mb-0"
                                                        type="button" title="Next">If BD Com</a>
                                                </div>
                                                <button class="btn bg-gradient-dark ms-2 mb-0 js-btn-next" type="button"
                                                    title="Next">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--single form panel-->
                                <div style="margin-top: 20px;"
                                    class="multisteps-form__panel pt-3 border-radius-xl bg-white"
                                    data-animation="FadeIn">
                                    <h5 class="font-weight-bolder">Description</h5>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <textarea name="long_desc"
                                                    style="width: 650px; height: 150px; padding: 10px; font-size: 16px; border: 1px solid #ccc;"></textarea>
                                            </div>
                                        </div>
                                        <div class="button-row d-flex mt-4">
                                            <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button"
                                                title="Prev">Prev</button>
                                            <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="button"
                                                title="Next">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="multisteps-form__panel pt-1 border-radius-xxl bg-white h-300"
                                    data-animation="FadeIn">
                                    <h5 class="font-weight-bolder">Additional Info</h5>
                                    <div class="multisteps-form__content mt-0">
                                        <div class="row mt-2">
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">CPU Model :</label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group input-group-dynamic">
                                                    <input type="text" name="cpuModel" class="form-control ">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group input-group-dynamic">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label class="form-label">CPU Quantity :</label>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="input-group input-group-dynamic">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="cpuQt" id="cpuqt0" value="0">
                                                                    <label class="form-check-label"
                                                                        for="cpuqt0">0</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="input-group input-group-dynamic">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="cpuQt" id="cpuqt1" value="1">
                                                                    <label class="form-check-label"
                                                                        for="cpuqt1">1</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="input-group input-group-dynamic">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="cpuQt" id="cpuqt2" value="2" checked>
                                                                    <label class="form-check-label"
                                                                        for="cpuqt2">2</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">CPU Freq:</label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group input-group-dynamic">
                                                    <input type="text" name="cpuFrequency" class="form-control ">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Sys Memory:</label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group input-group-dynamic">
                                                    <input type="text" name="systemMemory" class="form-control ">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">CPU Architecture :</label>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="input-group input-group-dynamic">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="cpuArchitecture" id="0" value="0">
                                                        <label class="form-check-label" for="0">0</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="cpuArchitecture" id="32" value="32">
                                                        <label class="form-check-label" for="32">32</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="cpuArchitecture" id="64" value="64" checked>
                                                        <label class="form-check-label" for="64">64</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Total Memory Slots:</label>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="totalMemorySlot" id="tms0" value="0" checked>
                                                        <label class="form-check-label" for="internal">0</label>
                                                    </div>
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="totalMemorySlot" id="tms1" value="1">
                                                        <label class="form-check-label" for="card">1</label>
                                                    </div>
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="totalMemorySlot" id="tms2" value="2">
                                                        <label class="form-check-label" for="card">2</label>
                                                    </div>
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="totalMemorySlot" id="tms4" value="4">
                                                        <label class="form-check-label" for="card">4</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Memory Module : </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="memoryModule" />
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Maximum Memory Capacity :</label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="maximumMemoryCapacity" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Compatible Drive Type :</label>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="compatibleDriveType" id="SAS" value="0" checked>
                                                        <label class="form-check-label" for="SAS">SAS</label>
                                                    </div>
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="compatibleDriveType" id="SATA" value="1">
                                                        <label class="form-check-label" for="SATA">SATA</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Drive Bays : </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="driveBays" />
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Maximum Expansion Units :</label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="maximumExpansionUnits" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">M.2 Drive Slots :</label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="driveSlots"
                                                            id="m20" value="0">
                                                        <label class="form-check-label" for="m20">0</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label"></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="driveSlots"
                                                            id="m22" value="2" checked>
                                                        <label class="form-check-label" for="m22">2</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label">Maximum Drive Bays with Expansion Unit :
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group input-group-dynamic">
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="maxBayWithExpanUnit" />
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="button-row d-flex mt-4 col-12">
                                                <button class="btn bg-gradient-light mb-0 js-btn-prev" type="button"
                                                    title="Prev">Prev</button>
                                                <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next"
                                                    type="button" title="Next">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--single form panel-->
                                <div class="multisteps-form__panel pt-3 border-radius-xl bg-white h-150 "
                                    data-animation="FadeIn">
                                    <h5 class="font-weight-bolder">Additional Info</h5>
                                    <div class="multisteps-form__content mt-3">
                                        <div class="row mt-3">
                                            <div class="col-5">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label" for="exampleFormControlInput1"> <b>Maximum
                                                            Local User Accounts</b>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-7">
                                                <div class="input-group input-group-dynamic">
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="MaximumLocalUserAccounts"
                                                        placeholder=" MaximumLocalUser" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-2">
                                                <div class="input-group input-group-dynamic">
                                                    <label class="form-label"> <b>LAN Port :</b>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label>RJ_45_1GbE :</label>
                                                    <select class="multisteps-form__input form-control"
                                                        name="RJ_45_1GbE" id="choices-currency" required>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="8">8</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label for="exampleFormControlInput1">RJ_45_10GbE:</label>
                                                    <select class="form-control " name="RJ_45_10GbE" id="choices-sizes">
                                                        <option value="0">0</option>
                                                        <option value="1" selected>1</option>
                                                        <option value="2">2</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group input-group-dynamic">
                                                    <label>RJ_45_25GbEt:</label>
                                                    <select class="form-control" name="RJ_45_25GbEt"
                                                        id="choices-RJ4525GbEt">
                                                        <option value="0">0</option>
                                                        <option value="1" selected>1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-2">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>USB Port :</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <select class="multisteps-form__input form-control"
                                                            name="usbPort" id="choices-usb2" required>

                                                            <option selected="">USB 3.2 Gen 1</option>
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>USB Copy :</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <select class="multisteps-form__input form-control"
                                                            name="usbCopy" id="choices-usb3">
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">NO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>eSATA Port/Expantion:</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <input class="multisteps-form__input form-control" type="text"
                                                            name="eSataPort" placeholder="eSATA Port/Expantion" />
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>High Availability :</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <select class="multisteps-form__input form-control"
                                                            name="highAvailabilty" id="choices-usb1">
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">NO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-5">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>Maximum Concurrent Connections:</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-7">
                                                    <div class="input-group input-group-dynamic">
                                                        <input class="multisteps-form__input form-control" type="text"
                                                            name="maximumConcurrentConnection"
                                                            placeholder=" SMB/AFP/FTP " />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-5">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>Maximum Concurrent Connections
                                                                (with
                                                                RAM expansion):</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-7">
                                                    <div class="input-group input-group-dynamic">
                                                        <input class="multisteps-form__input form-control" type="text"
                                                            name="maximumConcurrentConnectionWithRam"
                                                            placeholder=" SMB/AFP/FTP " />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>Internal Drives :</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="internalDrive-ext4" name="in_ext4" value="EXT4">
                                                                <label class="form-check-label"
                                                                    for="internalDrive-ext4">EXT4</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="internalDrive-btrfs" name="in_btrfs"
                                                                    value="Btrfs">
                                                                <label class="form-check-label"
                                                                    for="internalDrive-btrfs">Btrfs</label>
                                                            </div>
                                                        </div>
                                                        <!-- Add more checkboxes for "Internal" drives here -->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>External Drives:</b></label>
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="externalDrive-ext1" name="fat" value="FAT">
                                                                <label class="form-check-label"
                                                                    for="externalDrive-ext1">FAT</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="externalDrive-ext2" name="btrfs" value="Btrfs">
                                                                <label class="form-check-label"
                                                                    for="externalDrive-ext2">Btrfs</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="externalDrive-ext3" name="ext3" value="EXT3">
                                                                <label class="form-check-label"
                                                                    for="externalDrive-ext3">EXT3</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="externalDrive-ext4" name="ext4" value="EXT4">
                                                                <label class="form-check-label"
                                                                    for="externalDrive-ext4">EXT4</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="externalDrive-ext5" name="ntfs" value="NTFS">
                                                                <label class="form-check-label"
                                                                    for="externalDrive-ext5">NTFS</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="externalDrive-ext6" name="hfs" value="HFS+">
                                                                <label class="form-check-label"
                                                                    for="externalDrive-ext6">HFS</label>
                                                            </div>
                                                        </div>

                                                        <!-- Add more checkboxes for "External" drives here -->
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row mt-3">
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>Pool Support :</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <select class="multisteps-form__input form-control"
                                                        name="PoolSupport" id="choices-usb4">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>Read / Write Cache
                                                                :</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <select class="multisteps-form__input form-control"
                                                        name="readWriteCashe" id="choices-usb5">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-5">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>Syslog Events per Second:</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-7">
                                                    <div class="input-group input-group-dynamic">
                                                        <input class="multisteps-form__input form-control" type="text"
                                                            name="sysLogEventPerSecond" w-100
                                                            placeholder=" Syslog Events per Second "
                                                            id="exampleInputEmail1" aria-describedby="emailHelp" />
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-5">
                                                    <div class="input-group input-group-dynamic">
                                                        <label class="form-label"><b>Maximum IP cam (Licenses
                                                                required)
                                                                :</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-7">
                                                    <div class="input-group input-group-dynamic">
                                                        <input class="multisteps-form__input form-control" type="text"
                                                            name="maximumIpCam" placeholder=" Maximum IP cam " />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="button-row d-flex mt-0 mt-md-4">
                                                        <button class="btn bg-gradient-light mb-0 js-btn-prev"
                                                            type="button" title="Prev">Prev</button>
                                                        <button class="btn bg-gradient-dark ms-auto mb-0" type="submit"
                                                            title="Send">Send</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                    </form>
                </div>
            </div>
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
                                <a href="https://www.activelk.com" class="nav-link text-muted" target="_blank">Active
                                    Solutions</a>
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
    <script src="../../../assets/js/plugins/dropzone.min.js"></script>
    <script src="../../../assets/js/plugins/quill.min.js"></script>
    <script src="../../../assets/js/plugins/multistep-form.js"></script>
    <script>
    if (document.getElementById('edit-deschiption')) {
        var quill = new Quill('#edit-deschiption', {
            theme: 'snow' // Specify theme in configuration
        });
    };

    if (document.getElementById('choices-category')) {
        var element = document.getElementById('choices-category');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };

    if (document.getElementById('choices-sizes')) {
        var element = document.getElementById('choices-sizes');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-RJ4525GbEt')) {
        var element = document.getElementById('choices-RJ4525GbEt');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };

    if (document.getElementById('choices-usb1')) {
        var element = document.getElementById('choices-usb1');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-usb2')) {
        var element = document.getElementById('choices-usb2');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-usb3')) {
        var element = document.getElementById('choices-usb3');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-usb4')) {
        var element = document.getElementById('choices-usb4');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-usb5')) {
        var element = document.getElementById('choices-usb5');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-currency')) {
        var element = document.getElementById('choices-currency');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('InternalDrives')) {
        var element = document.getElementById('InternalDrives');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('search-results')) {
        var element = document.getElementById('search-results');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-highAvailability')) {
        var element = document.getElementById('choices-highAvailability');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };


    if (document.getElementById('choices-externalDrive')) {
        var element = document.getElementById('choices-externalDrive');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };

    if (document.getElementById('PoolSupport')) {
        var element = document.getElementById('PoolSupport');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };

    if (document.getElementById('SSDReadWrite')) {
        var element = document.getElementById('SSDReadWrite');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };


    if (document.getElementById('choices-usbCopy')) {
        var element = document.getElementById('choices-usbCopy');
        const example = new Choices(element, {
            searchEnabled: false
        });

    };
    if (document.getElementById('choices-lan1')) {
        var element = document.getElementById('choices-lan1');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };
    if (document.getElementById('choices-lan2')) {
        var element = document.getElementById('choices-lan');
        const example = new Choices(element, {
            searchEnabled: false
        });
    };

    if (document.getElementById('choices-tags')) {
        var tags = document.getElementById('choices-tags');
        const examples = new Choices(tags, {
            removeItemButton: true
        });

        examples.setChoices(
            [{
                    value: 'One',
                    label: 'Expired',
                    disabled: true
                },
                {
                    value: 'Two',
                    label: 'Out of Stock',
                    selected: true
                }
            ],
            'value',
            'label',
            false,
        );
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