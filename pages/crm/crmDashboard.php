<?php
session_start(); // Start a session

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page or display an error message
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION["user"];
$userLevel = $_SESSION["user_level"];
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Handsontable CSS -->
    <link href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <link href="./css/crmDashboard.css" rel="stylesheet">
    <style>

    </style>
</head>

<body>


    <div id="loadingScreen" class="loading-overlay">
        <div class="loader"></div>

        <div class="loading-text">Loading...</div>
    </div>

    <script>
        const userSession = <?php echo json_encode($_SESSION['user']); ?>;
        const userLevel = <?php echo json_encode($_SESSION['user_level']); ?>;
        const userCategory = <?php echo json_encode($_SESSION['category']); ?>;
        // console.log('User userLevel:', userLevel);
    </script>

    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-3 shadow-sm">
        <div class="container-fluid">
            <!-- Breadcrumb Navigation remains the same -->
            <nav aria-label="breadcrumb" class="me-2">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:window.history.back()" class="text-dark" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Go Home">
                            <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="../../dashboard.php" class="text-dark" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Return to Dashboard">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="#" class="text-dark" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Customer Relationship Management">
                            CRM
                        </a>
                    </li>
                    <li id="userDetails" class="breadcrumb-item">
                        <?php echo htmlspecialchars($_SESSION['user']); ?>
                    </li>
                </ol>
            </nav>
            <div id="userDetailsPopup" class="user-details-popup">
                <div class="text-center">
                    <img id="userAvatar" src="./img/dp.png" alt="User Avatar" class="user-avatar">
                    <h5 id="userName"></h5>
                    <div class="mt-3">
                        <span class="badge bg-primary" id="userRole"></span>
                    </div>
                </div>
            </div>

            <div class="titleArea">
                <h5 class="mt-1 title">CRM DASHBOARD</h5>
            </div>

            <!-- Search Areas -->
            <div class="search-container ">
                <div id="dashboardSearchArea" class="input-group mt-1 search-area">
                    <input id="dashboardTableSearch" type="search" autocomplete="off" class="form-control search-input"
                        placeholder="Search Par/Cust" aria-label="Search Par/Cust">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <div id="dealsTableSearchArea" class="input-group mt-1 search-area">
                    <input id="searchDealsTable" type="search" class="form-control search-input"
                        placeholder="Search Deals" aria-label="Search Deals">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div id="dealEventsSearchArea" class="input-group mt-1 search-area">
                    <input id="searchDealEvents" type="search" class="form-control search-input"
                        placeholder="Search Deal Events" aria-label="Search Deal Actions">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div id="partnerLoockupSearchArea" class="input-group mt-1 search-area">
                    <input id="partnerLoockup" type="search" class="form-control search-input"
                        placeholder="Search Reports" aria-label="Search Partners">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>


            <!-- Tabs -->
            <ul class="nav nav-tabs px-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard"
                        type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Click here to see CRM Dashboard" role="tab">Dashboard
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button"
                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Click here to see deal that allocated for you" role="tab">Deals
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="detailTable-tab" data-bs-toggle="tab" data-bs-target="#detaiTableTab"
                        type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Click here to see deal actions that allocated for you" role="tab">Deal Actions
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#reportTab"
                        type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Click here to see the common partner loockup" role="tab">Reports
                    </button>
                </li>
            </ul>
        </div>
    </nav>

    <div id="searchResults" class="list-group shadow gradient-wrapper container ms-5 p-4 ">
        <!-- Results will be dynamically inserted here -->
    </div>


    <div class="tab-content" id="myTabContent">
        <!-- Dashboard Tab -->
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
            <div class="container-fluid">
                <!-- Summary Cards Row remains the same -->
                <div class="row g-2">
                    <!-- Total Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Total Deals</h5>
                                <h2 id="totalDealsValue" class="card-text">N/A</h2>
                                <p id="tottaldealsPercentage" class="text-success mb-0">
                                    <i class="fas fa-chart-line"></i> 15% increased
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- OverDue Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">OverDue Deals</h5>
                                <h2 id="pendingTotal" class="card-text">N/A</h2>
                                <p class="text-danger mb-0">
                                    <i class="fas fa-exclamation-circle"></i> Requires attention
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Lead Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Lead Deals</h5>
                                <h2 id="initialDeals" class="card-text">N/A</h2>
                                <p class="text-info mb-0">
                                    <i class="fas fa-user-plus"></i> New prospects
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- In-Progress Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">In-Progress Deals</h5>
                                <h2 id="inProgressDeals" class="card-text">N/A</h2>
                                <p class="text-warning mb-0">
                                    <i class="fas fa-spinner fa-spin"></i> Active deals
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-2">



                    <!-- Quoted Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Quoted Deals</h5>
                                <h2 id="totalQuotedDeals" class="card-text">N/A</h2>
                                <p id="totalQuotePercentage" class="text-primary mb-0">
                                    <i class="fas fa-quote-right"></i> Pending approval
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Invoiced Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Invoiced Deals</h5>
                                <h2 id="invoicedDeals" class="card-text">N/A</h2>
                                <p class="text-info mb-0">
                                    <i class="fas fa-file-invoice-dollar"></i> Total Processed
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Completed Deals</h5>
                                <h2 id="completedTotal" class="card-text">N/A</h2>
                                <p class="text-success mb-0">
                                    <i class="fas fa-check-circle"></i> Until today
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Continuous Deals -->
                    <div class="col-6 col-md-3 col-lg">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Continuous Deals</h5>
                                <h2 id="continuousDeals" class="card-text">N/A</h2>
                                <p class="text-success mb-0">
                                    <i class="fas fa-handshake"></i> Partner sales
                                </p>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Deals Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Urgent Deals</h5>
                                <div class="btn-group" id="eventFilters">
                                    <button class="btn btn-sm btn-outline-primary active" data-filter="all">All</button>
                                    <button class="btn btn-sm btn-outline-primary"
                                        data-filter="followUp">Follow-up</button>
                                    <button class="btn btn-sm btn-outline-primary"
                                        data-filter="pending">OverDue</button>
                                </div>
                            </div>
                            <div class="card-body Deals-container">
                                <!-- Follow-up events -->
                                <div class="event-card followUp p-3 bg-light" data-status="followUp">
                                    <div class="event-header">
                                        <h6>Follow-up Event Title</h6>
                                        <span class="event-badge">Follow-up</span>
                                    </div>
                                    <div class="event-body">
                                        <p>Details about the follow-up event.</p>
                                    </div>
                                </div>
                                <!-- Pending events -->
                                <div class="event-card pending p-3 bg-light" data-status="pending">
                                    <div class="event-header">
                                        <h6>Pending Event Title</h6>
                                        <span class="event-badge">Pending</span>
                                    </div>
                                    <div class="event-body">
                                        <p>Details about the pending event.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Table Tab -->
        <div class="tab-pane fade" id="table" role="tabpanel">
            <div class="card">
                <div id="mainTable"></div>
            </div>
        </div>

        <div class="tab-pane fade" id="detaiTableTab" role="tabpanel">
            <div class="card">
                <div id="detailTable"></div>
            </div>
            <div id="hover-popup"
                style="  background-color: rgba(0, 0, 0, 0.8); font-family: Arial, sans-serif;  border-radius: 5px; color: white; padding: 6px; font-size: 14px; display:none; position: fixed;">
            </div>
        </div>

        <div class="tab-pane fade" id="reportTab" role="tabpanel">
            <div class="card">
                <div class="report-tab-content" id="report-tabContent">
                    <div id="tab1" class="tab-pane active">
                        <div class="d-flex justify-content-start align-items-center gap-4 p-2 ">
                            <h6 class="align-self-center">DAILY PERFORMANCE</h6>
                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <h6 class="align-self-center">Date:</h6>
                                <input class="form-control border-radius-lg h-50" type="date" id="performanceDate" value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>" />
                            </div>
                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <h6 class="align-self-center">Rep:</h6>
                                <select id="performanceRep" class="text-sm">
                                    <option>SELECT</option>
                                </select>
                            </div>
                            <div class="ms-auto d-flex justify-content-start align-items-center gap-3">
                                <div class="d-flex justify-content-start align-items-center gap-3 bg-info px-2 rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Total Deals">
                                    <div class="text-white"  id="dailyTotalDeal">0</div>
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="d-flex justify-content-start align-items-center gap-3 bg-success px-2 rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Total Actions">
                                    <div class="text-white" id="dailyTotalDealActions">0</div>
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="table-container" id="table1"></div>
                            <div class="table-container" id="table2"></div>
                        </div>
                    </div>
                    <div id="tab2" class="tab-pane" style="display:none;">
                        <h4 class="p-2">GP Expectations</h3>
                            <div id="gpTable"></div>

                    </div>
                    <div id="tab3" class="tab-pane" style="display:none;">
                        <h2>Calendar Tab</h2>
                        <p>Check your schedule and upcoming events.</p>
                    </div>
                    <div id="tab4" class="tab-pane" style="display:none;">
                        <h2>Settings Tab</h2>
                        <p>Configure your application preferences and settings.</p>
                    </div>
                    <div id="tab5" class="tab-pane" style="display:none;">
                        <h2>Profile Tab</h2>
                        <p>Manage your personal profile and account information.</p>
                    </div>
                </div>
                <!-- Circular Menu -->
            </div>
            <div class="circular-menu" id="circularMenu">
                <div class="nine-dots">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>

                <div class="icon-wrapper">
                    <div class="icon-btn" onclick="switchTab('tab1')" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="DAILY PERFORMANCE">
                        <img class="w-75 h-auto" src="./img/performance.svg">
                    </div>
                    <div class="icon-btn" onclick="switchTab('tab2')" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="GP EXPECTATION">
                        <img class="w-75 h-auto" src="./img/gp.svg">
                    </div>
                    <div class="icon-btn" onclick="switchTab('tab3')" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Calendar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="icon-btn" onclick="switchTab('tab4')" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Settings">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-settings">
                            <path
                                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.08a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.08a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.51a2 2 0 0 1 1-1.74l.15-.08a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                            </path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </div>
                    <div class="icon-btn" onclick="switchTab('tab5')" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Profile">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-user">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>

    <div class="modal fade custom-modal" id="mainTableModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Deal Details <span id="currentItemCode"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="dealNo" class="form-label">Deal No.</label>
                                <input type="text" class="form-control h-50" id="dealNo" name="dealNo" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="dealDate" class="form-label">Date</label>
                                <input type="date" class="form-control h-50" id="dealDate" name="dealDate">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="dealSalesRep" class="form-label ">Sales Rep</label>
                                <input type="text" id="dealSalesRep" class="form-control h-50" disabled>
                            </div>
                            <div class="col-md-3 mb-3 border-3 ">
                                <label for="dealStage" class="form-label">Stage</label>
                                <select class="form-select " id="dealStage" name="dealStage">
                                    <option selected value="initial">Initial</option>
                                    <option value="continuous">Continuous</option>
                                    <option value="POC">POC</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="on-hold">On Hold</option>
                                    <option value="no-interest">No Interest</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <tom class="col-md-12 mb-3">
                                <label for="dealDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="dealDescription" name="dealDescription" rows="3"
                                    placeholder="Enter something about this deal.
