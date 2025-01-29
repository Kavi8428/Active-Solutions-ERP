<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@12.1.0/dist/handsontable.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>
    <link rel="stylesheet" href="./css/monthlySalesReport.css">
    <style>
        .chart-container {
            max-width: 720px;
            margin: 2rem auto;

        }

        .center-text {
            position: absolute;
            top: 40%;
            left: 50%;
            color: white;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid ">
        <div class="d-flex flex-md-row align-items-center ">
            <i class="fa fa-arrow-circle-left me-3 " id="back" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="BACK"></i>
            <h5 class="mb-0 me-3">Sales Reports</h5>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="By clicking this can see the summery view of  top managers, top cstomers, top categories for the current year.">
                    <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#charts" type="button" role="tab">
                        <i class="fa fa-pie-chart " aria-hidden="true"></i>
                        </i>
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the DETAIL view of invoices">
                    <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                        <i class="fas fa-list  me-2"></i>
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the SUMMERY view of invoices">
                    <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#summery" type="button" role="tab">
                        <i class="fa fa-sort-amount-desc " aria-hidden="true"></i>
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the customer SALE summery">
                    <button class="nav-link" id="cus-tab" data-bs-toggle="tab" data-bs-target="#cusSalesum" type="button" role="tab">
                        <i class="fas fa-balance-scale me-2"></i>CUS SALE SUM
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the customer GP summery">
                    <button class="nav-link" id="cus-gp-tab" data-bs-toggle="tab" data-bs-target="#cusGpSum" type="button" role="tab">
                        <i class="fas fa-balance-scale me-2"></i>CUS GP SUM
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the rep SALE summery">
                    <button class="nav-link" id="rep-sale-tab" data-bs-toggle="tab" data-bs-target="#repSaleSum" type="button" role="tab">
                        <i class="fas fa-user me-2"></i>REP SALE SUM
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the rep GP summery">
                    <button class="nav-link" id="rep-gp-tab" data-bs-toggle="tab" data-bs-target="#repGpSum" type="button" role="tab">
                        <i class="fas fa-user me-2"></i>REP GP SUM
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the product SALE summery">
                    <button class="nav-link" id="pro-sale-tab" data-bs-toggle="tab" data-bs-target="#proSaleSum" type="button" role="tab">
                        <i class="fas fa-product-hunt me-2"></i>PROD SALE SUM
                    </button>
                </li>
                <li class="nav-item" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the product GP summery">
                    <button class="nav-link" id="pro-gp-tab" data-bs-toggle="tab" data-bs-target="#proGpSum" type="button" role="tab">
                        <i class="fas fa-product-hunt  me-2"></i>PROD GP SUM
                    </button>
                </li>
                <li class="nav-item" role="switch" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to see the over view of sales">
                    <button class="nav-link" id="overView-tab" data-bs-toggle="tab" data-bs-target="#overView" type="button" role="tab">
                        <i class="fas fa-eye  me-2"></i>OVER VIEW
                    </button>
                </li>
            </ul>
        </div>
        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Summary Tab -->
            <section class="tab-pane fade show active" id="charts" role="tabpanel">
                <!-- Summary Cards -->
                <div class="card">
                    <div class="card-body">
                        <div style="font-size: larger;" class="row mb-4">
                            <div class="col-md-6">
                                <h4>Top Managers</h4>
                                <div id="topManagersChart">
                                    <table id="managerTable" class=" table table-borderless border-2 border-radius-bottom-end-circle bg-transparent ">
                                        <tr class="border-2 bg-transparent ">
                                            <td class="bg-transparent p-3 border-end-0 "></td>
                                            <th class="text-end p-3 bg-transparent "></th>
                                        </tr>
                                        <tr>
                                            <td class="bg-transparent p-3  border-end-0"></td>
                                            <th class="text-end p-3 bg-transparent "></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Top Customers</h4>
                                <div id="topCustomerChart">
                                    <table id="customerTable" class=" table table-borderless border-2 border-radius-bottom-end-circle bg-transparent ">
                                        <tr class="border-2 bg-transparent ">
                                            <td class="bg-transparent p-3 border-end-0 ">Company1</td>
                                            <th class="text-end p-3 bg-transparent ">12000</th>
                                        </tr>
                                        <tr>
                                            <td class="bg-transparent p-3  border-end-0">Company2</td>
                                            <th class="text-end p-3 bg-transparent ">12000</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h4>Category Distribution</h4>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span id="top1Category">Electronics</span>
                                        <span id="top1CategoryValue">45%</span>
                                    </div>
                                    <div class="progress">
                                        <div id="top1CategoryProgress" class="progress-bar bg-info" role="progressbar" style="width: 45%"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span id="top2Category">Services</span>
                                        <span id="top2CategoryValue">35%</span>
                                    </div>
                                    <div class="progress">
                                        <div id="top2CategoryProgress" class="progress-bar bg-warning" role="progressbar" style="width: 35%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Monthly Comparison</h4>
                                <table id="monthCompare" class="table">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Invoices</th>
                                            <th>Total Value</th>
                                            <th>Growth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>January</td>
                                            <td>45</td>
                                            <td>$52,000</td>
                                            <td class="text-success">+5.2%</td>
                                        </tr>
                                        <tr>
                                            <td>February</td>
                                            <td>52</td>
                                            <td>$58,500</td>
                                            <td class="text-success">+12.5%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="tab-pane fade" id="summery" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="monthFilter">MONTH</label>
                                    <input type="text" class="form-control" id="sumMonthFilter" placeholder="Select Month-Year">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">CUSTOMER</label>
                                <select class="form-select" id="sumCustomerFilter">
                                    <option value="">All Customers</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">CATEGORY</label>
                                <select class="form-select" id="sumCategoryFilter">
                                    <option value="">All Categories</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-sm btn-info w-100">
                                    <i class="fas fa-filter me-2"></i>FILTER
                                </button>
                            </div>
                        </div>
                        <row>
                            <div id="summeryTable"></div>
                        </row>


                    </div>
                </div>
            </section>


            <section class="tab-pane fade" id="details" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="monthFilter"
                                    placeholder="Select Month-Year"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Month and Year (e.g., 11-2024)">
                            </div>

                            <div class="col-md-3 mt-1 ">
                                <select class="form-select" id="customerFilter" data-bs-toggle="tooltip" data-bs-placement="top" title="Select a Customer">
                                    <option value="">All Customers</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-1">
                                <select class="form-select" id="categoryFilter" data-bs-toggle="tooltip" data-bs-placement="top" title="Select a Category">
                                    <option value="">All Categories</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end mb-1">
                                <button class="btn btn-sm btn-info w-100" onclick="applyFilters()">
                                    <i class="fas fa-filter me-2"></i>FILTER
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="invoiceTable">
                                    <thead>
                                        <tr>
                                            <th onclick="sortTable(0)">Date <i class="fas fa-sort sort-icon"></i></th>
                                            <th onclick="sortTable(1)">Invoice # <i class="fas fa-sort sort-icon"></i></th>
                                            <th onclick="sortTable(2)">Customer <i class="fas fa-sort sort-icon"></i></th>
                                            <th onclick="sortTable(3)">Category <i class="fas fa-sort sort-icon"></i></th>
                                            <th onclick="sortTable(4)">Value <i class="fas fa-sort sort-icon"></i></th>
                                            <th onclick="sortTable(5)">VAT <i class="fas fa-sort sort-icon"></i></th>
                                            <th onclick="sortTable(6)">Other <i class="fas fa-sort sort-icon"></i></th>
                                            <th onclick="sortTable(7)">Total <i class="fas fa-sort sort-icon"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceTableBody" class="scrollable-body">
                                        <!-- Rows will be dynamically inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Details Tab -->
            <section class="tab-pane fade" id="cusSalesum" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-sm-3">
                                <h6 class="pt-4">CUSTOMER SALES SUMMERY REPORTS</h6>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="cusFromMonth">FROM</label>
                                    <input type="date" class="form-control" id="cusFromMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="cusToMonth">TO</label>
                                    <input type="date" class="form-control" id="cusToMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <button type="button" id="cusFilterBtn" class="btn btn-sm btn-info m-4">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    FILTER
                                </button>
                            </div>
                        </div>

                        <div id="salesTable"></div>
                    </div>
                </div>
            </section>

            <section class="tab-pane fade" id="cusGpSum" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-sm-3">
                                <h6 class="pt-4">CUSTOMER GP SUMMERY REPORTS</h6>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="cusFromMonth">FROM</label>
                                    <input type="date" class="form-control" id="cusGpFromMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="cusToMonth">TO</label>
                                    <input type="date" class="form-control" id="cusGpToMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <button type="button" id="cusGpFilterBtn" class="btn btn-sm btn-info m-4">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    FILTER
                                </button>
                            </div>
                        </div>

                        <div id="gpTable"></div>
                    </div>
                </div>
            </section>

            <section class="tab-pane fade" id="repSaleSum" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-sm-3">
                                <h6 class="pt-4">REP SALES SUMMERY REPORTS</h6>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="repSaleFromMonth">FROM</label>
                                    <input type="date" class="form-control" id="repSaleFromMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="repSaleToMonth">TO</label>
                                    <input type="date" class="form-control" id="repSaleToMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <button type="button" id="repSaleBtn" class="btn btn-sm btn-info m-4">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    FILTER
                                </button>
                            </div>
                        </div>

                        <div id="repSalesTable"></div>
                        <div style="background : #cedfdf" >
                            <h4 class="mt-4 p-2">Graphical View</h4>
                            <div class="chart-container">
                                <canvas id="repSalesChart"></canvas>
                                <div class="center-text" id="repSaleTotal"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>


            <section class="tab-pane fade" id="repGpSum" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-sm-3">
                                <h6 class="pt-4">REP GP SUMMERY REPORTS</h6>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="repGpFromMonth">FROM</label>
                                    <input type="date" class="form-control" id="repGpFromMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="repGpToMonth">TO</label>
                                    <input type="date" class="form-control" id="repGpToMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <button type="button" id="repGpBtn" class="btn btn-sm btn-info m-4">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    FILTER
                                </button>
                            </div>
                        </div>

                        <div id="repGpTable"></div>
                    </div>
                </div>
            </section>


            <section class="tab-pane fade" id="proSaleSum" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-sm-3">
                                <h6 class="pt-4">PRODUC SALE SUMMERY REPORTS</h6>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="proSaleFromMonth">FROM</label>
                                    <input type="date" class="form-control" id="proSaleFromMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="proSaleToMonth">TO</label>
                                    <input type="date" class="form-control" id="proSaleToMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <button type="button" id="proSaleBtn" class="btn btn-sm btn-info m-4">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    FILTER
                                </button>
                            </div>
                        </div>

                        <div id="proSaleTable"></div>

                    </div>
                </div>
            </section>



            <section class="tab-pane fade" id="proGpSum" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-sm-3">
                                <h6 class="pt-4">PRODUC GP SUMMERY REPORTS</h6>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="proGpFromMonth">FROM</label>
                                    <input type="date" class="form-control" id="proGpFromMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="proGpToMonth">TO</label>
                                    <input type="date" class="form-control" id="proGpToMonth" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <button type="button" id="proGpBtn" class="btn btn-sm btn-info m-4">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    FILTER
                                </button>
                            </div>
                        </div>

                        <div id="proGpTable"></div>
                    </div>
                </div>
            </section>
            <section class="tab-pane fade" id="overView" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-sm-3">
                                <h6 class="pt-4">OVER VIEW</h6>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="overViewFrom">FROM</label>
                                    <input type="date" class="form-control" id="overViewFrom" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <div class="form-group">
                                    <label for="overViewTo">TO</label>
                                    <input type="date" class="form-control" id="overViewTo" placeholder="Select Month-Year">
                                </div>
                            </div>
                            <div class=" col-sm-3">
                                <button type="button" id="overViewBtn" class="btn btn-sm btn-info m-4">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    FILTER
                                </button>
                            </div>
                        </div>

                        <div id="overViewTable"></div>

                        <div class="container">
                            <h5 class="text-start">GRAPHICAL VIEW</h5>
                            <canvas id="overViewChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/handsontable@12.1.0/dist/handsontable.full.min.js"></script>
    <script src="./js/monthlySalesReport.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>
</body>

</html>