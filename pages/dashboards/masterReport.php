


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




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../../assets/img/logo (2).png">
    <title>
        MASTER REPORT
    </title>
    
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="../../../assets/css/material-dashboard.css?v=3.0.6" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/handsontable@latest/dist/handsontable.full.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/contextmenu.js/2.9.0/contextmenu.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/handsontable@latest/dist/handsontable.full.min.js"></script>


    <style>
       
        .filter-container {
            margin-bottom: 20px;
        }
        .pe-2 {
            padding-right: 0.5rem;
        }

        .context-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 8px 0;
            z-index: 1000;
        }
        .context-menu-item {
            padding: 8px 16px;
            cursor: pointer;
        }
        .context-menu-item:hover {
            background-color: #f2f2f2;
        }
        .custom-modal {
        max-width: 110%; /* Set the width of the modal */
        margin: 0; /* Remove default margin */
        position: fixed;
        left : 19% ;
        right: 0;
        /*transform: translateY(-50%);*/
    }
    .custom-modal .modal-content {
        width: 95%; /* Ensure the modal content also adheres to the set width */
    }
    .ht_master .htCore th {
      font-weight: bold;
      text-transform: uppercase;
    }
    .htRight {
      text-align: right;
    }
    #example {
      width: 100%;
      height: 100vh; /* Full viewport height */
    }
    #summeryByRepTable{
        width: 100%;
        height: 100vh; /* Full viewport height */
    }
    </style>


</head>

<body >
    <input type ="text" name="p_employee"id="p_employee" value="<?php echo $emailpm ?>" hidden >
   
    <main  >
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl"
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page"> <a href="../../pages/dashboards/masterFile.php" >master file</a> </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">MASTER REPORT</h6>
                </nav>
              
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
        
   
       
        <div  class="row">
                
                <div class="col-2">
                    <button class="btn btn-dark btn-sm " id="summeryByRep" >Summery by REP</button>
                </div>
                <div class="col-2">
                    <button class="btn btn-dark  btn-sm" id="summeryByItem">Summery by Item</button>
                </div>
        </div>
        <div  class="modal fade" id="summaryByRepModal" tabindex="-1" aria-labelledby="summaryByRepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="summaryByRepModalLabel">Summary by REP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="summeryByRepTable"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
        <div class="modal fade" id="summaryByItemModal" tabindex="-1" aria-labelledby="summaryByItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="summaryByItemModalLabel">Summary by Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content for Summary by Item will go here -->
                <table id="itemTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>MONTH</th>
                            <th>GP</th>
                            <th>SYNOLOGY</th>
                            <th>BDCOM</th>
                            <th>DRAYTEC</th>
                            <th>ZYXEL</th>
                            <th>HARD DRIVES</th>
                            <th>ACRONIS</th>
                            <th>GAJ</th>
                            <th>NETWORK</th>
                            <th>MAINTAIN</th>
                            <th>LABOUR</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
          <div  id="example"></div>
        </div>

    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Initialize DataTable -->
<script>





const phpScriptUrl = '../../functions/fetchMasterFile.php'; // Replace with the actual path



 // Fetch data from the PHP script
 fetch(phpScriptUrl)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Fields to display in the specified order
        const fieldsToDisplay = [
          'invDate', 'ivn', 'soldTo', 'endCustomer', 'rep', 'totalAmount', 'gp', 'gpp'
        ];

        // Function to format numbers with commas as thousand separators and two decimal places
        function formatNumber(num) {
          return parseFloat(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        // Filter keys and format data for Handsontable
        const keys = Object.keys(data[0]).filter(key => fieldsToDisplay.includes(key));
        const formattedData = data.map(item => fieldsToDisplay.map(key => {
          if (key === 'totalAmount' || key === 'gp') {
            return formatNumber(item[key]); // Format with commas and two decimal places
          }
          return item[key];
        }));

        // Add keys as headers and make them uppercase
        const headers = fieldsToDisplay.map(key => key.toUpperCase());

        // Custom renderer for right alignment and number formatting
        function rightAlignRenderer(instance, td, row, col, prop, value, cellProperties) {
          Handsontable.renderers.TextRenderer.apply(this, arguments);
          td.style.textAlign = 'right';
          if (prop === 'totalAmount' || prop === 'gp') {
            td.textContent = formatNumber(value);
          }
        }

        const container = document.getElementById('example');
        const hot = new Handsontable(container, {
          licenseKey: 'non-commercial-and-evaluation',  // Non-commercial use key
          data: formattedData,
          rowHeaders: true,
          colHeaders: headers, // Use the filtered and uppercase headers
          filters: true,
          width: '100%',
          height: 400,
          stretchH: 'all',
          fixedRowsTop: 0,  // Fix the headers
          dropdownMenu: true,
          cells: function (row, col) {
            const cellProperties = {};
            const field = fieldsToDisplay[col];
            if (field === 'totalAmount' || field === 'gp' || field === 'gpp') {
              cellProperties.renderer = rightAlignRenderer;
            }
            
            return cellProperties;
          }
        });
        window.addEventListener('resize', function() {
      hot.render();
    });
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });




// Fetch data from the PHP script

