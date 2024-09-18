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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->has('phno'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong> {{ $errors->first('phno') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <h1 class="mx-4 mt-3">
            Invoice
        </h1>


        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Register New Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('customer_register') }}" method="POST">
                            @csrf
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="" placeholder="Enter Name"
                                        required autofocus name="name">
                                </div>


                                <div class="form-group mt-3">
                                    <label for="phno">Phone Number</label>
                                    <input type="text" class="form-control" id=""
                                        placeholder="Enter Phone Number" name="phno">
                                </div>

                                <div class="form-group mt-3">
                                    <label for="crc">Customer Type </label>
                                    <select name="type" id="" class="form-control">
                                        <option selected disabled>Select Customer Type</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Whole Sale">Whole Sale</option>
                                    </select>
                                </div>

                                @if (auth()->user()->is_admin == '1')
                                    <div class="form-group mt-3">
                                        <label for="branch">Location<span class="text-danger">*</span></label>

                                        <select name="branch" id="" class="form-control" required>
                                            <option value="" selected disabled>Select Location
                                            </option>
                                            @foreach ($warehouses as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="form-group mt-3">
                                        <label for="branch">Location<span class="text-danger">*</span></label>

                                        <select name="branch" id="" class="form-control" required>
                                            @php
                                                $userPermissions = auth()->user()->level
                                                    ? json_decode(auth()->user()->level)
                                                    : [];
                                            @endphp
                                            <option value="" selected disabled>Select Location
                                            </option>
                                            @foreach ($warehouses as $branch)
                                                @if (in_array($branch->id, $userPermissions))
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif




                                <div class="form-group mt-3">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="phone number"
                                        placeholder="Enter Address" name="address">
                                </div>
                            </div>



                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save </button>
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <form method="post" id="myForm" action="{{ url('invoice_register') }}" enctype="multipart/form-data">
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
                            value="{{ $invoice_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_date" style="font-weight:bolder">Date</label>
                        <input type="date" name="invoice_date" class="form-control" max="{{ date('Y-m-d') }}"
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="overdue_date" class=" caption"
                            style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>
                        <input type="date" name="overdue_date" id="overdue_date" class="form-control round"
                            autocomplete="off" min="<?= date('Y-m-d') ?>" value="{{ date('Y-m-d') }}">
                    </div>

                    {{-- <div class="col-md-3 ">
                        <label for="payment_method" style="font-weight:bolder">{{ trans('Payment Methods') }}</label>
                        <select class="mb-4 form-control round" aria-label="Default select example"
                            name="payment_method" required>
                            <option value="Cash">Cash</option>
                            <option value="K Pay">K Pay</option>
                            <option value="Wave">Wave</option>
                            <option value="Others">Others</option>
                        </select>
                    </div> --}}

                    <input type="hidden" name="quote_category" id="quote_category" value="Invoice">
                </div>
                <hr>
                <div class="content-wrapper">
                    <div class="content-body">
                        <div class="mx-3">
                            <div class="card-content">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-12 cmp-pnl">
                                            <div id="customerpanel" class="inner-cmp-pnl">

                                                <div class="form-group row">
                                                    <div class="frmSearch col-sm-3">

                                                        <div class="frmSearch col-sm-12">
                                                            <div class="frmSearch col-sm-12">
                                                                <span style="font-weight:bolder">
                                                                    <label for="cst"
                                                                        class="caption">{{ trans('Search  Customer Name & Phone No.') }}</label>
                                                                </span>
                                                                <div class="form-group d-flex">
                                                                    <input type="text" id="customer"
                                                                        name="customer"
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

                                                    <div class="col-sm-2 mt-4">
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#modal-lg"
                                                            class="btn btn-primary text-white">Customer
                                                            Register</button>
                                                    </div>

                                                    <div class="frmSearch col-sm-3">
                                                        <div class="frmSearch col-sm-12">
                                                            <div class="frmSearch col-sm-12">
                                                                <span style="font-weight:bolder">
                                                                    <label for="cst" class="caption">Register
                                                                        Mode</label>
                                                                </span>
                                                                <select name="balance_due" id="balance_due"
                                                                    class="mb-4 form-control balance_due" required>

                                                                    <option value="Invoice">Invoice</option>
                                                                    <option value="Po Return">Po Return</option>

                                                                </select>

                                                                <div id="customer-box-result"></div>
                                                            </div>


                                                        </div>



                                                    </div>
                                                    <input type="hidden" id="service_id" name="service_id"
                                                        value="0">


                                                    <input type="hidden" name="manager_type"
                                                        value="{{ Auth::user()->type }}">

                                                    <input type="text" name="status" class="form-control"
                                                        value="draft" style="display: none">

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
                                                                class="form-control"></td>
                                                        <input type='hidden' name='customer_id' id="customer_id"
                                                            class="form-control">
                                                        <input type='hidden' name='status' id="status"
                                                            class="form-control" value="invoice">
                                                        <td class="text-center"><input type='text' name='phno'
                                                                id="phone_no" class="form-control"></td>
                                                        <td class="text-center"><input type='text' name='type'
                                                                id="type" class="form-control"></td>
                                                        <td class="text-center"><input type='text' name='address'
                                                                class="form-control" id="address"></td>
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

                                                    <option value="Default" selected>Default</option>
                                                    <option value="Whole Sale">Whole Sale</option>
                                                    <option value="Retail">Retail</option>
                                                </select>
                                            </div>
                                            {{-- @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin') --}}

                                            @if (auth()->user()->is_admin == '1')
                                                <div class="mt-4 frmSearch col-md-3">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst"
                                                                class="caption">{{ trans('Location') }}&nbsp;</label>
                                                        </span>
                                                        <select name="branch" id="location"
                                                            class="mb-4 form-control location" required>

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


                                            {{-- @else
                                            <div class="mt-4 frmSearch col-md-3">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="cst" class="caption">{{ trans('ChooseLocation') }}&nbsp;</label>
                                                    </span> <select name="location" id="location" class="mb-4 form-control location" required>

                                                        @foreach ($warehouses as $warehouse)
                                                        @if (auth()->user()->level == $warehouse->id)
                                                        <option value="{{ $warehouse->id }}" selected>
                                                            {{ $warehouse->name }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </select>

                                                </div>


                                            </div>
                                            @endif --}}


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
                                                        {{-- <th width="23%" class="text-center">
                                                            {{ trans('Descriptions') }}
                                                        </th> --}}
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
                                                        {{-- <th width="9%" class="text-center">
                                                            {{ trans('Expiry') }}
                                                        </th> --}}

                                                        <th width="10%" class="text-center">
                                                            {{ trans('Discounts') }}
                                                        </th>

                                                        <th width="14%" class="text-center">{{ trans('Amount') }}
                                                            ({{ config('currency.symbol') }})
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody id="showitem123">
                                                    <tr>


                                                        <td class="text-center" id="count">1</td>
                                                        <td>

                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <input type="checkbox" id="sell_status-0"
                                                                        value="1"
                                                                        class="form-check-input sell_status" />

                                                                    <input type="hidden" name="sell_status[]"
                                                                        id="sell_status_input-0"
                                                                        class="form-control sell_status_input"
                                                                        value="0" />
                                                                </div>

                                                                <div class="col">
                                                                    <input type="text"
                                                                        class="form-control productname typeahead item_name"
                                                                        name="part_number[]"
                                                                        value="{{ old('part_number') }}"
                                                                        placeholder="{{ trans('Enter Part Number') }}"
                                                                        id="productname-0" autocomplete="off">
                                                                </div>
                                                            </div>

                                                        </td>

                                                        {{-- <td><input type="text"
                                                                class="form-control description typeahead"
                                                                value="{{ old('part_description') }}"
                                                                name="part_description[]"
                                                                placeholder="{{ trans('') }}" id='description-0'
                                                                autocomplete="off"></td> --}}
                                                        <td><input type="text" class="form-control req amnt"
                                                                name="product_qty[]" id="amount-0"
                                                                autocomplete="off" value="1"><input
                                                                type="hidden" id="alert-0" value=""
                                                                name="alert[]"></td>
                                                        <td><input type="text" class="form-control item_unit"
                                                                name="item_unit[]" id="item_unit-0">

                                                        </td>
                                                        <td><input type="text" class="form-control price"
                                                                name="product_price[]" id="price-0"
                                                                autocomplete="off" value="0">
                                                        </td>
                                                        <td><input type="text" class="form-control retail_price"
                                                                name="retail_price[]" id="retail_price-0"
                                                                autocomplete="off" value="0">
                                                        </td>
                                                        {{-- <td><input type="text" class="form-control exp_date "
                                                                name="exp_date[]" id="exp_date-0" autocomplete="off">
                                                        </td> --}}

                                                        <td style="display: none;"><input type="text"
                                                                class="form-control warehouse " name="warehouse[]"
                                                                id="warehouse-0" autocomplete="off">
                                                        </td>
                                                        <td><input type="text" class="form-control vat "
                                                                name="discount[]" id="vat-0" value="0"
                                                                autocomplete="off" value="{{ old('discount') }}">
                                                        </td>

                                                        <td style="text-align:center">
                                                            <span class='ttlText' id="foc-0"></span>
                                                            <span
                                                                class="currenty">{{ config('currency.symbol') }}</span>
                                                            <strong>
                                                                <span class='ttlText' id="result-0"></span>
                                                            </strong>
                                                        </td>


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
                                                    <tr class="sub_c" style="display: table-row;">
                                                        <td colspan="2">

                                                        </td>
                                                        <td colspan="3" align="right"><strong>Sub Total
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="sub_total" class="form-control"
                                                                id="invoiceyoghtml" readonly
                                                                style="background-color: #E9ECEF">

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
                                                                class="form-control" id="total_discount">

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
                                                                style="background-color: #E9ECEF">

                                                        </td>

                                                    </tr>


                                                <tbody id="trContainer">
                                                    <tr class="sub_c">
                                                        <td colspan="2"></td>
                                                        <td colspan="3" align="right"><strong>Payment
                                                                Method</strong></td>
                                                        <td align="left" colspan="1" class="col-md-2">
                                                            <input type="text" name="payment_amount[]"
                                                                class="form-control payment_amount"
                                                                id="payment_amount" required>
                                                        </td>
                                                        <td align="left" colspan="1"
                                                            class="col-md-2 payment_method">
                                                            <select name="payment_method[]" id="payment_method"
                                                                class="form-control" required>
                                                                <option value="Cash">Cash</option>
                                                                <option value="K Pay">K Pay</option>
                                                                <option value="Wave">Wave</option>
                                                                <option value="Others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td align="left" colspan="1" class="col-md-1">
                                                            <button type="button" id="addRow"
                                                                class="btn btn-primary"><i
                                                                    class="fa-solid fa-plus"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>



                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="2">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Deposit
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2"><input type="text"
                                                            name="paid" class="form-control" id="paid"
                                                            onchange="paidFunction()">

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
                                                            readonly="">

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
                let item_unit = row.find('.item_unit');
                let retail_price = row.find('.retail_price');
                let warehouse = row.find('.warehouse');
                let buy_price = row.find('.buy_price');
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
                        retail_price.val(data.retail_price);
                        warehouse.val(data.warehouse_id);
                        buy_price.val(data.buy_price);
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

                    '<td><input type="text" class="form-control price" name="product_price[]" value="0" id="price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail_price" name="retail_price[]" value="0" id="retail_price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control vat" name="discount[]" value="0" id="vat-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display : none;"><input type="text" class="form-control buy_price" name="buy_price[]" value="0" id="buy_price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display : none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                    count +
                    '"   autocomplete="off"></td>' +

                    '<td style="text-align:center"><span class="currenty"></span><strong><span class="ttlText1" id="result-' +
                    count + '">0</span></strong></td>' +

                    '<td style="width: 5%;"><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
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

                // Function to add a new row
                $('#addRow').click(function() {
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


            // Initialize typeahead for the first row
            initializeTypeahead(count);

            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                let total_purchase = 0;
                let totalTax = 0;
                let salePriceCategory = $('#sale_price_category').val();
                let total_discount = 0;

                for (let i = 0; i < (count + 1); i++) {
                    var qty = parseInt($('#amount-' + i).val() || 0);
                    var item_name = $('#productname-' + i).val() || 0;
                    var sel = $('#focsel-' + i).val() || 0;
                    var buy_price = parseFloat($('#buy_price-' + i).val() || 0);
                    let price;
                    let discount = 0;

                    if (salePriceCategory === 'Default') {
                        let cuz_name = $("#type").val();
                        price = cuz_name === "Whole Sale" ? parseFloat($('#price-' + i).val() || 0) :
                            parseFloat($('#retail_price-' + i).val() || 0);
                    } else if (salePriceCategory === 'Whole Sale') {
                        price = parseFloat($('#price-' + i).val() || 0);
                    } else if (salePriceCategory === 'Retail') {
                        price = parseFloat($('#retail_price-' + i).val() || 0);
                    }

                    let taxRate = parseFloat($('#vat-' + i).val() || 0);

                    if (!isNaN(taxRate) && taxRate > 0) {
                        discount = taxRate;
                        $("#result-" + i).text((price * qty) - discount);
                    } else {
                        $("#result-" + i).text(price * qty);
                    }

                    total += price * qty;
                    total_purchase += buy_price * qty;
                    total_discount += discount;
                    totalTax += discount;
                }

                let taxt = total
                taxt = Math.ceil(taxt);
                let total_total = total - total_discount;

                $("#invoiceyoghtml").val(total);
                $("#item_discount").val(totalTax);
                $("#total_buy_price").val(total_purchase);
                $("#commercial_text").val(taxt);
                $("#total").val(total_total);
                $('#total_total').val(total_total);
                $('#total_discount').val('');
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
    <script>
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
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('sell_status')) {
                    const checkbox = e.target;
                    const inputId = checkbox.id.replace('sell_status-', 'sell_status_input-');
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.value = checkbox.checked ? '1' : '0';
                    }
                }
            });
        });
    </script>
</body>

</HTML>
