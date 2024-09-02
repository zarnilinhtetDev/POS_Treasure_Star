<!DOCTYPE html>
<HTML>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        input {
            position: relative;
            width: 150px;
            height: 40px;
            color: white;
        }

        input:before {
            position: absolute;
            top: 3px;
            left: 3px;
            content: attr(data-date);
            display: inline-block;
            color: black;
        }

        input::-webkit-datetime-edit,
        input::-webkit-inner-spin-button,
        input::-webkit-clear-button {
            display: none;
        }

        input::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 3px;
            right: 0;
            color: black;
            opacity: 1;
        }
    </style>
</head>

<body>

    <div class="container mx-auto">
        <div class="row">
            <div class="col-6">
            </div>
            <div class="gap-2 pt-2 col-6 d-flex align-items-center justify-content-end">
                <a href="javascript:void(0);"
                    onclick="printReceipt({{ json_encode($invoice) }},{{ json_encode($sells) }})"
                    class="btn btn-primary" style="border-radius:10px;"><i class="fa-solid fa-print"></i> Print</a>
                <a href="{{ url('daily_sales') }}" class="btn btn-primary" style="border-radius:10px;"><i
                        class="fa-regular fa-calendar-days"></i> Daily Sales</a>
                <a href="{{ url('pos_register') }}" class="text-white btn btn-primary" style="border-radius:10px;"><i
                        class="fa-solid fa-circle-plus"></i> POS Register</a>
            </div>
        </div>
        @foreach ($profile as $pic)
            @if ($invoice->branch == $pic->branch)
                <div class="mt-5 text-center">
                    <img src="{{ asset('logos/' . ($pic->logos ?? 'null')) }}" width="180" height="120">

                </div>
                <div class="row" style="margin-top: 30px;">
                    <h4 class="text-center fw-bold">{{ $pic->name }}</h4>

                    <p class="text-center fw-bold" style="font-size: 14px;">
                        {{ $pic->address }}
                        <br>
                        {{ $pic->phno1 }}, {{ $pic->phno2 }}
                    </p>
                </div>
            @endif
        @endforeach
        <div class="mt-3 row">
            <h6 class="text-center">Sales Receipt<br>
                <?= $currentDate = date('d-m-Y') ?></h6>
        </div>

        <div class="mt-3 row">
            <p class="fw-bold" style="font-size: 12px;">Sale ID: {{ $invoice->invoice_no }}
                <br>Employee :
                {{ auth()->user()->name }}
                <br>Customer :
                {{ $invoice->customer_name }}
                <br>Phone :
                {{ $invoice->phno }}
                <br>Address :
                {{ $invoice->address }}
            </p>

            <div class="mt-1 table-responsive">
                <table class="mt-1" style="font-size: 14px;width:100%">
                    <thead>
                        <tr class="text-left">
                            <th style="width: 25%;">Item Name.</th>
                            <th style="width: 25%;">Price</th>
                            <th style="width: 25%;">Quantity</th>
                            <th class="text-end" style="width: 10%;">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="height:30px">

                        @foreach ($invoices as $invoice)
                            @foreach ($invoice->sells as $key => $sell)
                                <tr class="text-start">
                                    <td>{{ $sell->part_number }}</td>
                                    <td>
                                        @if ($invoice->sale_price_category == 'Default')
                                            @if ($invoice->type == 'Whole Sale')
                                                {{ $sell->product_price }}
                                            @else
                                                {{ $sell->retail_price }}
                                            @endif
                                        @elseif ($invoice->sale_price_category == 'Whole Sale')
                                            {{ $sell->product_price }}
                                        @elseif ($invoice->sale_price_category == 'Retail')
                                            {{ $sell->retail_price }}
                                        @else
                                            {{ $sell->retail_price }}
                                        @endif
                                    </td>
                                    <td>{{ $sell->product_qty }}</td>
                                    <td class="text-end">
                                        @if ($invoice->sale_price_category == 'Default')
                                            @if ($invoice->type == 'Whole Sale')
                                                {{ $sell->product_price * $sell->product_qty }}
                                            @else
                                                {{ $sell->retail_price * $sell->product_qty }}
                                            @endif
                                        @elseif ($invoice->sale_price_category == 'Whole Sale')
                                            {{ $sell->product_price * $sell->product_qty }}
                                        @elseif ($invoice->sale_price_category == 'Retail')
                                            {{ $sell->retail_price * $sell->product_qty }}
                                        @else
                                            {{ $sell->retail_price * $sell->product_qty }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot style="border-top: 2px solid black !important;">
                        <!-- <tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Sub Total</td>
                            <td class="text-end fw-bold">{{ $invoice->net_total ?? 0 }}</td>
                        </tr> -->
                        <!-- <tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Discount</td>
                            <td class="text-end fw-bold">{{ $invoice->discount_total ?? 0 }}</td>
                        </tr>-->
                        <tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">{{ $invoice->total ?? 0 }}</td>
                        </tr>
                        <tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Discount</td>
                            <td class="text-end fw-bold">{{ $invoice->discount_total ?? 0 }}</td>
                        </tr>
                        <tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Cash</td>
                            <td class="text-end fw-bold">{{ $invoice->deposit ?? 0 }}</td>
                        </tr>
                        <tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Change Due</td>
                            <td class="text-end fw-bold">{{ $invoice->remain_balance ?? 0 }}</td>
                        </tr>
                        <!--<tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Deposit </td>
                            <td class="text-end fw-bold">{{ $invoice->deposit ?? 0 }}</td>
                        </tr>
                        <tr style="line-height: 30px;">
                            <td colspan="3" class="text-end fw-bold">Remaining Balance </td>
                            <td class="text-end fw-bold">{{ $invoice->remain_balance ?? 0 }}</td>
                        </tr> -->
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

    <script>
        function printReceipt(invoice, sells) {

            var tableBody = '';
            var itemPrice = ''; // Variable to store table body HTML
            let total = '';
            var name = invoice.customer_name ? `Name: ${invoice.customer_name}` : '';

            // Assuming invoice.items is an array of items
            sells.forEach(function(item) {

                if (invoice.sale_price_category == 'Default') {
                    if (invoice.type == 'Whole Sale') {
                        itemPrice = item.product_price
                        total = item.product_price * item.product_qty
                    } else {
                        itemPrice = item.retail_price
                        total = item.retail_price * item.product_qty
                    }
                } else if (invoice.sale_price_category == 'Whole Sale') {
                    itemPrice = item.product_price
                    total = item.product_price * item.product_qty
                } else if (invoice.sale_price_category == 'Retail') {
                    itemPrice = item.retail_price
                    total = item.retail_price * item.product_qty
                } else {
                    itemPrice = item.retail_price;
                    total = item.retail_price * item.product_qty
                }

                tableBody += `
        <tr>
            <td><span style="font-size:12px !important;line-height:30px; width: 15% !important;">${item.part_number}</span></td>
            <td class="aligncenter"><span style="font-size:12px !important;line-height:30px; width: 30% !important;">${itemPrice}</span></td>
            <td class="aligncenter"><span style="font-size:12px !important;line-height:30px; width: 25% !important;">${item.product_qty}</span></td>
            <td class="alignright"><span style="font-size:12px !important;line-height:30px;width: 25% !important;">${total}</span></td>
        </tr>
    `;
            });

            var data = `
<style>
    /* -------------------------------------
    GLOBAL
    A very basic CSS reset
------------------------------------- */
* {
    margin: 0;
    padding: 0;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    font-size: 14px;
}
img {
    max-width: 100%;
}
body {
    -webkit-font-smoothing: antialiased;
    -webkit-text-size-adjust: none;
    width: 100% !important;
    height: 100%;
    line-height: 1.6;
}
/* Let's make sure all tables have defaults */
table td {
    vertical-align: top;
}
body {
    background-color: #F6F6F6;
}
.body-wrap {
    background-color: #F6F6F6;
    width: 100%;
}
.container {
    display: block !important;
    max-width: 600px !important;
    margin: 0 auto !important;
    /* makes it centered */
    clear: both !important;
}
.content {
    max-width: 800px;
    margin: 0;
    display: block;
    padding: 5px;
}
.main {
    background: #fff;
    border: 1px solid #E9E9E9;
    border-radius: 3px;
}
.content-wrap {
    padding: 0px;
}
.content-block {
    padding: 0 0 10px;
}
.footer {
    width: 100%;
    clear: both;
    color: #999;
    padding: 20px;
}
.footer a {
    color: #999;
}
.footer p, .footer a, .footer unsubscribe, .footer td {
    font-size: 12px;
}
/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1, h2, h3 {
    font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
    color: #000;
    margin: 40px 0 0;
    line-height: 1.2;
    font-weight: 400;
}
h1 {
    font-size: 32px;
    font-weight: 500;
}
h2 {
    font-size: 24px;
}
h3 {
    font-size: 18px;
}
h4 {
    font-size: 14px;
    font-weight: 600;
}
p, ul, ol {
    margin-bottom: 10px;
    font-weight: normal;
}
p li, ul li, ol li {
    margin-left: 5px;
    list-style-position: inside;
}
/* -------------------------------------
    LINKS & BUTTONS
------------------------------------- */
a {
    color: #1AB394;
    text-decoration: underline;
}
.btn-primary {
    text-decoration: none;
    color: #FFF;
    background-color: #1AB394;
    border: solid #1AB394;
    border-width: 5px 10px;
    line-height: 2;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    display: inline-block;
    border-radius: 5px;
    text-transform: capitalize;
}
/* -------------------------------------
    OTHER STYLES THAT MIGHT BE USEFUL
------------------------------------- */
.last {
    margin-bottom: 0;
}
.first {
    margin-top: 0;
}
.aligncenter {
    text-align: center;
}
.alignright {
    text-align: right;
}
.alignleft {
    text-align: left;
}
.clear {
    clear: both;
}
.alert {
    font-size: 16px;
    color: #fff;
    font-weight: 500;
    padding: 20px;
    text-align: center;
}
.alert a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
}
.alert.alert-warning {
    background: #F8AC59;
}
.alert.alert-bad {
    background: #ED5565;
}
.alert.alert-good {
    background: #1AB394;
}
.invoice {
    margin: 0;
    text-align: left;
    width: 100%;
}
.invoice td {
    padding:0;
}
.invoice .invoice-items {
    width: 100%;
}
.invoice .invoice-items td {
    font-size:18px;
    border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
    border-top: 2px solid #333;
    // border-bottom: 2px solid #333;
    font-weight: 700;
}

.invoice .invoice-items .noborder {
    border: 0px;
}

.invoice .invoice-items .noborder td {
    border: 0px;
    font-weight: 700;
}
/* -------------------------------------
    RESPONSIVE AND MOBILE FRIENDLY STYLES
------------------------------------- */
@media only screen and (max-width: 640px) {
    h1, h2, h3, h4 {
        font-weight: 600 !important;
        margin: 20px 0 5px !important;
    }
    h1 {
        font-size: 22px !important;
    }
    h2 {
        font-size: 18px !important;
    }
    h3 {
        font-size: 16px !important;
    }
    .container {
        width: 100% !important;
    }
    .content, .content-wrap {
        padding: 10px !important;
    }
    .invoice {
        width: 100% !important;
    }
}
</style>
            <table style="margin-top:-60px">
    <tbody style="width:180px"><tr>
        <td></td>
        <td class="container" >
            <div class="content">
                <table >
                    <tbody><tr>
                        <td class="content-wrap aligncenter">
                            <table >
                                <tbody><tr>
                    <h3 style="font-weight:bold;margin-bottom:10px;">သရဖူစတိုး</h3>
                    <p style="font-size:13px">  အမှတ်(၃)၊ လမ်းမတော်လမ်း၊အနောက်ရပ်၊သီပေါမြို့။<br>
                        09453131493 , 09679007355 , 09421099135</p>


                                </tr>
                                <tr>
                                    <h6 style="margin-top:20px !important;">Sales Receipt</h6>
                                    <h6>${moment().format("DD-MM-YYYY")}</h6>
                                </tr>
                                <tr style="margin-right:20px">
                                        <table class="invoice">
                                            <tbody><tr>
                                                <td style="font-size:13px; line-height:26px;">
                                                ပြေစာအမှတ်: ${ invoice.invoice_no } <br>
                                          Cashier: {{ auth()->user()->name }}<br>
                                          <br>
                                          </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table  class="invoice-items" style="margin-top:0px !important;">
                                                        <thead>
                                                        <tr>
                                                            <th class="alignleft" style="50%"><span style="font-size:12px !important;line-height:10px">Item Name</span></th>
                                                            <th class="aligncenter"><span style="font-size:12px !important;line-height:10px">Price</span>
                                                            </th>
                                                            <th class="aligncenter"><span style="font-size:12px !important;line-height:10px">Quantity</span></ths>
                                                            <th class="alignright"><span style="font-size:12px !important;line-height:10px">Total</span>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tablebody">
                                                        ${tableBody}
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="total">
                                                                <td colspan="3" class="alignright" width="80%"><span style="font-size:13px !important;line-height:30px">Total</span></td>
                                                                <td class="alignright"><span style="font-size:13px !important;line-height:30px;">${invoice.total ?? 0}</span>
                                                            </tr><tr class="noborder">
                                                                <td colspan="3" class="alignright" width="80%"><span style="font-size:13px !important;line-height:30px">Discount</span></td>
                                                                <td class="alignright"><span style="font-size:13px !important;line-height:30px;">${invoice.discount_total ?? 0}</span>
                                                            </tr><tr class="noborder">
                                                                <td colspan="3" class="alignright" width="80%"><span style="font-size:13px !important;line-height:30px">Cash</span></td>
                                                                <td class="alignright"><span style="font-size:13px !important;line-height:30px;">${invoice.deposit ?? 0}</span>
                                                            </tr> <tr class="noborder">
                                                                <td colspan="3" class="alignright" width="80%"><span style="font-size:13px !important;line-height:30px">Change Due</span></td>
                                                                <td class="alignright"><span style="font-size:13px !important;line-height:30px;">${invoice.remain_balance ?? 0}</span>
                                                            </tr>

                                                       </tfoot></table>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
        </td>
        <td></td>
    </tr>
</tbody></table>
    `;
            var printWindow = window.open("", "", "width=800,height=600");
            printWindow.document.write(
                "<html><head><title>Receipt</title></head><body>"
            );
            printWindow.document.write("<pre>" + data + "</pre>"); // Using <pre> to preserve formatting
            printWindow.document.write("</body></html>");
            printWindow.document.close();
            // Wait for content to load before printing
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>


</body>

</HTML>
