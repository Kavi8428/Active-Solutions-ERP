<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HTML to PDF Example</title>
<!-- Include html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 40px;
        
    }
    .content {
        max-width: 650px;
        margin: 0 auto;
        
    }
    .content img {
        max-width: 110%;
        height: auto;
        margin-bottom: 10px;
    }
    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        margin-top: 20px;
    }
    button:hover {
        background-color: #0056b3;
    }
    /* Added styles */
    .table-container {
        border: 1px solid #C9C7C7;
        max-width: 100%; /* Full width within the container */
        min-height: 500px; /* Set minimum height */
        margin: 0 auto; /* Centering the table container */
        box-sizing: border-box; /* Include padding in the element's total width and height */
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }
    #pdfArea{
        margin-top: 20px;
    }
    .footer{
        margin-top: 12%;
        margin-left: 4%;
    }
    input{
        width: 300px;
    }
</style>
</head>
<body style="font-size: small;" >
    
<div class="content">
    <div id="pdfArea">
        <div  class="row">
            <div class="col-9">
                <div class="text-start">
                    <img class="img-fluid w-25 h-auto" src="../../assets/img/active-logo.png" alt="">
                </div>
            </div>
            <div class="col-3">
                <div class="text-right">
                    <strong></strong> AS-GIN-<input class="border-0 w-25" type="text" id="ginNo" readonly>
                </div>  
            </div>
        </div>
        <div class="text-center mt-4 ql-font-serif">
            <h3>GOOD ISSUE NOTE</h3>
        </div>
        <div class="text-start mt-5 flex-sm-fill ">
        E CUSTOMER:  &nbsp;&nbsp;   <strong><input id="endCustomer" class="border-0 " readonly></strong>
        </div>
        <div class="text-start mt-2 flex-sm-fill ">
        PARTNER:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;   <strong><input id="customer" class="border-0" readonly></strong>
        </div>
        <div class="text-start mt-2 flex-sm-fill ">
        DATE:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; <strong><input type="text" style="margin-left: 6px;" class="ms-10 border-0" id="ginDate" readonly></strong>
        </div>

        <!-- Wrapped the table in a container with fixed border -->
        <div style="margin-top: 10%;" class="table-container">
            <table class="table-bordered" id="ginItemsTable">
                <thead>
                    <tr>
                        <th style="width: 20%;">Item Code</th>
                        <th style="width: 16%;">Quantity</th>
                        <th>Serial Numbers</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically added using JavaScript -->
                </tbody>
            </table>
        </div>

        <div class="footer row">
            <div class="col-9">
                <div>Prepared by</div>
            </div>
            <div class="col-3">
                <div >Approved by</div>
            </div>
        </div>
    </div>

</div>
<button onclick=" generatePDF();" id="download-pdf">Download PDF</button>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

<script>

async function fetchGinItems(code) {
    try {
        const response = await fetch('../../functions/fetchGinItems.php'); // Update with your actual URL
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        const data = await response.json(); // Parse response as JSON

        ginItems = data;
        return ginItems.filter(item => item.gin_id == code);
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        return [];
    }
}

async function fetchGinData(code) {
    try {
        const response = await fetch('../../functions/fetchGin.php'); // Update with your actual URL
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        const data = await response.json(); // Parse response as JSON

        ginData = data;

        // Process and display the fetched data
        displayGinData(ginData, code);
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }
}

function displayGinItems(filteredGinItems) {
    const tableBody = document.querySelector('#ginItemsTable tbody');
    
    // Clear existing rows
    tableBody.innerHTML = '';
    
    // Iterate over filtered GIN items and create table rows
    filteredGinItems.forEach(item => {
        const row = document.createElement('tr');

        // Create cells for each column
        const itemCodeCell = document.createElement('td');
        itemCodeCell.style.textAlign = 'center';
        itemCodeCell.textContent = item.itemCode;
        
        const quantityCell = document.createElement('td');
        quantityCell.style.textAlign = 'center';
        quantityCell.textContent = item.quantity;
        
        const serialsCell = document.createElement('td');
        serialsCell.style.textAlign = 'center';
        
        // Split serials by comma
        const serialNumbers = item.serial.split(',');
        
        // Append each serial number with line break
        serialNumbers.forEach((serial, index) => {
            const serialSpan = document.createElement('span');
            serialSpan.textContent = serial.trim(); // Trim to remove any leading/trailing whitespace
            
            // Append span to serialsCell
            serialsCell.appendChild(serialSpan);
            
            // Add line break after each serial number except the last one
            if (index < serialNumbers.length - 1) {
                serialsCell.appendChild(document.createElement('br'));
            }
        });

        // Append cells to the row
        row.appendChild(itemCodeCell);
        row.appendChild(quantityCell);
        row.appendChild(serialsCell);

        // Append row to the table body
        tableBody.appendChild(row);
    });
}

function displayGinData(data, code) {
    data.forEach(grnItem => {
        if (grnItem.id == code) {
            const grnNo = grnItem.id;
            const date = grnItem.date;
            const customer = grnItem.customer;
            const endCustomer = grnItem.end_customer;
           // console.log('endCustomer',endCustomer)

            const ginNoElement = document.getElementById('ginNo');
            const ginDateElement = document.getElementById('ginDate');
            const customerElement = document.getElementById('customer');
            const endCustomerElement = document.getElementById('endCustomer');
            
            if (ginNoElement) ginNoElement.value = Number(grnNo);
            if (ginDateElement) ginDateElement.value = date;
            if (customerElement) customerElement.value = ' ' + customer;
            if (endCustomerElement) endCustomerElement.value = ' ' + endCustomer;
        }
    });
}

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

function generatePDF() {
    var element = document.querySelector('.content'); // This is the element you want to convert to PDF

    html2pdf().from(element).set({
        margin: [10, 0, 0, 0], // Set margins to zero to fit more content
        filename: 'good_issue_note.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 3 },
        jsPDF: { unit: 'pt', format: 'a4', orientation: 'portrait' }
    }).toPdf().get('pdf').then(function (pdf) {
        var totalPages = pdf.internal.getNumberOfPages();
    }).save().then(function() {
     window.location.href = "./gin.php";
    });
}

// Get the 'code' parameter from the URL
var code = getUrlParameter('code');
//console.log("Code from URL:", code);

// Fetch data and then generate PDF
(async function() {
    const [filteredGinItems] = await Promise.all([fetchGinItems(code), fetchGinData(code)]);
    displayGinItems(filteredGinItems);
   
})();


</script>

</body>
</html>
