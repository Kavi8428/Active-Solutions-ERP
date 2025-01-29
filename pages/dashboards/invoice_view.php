<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tax Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Set A4 size */
    @page {
      size: A4;
      margin: 1cm;
    }

    /* Make it responsive */
    @media print {
      body {
        width: 210mm;
        height: 297mm;
        margin: 0;
        padding: 0;
        background-image: url('../../assets/img/bg-smart-home-1.jpg');
        /* Replace with your image URL */
      }

      a {
        color: black;
        font-style: normal;
        font-size: smaller;
      }

      h6 {
        font-size: smaller;
        margin-top: 9px;
      }

      .dots {
        width: 20%;
      }

    }

    .logo {
      width: 300px;
      height: 80px;
      margin-bottom: 10px;
    }

    /* Styles for the invoice details table */
    #invoice-details {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
      font-size: 12px;

    }

    #invoice-details th,
    #invoice-details td {
      border: 1px solid #000;
      padding: 8px;
      text-align: center;
    }

    .invoice-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;

    }

    #vat-number {
      font-size: 12px;
    }


    /* Bill to table */


    .bill-to-table {
      margin-bottom: 5px;
      width: 100%;
      font-size: 12px;

    }

    .bill-to-table th,
    .bill-to-table td {
      border: 1px solid #000000;
      padding: 3px;
      text-align: left;
    }

    /* MAIN to table */


    #main-items {
      border: 1px solid #000 !important;
      border-collapse: collapse;
      width: 100%;
      height: 500px;
      font-size: 12px;

    }

    #main-items table tr {
      height: 20px;
    }

    .img-fluid {
      width: 24px;
      height: 24px;
    }


    /* Responsive styles */
    @media only screen and (max-width: 768px) {
      .invoice-header {
        flex-direction: column;
        justify-content: center;
      }

      .bill-to-table {
        width: 100%;
        align-items: right;
      }

      p {
        font-size: 8px;
      }

      h6 {
        font-size: 5px;
        margin-right: 1px;
      }

      .img-fluid {
        margin-left: 30px;
        width: 18px;
        height: 18px;
      }

      .vat-number {
        margin-left: 30px;
      }

      .dots {
        width: 50%;
      }
    }


    /* Footer Styles */

    #footer {
      font-size: 12px;
    }

    #footer h6 {
      font-size: 10px;

    }

    #footer .img-fluid {
      margin-left: 2px;
      width: 15px;
      height: 15px;
    }


    /* Additional Styles */

    .toast-container-center {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1050;
      /* Higher than the rest of the elements */
    }

    /* Overlay to blur or hide content */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      /* Dark semi-transparent overlay */
      backdrop-filter: blur(5px);
      /* Blurs the background */
      z-index: 1040;
      /* Just behind the toast */
    }

    /* Hide content on blur effect */
    body.blurred {
      overflow: hidden;
    }
  </style>
</head>

