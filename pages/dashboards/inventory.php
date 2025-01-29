<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Stock Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Handsontable CSS -->
    <link href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" rel="stylesheet">

    <link href="./css/inventory.css" rel="stylesheet">
    
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-3 shadow-sm">
        <div class="container-fluid">

            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="me-auto">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;" class="text-dark">
                            <i width="12px" height="12px" class="mb-1 fa-stack " viewBox="0 0 45 40">
                                <title>shop</title>
                                <!-- SVG content here -->
                            </i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="../../dashboard.php" class="text-dark ">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#" class="text-dark">Inventory</a>
                    </li>
                    <li class="breadcrumb-item active text-dark" aria-current="page">Stock</li>
                    <li class="ms-6">
                        <div class="input-group">
                            <input id="search" type="search" class=" ms-5 h-25 form-control-sm" placeholder="What are you looking for?" aria-label="Search">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </li>
                </ol>
            </nav>
            <!-- Upload Button -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item pe-2 ">
                    <a class="btn btn-sm btn-outline-secondary" href="../../pages/dashboards/gin.php">GIN</a>
                </li>
                <li class="nav-item pe-2 ">
                    <a class="btn btn-sm btn-outline-info" href="../../pages/dashboards/grn.php">GRN</a>
                </li>
                <li class="nav-item ">
                    <button class="btn btn-sm btn-outline-primary" id="addStockBtn" >
                       + Add
                    </button>
                </li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card hot-container">
                        <div id="stockGrid"></div>
                </div>
            </div>
        </div>

        <!-- Serial Numbers Modal -->
        <div class="modal fade custom-modal" id="serialNumbersModal" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Serial Numbers for <span id="currentItemCode"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="serialNumbersGrid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </main>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- Handsontable JS -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <script  src="./js/inventory.js" ></script>
</body>

</html>