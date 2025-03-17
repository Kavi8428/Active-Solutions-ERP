<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tax Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/invoice_view.css">
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
    <script src="./js/invoice_view.js"></script>


    


</body>

</html>