<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../pages/dashboards/css/invoiceDetails.css">

</head>

<body>

    <nav class="container-fluid d-flex flex-row p-1 justify-content-between align-content-center ">
        <div>
            <h4>📜 Invoice Details</h4>
        </div>
        <div class="d-flex flex-row justify-content-center gap-1 align-content-center">
            <div>
                <input type="search" id="searchBar" class="form-control mb-3" placeholder="Search by Invoice #, Customer, etc.">
            </div>
            <div>
                <select id="filterRep" class="form-select">
                    <option value="">Select Representative</option>
                    <!-- Rep options will be dynamically populated here -->
                </select>
            </div>
            <div>
                <input type="date" id="filterDate" class="form-control">
            </div>
        </div>
    </nav>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>PO #</th>
                        <th>Rep</th>
                        <th>Terms</th>
                        <th>Shipping Date</th>
                        <th>VAT</th>
                        <th>Discount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="invoiceTableBody">
                    <!-- Data will be injected here -->
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <script src="../../pages/dashboards/js/invoiceDetails.js">

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>