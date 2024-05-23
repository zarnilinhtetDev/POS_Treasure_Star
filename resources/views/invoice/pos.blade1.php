<!DOCTYPE html>
<HTML>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
            top: 6px;
            left: 6px;
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
            top: 6px;
            right: 0;
            color: black;
            opacity: 1;
        }
    </style>

</head>

<body>

    <div class="container-fluid" id="content">
        <h1 class="mt-3">
            POS
        </h1>
        <form method="post" id="myForm" action="{{ url('invoice_register') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6"><img src="{{ asset('image/prime.png') }}" alt="logo" style="width:200px;">
                    <div style="font-size: 18px;margin-left: 25px;">
                        <p>
                            <span style="font-weight:bolder">Shwe Mann Pharmacy<br>
                                No.286, Kyaik Ka San Road, Tarmwe Township,<br>
                                Yangon, Myanmar.<br>
                                <span> Phone: 09-740867976</span>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="mt-4 col-md-3">

                    <label for="date" style="font-weight:bolder">Invoice Number</label>
                    <input type="text" id="invoice_no" class="form-control" name="invoice_no" value="{{ $invoice_no }}" readonly>
                    <label for="date" class="" style="font-weight:bolder">Date</label>

                    <input type="date" name="invoice_date" class="form-control " max="{{ date('Y-m-d') }}" required><label for="date" style="font-weight:bolder">Invoice Category</label>
                    <select name="quote_category" id="quote_category" class="form-control" required>
                        <option value="" selected disabled>Select Category</option>
                        <option value="Invoice">Invoice</option>
                        <option value="POS">POS</option>
                    </select>
                    <label for="overdue" class="mt-1 caption" style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>


                    <input type="date" name="overdue_date" id="overdue_date" class="form-control round " autocomplete="off" min="<?= date('Y-m-d') ?>" required>
                    <label for="payment" style="font-weight:bolder">{{ trans('Payment Method') }}
                    </label>

                    <select class="mb-4 form-control round" aria-label="Default select example" name="payment_method" required>
                        <option selected disabled>Select Payment Method</option>
                        <option value="Cash">Cash</option>
                        <option value="Credit">Credit</option>
                        <option value="Consignment Terms">Consignment Terms</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="payment" style="font-weight:bolder">{{ trans('Sale Price Category') }}
                    </label>
                    <select class="mb-4 form-control round " aria-label="Default select example" name="sale_price_category" id="sale_price_category" required>

                        <option value="Default" selected>Default</option>
                        <option value="Whole Sale">Whole Sale</option>
                        <option value="Retail">Retail</option>
                    </select>
                </div>



                <div class="content-wrapper">
                    <div class="content-body">
                        <div class="card">
                            <div class="card-content">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-6 cmp-pnl">
                                            <div id="customerpanel" class="inner-cmp-pnl">

                                                <div class="form-group row">
                                                    <div class="frmSearch col-sm-4">
                                                        <div class="frmSearch col-sm-12">
                                                            <span style="font-weight:bolder">
                                                                <label for="cst" class="caption">{{ trans('Search With Customer Name & Phone Number') }}</label>
                                                            </span>
                                                            <input type="text" id="customer" name="customer" class="form-control round" autocomplete="off">

                                                            <button type="submit" class="mt-3 btn btn-primary" id="customer_search">Add</button>

                                                            <div id="customer-box-result"></div>
                                                        </div>


                                                    </div>

                                                    <div class="frmSearch col-md-4">
                                                        <div class="frmSearch col-sm-12">
                                                            <span style="font-weight:bolder">
                                                                <label for="cst" class="caption">{{ trans('Search Item Name ') }}<br>&nbsp;</label>
                                                            </span>
                                                            <input type="text" class="form-control productname typeahead" name="itemname" id='productname' autocomplete="off">

                                                            <div id="customer-box-result"></div>
                                                        </div>
                                                    </div>
                                                    <div class="frmSearch col-md-4">
                                                        <div class="frmSearch col-sm-12">
                                                            <span style="font-weight:bolder">
                                                                <label for="cst" class="caption">{{ trans('Search Item Barcode') }}<br>&nbsp;</label>
                                                            </span>
                                                            <input type="text" class="form-control productname typeahead" name="barcode" id='barcode' autocomplete="off">
                                                            <div id="customer-box-result"></div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="service_id" name="service_id" value="0">


                                                    <input type="hidden" name="manager_type" value="{{ Auth::user()->type }}">

                                                    <input type="text" name="status" class="form-control" value="draft" style="display: none">

                                                </div>
                                            </div>
                                            <div class="col-sm-6 cmp-pnl">

                                                <div class="inner-cmp-pnl">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row table-responsive " style="margin-top:1vh;">
                                            <table class="table table-bordered">
                                                <thead style="background-color:#0047AA;color:white;">
                                                    <tr class="item_header bg-gradient-directional-blue white">
                                                        <th class="text-center" style="width: 13%;">
                                                            {{ trans('Name') }}
                                                        </th>
                                                        <th class="text-center" style="width: 13%;">
                                                            {{ trans('Phone Number') }}
                                                        </th>
                                                        <th class="text-center" style="width: 18%;">
                                                            {{ trans('Customer Type') }}
                                                        </th>
                                                        <th class="text-center" style="width: 13%;">
                                                            {{ trans('Address') }}
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="item_header bg-gradient-directional-blue white">
                                                        <td class="text-center"><input type='text' name='customer_name' id="name" class="form-control"></td>
                                                        <input type='hidden' name='customer_id' id="customer_id" class="form-control">
                                                        <input type='hidden' name='status' id="status" class="form-control" value="pos">
                                                        <td class="text-center"><input type='text' name='phno' id="phone_no" class="form-control"></td>
                                                        <td class="text-center"><input type='text' name='type' id="type" class="form-control"></td>
                                                        <td class="text-center"><input type='text' name='address' class="form-control" id="address"></td>
                                                    </tr>
                                                </tbody>
                                            </table>


                                        </div>



                                        <div class="row table-responsive " style="margin-top:1vh;">
                                            <!-- <table class="table-responsive tfr my_stripe"> -->
                                            <table class="table table-bordered">
                                                <thead style="background-color:#0047AA;color:white;">
                                                    <tr class="item_header bg-gradient-directional-blue white" style="margin-bottom:10px;">
                                                        <th width="5%" class="text-center">{{ trans('No') }}
                                                        </th>
                                                        <th width="18%" class="text-center">
                                                            {{ trans('Item Name') }}
                                                        </th>
                                                        <th width="23%" class="text-center">
                                                            {{ trans('Descriptions') }}
                                                        </th>
                                                        <th width="8%" class="text-center">
                                                            {{ trans('Qty') }}
                                                        </th>
                                                        <th width="10%" class="text-center">{{ trans('Unit') }}
                                                        </th>

                                                        <th width="9%" class="text-center">
                                                            {{ trans('Whole Sale Price') }}
                                                        </th>
                                                        <th width="9%" class="text-center">
                                                            {{ trans('Retail Price') }}
                                                        </th>
                                                        <th width="9%" class="text-center">
                                                            {{ trans('Expire Date') }}
                                                        </th>

                                                        <!-- <th width="10%" class="text-center">
                                                        {{ trans('Discounts (%)') }}
                                                    </th> -->

                                                        <th width="14%" class="text-center">{{ trans('Amount') }}
                                                            ({{ config('currency.symbol') }})
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody id="showitem123">
                                                    <tr>
                                                        <input type="hidden" class="form-control barcode typeahead" name="barcode[]" value="{{ old('barcode') }}" placeholder="{{ trans('Enter BarCode') }}" id='barcode-0' autocomplete="off">

                                                        <td class="text-center" id="count">1</td>
                                                        <td><input type="text" class="form-control productname typeahead" name="part_number[]" value="{{ old('part_number') }}" placeholder="{{ trans('Enter Part Number') }}" id='item_name-0' autocomplete="off">
                                                        </td>

                                                        <td><input type="text" class="form-control description typeahead" value="{{ old('part_description') }}" name="part_description[]" placeholder="{{ trans('') }}" id='description-0' autocomplete="off"></td>
                                                        <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0" autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>
                                                        <td><input type="text" class="form-control unit" name="unit[]" id="item_unit-0">

                                                        </td>
                                                        <td><input type="text" class="form-control price" name="product_price[]" id="price-0" autocomplete="off" value="0">
                                                        </td>
                                                        <td><input type="text" class="form-control retail_price" name="retail_price[]" id="retail_price-0" autocomplete="off" value="0">
                                                        </td>
                                                        <td><input type="text" class="form-control exp_date " name="exp_date[]" id="exp_date-0" autocomplete="off">
                                                        </td>
                                                        <!-- <td><input type="text" class="form-control vat " name="discount[]" id="vat-0" autocomplete="off" value="{{ old('discount') }}">
                                                    </td> -->

                                                        <td style="text-align:center">
                                                            <span class='ttlText' id="foc-0"></span>
                                                            <span class="currenty">{{ config('currency.symbol') }}</span>
                                                            <strong>
                                                                <span class='ttlText' id="result-0"></span>
                                                            </strong>
                                                        </td>
                                                        <input type="hidden" class="form-control vat " name="product_tax[]" id="vat-0" value="0">
                                                        <input type="hidden" name="total_tax[]" id="taxa-0" value="0">
                                                        <input type="hidden" name="total_discount[]" id="disca-0" value="0">
                                                        <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                                        <input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">
                                                        <input type="hidden" attr-org="" name="unit[]" id="unit-0" value="">
                                                        <input type="hidden" name="unit_m[]" id="unit_m-0" value="1">
                                                        <input type="hidden" name="code[]" id="hsn-0" value="">
                                                        <input type="hidden" name="serial[]" id="serial-0" value="">
                                                        {{-- <td></td> --}}
                                                    </tr>
                                                </tbody>

                                            </table>

                                            <table class="mt-3">
                                                <tbody id="showitem">




                                                    <tr style="display: table-row;">
                                                        <td></td>
                                                        <td colspan="">

                                                        </td>



                                                    </tr>


                                                    <tr class="last-item-row sub_c">
                                                        <td></td>
                                                        <td class="add-row">
                                                            <button type="button" class="btn btn-success" id="addproduct" style="margin-top:20px;margin-bottom:20px;display:none">
                                                                <i class="fa fa-plus-square"></i>
                                                                {{ trans('Add row') }}
                                                            </button>
                                                            <button type="button" class="btn btn-primary" id="calculate">
                                                                Calculate
                                                            </button>

                                                            <a href="{{ URL('items') }}" target="_blank" id="item_search">
                                                                <button type="button" class="btn btn-success">
                                                                    <i class="fa fa-plus-square"></i> Item Search
                                                                </button></a>




                                                        </td>
                                                        <td colspan="6"></td>
                                                        <br><br>

                                                    </tr>


                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">
                                                            @if (isset($employees[0]))
                                                            {{ trans('general.employee') }}
                                                            <select name="user_id" class="selectpicker form-control">
                                                                <option value="{{ $logged_in_user->id }}">
                                                                    {{ $logged_in_user->first_name }}
                                                                </option>
                                                                @foreach ($employees as $employee)
                                                                <option value="{{ $employee->id }}">
                                                                    {{ $employee->first_name }}
                                                                    {{ $employee->last_name }}
                                                                </option>
                                                                @endforeach

                                                            </select>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td>

                                                        </td>
                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Total Amount
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input type="text" name="total" class="form-control" id="invoiceyoghtml" readonly style="background-color: #E9ECEF">

                                                        </td>

                                                    </tr>



                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Deposit
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2"><input type="text" name="paid" class="form-control" id="paid" onchange="paidFunction()">

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Remaining Balance
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2"><input type="text" name="balance" class="form-control" id="balance" readonly="">

                                                        </td>
                                                    </tr>

                                                    <tr class="sub_c " style="display: table-row;">
                                                        <td colspan="12"> <label for="remark">Remark</label>
                                                            <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                                        </td>
                                                    </tr>
                                                    <tr class="sub_c " style="display: table-row;">


                                                        <td align="right" colspan="9">

                                                            <button id="submitButton" class="mt-3 btn btn-danger" type="submit">Save</button>


                                                            <a href="{{ url('create_vehicle') }}" type="submit" class="mt-3 btn btn-warning">Cancel
                                                            </a>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>

        </form>

        <script>
            $(document).ready(function() {
                let count = 0;

                // Search item name suggestion (get item name from db)
                function initializeTypeahead() {
                    $('#productname').typeahead({
                        source: function(query, process) {
                            return $.ajax({
                                url: "{{ route('autocomplete.part-code-invoice') }}",
                                method: 'POST',
                                data: {
                                    query: query
                                },
                                dataType: 'json',
                                success: function(data) {
                                    console.log(data);
                                    process(data);
                                },
                                error: function(error) {
                                    console.error(error);
                                }
                            });
                        },

                    });

                    $('#barcode').typeahead({
                        source: function(query, process) {
                            return $.ajax({
                                url: "{{ route('autocomplete.barcode-invoice') }}",
                                method: 'POST',
                                data: {
                                    query: query
                                },
                                dataType: 'json',
                                success: function(data) {
                                    console.log(data);
                                    process(data);
                                },
                                error: function(error) {
                                    console.error(error);
                                }
                            });
                        },

                    });
                }



                function updateItemName(item) {
                    // Check if the table body is empty

                    if ($("#item_name-0").val() === "") {
                        let cuz_name = $("#type").val();
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('get.part.data-invoice') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                itemname: item
                            },
                            success: function(data) {
                                $("#item_name-0").val(data['item']['item_name']);
                                $("#description-0").val(data['item']['descriptions']);
                                $("#exp_date-0").val(data['item']['expired_date']);
                                $("#barcode-0").val(data['item']['barcode']);
                                $("#price-0").val(data['item']['wholesale_price']);

                                $("#item_unit-0").val(data['item']['unit']);
                                $("#retail_price-0").val(data['item']['retail_price']);
                                // let priceValue = cuz_name === "Whole Sale" ? data['item'][
                                //     'wholesale_price'
                                // ] : data['item']['retail_price'];
                                // $("#price-0").val(priceValue);
                                if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                                    alert(data.quantity + " quantity!");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('get.barcode.data-invoice') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                barcode: item
                            },
                            success: function(data) {
                                $("#item_name-0").val(data['item']['item_name']);
                                $("#description-0").val(data['item']['descriptions']);
                                $("#exp_date-0").val(data['item']['expired_date']);
                                $("#barcode-0").val(data['item']['barcode']);
                                $("#item_unit-0").val(data['item']['unit']);
                                $("#price-0").val(data['item']['wholesale_price']);
                                $("#retail_price-0").val(data['item']['retail_price']);
                                // let priceValue = cuz_name === "Whole Sale" ? data['item'][
                                //     'wholesale_price'
                                // ] : data['item']['retail_price'];
                                // $("#price-0").val(priceValue);
                                if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                                    alert(data.quantity + " quantity!");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }

                        });

                    } else {

                        if ($("#item_name-0").val() === $("#productname").val() || $("#barcode-0").val() === $(
                                "#barcode").val()) {

                            var existingRow = $("#amount-0");

                            var currentQuantity = parseInt(existingRow.val());
                            existingRow.val(currentQuantity + 1);


                        } else {
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('get.part.data-invoice') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    itemname: item
                                },
                                success: function(data) {
                                    if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                                        alert(data.quantity + " quantity!");
                                    }
                                    addNewRow(data['item']);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });

                            $.ajax({
                                type: 'POST',
                                url: "{{ route('get.barcode.data-invoice') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    barcode: item
                                },
                                success: function(data) {
                                    if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                                        alert(data.quantity + " quantity!");
                                    }
                                    addNewRow(data['item']);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        }
                        // If not empty, proceed with the AJAX calls as before

                    }


                }



                initializeTypeahead();


                function addNewRow(item) {
                    let cuz_name = $("#type").val();
                    let existingRow = $("#showitem123 input.productname[value='" + item['item_name'] + "']")
                        .closest(
                            'tr');
                    if (existingRow.length > 0) {
                        // Item already exists, update quantity
                        let qtyInput = existingRow.find('.req.amnt');
                        let currentQty = parseInt(qtyInput.val()) || 0;
                        qtyInput.val(currentQty + 1);
                        // Recalculate totals
                    } else {
                        $.ajax({

                            type: 'GET',
                            url: "{{ route('get.part.data-unit') }}",
                            data: {
                                // _token: "{{ csrf_token() }}",

                            },
                            success: function(data) {


                                var selectBox = document.getElementById("unit-" + count);

                                // Loop through the data array
                                data.forEach(function(item) {
                                    // Create an option element
                                    var option = document.createElement("option");

                                    // Set the value attribute to the unit id
                                    option.value = item.unit;

                                    // Set the text of the option to the unit name
                                    option.text = item.unit;

                                    // Append the option to the select element
                                    selectBox.appendChild(option);
                                });


                            },
                            error: function(error) {
                                console.error(error);
                            }
                        });
                        count++;
                        let rowCount = $("#showitem123 tr").length;
                        let newRow = '<tr>' +

                            '<td class="text-center">' + (rowCount + 1) + '</td>' +
                            '<td style="display:none"><input type="hidden" class="form-control barcode typeahead" name="barcode[]" id="barcode-' +
                            count + '" autocomplete="off" value="' + item['barcode'] + '"></td>' +
                            '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="item_name-' +
                            count + '" autocomplete="off" value="' + item['item_name'] + '"></td>' +
                            '<td><input type="text" class="form-control description typeahead" name="part_description[]" required id="description-' +
                            count + '" autocomplete="off" value="' + item['descriptions'] + '"></td>' +
                            '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                            count +
                            '" autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                            '<td><input type="text" class="form-control unit " name="unit[]" id="item_unit-' + count +
                            '" autocomplete ="off"  value="' + item['unit'] +
                            '" required> </td>' +

                            '<td><input type="text" class="form-control price" name="product_price[]" id="price-' +
                            count + '" autocomplete="off" value="' + item['wholesale_price'] +
                            '"></td>' +
                            '<td><input type="text" class="form-control retail_price" name="retail_price[]" id="retail_price-' +
                            count + '" autocomplete="off" value="' + item['retail_price'] +
                            '"></td>' +
                            '<td><input type="text" class="form-control exp_date" name="exp_date[]" id="exp_date-' +
                            count + '" autocomplete="off" value="' + item['expired_date'] + '"></td>' +

                            '<td style="text-align:center"><span class="currenty"></span><strong><span id="result-' +
                            count + '">0</span></strong></td>' +
                            '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                            '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                            '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' +
                            count + '" value="0">' +
                            '<input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">' +
                            '<input type="hidden" attr-org="" name="unit[]" id="unit-0" value="">' +
                            '<input type="hidden" name="unit_m[]" id="unit_m-0" value="1">' +
                            '<input type="hidden" name="code[]" id="hsn-0" value="">' +
                            '<input type="hidden" name="serial[]" id="serial-0" value="">' +
                            '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
                            '</tr>';
                        $("#showitem123").append(newRow);
                    }
                }

                $("#addproduct").click(function(e) {
                    e.preventDefault();
                    count++;
                    $.ajax({

                        type: 'GET',
                        url: "{{ route('get.part.data-unit') }}",
                        data: {
                            // _token: "{{ csrf_token() }}",

                        },
                        success: function(data) {
                            // itemNameInput.val(data.retail_price);
                            // partDesc.val(data.descriptions);
                            // exp_date.val(data.expired_date);

                            var selectBox = document.getElementById("unit-" + count);

                            // Loop through the data array
                            data.forEach(function(item) {
                                // Create an option element
                                var option = document.createElement("option");

                                // Set the value attribute to the unit id
                                option.value = item.unit;

                                // Set the text of the option to the unit name
                                option.text = item.unit;

                                // Append the option to the select element
                                selectBox.appendChild(option);
                            });


                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                    let rowCount = $("#showitem123 tr").length;
                    let newRow = '<tr>' +

                        '<td class="text-center">' + (rowCount + 1) + '</td>' +
                        '<td style="display:none"><input type="hidden" class="form-control barcode typeahead" name="barcode[]" id="barcode-' +
                        count + '" autocomplete="off"></td>' +
                        '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="item_name-' +
                        count + '" autocomplete="off"></td>' +
                        '<td><input type="text" class="form-control description typeahead" name="part_description[]" required id="description-' +
                        count + '" autocomplete="off"></td>' +
                        '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                        count +
                        '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                        '<td><input type="text" class="form-control unit " name="unit[]" id="item_unit-' +
                        count +
                        '" autocomplete ="off" required> </td>' +

                        '<td><input type="text" class="form-control price" name="product_price[]" value="0" id="price-' +
                        count + '"   autocomplete="off"></td>' +
                        '<td><input type="text" class="form-control retail_price" name="retail_price[]" value="0" id="retail_price-' +
                        count + '"   autocomplete="off"></td>' +
                        '<td><input type="text" class="form-control exp_date " name="exp_date[]" id="exp_date-' +
                        count +
                        '"   autocomplete="off"></td>' +

                        '<td style="text-align:center"><span class="currenty"></span><strong><span id="result-' +
                        count + '">0</span></strong></td>' +
                        '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                        '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                        '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' +
                        count + '" value="0">' +
                        '<input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">' +
                        // '<input type="hidden" attr-org="" name="unit[]" id="unit-0" value="">' +
                        '<input type="hidden" name="unit_m[]" id="unit_m-0" value="1">' +
                        '<input type="hidden" name="code[]" id="hsn-0" value="">' +
                        '<input type="hidden" name="serial[]" id="serial-0" value="">' +
                        '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
                        '</tr>';
                    $("#showitem123").append(newRow);
                    initializeTypeahead(count);
                });


                $(document).on('click', '.remove_item_btn', function(e) {
                    e.preventDefault();
                    let row_item = $(this).parent().parent();
                    $(row_item).remove();

                    // Update row numbers
                    $('#showitem123 tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });

                    initializeTypeaheads();
                });

                $(document).on('change', '.productname', function() {
                    let itemCode = $(this).val();
                    let row = $(this).closest('tr');
                    updateItemName(itemCode);
                });
                // Initialize typeahead for the first row
                initializeTypeahead(count);
                $(document).on("click", '#calculate', function(e) {
                    e.preventDefault();
                    let total = 0;
                    let totalTax = 0;
                    let salePriceCategory = $('#sale_price_category').val();
                    console.log(salePriceCategory);

                    for (let i = 0; i < (count + 1); i++) {
                        var qty = parseInt($('#amount-' + i).val() || 0);
                        var item_name = $('#productname-' + i).val() || 0;
                        var sel = $('#focsel-' + i).val() || 0;
                        let taxRate = parseFloat($('#vat-' + i).val() || 0);

                        let price;
                        if (salePriceCategory === 'Default') {
                            let cuz_name = $("#type").val();
                            console.log(cuz_name);
                            price = cuz_name === "Whole Sale" ? price = $('#price-' + i).val() :
                                $('#retail_price-' + i).val() || 0;
                            console.log($('#retail_price-' + i).val() || 0);
                            // $("#price-" + i + ).val(priceValue);
                            // price = parseInt($('#price-' + i).val() || 0);
                        } else if (salePriceCategory === 'Whole Sale') {
                            price = parseInt($('#price-' + i).val() || 0);
                        } else if (salePriceCategory === 'Retail') {
                            price = parseInt($('#retail_price-' + i).val() || 0);
                        }

                        if (!isNaN(taxRate) && taxRate >= 0) {
                            let itemTax = (price * qty * taxRate) / 100;
                            totalTax += itemTax;
                        }

                        if (!isNaN(taxRate) && taxRate > 0) {
                            let discount = (price * qty * taxRate) / 100;
                            $("#result-" + i).text((price * qty) - discount);
                        } else {
                            $("#result-" + i).text(price * qty);
                        }

                        total += price * qty;
                    }

                    let taxt = total * 0.05; // Calculate tax based on the updated total
                    taxt = Math.ceil(taxt);
                    let total_total = total - totalTax;
                    $("#invoiceyoghtml").val(total);
                    $("#commercial_text").val(
                        totalTax); // Update tax value
                    $("#total").val(total_total);
                });

                function paidFunction() {
                    let paid = document.getElementById("paid").value;
                    let total_p = document.getElementById("invoiceyoghtml").value;
                    let balance = total_p - paid;
                    $("#balance").val(balance);
                }
            });
        </script>
        <script>
            $(document).on('click', '.remove_item_btn', function(e) {
                e.preventDefault();
                let row_item = $(this).parent().parent();
                $(row_item).remove();
                count--;
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                for (let i = 0; i < (count + 1); i++) {
                    // if ($('#amount-' + i).is(":empty")) {
                    //     var price = 1;
                    // } else {
                    //     var price = parseInt($('#amount-' + i).val()); //get value from amount
                    // }

                    var qty = parseInt($('#amount-' + i).val()); //get value from amount
                    var item_name = $('#productname-' + i).val(); //get value from amount
                    var sel = $('#focsel-' + i).val(); //get value from amount



                    let price = parseInt($('#price-' + i).val()); //get vlaue from price

                    console.log("price" + price)
                    // console.log("price2"+Object.values(price2))
                    $("#result-" + i).text((price *
                        qty));

                    //  $("#price-"+ i).val(data['retail_sale']);
                    if (sel >= 1) {
                        $("#foc-" + i).text('FOC');
                        price = 0;
                        // total = 0; //total adding (amount*price)
                    }
                    if (sel < 1) {
                        $("#foc-" + i).text((price * qty));
                        //  total = total + (price * qty); //total adding (amount*price)
                    } /// set  (amount*price) to result subtotal for each product

                    total = total + (price * qty); //total adding (amount*price)

                }
                let taxt = total * 0.05;
                taxt = Math.ceil(taxt);
                let total_total = taxt + total;
                $("#invoiceyoghtml").val(total); //set  (amount*price)  per invoice  subtotal
                // $("#commercial_text").val(taxt); //commercial taxt 5% of total (sub total)
                $("#total").val(total_total); //super total
                $('#extra_discount').val('');
                $('#paid').val('');
                $('#balance').val('');
                // alert("Text:sdfgsdf"+ qty + "count is ;" + count);

            });






            function paidFunction() {

                let paid = document.getElementById("paid").value;
                let total_p = document.getElementById("invoiceyoghtml").value;
                let balance = total_p - paid;
                $("#balance").val(balance); //update balance
            }
        </script>






        <script>
            $(document).ready(function() {
                var path = "{{ route('customer_service_search') }}";


                $('#customer').typeahead({
                    source: function(query, process) {
                        return $.get(path, {
                            query: query
                        }, function(data) {
                            // Format the data for Typeahead
                            var formattedData = [];
                            $.each(data, function(index, customer) {
                                // Check if the query matches the name or phone number
                                if (customer.name.toLowerCase().indexOf(query
                                        .toLowerCase()) !== -1) {
                                    // If the query matches the name, show the name
                                    formattedData.push(customer.name);
                                } else if (customer.phno.indexOf(query) !== -1) {
                                    // If the query matches the phone number, show the phone number
                                    formattedData.push(customer.phno);
                                }
                            });
                            return process(formattedData);
                        });
                    }
                });




                $(document).on('click', '#customer_search', function(e) {
                    e.preventDefault();
                    let serialNumber = $("#customer").val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('customer_service_search_fill') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            model: serialNumber // Adjusted to match server-side parameter name
                        },
                        success: function(data) {
                            console.log(data);

                            $("#name").val(data['customer']['name']);
                            $("#customer_id").val(data['customer']['id']);
                            $("#phone_no").val(data['customer']['phno']);
                            $("#type").val(data['customer']['type']);
                            $("#address").val(data['customer']['address']);
                            // Adjusted to match server-side data
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>







        <script>
            $("input").on("change", function() {
                if (this.value && moment(this.value, "YYYY-MM-DD").isValid()) {
                    this.setAttribute(
                        "data-date",
                        moment(this.value, "YYYY-MM-DD").format("DD/MM/YYYY")
                    );
                } else {
                    this.setAttribute("data-date", "dd/mm/yyyy");
                }
            }).trigger("change");
        </script>
        <script>
            //Enter Key click add row
            $(document).on('keydown', '.form-control', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#addproduct').click();
                }
            });
        </script>
</body>

</HTML>
