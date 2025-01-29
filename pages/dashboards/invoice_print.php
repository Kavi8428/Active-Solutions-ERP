<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>NEW INVOICE</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <style>
        img{
            max-width: 100px;
            height: auto;
        }
        table{
            border: 1px;
            border-style: solid;
            border-color: black;
            font-size: smaller;
        }
        label{
            font-size: smaller;
        }
    </style>
    <body>
    <div class="container">
        <section class="head" >
            <div class="row">
                <div class="col-3">
                    <img class="img" src="../../assets/img/activeLogo.png" alt="">
                </div>
            </div>
            <div class="row">
                <div class="col-9"> 
                </div>
                <div class="col-3 text-end ">
                    <h6>Tax Invoice</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label>Our VAT Reg No. 742471004 - 7000</label>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <table class=" w-100 h-100 table-bordered ">
                        <thead>
                            <th>Bill To</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    
                                    <select name="" id="input" class="form-control" required="required">
                                        <option value="">SELECT</option>
                                    </select>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-3">
                    
                </div>
                <div class="col-3 text-end ">
                    <table class=" table-bordered ">
                        <tbody>
                            <tr>
                                <td>Date</td>
                                <td><input class=" border-0 " type="date" id="date" ></td>
                            </tr>
                            <tr>
                                <td>Invoice #</td>
                                <td><input class=" border-0 " type="text" id="invNumber" ></td>
                            </tr>
                            <tr>
                                <td>P.O.Number</td>
                                <td><input class=" border-0 " type="text" id="poNumber" ></td>
                            </tr>
                            <tr>
                                <td>Terms</td>
                                <td><input class=" border-0 " type="text" id="terms" ></td>
                            </tr>
                            <tr>
                                <td>Rep </td>
                                <td><input class=" border-0 " type="text" id="rep" ></td>
                            </tr>
                            <tr>
                                <td>Ship</td>
                                <td><input class=" border-0 " type="text" id="ship" ></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>  
    



    </div>
        <script src="" async defer></script>
    </body>
</html>