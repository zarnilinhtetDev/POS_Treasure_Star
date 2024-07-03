<!DOCTYPE html>
<HTML>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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


        <h1 class="mx-4 mt-3">
            Quotation Edit
        </h1>

        <form method="post" id="myForm" action="{{ url('/invoice_update', $quotation->id) }}"
            enctype="multipart/form-data">
            @csrf

            <div class="mx-3 row ">

                <div class="my-3 mt-4 row">
                    <div class="col-md-3">
                        <label for="quote_no" style="font-weight:bolder">Quotation Number</label>
                        <input type="text" id="quote_no" class="form-control" name="quote_no"
                            value="{{ $quotation->quote_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="quote_date" style="font-weight:bolder">Date</label>
                        <input type="date" name="quote_date" class="form-control" max="{{ date('Y-m-d') }}"
                            value="{{ $quotation->quote_date }}" required>
                    </div>



                    <input type="hidden" name="quote_category" id="quote_category" value="quotation">
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

                                                <div class="frmSearch col-sm-7">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst"
                                                                class="caption">{{ trans('Search  Customer Name & Phone No.') }}</label>
                                                        </span>
                                                        <div class="form-group d-flex">
                                                            <input type="text" id="customer" name="customer"
                                                                class="mr-2 form-control round" autocomplete="off"
                                                                placeholder="Search.....">
                                                            &nbsp;&nbsp;&nbsp; <button type="submit"
                                                                class="btn btn-primary"
                                                                id="customer_search">Add</button>
                                                        </div>

                                                        <div id="customer-box-result"></div>
                                                    </div>
                                                </div>
                                                <!--  -->
                                                <input type="hidden" id="service_id" name="service_id" value="0">


                                                <input type="hidden" name="manager_type"
                                                    value="{{ Auth::user()->type }}">

                                                <input type="text" name="status" class="form-control" value="draft"
                                                    style="display: none">

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
                                                    <td class="text-center"><input type='text' name='customer_name'
                                                            id="name" class="form-control"
                                                            value="{{ $quotation->customer_name }}"></td>
                                                    <input type='hidden' name='customer_id' id="customer_id"
                                                        class="form-control">
                                                    <input type='hidden' name='status' id="status"
                                                        class="form-control" value="quotation">
                                                    <td class="text-center"><input type='text' name='phno'
                                                            id="phone_no" class="form-control"
                                                            value="{{ $quotation->phno }}"></td>
                                                    <td class="text-center"><input type='text' name='type'
                                                            id="type" class="form-control"
                                                            value="{{ $quotation->type }}"></td>
                                                    <td class="text-center"><input type='text' name='address'
                                                            value="{{ $quotation->address }}" class="form-control"
                                                            id="address"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>




                                    <div class="row table-responsive " style="margin-top:1vh;">
                                        <div class="mt-4 frmSearch col-md-4">
                                            <label for="payment"
                                                style="font-weight:bolder">{{ trans('Sale Price Category') }}
                                            </label>
                                            <select class="mb-4 form-control round "
                                                aria-label="Default select example" name="sale_price_category"
                                                id="sale_price_category" required>
                                                <option value="{{ $quotation->sale_price_category }}" selected>
                                                    {{ $quotation->sale_price_category }}
                                                </option>
                                                <option value="Default">Default</option>
                                                <option value="Whole Sale">Whole Sale</option>
                                                <option value="Retail">Retail</option>
                                            </select>
                                        </div>
                                        @if (auth()->user()->is_admin == '1')
                                            <div class="mt-4 frmSearch col-md-3">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="cst"
                                                            class="caption">{{ trans('Location') }}&nbsp;</label>
                                                    </span> <select name="branch" id="location"
                                                        class="mb-4 form-control location" required>
                                                        @foreach ($warehouses as $branch)
                                                            @if ($branch->id == $quotation->branch)
                                                                <option value="{{ $branch->id }}" selected>
                                                                    {{ $branch->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">
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
                                                    <select name="branch" id="branch" class="form-control"
                                                        required>
                                                        @php
                                                            $userPermissions = auth()->user()->level
                                                                ? json_decode(auth()->user()->level)
                                                                : [];
                                                        @endphp
                                                        @foreach ($warehouses as $branch)
                                                            @if ($branch->id == $quotation->branch)
                                                                <option value="{{ $branch->id }}" selected>
                                                                    {{ $branch->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
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
                                        <table class="table table-bordered">
                                            <thead style="background-color:#0047AA;color:white;">
                                                <tr class="item_header bg-gradient-directional-blue white"
                                                    style="margin-bottom:10px;">
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
                                                        {{ trans('လက်ကားစျေး') }}
                                                    </th>
                                                    <th width="9%" class="text-center">
                                                        {{ trans('လက်လီစျေး') }}
                                                    </th>
                                                    <th width="9%" class="text-center">
                                                        {{ trans('Expiry') }}
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
                                                @foreach ($sell as $key => $sell)
                                                    <tr>
                                                        <td class="text-center" id="count">
                                                            {{ $key + 1 }}
                                                        </td>
                                                        <td><input type="text"
                                                                class="form-control productname typeahead"
                                                                name="part_number[]" value="{{ $sell->part_number }}"
                                                                placeholder="{{ trans('Enter Item Name') }}"
                                                                id='productname-0' autocomplete="off">
                                                        </td>

                                                        <td><input type="text"
                                                                class="form-control description typeahead"
                                                                value="{{ $sell->description }}"
                                                                name="part_description[]"
                                                                placeholder="{{ trans('') }}" id='description-0'
                                                                autocomplete="off"></td>
                                                        <td><input type="text" class="form-control req amnt"
                                                                name="product_qty[]" id="amount-0"
                                                                autocomplete="off"
                                                                value="{{ $sell->product_qty }}"><input
                                                                type="hidden" id="alert-0" value=""
                                                                name="alert[]"></td>
                                                        <td><input type="text" class="form-control item_unit"
                                                                name="item_unit[]" id="item_unit-0"
                                                                value="{{ $sell->unit }}">

                                                        </td>
                                                        <td><input type="text" class="form-control price"
                                                                name="product_price[]" id="price-0"
                                                                autocomplete="off"
                                                                value="{{ $sell->product_price }}">
                                                        </td>
                                                        <td><input type="text" class="form-control retail_price"
                                                                name="retail_price[]" id="retail_price-0"
                                                                autocomplete="off" value="{{ $sell->retail_price }}">
                                                        </td>
                                                        <td><input type="text" class="form-control exp_date "
                                                                name="exp_date[]" id="exp_date-0" autocomplete="off"
                                                                value="{{ $sell->exp_date }}">
                                                        </td>

                                                        <td style="display: none;"><input type="text"
                                                                class="form-control warehouse " name="warehouse[]"
                                                                id="warehouse-0" autocomplete="off"
                                                                value="{{ $sell->warehouse }}">
                                                        </td>
                                                        <!-- <td><input type="text" class="form-control vat " name="discount[]" id="vat-0" autocomplete="off" value="{{ old('discount') }}">
                                                    </td> -->

                                                        <td style="text-align:center">
                                                            <strong>
                                                                <span class='ttlText1' id="foc-0"></span>
                                                            </strong>
                                                            <span
                                                                class="currenty">{{ config('currency.symbol') }}</span>
                                                            <strong>
                                                                <span class='ttlText'
                                                                    id="result-{{ $key }}">
                                                                    {{ intval($sell->product_qty) * floatval($sell->product_price) - (intval($sell->product_qty) * floatval($sell->product_price) * intval($sell->discount)) / 100 }}

                                                                </span>
                                                            </strong>
                                                        </td>
                                                        <td><button type="submit"
                                                                class="btn btn-danger remove_item_btn"
                                                                id="removebutton">Remove</button></td>
                                                        <input type="hidden" class="form-control vat "
                                                            name="product_tax[]" id="vat-0" value="0">
                                                        <input type="hidden" name="total_tax[]" id="taxa-0"
                                                            value="0">
                                                        {{-- <input type="hidden" name="total_discount[]" id="disca-0"
                                                            value="0"> --}}
                                                        <input type="hidden" class="ttInput"
                                                            name="product_subtotal[]" id="total-0" value="0">
                                                        <input type="hidden" class="pdIn" name="product_id[]"
                                                            id="pid-0" value="0">
                                                        <input type="hidden" attr-org="" name="unit[]"
                                                            id="unit-0" value="">
                                                        <input type="hidden" name="unit_m[]" id="unit_m-0"
                                                            value="1">
                                                        <input type="hidden" name="code[]" id="hsn-0"
                                                            value="">
                                                        <input type="hidden" name="serial[]" id="serial-0"
                                                            value="">
                                                        {{-- <td></td> --}}
                                                    </tr>
                                                @endforeach
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

                                                        <a href="{{ URL('items') }}" target="_blank"
                                                            id="item_search">
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
                                                    <td colspan="3" align="right"><strong>Sub Total
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2" class="col-md-4"><input
                                                            type="text" name="sub_total" class="form-control"
                                                            id="invoiceyoghtml" readonly
                                                            style="background-color: #E9ECEF"
                                                            value="{{ $quotation->sub_total }}">

                                                    </td>

                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="2">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Discount
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2" class="col-md-4"><input
                                                            type="text" name="discount" class="form-control"
                                                            id="total_discount"
                                                            value="{{ $quotation->discount_total }}">

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
                                                            value="{{ $quotation->total }}">

                                                    </td>

                                                </tr>



                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="2">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Deposit
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2"><input type="text"
                                                            name="paid" class="form-control" id="paid"
                                                            onchange="paidFunction()"
                                                            value="{{ $quotation->deposit }}">

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
                                                            readonly value="{{ $quotation->remain_balance }}">

                                                    </td>
                                                </tr>

                                                <tr class="sub_c " style="display: table-row;">
                                                    <td colspan="12"> <label for="remark">Remark</label>
                                                        <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                                    </td>
                                                </tr>
                                                <tr class="sub_c " style="display: table-row;">


                                                    <td align="right" colspan="9">

                                                        <button id="submitButton" class="mt-3 btn btn-primary"
                                                            type="submit">Save</button>


                                                        <a href="{{ url('quotation') }}" type="submit"
                                                            class="mt-3 btn btn-warning">Cancel
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
                        exp_date.val(data.expired_date);
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
                    '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="productname-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control description typeahead" name="part_description[]" required id="description-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                    count +
                    '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                    '<td><input type="text" class="form-control item_unit " name="item_unit[]" id="item_unit-' +
                    count +
                    '" autocomplete ="off" required> </td>' +

                    '<td><input type="text" class="form-control price" name="product_price[]" value="0" id="price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail_price" name="retail_price[]" value="0" id="retail_price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control exp_date " name="exp_date[]" id="exp_date-' +
                    count +
                    '"   autocomplete="off"></td>' +

                    '<td style="display : none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                    count +
                    '"   autocomplete="off"></td>' +

                    '<td style="text-align:center"><span class="currenty"></span><strong><span class="ttlText1" id="result-' +
                    count + '">0</span></strong></td>' +
                    '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +

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
                let cuz_name = $("#type").val();
                updateItemName(itemCode, row, cuz_name);
            });
            // Initialize typeahead for the first row
            initializeTypeahead(count);


            $(document).ready(function() {
                calculateTotal();

            });

            function calculateTotal() {
                let salePriceCategory = $('#sale_price_category').val();

                // Iterate through each row to calculate subtotal for each item
                $('#showitem123 tr').each(function() {
                    let row = $(this);
                    let qty = parseInt(row.find('.req.amnt').val()) || 0;
                    let price;

                    if (salePriceCategory === 'Default') {
                        let cuz_name = $("#type").val();
                        price = cuz_name === "Whole Sale" ? parseFloat(row.find('.price').val()) || 0 :
                            parseFloat(row.find('.retail_price').val()) || 0;
                    } else if (salePriceCategory === 'Whole Sale') {
                        price = parseFloat(row.find('.price').val()) || 0;
                    } else if (salePriceCategory === 'Retail') {
                        price = parseFloat(row.find('.retail_price').val()) || 0;
                    }

                    let discount = parseFloat(row.find('.vat').val()) || 0;

                    let total = qty * price;
                    let discountAmount = (total * discount) / 100;
                    let subtotal = total - discountAmount;

                    row.find('.ttlText1').text(subtotal.toFixed(2));
                });

                let total = 0;
                let totalTax = 0;

                // Iterate through each row to calculate the grand total and total tax
                $('#showitem123 tr').each(function() {
                    let row = $(this);
                    let qty = parseInt(row.find('.req.amnt').val()) || 0;
                    let price;

                    if (salePriceCategory === 'Default') {
                        let cuz_name = $("#type").val();
                        price = cuz_name === "Whole Sale" ? parseFloat(row.find('.price').val()) || 0 :
                            parseFloat(row.find('.retail_price').val()) || 0;
                    } else if (salePriceCategory === 'Whole Sale') {
                        price = parseFloat(row.find('.price').val()) || 0;
                    } else if (salePriceCategory === 'Retail') {
                        price = parseFloat(row.find('.retail_price').val()) || 0;
                    }

                    let discount = parseFloat(row.find('.vat').val()) || 0;

                    let itemTotal = qty * price;

                    if (!isNaN(discount) && discount >= 0) {
                        let itemTax = (itemTotal * discount) / 100;
                        totalTax += itemTax;
                    }

                    if (!isNaN(discount) && discount > 0) {
                        let discountAmount = (itemTotal * discount) / 100;
                        itemTotal -= discountAmount;
                    }

                    total += itemTotal;

                    row.find('.ttlText1').text(itemTotal);
                    if (price > 0) {
                        row.find('.ttlText1').show();
                        row.find('.ttlText').hide();
                    } else {
                        row.find('.ttlText1').hide();
                        row.find('.ttlText').show();
                    }
                });

                let tax = total * 0.05;
                tax = Math.ceil(tax);

                let totalTotal = total - totalTax;

                $('#invoiceyoghtml').val(total);
                $('#commercial_text').val(totalTax.toFixed(2));
                $('#total').val(totalTotal.toFixed(2));
                $('#extra_discount').val('');
                $('#paid').val('');
                $('#total_total').val(total);
                $('#balance').val('');
            }

            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                calculateTotal();
            });


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

        function paidFunction() {

            let paid = document.getElementById("paid").value;
            let total_p = document.getElementById("total_total").value;
            let balance = total_p - paid;
            $("#balance").val(balance); //update balance
        }
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
    <script>
        $(document).on("input", "#total_discount", function() {
            let subtotal = parseFloat($("#invoiceyoghtml").val()) || 0;
            let discount = parseFloat($(this).val()) || 0;
            let total = subtotal - discount;
            $("#total_total").val(total);
        });
    </script>
</body>

</HTML>