Eg : American & Efird Lanka (Pvt)Ltd - Customer Sushaan gunasekara - Head of IT "></textarea>
                            </tom>
                        </div>
                        <div class=" row">
                            <div class="col-md-3 mb-3">
                                <label for="dealCustomer" class="form-label">Customer</label>
                                <select class="form-select mt-1" id="dealCustomer" name="dealCustomer">
                                    <option selected disabled>Select Customer</option>
                                    <option selected>ADD CUSTOMER</option>
                                    <!-- Add dynamic options here -->
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cusTel" class="form-label">Contact No.</label>
                                <input type="tel" class="form-control h-50 " id="cusTel" name="cusTel"
                                    placeholder="01123456789">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="dealPartner" class="form-label">Partner</label>
                                <select class="form-select mt-1" id="dealPartner" name="dealPartner">
                                    <option selected disabled value="">Select Partner</option>
                                    <!-- Add dynamic options here -->
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="dealPartRep" class="form-label">Part. Rep</label>
                                <select class="form-select" id="dealPartRep" name="dealPartRep">
                                    <option value="" selected disabled>
                                        Enter Customer Employee
                                    </option>
                                    <!-- Add dynamic options here -->
                                </select>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-md-3 mb-3">
                                <label for="multyTender" class="form-label">Multy-Tender-ID:</label>
                                <input type="number" class="form-control mt-1" id="multyTender" name="multyTender" min="1" >
                                </input>
                            </div>
                            <div class="col-md-9 mb-3">
                                <label for="multyTender" class="form-label ">Multy-Tender Description:</label>
                                <textarea id="multyTenderDescription" class=" form-control form-text  h-25 " disabled ></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="dealModalClose" type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="dealBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade custom-modal" id="detaiTableModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white py-2">
                    <h5 class="modal-title">DEAL ACTIONS</h5>
                    <button id="actionXbtn" type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="dealForm" class="needs-validation" novalidate>
                        <!-- Rest of the form content remains the same as in your working version -->
                        <!-- First Row -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="trnNo">DEAL No :</label>
                                <input type="text" id="id" hidden>
                                <input type="text" class="form-control h-50" id="trnNo" disabled required>
                            </div>
                            <div class="col-md-4">
                                <label for="date">DATE :</label>
                                <input type="date" class="form-control h-50" placeholder="Eg : 2024-01-03" id="date"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label for="salesRep">SALES REP :</label>
                                <!-- <select class="form-select" id="salesRep" required>
                                      
                                    </select> -->
                                <input id="salesRep" class="form-control h-50" value="<?php echo $user ?>" disabled>
                            </div>
                        </div>
                        <!-- Second Row -->

                        <div class="row g-3 mt-1">

                            <div class="col-md-3">
                                <label for="type">TYPE :</label>
                                <select class="form-select select2-selection h-50" id="type" required>
                                    <option selected value="">Select Type</option>
                                    <option value="Discussion">Discussion</option>
                                    <option value="Price-List">Price List</option>
                                    <option value="Quote">Quote</option>
                                    <option value="Demo">Demo</option>
                                    <option value="Visit">Visit</option>
                                    <option value="Invoice">Invoice</option>
                                    <option value="Complete">Complete</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="media">MEDIA :</label>
                                <select class="form-select select2-selection h-50" id="media" required>
                                    <option selected value="">Select Media</option>
                                    <option value="Call">Call</option>
                                    <option value="Email">E-Mail</option>
                                    <option value="Whats-App">Whats App</option>
                                    <option value="Physical">Physical</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="type">ACTION :</label>
                                <textarea class="form-control h-50 " placeholder="Explain what have you done"
                                    id="action" required></textarea>
                            </div>
                        </div>

                        <!-- Third Row -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label for="fup">FUP USER :</label>
                                <select style=" height: 300px; overflow-y: auto;" class="form-select select2-first mt-4"
                                    id="fup" required>
                                    <option value="">Select User</option>

                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="followup">FOLLOW-UP DATE :</label>
                                <input type="date" class="form-control h-50 " id="followup">
                            </div>
                            <div class="col-md-6">
                                <label for="fupAction">FUP ACTION :</label>
                                <textarea class="form-control h-50 "
                                    placeholder="Explain what will be done in the fup day" id="fupAction"></textarea>
                            </div>

                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label for="brand">BRAND :</label>
                                <select class="form-select mt-2" id="brand" required>
                                    <option value="">Select Brand</option>

                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="model">MODEL :</label>
                                <select class="form-select" id="model" required>
                                    <option value="">Select Model</option>

                                </select>
                            </div>

                            <!-- Fifth Row -->
                            <div class="col-md-3">
                                <label for="inv">INV :</label>
                                <input type="text" class="form-control h-50 " id="inv" placeholder="INV">
                            </div>
                            <div class="col-md-3">
                                <label for="quote">QUOTE :</label>
                                <input type="text" class="form-control h-50 " id="quote" placeholder="Quote">
                            </div>

                        </div>
                        <div class="row">
                            <div class=" ps-2 col-md-6">
                                <label for="fileUpload" class="form-label">UPLOAD FILES:</label>
                                <div class="input-group">
                                    <input type="file" id="fileUpload" class="form-control" multiple>
                                    <button type="button" id="viewFilesBtn" class="btn btn-secondary ">
                                        View Files
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="followup">SUP TICKET :</label>
                                <input type="text" class="form-control h-50 mt-2 " id="supTicket"
                                    placeholder="supTicket number if available">
                            </div>
                        </div>
                        <div class="row" hidden id="gpArea">
                            <!-- Fifth Row -->
                            <div class=" col-6 col-md-6 col-sm-12 ">
                                <label for="gp">GP :</label>
                                <input type="number" class="form-control h-50 " id="gp" placeholder="Expected GP">
                            </div>
                            <div class="col-6 col-md-6 col-sm-12 ">
                                <label for="gpMonth">GP-MONTH :</label>
                                <input type="month" class="form-control h-50 " id="gpMonth"
                                    placeholder="Expected Month">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button id="eventModalClose" type="button" class="btn btn-danger me-2"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="eventBtn" class="btn btn-success">Save Deal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="filePreviewModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>

                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="#" frameborder="0" width="100%" height="500"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="company-name" class="form-label">Company Name :</label>
                            <input type="text" class="form-control" id="company-name" placeholder="Enter company name">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address :</label>
                            <input type="text" class="form-control" id="address" placeholder="Enter address">
                        </div>
                        <div class="mb-3">
                            <label for="payment-terms" class="form-label">Payment Terms :</label>
                            <input type="text" class="form-control" id="payment-terms"
                                placeholder="Enter payment terms">
                        </div>
                        <div class="mb-3">
                            <label for="credit-limit" class="form-label">Credit Limit :</label>
                            <input type="number" class="form-control" id="credit-limit"
                                placeholder="Enter credit limit">
                        </div>
                        <div class="mb-3">
                            <label for="company-type" class="form-label">Company Type</label>
                            <select class="form-control" name="type" id="companyType" required>
                                <option>select</option>
                                <option value="partner">&nbsp;Partner</option>
                                <option value="end_customer">&nbsp;End Customer
                                </option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="vat-number" class="form-label">VAT Number</label>
                            <input type="text" class="form-control" id="vat-number" placeholder="Enter VAT number">
                        </div>
                        <div class="mb-3">
                            <label for="customer-notes" class="form-label">Say a something about this customer</label>
                            <textarea class="form-control" id="customer-notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="createCustomer" type="button" class="btn btn-primary">Save Customer</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="customerEmployeeModal" tabindex="1" aria-labelledby="customerEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerEmployeeModalLabel">Customer Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="ce-name" class="form-label">CE Name</label>
                                <input type="text" class="form-control" id="ce-name" placeholder="Enter CE name">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="tel01" class="form-label">Tel 01</label>
                                <input type="tel" class="form-control" id="tel01" placeholder="Enter telephone number">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="tel02" class="form-label">Tel 02</label>
                                <input type="tel" class="form-control" id="tel02" placeholder="Enter telephone number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="dob" class="form-label">DOB</label>
                                <input type="date" class="form-control" id="dob">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="company-type" class="form-label">Type</label>
                                <select class="form-select" id="company-type">
                                    <option value="select"> &nbsp;select</option>
                                    <option value="sales">&nbsp;Sales</option>
                                    <option value="presales">&nbsp;Presales</option>
                                    <option value="technical">&nbsp;Technical</option>
                                    <option value="account">&nbsp;Account</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="EmplyeeModalcustomer" class="form-label">Partner</label>
                                <input type="text" class="form-control" disabled id="EmplyeeModalcustomer">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="employeeNotes" class="form-label">Notes</label>
                                <input type="text" class="form-control" id="employeeNotes">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="saveEmployee" type="button" class="btn btn-primary">Save Employee</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-labelledby="customerDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold" id="customerDetailsModalLabel">
                        <i class="bi bi-building-fill me-2"></i>Customer Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="customerInfoSection" class="bg-light rounded p-3 mb-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <span class="fw-bold text-primary">Co. Name :</span>
                                    </div>
                                    <div class="col-8">
                                        <span id="companyName" class="text-muted"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <span class="fw-bold text-primary">Address :</span>
                                    </div>
                                    <div class="col-8">
                                        <span id="companyAddress" class="text-muted"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <span class="fw-bold text-primary">Credit Limit :</span>
                                    </div>
                                    <div class="col-8">
                                        <span id="creditLimit" class="text-muted"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <span class="fw-bold text-primary">Pay Terms :</span>
                                    </div>
                                    <div class="col-8">
                                        <span id="paymentTerms" class="text-muted"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="employeesSection">
                        <h6 class="mb-3 text-secondary border-bottom pb-2">
                            <i class="bi bi-people-fill me-2"></i>Customer Employees
                        </h6>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle" id="employeesTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone 1</th>
                                        <th>Phone 2</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody id="employeesTableBody">
                                    <!-- Employees will be dynamically added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Handsontable JS -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="./js/crmDashboard.js"></script>

</body>

</html>