// Fetch data from the PHP script
fetch(phpScriptUrl)
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    console.log('data',data);

    const totals = {};
                data.forEach(item => {
                    const month = item.gpMonth;
                    if (!totals[month]) {
                        totals[month] = {
                            gp: 0,
                            synology: 0,
                            bdcom: 0,
                            draytec: 0,
                            zyxel: 0,
                            hardDrives: 0,
                            acronis: 0,
                            gaj: 0,
                            network: 0,
                            maintain: 0,
                            labour: 0
                        };
                    }
                    totals[month].gp += parseFloat(item.gp) || 0;
                    totals[month].synology += parseFloat(item.synology) || 0;
                    totals[month].bdcom += parseFloat(item.bdcom) || 0;
                    totals[month].draytec += parseFloat(item.draytec) || 0;
                    totals[month].zyxel += parseFloat(item.zyxel) || 0;
                    totals[month].hardDrives += parseFloat(item.hardDrives) || 0;
                    totals[month].acronis += parseFloat(item.acronis) || 0;
                    totals[month].gaj += parseFloat(item.gaj) || 0;
                    totals[month].network += parseFloat(item.network) || 0;
                    totals[month].maintain += parseFloat(item.maintain) || 0;
                    totals[month].labour += parseFloat(item.labour) || 0;
                });

                // Generate table rows
                const itemTBody = document.querySelector('#itemTable tbody');
                for (const month in totals) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${month}</td>
                        <td>${totals[month].gp}</td>
                        <td>${totals[month].synology}</td>
                        <td>${totals[month].bdcom}</td>
                        <td>${totals[month].draytec}</td>
                        <td>${totals[month].zyxel}</td>
                        <td>${totals[month].hardDrives}</td>
                        <td>${totals[month].acronis}</td>
                        <td>${totals[month].gaj}</td>
                        <td>${totals[month].network}</td>
                        <td>${totals[month].maintain}</td>
                        <td>${totals[month].labour}</td>
                    `;
                    itemTBody.appendChild(row);
                }

                let groupedData = {};
                let reps = new Set();

                data.forEach(row => {
                  if (!groupedData[row.gpMonth]) {
                    groupedData[row.gpMonth] = { totalGP: 0, reps: {} };
                  }
                  groupedData[row.gpMonth].totalGP += parseFloat(row.gp);
                  if (!groupedData[row.gpMonth].reps[row.rep]) {
                    groupedData[row.gpMonth].reps[row.rep] = 0;
                  }
                  groupedData[row.gpMonth].reps[row.rep] += parseFloat(row.gp);

                  reps.add(row.rep);
                });

                // Prepare data for Handsontable
                let hotData = [];
                let headers = ['MONTH', 'GP', ...Array.from(reps)];

                for (let month in groupedData) {
                  let row = [month, groupedData[month].totalGP.toFixed(2)];
                  Array.from(reps).forEach(rep => {
                    row.push(groupedData[month].reps[rep] !== undefined ? groupedData[month].reps[rep].toFixed(2) : '0.00');
                  });
                  hotData.push(row);
                }

                // Initialize Handsontable
                var container = document.getElementById('summeryByRepTable');
                var hot = new Handsontable(container, {
                  data: hotData,
                  colHeaders: headers,
                  filters: true,
                  dropdownMenu: true,
                  rowHeaders: true,
                  width: '100%',
                  height: 'auto',
                  stretchH: 'all',
                  licenseKey: 'non-commercial-and-evaluation'
                });

                window.addEventListener('resize', function() {
                    hot.render();});

                // Prepare detailed table data
                let tableData = [];

                data.forEach(row => {
                  let rowData = [];
                  rowData.push(row.invDate);
                  rowData.push(row.ivn);
                  rowData.push(row.directPartner);
                  rowData.push(row.endCustomer);
                  rowData.push(row.rep);

                  // Ensure the value is a number and has two decimal points
                  rowData.push(parseFloat(row.totalAmount).toFixed(2)); 
                  rowData.push(parseFloat(row.gp).toFixed(2)); 
                  rowData.push(parseFloat(row.gpp).toFixed(2)); 

                  tableData.push(rowData);
                });

                    $('#summeryByRep').on('click', function() {
                    $('#summaryByRepModal').modal('show');
                    //document.getElementById('context-menu').style.display = 'none';
                });

                $('#summeryByItem').on('click', function() {
                    $('#summaryByItemModal').modal('show');
                    //document.getElementById('context-menu').style.display = 'none';
                });

                  })
                  .catch(error => {
                    console.error('Error fetching data:', error);
                  });

                function filterTable() {
                    const monthInput = document.getElementById('month').value;
                    const selectedMonth = new Date(monthInput);
                    const table = $('#data-table').DataTable();
                    
                    table.rows().every(function() {
                        const dateCell = this.data()[0];
                        const rowDate = new Date(dateCell);
                        
                        if (selectedMonth && rowDate.getMonth() === selectedMonth.getMonth() && rowDate.getFullYear() === selectedMonth.getFullYear()) {
                            this.nodes().to$().show();
                        } else {
                            this.nodes().to$().hide();
                        }
                    });
                    
                    table.draw();
                }
</script> 
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
    
    


  
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../../../assets/js/material-dashboard.min.js?v=3.0.6"></script>
</body>

</html>