<body>
  <div class="container bg-white">
    <!-- Header section -->
    <div class="header-rect" style="background-image: url('../../assets/img/bg-smart-home-1.jpg'); "></div>
    <div class="row mt-5">
      <div class="col-md-9">
        <img src="../../assets/img/active-logo.png" class="logo" alt="Logo">
        <div id="vat-number" class="vat-number">Our VAT Reg No. 742471004 - 7000</div>
      </div>
      <div class="col-md-3">
        <div class="invoice-header">
          <div>
            <h2 id="invoiceType">Tax Invoice</h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Bill to table -->
    <div class="row">
      <div class="col-md-8">
        <table style="width: 70%; height:80%; margin-top: 10px; " class="bill-to-table">
          <tr>
            <td>Bill To:</td>
          </tr>
          <tr>
            <td id="billTo">
              Alphasonic Technologies (Pvt) Ltd <br>
              No:07, Araliya Gardens, <br>
              Nawala, Rajagiriya.<br>
              VAT No.: 101045234 - 7000<br>
            </td>
          </tr>
        </table>
      </div>
      <div class="col-md-4">
        <!-- First Table -->
        <table id="invoice-details" style="width: 100%;" class=" table invoice-table mb-2">
          <tr>
            <td>Date :</td>
            <td id="date"></td>
          </tr>
          <tr>
            <td>Invoice #:</td>
            <td id="invNo"></td>
          </tr>
          <tr>
            <td>P.O Number :</td>
            <td id="poNo"></td>
          </tr>
          <tr>
            <td>Terms :</td>
            <td id="terms"></td>
          </tr>
          <tr>
            <td>Rep :</td>
            <td id="rep"></td>
          </tr>
          <tr>
            <td>Ship:</td>
            <td id="shipDate"></td>
          </tr>
        </table>
      </div>
    </div>
    <!-- Main Table -->
    <table style=" height:300px !important;  " id="main-items" class="  table table-borderless ">
      <thead>
        <tr>
          <td style=" border: 1px solid #000 !important;  ">Quantity</td>
          <td style=" border: 1px solid #000 !important;  ">Item Code</td>
          <td style=" border: 1px solid #000 !important;  ">Description</td>
          <td style=" border: 1px solid #000 !important;  ">Rate</td>
          <td style=" border: 1px solid #000 !important;  ">Amount</td>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
        <tr style=" border-top: 1px solid #000 !important; height: 50px !important; " rowspan="3">
          <td style=" border-right: 1px solid #000 !important; " colspan="3">
            <p>All Payments should be written in favor of “Active Solutions" and crossed "A/C Payee" Only. (Not responsible for any other payment method for credit invoices)</p>
          </td>
          <td colspan="2">
            <table style="width:100%;">
              <tr>
                <td><b>Subtotal</b></td>
                <td class="text-end" id="subtotal"></td>
              </tr>
              <tr id="discountArea">
                <td><b>Discount</b></td>
                <td class="text-end" id="discount"></td>
              </tr>
              <tr>
                <td><b>Vat(18%)</b></td>
                <td class="text-end" id="vat"></td>
              </tr>
              <tr>
                <td style="font-size:large;"><b>Total</b></td>
                <td class="text-end" id="total"><b></b></td>
              </tr>
            </table>
          </td>
        </tr>
      </tfoot>
    </table>



    <!-- Main table -->

    <!-- Footer section -->
    <section id="footer">
      <p>Goods Received In Good Condition:</p><br><br>
      <div class="row ">
        <div class="col-3 dots text-center">
          ......................................................................
        </div>
        <div class="col-3 dots text-center">
          .....................................................
        </div>
        <div class="col-3 dots text-center">
          .....................................................
        </div>
        <div class="col-3 dots text-center">
          .....................................................
        </div>
      </div>
      <div class="row">
        <div class="col-3 text-center">
          <p>Customer Signature With Seal</p>
        </div>
        <div class="col-3 text-center">
          <p>Name and ID No</p>
        </div>
        <div class="col-3 text-center">
          <p>Delivered by</p>
        </div>
        <div class="col-3 text-center">
          <p>Authorized Signature</p>
        </div>
      </div>
      <p class="text-center">
        Manufacturer warranty available on all new items for 1 year less 15 Business Days unless specified otherwise.
        Warranty will be void if equipment has been damaged by user or any external factors such as lightning, power surges etc.
        Warranty not applicable for mouse, keyboards, cables, toners and other consumables
      </p>
      <div class="row mb-2 ">
        <div class="col-5 text-center d-flex align-items-center">
          <img src="../../assets/img/icons/main/home.png" width="24" height="24" class="img-fluid rounded-top" alt="" />
          <h6 style="margin: 2px 0px 0px 10px ; ">32/2,-2/1 Nandimithra Place, Colombo 06, Sri Lanka</h6>
        </div>
        <div class="col-2 text-center d-flex align-items-center">
          <img src="../../assets/img/icons/main/phone.png" width="24" height="24" class="img-fluid rounded-top" alt="" />
          <h6 style="margin: 1px 0px 0px 10px ; ">+94 117115200</h6>
        </div>
        <div class="col-3 text-center d-flex align-items-center">
          <img src="../../assets/img/icons/main/mail.png" width="24" height="24" class="img-fluid rounded-top" alt="" />
          <h6 style="margin: 0px 0px 0px 10px ; ">
            <a type="email" href="sales@activelk.com">sales@activelk.com</a>

          </h6>
        </div>
        <div class="col-2 text-center d-flex align-items-center">
          <img src="../../assets/img/icons/main/web.png" width="24" height="24" class="img-fluid rounded-top" alt="" />
          <h6 style="margin: 0px 0px 0px 10px ; ">
            <a href="https://www.activelk.com">www.activelk.com</a>
          </h6>
        </div>
      </div>

    </section>






    <!-- Toast Structure -->
    <div class="overlay d-none" id="toastOverlay"></div>

    <!-- Toast Container in the center -->
    <div class="toast-container toast-container-center d-none" id="toastContainer">
      <!-- Toast HTML structure -->
      <div class="toast" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
        <div class="toast-header">
          <strong class="me-auto">Notification</strong>
          <small>Just now</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          Hello, world! This is a centered toast message.
        </div>
      </div>
    </div>

    <!-- Bootstrap CSS (Optional, for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS and Popper.js (Required for the toast functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Create a new URLSearchParams object using the query string from the URL
        const urlParams = new URLSearchParams(window.location.search);
        // Get the value of the 'id' query parameter
        const urlID = urlParams.get('id');
        if (urlID) {
          // console.log(urlID); // This will log the value of the 'id' parameter from the URL
          fetchInvoice(urlID);
          //  fetchInvoiceItems();

        } else {
          console.log('URL value is not set');
          const message = 'URL value is not set';
          const background = 'bg-danger'
          showToast(message, background);
          // Delay the redirection by 15 seconds (15000 milliseconds)
          setTimeout(() => {
            if (document.referrer) {
              location.href = document.referrer; // Redirect to the previous page
            } else {
              location.href = 'invoice.php'; // Fallback to a default page
            }
          }, 3000); // 15-second delay
        }
      });

      function fetchInvoice(urlID) {
        fetch('../../functions/fetchInvoice.php')
          .then(response => {
            if (!response.ok) {
              throw new Error('Error while fetchin g data');
            }
            return response.json()
          })
          .then(response => {
            fetchInvoiceItems(urlID, response);

          })
          .catch(error => {
            console.log('Error occured while fetching data', error);
          })

      }

      function fetchInvoiceItems(urlID, generalItems) {
        //console.log('generalItems',generalItems);

        fetch('../../functions/fetchInvoiceItems.php')
          .then(response => {
            if (!response.ok) {
              throw new Error('Error in fetching process');
            }
            return response.json();
          })
          .then(response => {
            // console.log('fetched Items',response);
            populateData(urlID, generalItems, response);
          })
          .catch(error => {
            console.log('Error Occured with fetching inv items');
          })
      }

      // Function to insert a line break after every 50 characters, ensuring it's after a space
      function formatDescription(description, maxLineLength = 50) {
        let formattedDescription = '';
        let words = description.split(' '); // Split description by spaces
        let line = '';

        words.forEach(word => {
          if ((line + word).length <= maxLineLength) {
            line += word + ' ';
          } else {
            formattedDescription += line.trim() + '<br>'; // Add line break after 50 characters
            line = word + ' '; // Start new line
          }
        });

        // Append the remaining words to the description
        formattedDescription += line.trim();

        return formattedDescription;
      }

      function populateData(urlID, generalItems, items) {
        // console.log('urlID', urlID);
        // console.log('generalItems', generalItems);
        // console.log('items', items);
        // Filter generalItems where the id matches urlID
        const filteredGeneralData = generalItems.filter(item => item.inv == urlID);
        // Filter items where inv_no matches urlID

        let vatStatus;
        let discount;
        let discountStatus;
        let invFk;
        // console.log('filteredGeneralData', filteredGeneralData);
        filteredGeneralData.forEach(genItem => {

          vatStatus = genItem.vat;
          discount = genItem.discountValue;
          discountStatus = genItem.discountStatus;
          invFk =genItem.id;
        });

        const filteredItems = items.filter(item => item.inv_no == invFk);


        if (filteredItems.length > 0) {
          const tbody = document.querySelector('#main-items tbody');
          tbody.innerHTML = ''; // Clear existing rows

          let subtotalValue = 0; // Initialize subtotal
          let fetchedVat = 0;
          let subtotal = document.getElementById('subtotal');
          let discountElemnt = document.getElementById('discount');
          let vat = document.getElementById('vat');
          let total = document.getElementById('total');
          const itemCount = filteredItems.length;
          unitDiscount = parseFloat(discount)/itemCount;



          filteredItems.forEach(item => {
            const row = document.createElement('tr');
            let quantity;
            let itemCode;
            let description;
            let unitPrice;
            let rowTotal;

            // console.log('vatStatus',vatStatus);
            if (vatStatus == 'yes') {
              console.log('yes');
              quantity = item.qt;
              itemCode = item.item_code;
              description = item.description;
              unitPrice = parseFloat(item.unit_price) + parseFloat(item.vat);
              rowTotal = item.total;
              if (discountStatus == 'hide') {
                document.getElementById('discountArea').hidden=true;
                //discountElemnt.textContent = discount;
                console.log('unitDiscount', unitDiscount);
               // console.log('discountStatus', discountStatus);
               unitPrice -= unitDiscount;
              }
              else{
                discountElemnt.textContent = discount;
                console.log('item Discount', discount);
                console.log('discountStatus', discountStatus);
              }

              rowTotal = unitPrice * quantity;
              subtotalValue += parseFloat(rowTotal);


              // Sum up the total value for each item
              subtotalValue += parseFloat(item.total);
              fetchedVat = (parseFloat(item.vat)) / 100;
              // Assign the calculated subtotal to the respective element
              subtotal.textContent = formatCurrency(subtotalValue); // Ensure 2 decimal places
              // You can also calculate VAT and total here if needed
              let vatValue = subtotalValue * fetchedVat; // For example, 15% VAT
              let totalValue = subtotalValue + vatValue;
              vat.textContent = formatCurrency(vatValue); // Assign VAT value
              total.textContent = formatCurrency(totalValue); // Assign total value

             



            } else if (vatStatus == 'no') {

              quantity = item.qt;
              itemCode = item.item_code;
              description = item.description;
              rowVat = parseFloat(item.unit_price) * (parseFloat(item.vat) / 100);
              unitPrice = parseFloat(item.unit_price) + rowVat;
              rowTotal = unitPrice * quantity;
              subtotalValue += parseFloat(rowTotal);
              discount.value = item.discount;
              total.textContent = formatCurrency(subtotalValue); // Assign total value

            }

            row.innerHTML = `
                <td style="border-right: 1px solid #000 !important;">${quantity}</td>
                <td style="border-right: 1px solid #000 !important;">${itemCode}</td>
                <td style="border-right: 1px solid #000 !important;">${description.replace(/\n/g, '<br>')}</td>
                <td style="border-right: 1px solid #000 !important; text-align:end; ">${formatCurrency(unitPrice)}</td>
                <td class="text-end" >${formatCurrency(rowTotal)}</td>
            `;
            tbody.appendChild(row);


            // console.log('fetchedVat',fetchedVat);
          });


        }


        if (filteredGeneralData.length > 0) {
          const billToElement = document.getElementById('billTo');
          billToElement.innerHTML = filteredGeneralData[0].customer || '';

          const date = document.getElementById('date');
          date.innerHTML = filteredGeneralData[0].inv_date || '';

          const invNo = document.getElementById('invNo');
          invNo.innerHTML = 'AS000' + filteredGeneralData[0].id || '';

          const poNo = document.getElementById('poNo');
          poNo.innerHTML = filteredGeneralData[0].po_num || '';

          const terms = document.getElementById('terms');
          terms.innerHTML = filteredGeneralData[0].terms || '';

          const rep = document.getElementById('rep');
          rep.innerHTML = filteredGeneralData[0].rep || '';

          const shipDate = document.getElementById('shipDate');
          shipDate.innerHTML = filteredGeneralData[0].shipping_date || '';
        } else {
          message = 'There is no item to display..';
          background = 'bg-danger'
          showToast(message, background);
          window.location.href = './invoice_new.php'
        }
      }

      function formatCurrency(value) {
        return parseFloat(value).toLocaleString('en-US', {
          style: 'decimal',
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      }

      // Function to show toast
      function showToast(message, background) {
        // Set the toast message dynamically
        const toastBody = document.querySelector('#liveToast .toast-body');
        toastBody.textContent = message;
        toastBody.classList.add(background); // Add the background class passed as a parameter


        // Display the overlay and blur the background
        document.getElementById('toastOverlay').classList.remove('d-none');
        document.getElementById('toastContainer').classList.remove('d-none');
        document.body.classList.add('blurred');

        // Initialize and show the toast
        const toastElement = document.getElementById('liveToast');
        const toastBootstrap = new bootstrap.Toast(toastElement);

        toastBootstrap.show();

        // When toast is hidden, remove the overlay and restore page
        toastElement.addEventListener('hidden.bs.toast', () => {
          document.getElementById('toastOverlay').classList.add('d-none');
          document.getElementById('toastContainer').classList.add('d-none');
          document.body.classList.remove('blurred');
        });
      }
    </script>


</body>

</html>