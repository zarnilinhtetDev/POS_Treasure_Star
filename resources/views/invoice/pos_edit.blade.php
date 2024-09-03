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

    <div class="container-fluid " id="content">


        <h1 class="mx-4 mt-3">
            Suspend
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
                {{-- <input type='hidden' name='sale_by' id="sale_by" value="{{ auth()->user()->name }}" class="form-control"> --}}
                <div class="mt-4 row">
                    <div class="col-md-3">
                        <label for="invoice_no" style="font-weight:bolder">POS Number</label>
                        <input type="text" id="invoice_no" class="form-control" name="invoice_no"
                            value="{{ $invoice->invoice_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_date" style="font-weight:bolder">Date</label>
                        <input type="date" name="invoice_date" class="form-control"
                            value="{{ $invoice->invoice_date }}" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3" style="display:none">
                        <label for="overdue_date" class=" caption"
                            style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>
                        <input type="date" name="overdue_date" id="overdue_date" class="form-control round"
                            autocomplete="off" min="<?= date('Y-m-d') ?>" value="{{ $invoice->overdue_date }}">
                    </div>
                    <div class="col-md-3 ">
                        <label for="payment_method" style="font-weight:bolder">{{ trans('Payment Methods') }}</label>
                        <select class="mb-4 form-control round" aria-label="Default select example"
                            name="payment_method" required>
                            <option value="Cash" {{ $invoice->payment_method == 'Cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="K Pay" {{ $invoice->payment_method == 'K Pay' ? 'selected' : '' }}>K Pay
                            </option>
                            <option value="Wave" {{ $invoice->payment_method == 'Wave' ? 'selected' : '' }}>Wave
                            </option>
                            <option value="Others" {{ $invoice->payment_method == 'Others' ? 'selected' : '' }}>Others
                            </option>
                        </select>
                    </div>

                    <input type="hidden" name="quote_category" id="quote_category" value="POS">
                </div>
                <div class="content-wrapper">
                    <div class="content-body">
                        <div class="">
                            <div class="card-content">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-6 cmp-pnl">
                                            <div id="customerpanel" class="inner-cmp-pnl">


                                                <div class="mt-3 form-group row">
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

                                                    <input type="hidden" id="service_id" name="service_id"
                                                        value="0">


                                                    <input type="hidden" name="manager_type"
                                                        value="{{ Auth::user()->type }}">



                                                </div>
                                            </div>
                                            <div class="col-sm-6 cmp-pnl">

                                                <div class="inner-cmp-pnl">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4 row table-responsive" style="margin-top:1vh;">
                                            <table class="table table-bordered">
                                                <thead style="background-color:#0047AA;color:white;">
                                                    <tr class="item_header bg-gradient-directional-blue white">
                                                        <th class="text-center" style="width: 13%;">
                                                            {{ trans('Name') }}
                                                        </th>
                                                        <th class="text-center" style="width: 14%;">
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
                                                            class="form-control" value="pos">
                                                        <td class="text-center"><input type='text' name='phno'
                                                                id="phone_no" class="form-control"
                                                                value="{{ $invoice->phno }}"></td>
                                                        <td class="text-center"><input type='text' name='type'
                                                                id="type" class="form-control"
                                                                value="{{ $invoice->type }}"></td>
                                                        <td class="text-center"><input type='text' name='address'
                                                                class="form-control" id="address"
                                                                value="{{ $invoice->address }}"></td>
                                                    </tr>


                                                </tbody>
                                            </table>


                                        </div>
                                        <hr>
                                        @if (auth()->user()->is_admin == '1')
                                            <div class="mt-4 frmSearch col-md-3">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="cst"
                                                            class="caption">{{ trans('Location') }}&nbsp;</label>
                                                    </span> <select name="branch" id="location"
                                                        class="mb-4 form-control location" required>
                                                        @foreach ($warehouses as $branch)
                                                            @if ($branch->id == $invoice->branch)
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
                                                            @if ($branch->id == $invoice->branch)
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
                                        <div class="mt-4 frmSearch col-md-3">
                                            <div class="frmSearch col-sm-12">
                                                <span style="font-weight:bolder">
                                                    <label for="cst"
                                                        class="caption">{{ trans('Search Item Name ') }}&nbsp;</label>
                                                </span>
                                                <input type="text" class="form-control productname typeahead"
                                                    name="itemname" id='productname' autocomplete="off"
                                                    placeholder="Search Item Name ">

                                                <div id="customer-box-result"></div>
                                            </div>


                                        </div>

                                        <div class="mt-4 frmSearch col-md-3">
                                            <div class="frmSearch col-sm-12">
                                                <span style="font-weight:bolder">
                                                    <label for="cst"
                                                        class="caption">{{ trans('Search Item Barcode') }}&nbsp;</label>
                                                </span>
                                                <input type="text" class="form-control productname typeahead"
                                                    name="barcode" id='barcode' autocomplete="off"
                                                    placeholder="Search Item Barcode ">
                                                <div id="customer-box-result"></div>
                                            </div>



                                        </div>

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


                                        <div class="row table-responsive " style="margin-top:1vh;">
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
                                                        <th style="display: none;" width="9%"
                                                            class="text-center">
                                                            {{ trans('၀ယ်စျေး') }}
                                                        </th>
                                                        <th width="9%" class="text-center" style="display:none">
                                                            {{ trans('Expiry') }}
                                                        </th>


                                                        <th width="14%" class="text-center">{{ trans('Amount') }}
                                                            ({{ config('currency.symbol') }})
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody id="showitem123">
                                                    @foreach ($sells as $key => $sell)
                                                        <tr>
                                                            <input type="hidden"
                                                                class="form-control barcode typeahead"
                                                                name="barcode[]" value="{{ old('barcode') }}"
                                                                placeholder="{{ trans('Enter BarCode') }}"
                                                                id='barcode-0' autocomplete="off">

                                                            <td class="text-center" id="count">
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td><input type="text"
                                                                    class="form-control productname typeahead"
                                                                    name="part_number[]"
                                                                    value="{{ $sell->part_number }}"
                                                                    placeholder="{{ trans('Enter Part Number') }}"
                                                                    id='item_name-0' autocomplete="off">
                                                            </td>

                                                            <td><input type="text"
                                                                    class="form-control description typeahead"
                                                                    value="{{ $sell->description }}"
                                                                    name="part_description[]"
                                                                    placeholder="{{ trans('') }}"
                                                                    id='description-0' autocomplete="off"></td>
                                                            <td><input type="text" class="form-control req amnt"
                                                                    name="product_qty[]" id="amount-0"
                                                                    autocomplete="off"
                                                                    value="{{ $sell->product_qty }}"><input
                                                                    type="hidden" id="alert-0" value=""
                                                                    name="alert[]"></td>
                                                            <td><input type="text" class="form-control unit"
                                                                    name="item_unit[]" value="{{ $sell->unit }}"
                                                                    id="item_unit-0">

                                                            </td>
                                                            <td><input type="text" class="form-control price"
                                                                    name="product_price[]" id="price-0"
                                                                    autocomplete="off"
                                                                    value="{{ $sell->product_price }}">
                                                            </td>
                                                            <td><input type="text"
                                                                    class="form-control retail_price"
                                                                    name="retail_price[]" id="retail_price-0"
                                                                    autocomplete="off"
                                                                    value="{{ $sell->retail_price }}">
                                                            </td>
                                                            <td style="display: none;"><input type="text"
                                                                    class="form-control buy_price" name="buy_price[]"
                                                                    id="buy_price-0" autocomplete="off"
                                                                    value="{{ $sell->buy_price }}">
                                                            </td>
                                                            <td style="display:none"><input type="text"
                                                                    class="form-control exp_date " name="exp_date[]"
                                                                    id="exp_date-0" autocomplete="off"
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
                                                            <input type="hidden" class="form-control vat "
                                                                name="product_tax[]" id="vat-0" value="0">
                                                            <input type="hidden" name="total_tax[]" id="taxa-0"
                                                                value="0">
                                                            <input type="hidden" name="total_discount[]"
                                                                id="disca-0" value="0">
                                                            <input type="hidden" class="ttInput"
                                                                name="product_subtotal[]" id="total-0"
                                                                value="0">
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
                                                            <td><button type="submit"
                                                                    class="btn btn-danger remove_item_btn"
                                                                    id="removebutton">Remove</button></td>
                                                            </input>
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
                                                                style="margin-top:20px;margin-bottom:20px;display: none;">
                                                                <i class="fa fa-plus-square"></i>
                                                                {{ trans('Add row') }}
                                                            </button>
                                                            <button type="button" class="btn btn-primary"
                                                                id="calculate">
                                                                Calculate
                                                            </button>

                                                            @if (in_array('Item', $choosePermission) || auth()->user()->is_admin == '1')
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
                                                    <tr style="display: none;" class="sub_c"
                                                        style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Total Purchase
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="total_buy_price"
                                                                class="form-control" id="total_buy_price" readonly
                                                                style="background-color: #E9ECEF"
                                                                value="{{ $invoice->total_buy_price }}">

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
                                                                id="invoiceyoghtml" value="{{ $invoice->sub_total }}"
                                                                readonly style="background-color: #E9ECEF">

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
                                                                value="{{ $invoice->discount_total }}"
                                                                id="total_discount">

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
                                                                value="{{ $invoice->total }}" id="total_total"
                                                                readonly style="background-color: #E9ECEF">

                                                        </td>

                                                    </tr>



                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Cash
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2"><input type="text"
                                                                name="paid" class="form-control" id="paid"
                                                                value="{{ $invoice->deposit }}"
                                                                onchange="paidFunction()">

                                                        </td>

                                                    </tr>
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Change Due
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2"><input type="text"
                                                                name="balance" class="form-control" id="balance"
                                                                value="{{ $invoice->remain_balance }}" readonly>

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
                                                                type="submit">Confirm</button>


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
                            var Selectedlocation = $('#location').val();
                            return $.ajax({
                                url: "{{ route('autocomplete.part-code-invoice') }}",
                                method: 'POST',
                                data: {
                                    query: query,
                                    location: Selectedlocation,
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
                            var Selectedlocation = $('#location').val();
                            return $.ajax({
                                url: "{{ route('autocomplete.barcode-invoice') }}",
                                method: 'POST',
                                data: {
                                    query: query,
                                    location: Selectedlocation,
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
                    var Selectedlocation = $('#location').val();

                    if ($("#item_name-0").val() === "") {
                        let cuz_name = $("#type").val();

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('get.part.data-invoice') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                itemname: item,
                                location: Selectedlocation,
                            },
                            success: function(data) {
                                $("#item_name-0").val(data['item']['item_name']);
                                $("#description-0").val(data['item']['descriptions']);
                                $("#exp_date-0").val(data['item']['expired_date']);
                                $("#barcode-0").val(data['item']['barcode']);
                                $("#price-0").val(data['item']['wholesale_price']);
                                $("#item_unit-0").val(data['item']['item_unit']);
                                $("#retail_price-0").val(data['item']['retail_price']);
                                $("#buy_price-0").val(data['item']['buy_price']);
                                $("#warehouse-0").val(data['item']['warehouse_id']);
                                $('#productname').value('');
                                $('#barcode').value('');
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
                                barcode: item,
                                location: Selectedlocation,
                            },
                            success: function(data) {
                                $("#item_name-0").val(data['item']['item_name']);
                                $("#description-0").val(data['item']['descriptions']);
                                $("#exp_date-0").val(data['item']['expired_date']);
                                $("#barcode-0").val(data['item']['barcode']);
                                $("#item_unit-0").val(data['item']['item_unit']);
                                $("#price-0").val(data['item']['wholesale_price']);
                                $("#buy_price-0").val(data['item']['buy_price']);
                                $("#retail_price-0").val(data['item']['retail_price']);
                                $("#warehouse-0").val(data['item']['warehouse_id']);
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

                        if ($("#item_name-").val() === $("#productname").val() || $("#barcode-").val() === $(
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
                                    itemname: item,
                                    location: Selectedlocation,
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
                                    barcode: item,
                                    location: Selectedlocation,
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


                    }


                }
                initializeTypeahead();



                function addNewRow(item) {
                    let cuz_name = $("#type").val();
                    let existingRow = $("#showitem123 input.productname[value='" + item['item_name'] + "']")
                        .closest(
                            'tr');
                    console.log(item['item_name']);
                    console.log(existingRow);
                    if (existingRow.length > 0) {
                        // Item already exists, update quantity
                        let qtyInput = existingRow.find('.req.amnt');
                        let currentQty = parseInt(qtyInput.val()) || 0;
                        qtyInput.val(currentQty + 1);
                        $('#productname').value('');
                        $('#barcode').value('');
                        // Recalculate totals
                    } else {
                        count++;
                        let rowCount = $("#showitem123 tr").length;
                        let newRow = '<tr>' +

                            '<td class="text-center">' + (rowCount + 1) + '</td>' +
                            '<td style="display:none"><input type="hidden" class="form-control barcode typeahead" name="barcode[]" id="barcode-' +
                            count + '" autocomplete="off" value="' + item['barcode'] + '"></td>' +
                            '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="item_name-' +
                            count + '" autocomplete="off" value="' + item['item_name'] + '"></td>' +
                            '<td><input type="text" class="form-control description typeahead" name="part_description[]" id="description-' +
                            count + '" autocomplete="off" value="' + (item['descriptions'] ?? '') + '"></td>' +
                            '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                            count +
                            '" autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                            '<td><input type="text" class="form-control unit " name="item_unit[]" id="item_unit-' +
                            count +
                            '" autocomplete ="off"  value="' + item['item_unit'] +
                            '" required> </td>' +

                            '<td><input type="text" class="form-control price" name="product_price[]" id="price-' +
                            count + '" autocomplete="off" value="' + (item['wholesale_price'] ?? 0) +
                            '"></td>' +
                            '<td><input type="text" class="form-control retail_price" name="retail_price[]" id="retail_price-' +
                            count + '" autocomplete="off" value="' + (item['retail_price'] ?? 0) +
                            '"></td>' +
                            '<td style="display:none"><input type="text" class="form-control buy_price" name="buy_price[]" id="buy_price-' +
                            count + '" autocomplete="off" value="' + (item['buy_price'] ?? 0) +
                            '"></td>' +
                            '<td style="display:none"><input type="text" class="form-control exp_date" name="exp_date[]" id="exp_date-' +
                            count + '" autocomplete="off" value="' + item['expired_date'] + '"></td>' +
                            '<td style="display: none;"><input  type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                            count + '" autocomplete="off" value="' + item['warehouse_id'] + '"></td>' +

                            '<td style="text-align:center"><span class="currenty"></span><strong><span class ="ttlText" id="result-' +
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
                        calculateTotals();
                    }
                }

                // $("#addproduct").click(function(e) {
                //     e.preventDefault();
                //     count++;

                //     let rowCount = $("#showitem123 tr").length;
                //     let newRow = '<tr>' +

                //         '<td class="text-center">' + (rowCount + 1) + '</td>' +
                //         '<td style="display:none"><input type="hidden" class="form-control barcode typeahead" name="barcode[]" id="barcode-' +
                //         count + '" autocomplete="off"></td>' +
                //         '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="item_name-' +
                //         count + '" autocomplete="off"></td>' +
                //         '<td><input type="text" class="form-control description typeahead" name="part_description[]" required id="description-' +
                //         count + '" autocomplete="off"></td>' +
                //         '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                //         count +
                //         '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                //         '<td><input type="text" class="form-control unit " name="item_unit[]" id="item_unit-' +
                //         count +
                //         '" autocomplete ="off" required> </td>' +

                //         '<td><input type="text" class="form-control price" name="product_price[]" value="0" id="price-' +
                //         count + '"   autocomplete="off"></td>' +
                //         '<td><input type="text" class="form-control retail_price" name="retail_price[]" value="0" id="retail_price-' +
                //         count + '"   autocomplete="off"></td>' +
                //         '<td style="display:none"><input type="text" class="form-control exp_date " name="exp_date[]" id="exp_date-' +
                //         count +
                //         '"   autocomplete="off"></td>' +
                //         '<td style="display: none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                //         count +
                //         '"   autocomplete="off"></td>' +

                //         '<td style="text-align:center"><span class="currenty"></span><strong><span class="ttlText" id="result-' +
                //         count + '">0</span></strong></td>' +
                //         '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                //         '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                //         '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' +
                //         count + '" value="0">' +
                //         '<input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">' +
                //         // '<input type="hidden" attr-org="" name="unit[]" id="unit-0" value="">' +
                //         '<input type="hidden" name="unit_m[]" id="unit_m-0" value="1">' +
                //         '<input type="hidden" name="code[]" id="hsn-0" value="">' +
                //         '<input type="hidden" name="serial[]" id="serial-0" value="">' +
                //         '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
                //         '</tr>';
                //     $("#showitem123").append(newRow);
                //     initializeTypeahead(count);
                //     calculateTotals();
                // });


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
                    $(this).val('');

                });
                // Initialize typeahead for the first row
                initializeTypeahead(count);

                $(document).ready(function() {
                    calculateTotals();
                });

                function calculateTotals() {
                    let total = 0;
                    let totalPurchase = 0;
                    let totalTax = 0;
                    let salePriceCategory = $('#sale_price_category').val();

                    $('#showitem123 tr').each(function(index, row) {
                        let qty = parseFloat($(row).find('input[name="product_qty[]"]').val()) || 0;
                        let price = parseFloat($(row).find('input[name="product_price[]"]').val()) || 0;
                        let buy_price = parseFloat($(row).find('input[name="buy_price[]"]').val()) || 0;

                        if (salePriceCategory === 'Default') {
                            let cuz_name = $("#type").val();
                            price = cuz_name === "Whole Sale" ? parseFloat($(row).find(
                                    'input[name="product_price[]"]').val()) || 0 :
                                parseFloat($(row).find('input[name="retail_price[]"]').val()) || 0;
                        } else if (salePriceCategory === 'Whole Sale') {
                            price = parseFloat($(row).find('input[name="product_price[]"]').val()) || 0;
                        } else if (salePriceCategory === 'Retail') {
                            price = parseFloat($(row).find('input[name="retail_price[]"]').val()) || 0;
                        }

                        let taxRate = parseFloat($(row).find('input[name="vat[]"]').val()) || 0;

                        if (!isNaN(taxRate) && taxRate >= 0) {
                            let itemTax = (price * qty * taxRate) / 100;
                            totalTax += itemTax;
                        }

                        let itemTotal = price * qty;
                        let purchase = buy_price * qty;
                        $(row).find('.ttlText').text(itemTotal);

                        total += itemTotal;
                        totalPurchase += purchase;
                    });

                    let taxt = total * 0.05; // Calculate tax based on the updated total
                    taxt = Math.ceil(taxt);
                    let total_total = total - totalTax;
                    let paid = parseFloat(document.getElementById("paid").value) ||
                        0;
                    let total_p = parseFloat(document.getElementById("total_total").value) ||
                        0;
                    let balance = total_total - paid;

                    $("#balance").val(balance);
                    $("#invoiceyoghtml").val(total);
                    $("#total_buy_price").val(totalPurchase);
                    $("#commercial_text").val(totalTax); // Update tax value
                    $("#total").val(total_total);
                    $('#total_total').val(total_total);
                    // $("#total_discount").val('');
                }

                $(document).on("click", '#calculate', function(e) {
                    e.preventDefault();
                    calculateTotals();
                });

                function paidFunction() {
                    let paid = document.getElementById("paid").value;
                    let total_p = document.getElementById("total_total").value;
                    let balance = total_p - paid;
                    $("#balance").val(balance);


                }
            });
        </script>
        <script>
            // $(document).on('click', '.remove_item_btn', function(e) {
            //     e.preventDefault();
            //     let row_item = $(this).parent().parent();
            //     $(row_item).remove();
            //     count--;
            // });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $(document).on("click", '#calculate', function(e) {
            //     e.preventDefault();
            //     let total = 0;
            //     for (let i = 0; i < (count + 1); i++) {
            //         // if ($('#amount-' + i).is(":empty")) {
            //         //     var price = 1;
            //         // } else {
            //         //     var price = parseInt($('#amount-' + i).val()); //get value from amount
            //         // }

            //         var qty = parseInt($('#amount-' + i).val()); //get value from amount
            //         var item_name = $('#productname-' + i).val(); //get value from amount
            //         var sel = $('#focsel-' + i).val(); //get value from amount



            //         let price = parseInt($('#price-' + i).val()); //get vlaue from price

            //         console.log("price" + price)
            //         // console.log("price2"+Object.values(price2))
            //         $("#result-" + i).text((price *
            //             qty));

            //         //  $("#price-"+ i).val(data['retail_sale']);
            //         if (sel >= 1) {
            //             $("#foc-" + i).text('FOC');
            //             price = 0;
            //             // total = 0; //total adding (amount*price)
            //         }
            //         if (sel < 1) {
            //             $("#foc-" + i).text((price * qty));
            //             //  total = total + (price * qty); //total adding (amount*price)
            //         } /// set  (amount*price) to result subtotal for each product

            //         total = total + (price * qty); //total adding (amount*price)

            //     }
            //     let taxt = total * 0.05;
            //     taxt = Math.ceil(taxt);
            //     let total_total = taxt + total;
            //     $("#invoiceyoghtml").val(total); //set  (amount*price)  per invoice  subtotal
            //     // $("#commercial_text").val(taxt); //commercial taxt 5% of total (sub total)
            //     $("#total").val(total_total); //super total
            //     $('#extra_discount').val('');
            //     $('#paid').val('');
            //     $('#balance').val('');
            //     $('#total_total').val(total_total);
            //     $('#total_discount').val('');
            //     // alert("Text:sdfgsdf"+ qty + "count is ;" + count);

            // });






            function paidFunction() {

                let paid = document.getElementById("paid").value;
                let total_p = document.getElementById("total_total").value;
                let balance = total_p - paid;
                $("#balance").val(balance);
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
        {{-- <script>
            //Enter Key click add row
            $(document).on('keydown', '.form-control', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#addproduct').click();
                }
            });
        </script> --}}

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
