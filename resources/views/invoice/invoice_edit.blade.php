<!DOCTYPE html>
<HTML>

<head>
    <link rel="stylesheet" href="{{ asset('locallink/css/bootstrap.min.css') }}">
    <script src="{{ asset('locallink/js/ajax_jquery.js') }}"></script>
    <script src="{{ asset('locallink/js/typehead.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <script src="{{ asset('locallink/js/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .item_table {
            width: 1630px;
            table-layout: fixed;
        }

        .item_table th,
        .item_table td {
            padding: 20px;
        }
    </style>




</head>

<body>

    <div class="container-fluid" id="content">


        <h1 class="mx-4 mt-3">
            Invoice Edit
        </h1>

        <form method="post" id="myForm" action="{{ url('/invoice_update', $invoice->id) }}"
            enctype="multipart/form-data">
            @csrf


            {{-- Permission Php --}}
            @php
                $choosePermission = [];
                if (auth()->user()->permission) {
                    $decodedPermissions = json_decode(auth()->user()->permission, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $choosePermission = $decodedPermissions;
                    }
                }
            @endphp
            {{-- End Php --}}

            <div class="mx-3 row ">

                <div class="my-3 mt-4 row">
                    <div class="col-md-3">
                        <label for="invoice_no" style="font-weight:bolder">Invoice Number</label>
                        <input type="text" id="invoice_no" class="form-control" name="invoice_no"
                            value="{{ $invoice->invoice_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_date" style="font-weight:bolder">Date</label>
                        <input type="date" name="invoice_date" class="form-control" max="{{ date('Y-m-d') }}"
                            value="{{ $invoice->invoice_date }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="overdue_date" class=" caption"
                            style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>
                        <input type="date" name="overdue_date" id="overdue_date" class="form-control round"
                            autocomplete="off" min="<?= date('Y-m-d') ?>" value="{{ $invoice->overdue_date }}">
                    </div>


                    <input type="hidden" name="quote_category" id="quote_category" value="Invoice">
                </div>
                <hr>
                <div class="content-wrapper">
                    <div class="content-body">
                        <div class="mx-3">
                            <div class="card-content">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-6 cmp-pnl">
                                            <div id="customerpanel" class="inner-cmp-pnl">

                                                <div class="form-group row">
                                                    <div class="frmSearch col-sm-6">

                                                        <div class="frmSearch col-sm-12" style="">
                                                            <div class="frmSearch col-sm-12">
                                                                <span style="font-weight:bolder">
                                                                    <label for="cst"
                                                                        class="caption">{{ trans('Search  Customer Name & Phone No.') }}</label>
                                                                </span>
                                                                <div class="form-group d-flex">
                                                                    <input type="text" id="customer" name="customer"
                                                                        class="mr-2 form-control round"
                                                                        autocomplete="off" placeholder="Search.....">
                                                                    &nbsp;&nbsp;&nbsp; <button type="submit"
                                                                        class="btn btn-primary"
                                                                        id="customer_search">Add</button>
                                                                </div>

                                                                <div id="customer-box-result"></div>
                                                            </div>


                                                        </div>

                                                    </div>


                                                    <div class="frmSearch col-sm-6">
                                                        <div class="frmSearch col-sm-12">
                                                            <div class="frmSearch col-sm-12">
                                                                <span style="font-weight:bolder">
                                                                    <label for="cst" class="caption">Register
                                                                        Mode</label>
                                                                </span>
                                                                <select name="balance_due" id="balance_due"
                                                                    class="mb-4 form-control balance_due" required>

                                                                    <option value="Invoice"
                                                                        @if ($invoice->balance_due == 'Invoice') selected @endif>
                                                                        Invoice</option>
                                                                    <option value="Po Return"
                                                                        @if ($invoice->balance_due == 'Po Return') selected @endif>
                                                                        Po Return</option>

                                                                </select>

                                                                <div id="customer-box-result"></div>
                                                            </div>


                                                        </div>



                                                    </div>
                                                    <input type="hidden" id="service_id" name="service_id"
                                                        value="0">


                                                    <input type="hidden" name="manager_type"
                                                        value="{{ Auth::user()->type }}">

                                                    {{-- <input type="text" name="status" class="form-control"
                                                        value="draft" style="display: none"> --}}

                                                </div>
                                            </div>
                                            <div class="col-sm-6 cmp-pnl">

                                                <div class="inner-cmp-pnl">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 row table-responsive " style="margin-top:1vh;">
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
                                                        <td class="text-center"><input type='text'
                                                                name='customer_name' id="name"
                                                                class="form-control"
                                                                value="{{ $invoice->customer_name }}"></td>
                                                        <input type='hidden' name='customer_id' id="customer_id"
                                                            class="form-control" value="{{ $invoice->customer_id }}">
                                                        <input type='hidden' name='status' id="status"
                                                            class="form-control" value="invoice">
                                                        <td class="text-center"><input type='text' name='phno'
                                                                id="phone_no" class="form-control"
                                                                value="{{ $invoice->phno }}"></td>
                                                        <td class="text-center"><input type='text' name='type'
                                                                id="type" class="form-control"
                                                                value="{{ $invoice->type }}"></td>
                                                        <td class="text-center"><input type='text' name='address'
                                                                value="{{ $invoice->address }}" class="form-control"
                                                                id="address"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>




                                        <div class="row table-responsive " style="margin-top:1vh;">
                                            <div class="mt-4 frmSearch col-md-3">
                                                <label for="payment"
                                                    style="font-weight:bolder">{{ trans('Sale Price Category') }}
                                                </label>
                                                <select class="mb-4 form-control round "
                                                    aria-label="Default select example" name="sale_price_category"
                                                    id="sale_price_category" required>
                                                    <option value="{{ $invoice->sale_price_category }}" selected>
                                                        {{ $invoice->sale_price_category }}
                                                    </option>
                                                    <option value="Default">Default</option>
                                                    <option value="Whole Sale">Whole Sale</option>
                                                    <option value="Retail">Retail</option>
                                                </select>
                                            </div>






                                            {{-- @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin') --}}



                                            @if (auth()->user()->is_admin == '1')
                                                <div class="mt-4 frmSearch col-md-3">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight: bolder;">
                                                            <label for="cst"
                                                                class="caption">{{ trans('Location') }}&nbsp;</label>
                                                        </span>
                                                        <select name="branch" id="location"
                                                            class="mb-4 form-control location" required>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->id }}"
                                                                    {{ $invoice->branch == $warehouse->id ? 'selected' : '' }}>
                                                                    {{ $warehouse->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mt-4 frmSearch col-md-3">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst"
                                                                class="caption">{{ trans('Location') }}&nbsp;</label>
                                                        </span>
                                                        <select name="branch" id="location"
                                                            class="mb-4 form-control location" required>

                                                            @php
                                                                $userPermissions = auth()->user()->level
                                                                    ? json_decode(auth()->user()->level)
                                                                    : [];
                                                            @endphp

                                                            @foreach ($warehouses as $branch)
                                                                @if (in_array($branch->id, $userPermissions))
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>


                                            @endif

                                            <!-- <table class="table-responsive tfr my_stripe"> -->
                                            <div class="table-responsive">
                                                <table class="table table-bordered item_table" style="width: 1630px;">
                                                    <!-- Fixed width -->
                                                    <thead style="background-color:#0047AA;color:white;">
                                                        <tr class="item_header bg-gradient-directional-blue white"
                                                            style="margin-bottom:10px;">
                                                            <th width="5%" class="text-center">
                                                                {{ trans('No') }}
                                                            </th>
                                                            <th width="35%" class="text-center">
                                                                {{ trans('Item Name') }}
                                                            </th>
                                                            <th width="10%" class="text-center">
                                                                {{ trans('Qty') }}
                                                            </th>
                                                            <th width="10%" class="text-center">
                                                                {{ trans('Unit') }}
                                                            </th>
                                                            <th width="12%" class="text-center">
                                                                {{ trans('လက်လီစျေး') }}
                                                            </th>
                                                            <th width="10%" class="text-center">
                                                                {{ trans('Discounts') }}
                                                            </th>
                                                            <th width="13%" class="text-center">
                                                                {{ trans('Amount') }}
                                                                ({{ config('currency.symbol') }})
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="showitem123">
                                                        @foreach ($sell as $key => $sell)
                                                            <tr>
                                                                <td class="text-center" id="count">
                                                                    {{ $key + 1 }}</td>
                                                                <td>
                                                                    <div class="row align-items-center">
                                                                        <div class="col-auto">
                                                                            <input type="checkbox" id="sell_status-0"
                                                                                value="1"
                                                                                class="form-check-input sell_status"
                                                                                {{ $sell->status == 1 ? 'checked' : '0' }} />
                                                                            <input type="hidden" name="sell_status[]"
                                                                                id="sell_status_input-0"
                                                                                class="form-control sell_status_input"
                                                                                value="0" />
                                                                        </div>
                                                                        <div class="col">
                                                                            <input type="text"
                                                                                class="form-control productname typeahead"
                                                                                name="part_number[]"
                                                                                value="{{ $sell->part_number }}"
                                                                                placeholder="{{ trans('Enter Part Number') }}"
                                                                                id='productname-0' autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><input type="text"
                                                                        class="form-control req amnt"
                                                                        name="product_qty[]" id="amount-0"
                                                                        autocomplete="off"
                                                                        value="{{ $sell->product_qty }}"><input
                                                                        type="hidden" id="alert-0" value=""
                                                                        name="alert[]"></td>
                                                                <td><input type="text"
                                                                        class="form-control item_unit"
                                                                        name="item_unit[]" id="item_unit-0"
                                                                        value="{{ $sell->unit }}"></td>
                                                                <td><input type="text"
                                                                        class="form-control retail_price"
                                                                        name="retail_price[]" id="retail_price-0"
                                                                        autocomplete="off"
                                                                        value="{{ $sell->retail_price }}"></td>
                                                                <td style="display: none;"><input type="text"
                                                                        class="form-control warehouse"
                                                                        name="warehouse[]" id="warehouse-0"
                                                                        autocomplete="off"
                                                                        value="{{ $sell->warehouse }}"></td>
                                                                <td style="display: none;"><input type="text"
                                                                        class="form-control exp_date"
                                                                        name="exp_date[]" id="vat-0"
                                                                        autocomplete="off"
                                                                        value="{{ $sell->exp_date }}"></td>
                                                                <td><input type="text" class="form-control vat"
                                                                        name="discount[]" id="vat-0"
                                                                        autocomplete="off"
                                                                        value="{{ $sell->discount }}"></td>
                                                                <td style="text-align:center"><strong><span
                                                                            class='ttlText1'
                                                                            id="foc-0"></span></strong></td>
                                                                <td style="width: 3%;"><button type="button"
                                                                        class="btn btn-danger remove_item_btn"
                                                                        id="removebutton"><i
                                                                            class="fa-solid fa-minus"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>



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
                                                            <button type="button" class="btn btn-success"
                                                                id="addproduct"
                                                                style="margin-top:20px;margin-bottom:20px;">
                                                                <i class="fa fa-plus-square"></i>
                                                                {{ trans('Add row') }}
                                                            </button>
                                                            <button type="button" class="btn btn-primary"
                                                                id="calculate">
                                                                Calculate
                                                            </button>


                                                            @if (in_array('Invoice Delete', $choosePermission) || auth()->user()->is_admin == '1')
                                                                <a href="{{ URL('items') }}" target="_blank"
                                                                    id="item_search">
                                                                    <button type="button" class="btn btn-success">
                                                                        <i class="fa fa-plus-square"></i> Item Search
                                                                    </button></a>
                                                            @endif




                                                        </td>
                                                        <td colspan="6"></td>
                                                        <br><br>

                                                    </tr>


                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">
                                                            @if (isset($employees[0]))
                                                                {{ trans('general.employee') }}
                                                                <select name="user_id"
                                                                    class="selectpicker form-control">
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
                                                        <td colspan="3" align="right"><strong>Sub Total
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="sub_total" class="form-control"
                                                                id="invoiceyoghtml" readonly
                                                                style="background-color: #E9ECEF"
                                                                value="{{ $invoice->sub_total }}">

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Overall Discount
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="total_discount"
                                                                class="form-control" id="total_discount"
                                                                value="{{ $invoice->discount_total }}">

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Item Discount
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" class="form-control"
                                                                id="item_discount" readonly>

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Total
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="total" class="form-control"
                                                                id="total_total" readonly
                                                                style="background-color: #E9ECEF"
                                                                value="{{ $invoice->total }}">

                                                        </td>

                                                    </tr>


                                                <tbody id="trContainer">
                                                    @foreach ($payment_method as $index => $payment)
                                                        <tr class="sub_c">
                                                            <td colspan="2"></td>
                                                            <td colspan="3" align="right">
                                                                @if ($index === 0)
                                                                    <strong>Payment Method</strong>
                                                                @endif
                                                            </td>
                                                            <td align="left" colspan="1" class="col-md-2">
                                                                <input type="text" name="payment_amount[]"
                                                                    class="form-control payment_amount"
                                                                    id="payment_amount"
                                                                    value="{{ $payment->payment_amount }}" required>
                                                            </td>
                                                            <td align="left" colspan="1"
                                                                class="col-md-2 payment_method">
                                                                <select name="payment_method[]" id="payment_method"
                                                                    class="form-control" required>
                                                                    <option value="Cash"
                                                                        {{ $payment->payment_method === 'Cash' ? 'selected' : '' }}>
                                                                        Cash</option>
                                                                    <option value="K Pay"
                                                                        {{ $payment->payment_method === 'K Pay' ? 'selected' : '' }}>
                                                                        K Pay</option>
                                                                    <option value="Wave"
                                                                        {{ $payment->payment_method === 'Wave' ? 'selected' : '' }}>
                                                                        Wave</option>
                                                                    <option value="Others"
                                                                        {{ $payment->payment_method === 'Others' ? 'selected' : '' }}>
                                                                        Others</option>
                                                                </select>
                                                            </td>
                                                            <td align="left" colspan="1" class="col-md-1">
                                                                @if ($index === 0)
                                                                    <button type="button" id="addRow"
                                                                        class="btn btn-primary">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </button>
                                                                @endif

                                                                @if ($index === 1)
                                                                    <button class="removeRow btn btn-danger"><i
                                                                            class="fa-solid fa-minus"></i></button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                </tbody>




                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="2">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Deposit
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2"><input type="text"
                                                            name="paid" class="form-control" id="paid"
                                                            onchange="paidFunction()"
                                                            value="{{ $invoice->deposit }}">

                                                    </td>

                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="2">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Remaining Balance
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2"><input type="text"
                                                            name="balance" class="form-control" id="balance"
                                                            readonly value="{{ $invoice->remain_balance }}">

                                                    </td>
                                                </tr>

                                                <tr class="sub_c " style="display: table-row;">
                                                    <td colspan="12"> <label for="remark">Remark</label>
                                                        <textarea name="remark" id="remark" class="form-control" rows="2">{{ $invoice->remark }}</textarea>

                                                    </td>
                                                </tr>
                                                <tr class="sub_c " style="display: table-row;">


                                                    <td align="right" colspan="9">

                                                        <button id="submitButton" class="mt-3 btn btn-primary"
                                                            type="submit">Save</button>


                                                        <a href="{{ url('invoice') }}" type="submit"
                                                            class="mt-3 btn btn-danger">Cancel
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
        </form>

    </div>

    <script>
        $(document).ready(function() {
            let count = 0;
            // search item name suggestion (get item name from db)
            function initializeTypeahead(count) {
                $('#productname-' + count).typeahead({
                    source: function(query, process) {
                        if (!$('#sell_status-' + count).is(':checked')) {
                            var Selectedlocation = $('#location').val();
                            return $.ajax({
                                url: "{{ route('autocomplete-part-code-invoice') }}",
                                method: 'POST',
                                data: {
                                    query: query,
                                    location: Selectedlocation,
                                },
                                dataType: 'json',
                                success: function(data) {
                                    console.log(data);
                                    process(data);
                                }
                            });
                        }
                    }
                });
            }

            function initializeTypeaheads() {
                for (let i = 0; i <= count; i++) {
                    initializeTypeahead(i);
                }
            }

            function updateItemName(item_name, row, cuz_name) {
                let itemNameInput = row.find('.price');
                let partDesc = row.find('.description');
                let exp_date = row.find('.exp_date');
                let warehouse = row.find('.warehouse');
                let item_unit = row.find('.item_unit');
                let retail_price = row.find('.retail_price');
                var Selectedlocation = $('#location').val();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('get-part-data-invoice') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        item_name: item_name,
                        location: Selectedlocation,
                    },
                    success: function(data) {
                        //  itemNameInput.val(data.retail_price);

                        itemNameInput.val(data.wholesale_price);

                        partDesc.val(data.descriptions);
                        exp_date.val(data.radio_category);
                        item_unit.val(data.item_unit);
                        warehouse.val(data.warehouse_id);
                        retail_price.val(data.retail_price);
                        if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                            alert(data.quantity + " quantity!");
                        }

                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
            $("#addproduct").click(function(e) {
                e.preventDefault();
                count++;

                let rowCount = $("#showitem123 tr").length;
                let newRow = '<tr>' +
                    '<td class="text-center">' + (rowCount + 1) + '</td>' +
                    '<td style="display:none"><input type="hidden" class="form-control barcode typeahead" name="barcode[]" id="barcode-' +
                    count + '" autocomplete="off"></td>' +
                    '<td>' +
                    '<div class="row align-items-center">' +
                    '<div class="col-auto">' +
                    '<input type="checkbox" id="sell_status-' + count +
                    '" value="1" class="form-check-input sell_status" />' +
                    '<input type="hidden" name="sell_status[]" id="sell_status_input-' + count +
                    '" value="0" class="form-control sell_status_input" />' +
                    '</div>' +
                    '<div class="col">' +
                    '<input type="text" class="form-control productname typeahead item_name" name="part_number[]" id="productname-' +
                    count + '" autocomplete="off" placeholder="Enter Part Number">' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                    count +
                    '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                    '<td><input type="text" class="form-control item_unit " name="item_unit[]" id="item_unit-' +
                    count +
                    '" autocomplete ="off" required> </td>' +
                    '<td><input type="text" class="form-control retail_price" name="retail_price[]" value="0" id="retail_price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control vat" name="discount[]" value="0" id="vat-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display : none;"><input type="text" class="form-control exp_date" name="exp_date[]" value="0" id="exp_date-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display : none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                    count +
                    '"   autocomplete="off"></td>' +

                    '<td style="text-align:center"><span class="currenty"></span><strong><span class="ttlText1" id="result-' +
                    count + '">0</span></strong></td>' +
                    '<td style="width: 3%;"><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton"><i class="fa-solid fa-minus"></i></button></td>' +
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


            $(document).ready(function() {
                function calculatePayment() {
                    let total = 0;
                    $('.payment_amount').each(function() {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    total = Math.round(total);
                    $('#paid').val(total);
                    paidFunction();
                }

                function paidFunction() {
                    let paid = parseFloat($('#paid').val()) || 0;
                    let total_p = parseFloat($('#total_total').val()) || 0;
                    let balance = total_p - paid;
                    balance = Math.round(balance);
                    $('#balance').val(balance);
                }

                $(document).on('input', '.payment_amount', function() {
                    calculatePayment();
                });

                $('#paid').on('input', function() {
                    paidFunction();
                });

                $('#addRow').click(function() {
                    // Check the number of rows
                    if ($('#trContainer tr.sub_c').length < 4) {
                        var newRow = `<tr class="sub_c">
                <td colspan="2"></td>
                <td colspan="3" align="right"><strong></strong></td>
                <td align="left" colspan="1" class="col-md-2">
                    <input type="text" name="payment_amount[]" class="form-control payment_amount">
                </td>
                <td align="left" colspan="1" class="col-md-2">
                    <select name="payment_method[]" class="form-control">
                        <option value="Cash">Cash</option>
                        <option value="K Pay">K Pay</option>
                        <option value="Wave">Wave</option>
                        <option value="Others">Others</option>
                    </select>
                </td>
                <td align="left" colspan="1" class="col-md-1">
                    <button class="removeRow btn btn-danger"><i class="fa-solid fa-minus"></i></button>
                </td>
            </tr>`;

                        $('#trContainer').append(newRow);
                    } else {
                        alert('You can only add a maximum of 4 payment rows.');
                    }
                });

                $(document).on('click', '.removeRow', function() {
                    $(this).closest('tr').remove();
                    calculatePayment();
                });

                calculatePayment();
            });


            $(document).on('click', '.typeahead .dropdown-item', function(e) {
                e.preventDefault();
                if ($("#customer").val()) {

                } else {
                    const itemCode = $(this).text().trim();
                    const row = $(this).closest('tr');
                    let cuz_name = $("#type").val();
                    updateItemName(itemCode, row, cuz_name);
                    $('#productname').val('');
                }
            });


            $(document).ready(function() {
                function calculateTotals() {
                    let salePriceCategory = $('#sale_price_category').val();

                    let total = 0;
                    let totalTax = 0;
                    let totalTotal = 0;
                    let itemDiscount = 0;

                    $('#showitem123 tr').each(function() {
                        let row = $(this);
                        let qty = parseInt(row.find('.req.amnt').val()) || 0;
                        let price;

                        if (salePriceCategory === 'Default') {
                            let cuz_name = $("#type").val();
                            price = cuz_name === "Whole Sale" ? parseFloat(row.find('.price')
                                .val()) || 0 : parseFloat(row.find('.retail_price').val()) || 0;
                        } else if (salePriceCategory === 'Whole Sale') {
                            price = parseFloat(row.find('.price').val()) || 0;
                        } else if (salePriceCategory === 'Retail') {
                            price = parseFloat(row.find('.retail_price').val()) || 0;
                        }

                        let discount = parseFloat(row.find('.vat').val()) || 0;
                        let itemTotal = qty * price;
                        totalTotal += itemTotal;


                        if (!isNaN(discount) && discount >= 0) {
                            let itemTax = itemTotal - discount;
                            totalTax += itemTax;
                        }

                        if (!isNaN(discount) && discount > 0) {
                            let discountAmount = itemTotal - discount;
                            itemTotal = discountAmount;
                        }

                        total += itemTotal;
                        itemDiscount += discount;


                        row.find('.ttlText1').text(itemTotal);

                    });


                    let paid = parseFloat(document.getElementById("paid").value) ||
                        0;
                    let total_p = parseFloat(document.getElementById("total_total").value) ||
                        0;
                    let total_discount = parseFloat(document.getElementById("total_discount").value) ||
                        0;


                    let totalDiscount = total - total_discount;
                    let balance = total - paid - total_discount;


                    $("#balance").val(balance);
                    $("#item_discount").val(itemDiscount);
                    $('#invoiceyoghtml').val(totalTotal);
                    $('#total_total').val(totalDiscount);
                }

                // Bind function to button click
                $(document).on("click", '#calculate', function(e) {
                    e.preventDefault();
                    calculateTotals();
                });

                // Automatically run on page load
                calculateTotals();


            });




        });


        function paidFunction() {
            let paid = document.getElementById("paid").value;
            let total_p = document.getElementById("invoiceyoghtml").value;
            let balance = total_p - paid;
            $("#balance").val(balance);
        }
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
    </script>

    <script>
        $(document).ready(function() {
            var path = "{{ route('customer_service_search') }}";
            $('#customer').typeahead({
                source: function(query, process) {
                    var Selectedlocation = $('#location').val();

                    return $.get(path, {
                        query: query,
                        location: Selectedlocation,
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
                        model: serialNumber,
                        location: $('#location').val()
                    },
                    success: function(data) {
                        console.log(data);

                        if (data.customer) {
                            $("#name").val(data.customer.name);
                            $("#customer_id").val(data.customer.id);
                            $("#phone_no").val(data.customer.phno);
                            $("#type").val(data.customer.type);
                            $("#address").val(data.customer.address);
                            $("#customer").val('');
                        } else {
                            console.error("Customer not found");
                            $("#customer").val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

            });
        });
    </script>







    {{-- <script>
        $("input[type='date']").on("change", function() {
            if (this.value && moment(this.value, "YYYY-MM-DD").isValid()) {
                this.setAttribute(
                    "data-date",
                    moment(this.value, "YYYY-MM-DD").format("DD/MM/YYYY")
                );
            } else {
                this.setAttribute("data-date", "dd/mm/yyyy");
            }
        }).trigger("change");
    </script> --}}
    <script>
        //Enter Key click add row
        $(document).on('keydown', '.form-control', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#addproduct').click();
            }
        });
    </script>
    <script>
        $(document).on("input", "#total_discount", function() {
            let subtotal = parseFloat($("#invoiceyoghtml").val()) || 0;
            let totalDiscount = parseFloat($("#total_discount").val()) || 0;
            let totalVAT = 0;
            $(".vat").each(function() {
                totalVAT += parseFloat($(this).val()) || 0;
            });
            let total = subtotal - totalDiscount - totalVAT;
            $("#total_total").val(total);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateHiddenInput(checkbox) {
                const input = checkbox.closest('tr').querySelector('.sell_status_input');
                if (input) {
                    input.value = checkbox.checked ? '1' : '0';
                }
            }

            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('sell_status')) {
                    updateHiddenInput(e.target);
                }
            });

            document.querySelectorAll('.sell_status').forEach(function(checkbox) {
                updateHiddenInput(checkbox);
            });
        });
    </script>


</body>

</HTML>
