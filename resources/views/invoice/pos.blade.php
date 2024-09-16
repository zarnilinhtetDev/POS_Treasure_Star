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
    <!-- As a link -->
    <nav class="navbar navbar-light bg-light justify-content-between">
        <h1 class="mx-4">POS</h1>
        @if (session('success'))
            <h4 class="text-success">{{ session('success') }}</h4>
        @endif
        @if (session('delete'))
            <h4 class="text-danger">{{ session('delete') }}</h4>
        @endif
        <form class="form-inline">

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

            <div class="row">
                <div class="col">
                    <a href="{{ url('sale_return_register') }}" type="button" class="mr-auto btn btn-primary ">
                        Sale Return</a>
                </div>
                @if (in_array('Suspend', $choosePermission) || auth()->user()->is_admin == '1')
                    <div class="col">
                        <button type="button" class="mx-2 btn btn-primary" data-toggle="modal" data-target="#modal-xl">
                            Suspended
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </nav>

    <div class="container-fluid " id="content">
        <hr>

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


        <div class="modal fade" id="modal-xl">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Suspended List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Your HTML code -->
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>POS No.</th>
                                        <th>Customer Name</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = ' 1';
                                    @endphp
                                    @foreach ($suspends as $suspend)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $suspend->invoice_no }}</td>
                                            <td>{{ $suspend->customer_name ?? 'N/A' }}</td>
                                            <td>{{ $suspend->total }}</td>
                                            <td>{{ date('Y-m-d', strtotime($suspend->created_at)) }}
                                            </td>
                                            <td>
                                                <a href="{{ url('suspend_delete', $suspend->id) }}"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this Suspend ?')">
                                                    Delete</a>
                                                <a href="{{ url('invoice_edit', $suspend->id) }}"
                                                    class="btn btn-primary">Unsuspend</a>
                                            </td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


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

            <div class="mx-3 row ">

                <div class="mt-4 row">
                    <div class="col-md-3">
                        <label for="invoice_no" style="font-weight:bolder">POS Number</label>
                        <input type="text" id="invoice_no" class="form-control" name="invoice_no"
                            value="{{ $invoice_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_date" style="font-weight:bolder">Date</label>
                        <input type="date" name="invoice_date" class="form-control" max="<?php echo date('Y-m-d'); ?>"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="col-md-3" style="display:none">
                        <label for="overdue_date" class=" caption"
                            style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>
                        <input type="date" name="overdue_date" id="overdue_date" class="form-control round"
                            autocomplete="off" min="<?= date('Y-m-d') ?>">
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

                    <input type="hidden" name="quote_category" id="quote_category" value="POS">
                </div>
                <div class="content-wrapper">
                    <div class="content-body">
                        <div class="">
                            <div class="card-content">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-12 cmp-pnl">
                                            <div id="customerpanel" class="inner-cmp-pnl">
                                                <div class="mt-3 form-group row">
                                                    <div class="frmSearch col-sm-3">
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


                                                    <div class="col-sm-2 mt-4">
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#modal-lg"
                                                            class="btn btn-primary text-white">Customer
                                                            Register</button>
                                                    </div>

                                                    <input type="hidden" id="service_id" name="service_id"
                                                        value="0">


                                                    <input type="hidden" name="manager_type"
                                                        value="{{ Auth::user()->type }}">


                                                </div>
                                            </div>
                                            <div class="col-sm-3 cmp-pnl">

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
                                                                class="form-control"></td>
                                                        <input type='hidden' name='customer_id' id="customer_id"
                                                            class="form-control">
                                                        <input type='hidden' name='status' id="status"
                                                            class="form-control" value="pos">
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
                                        <hr>
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
                                                <input type="text"
                                                    class="form-control productname typeahead barcode-input"
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

                                                <option value="Default" selected>Default</option>
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
                                                        <th width="5%" class="text-center">{{ trans('No.') }}
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
                                                        <th style="display: none;"width="9%" class="text-center">
                                                            {{ trans('၀ယ်စျေး') }}
                                                        </th>
                                                        <!-- <th width="9%" class="text-center">
                                                            {{ trans('Expiry') }}
                                                        </th> -->

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
                                                        {{-- <input type='hidden' name='sale_by' id="sale_by"
                                                            value="{{ auth()->user()->name }}" class="form-control"> --}}
                                                        <input type="hidden" class="form-control barcode typeahead"
                                                            name="barcode[]" value="{{ old('barcode') }}"
                                                            placeholder="{{ trans('Enter BarCode') }}" id='barcode-0'
                                                            autocomplete="off">

                                                        <td class="text-center" id="count">1</td>
                                                        <td><input type="text"
                                                                class="form-control productname typeahead"
                                                                name="part_number[]" value="{{ old('part_number') }}"
                                                                placeholder="{{ trans('Enter Part Number') }}"
                                                                id='item_name-0' autocomplete="off">
                                                        </td>

                                                        <td><input type="text"
                                                                class="form-control description typeahead"
                                                                value="{{ old('part_description') }}"
                                                                name="part_description[]"
                                                                placeholder="{{ trans('') }}" id='description-0'
                                                                autocomplete="off"></td>
                                                        <td><input type="text" class="form-control req amnt"
                                                                name="product_qty[]" id="amount-0"
                                                                autocomplete="off" value="1"><input
                                                                type="hidden" id="alert-0" value=""
                                                                name="alert[]"></td>
                                                        <td><input type="text" class="form-control unit"
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
                                                        <td style="display: none;"><input type="text"
                                                                class="form-control buy_price" name="buy_price[]"
                                                                id="buy_price-0" autocomplete="off" value="0">
                                                        </td>
                                                        <td style="display:none;"><input type="hidden"
                                                                class="form-control exp_date " name="exp_date[]"
                                                                id="exp_date-0" autocomplete="off">
                                                        </td>
                                                        <td style="display: none;"><input type="text"
                                                                class="form-control warehouse " name="warehouse[]"
                                                                id="warehouse-0" autocomplete="off">
                                                        </td>


                                                        <td style="text-align:center">
                                                            <span class='ttlText' id="foc-0"></span>
                                                            <span
                                                                class="currenty">{{ config('currency.symbol') }}</span>
                                                            <strong>
                                                                <span class='ttlText' id="result-0"></span>
                                                            </strong>
                                                        </td>
                                                        <input type="hidden" class="form-control vat "
                                                            name="product_tax[]" id="vat-0" value="0">
                                                        <input type="hidden" name="total_tax[]" id="taxa-0"
                                                            value="0">
                                                        <input type="hidden" name="total_discount[]" id="disca-0"
                                                            value="0">
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
                                                        <td><button type="submit"
                                                                class="btn btn-danger remove_item_btn"
                                                                id="removebutton">Remove</button></td>

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
                                                                style="margin-top:20px;margin-bottom:20px;display:none;">
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
                                                                style="background-color: #E9ECEF">

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
                                                        <td colspan="3" align="right"><strong>Discount
                                                            </strong>
                                                        </td>
                                                        <td align="left" colspan="2" class="col-md-4"><input
                                                                type="text" name="discount" class="form-control"
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
                                                                id="payment_amount">
                                                        </td>
                                                        <td align="left" colspan="1"
                                                            class="col-md-2 payment_method">
                                                            <select name="payment_method[]" id="payment_method"
                                                                class="form-control">
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


                                                <tr class="sub_c">
                                                    <td colspan="2">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Cash
                                                        </strong>
                                                    </td>
                                                    <td align="left" colspan="2"><input type="text"
                                                            name="paid" class="form-control" id="paid"
                                                            onchange="paidFunction()">

                                                    </td>

                                                </tr>
                                                <tr class="sub_c">
                                                    <td colspan="2">

                                                    </td>
                                                    <td colspan="3" align="right"><strong>Change Due
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
                                                        @if (in_array('Suspend', $choosePermission) || auth()->user()->is_admin == '1')
                                                            <button id="suspend" class="mt-3 btn btn-primary"
                                                                type="submit">Suspend</button>
                                                        @endif
                                                        <button id="submitButton" class="mt-3 btn btn-primary"
                                                            type="submit">Save</button>


                                                        <a href="{{ url('pos') }}" type="submit"
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
                    //close for barcode suggestion
                    $('#').typeahead({
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
                    // Cache DOM elements
                    var $location = $('#location');
                    var $itemName0 = $("#item_name-0");
                    var $description0 = $("#description-0");
                    var $expDate0 = $("#exp_date-0");
                    var $barcode0 = $("#barcode-0");
                    var $price0 = $("#price-0");
                    var $itemUnit0 = $("#item_unit-0");
                    var $retailPrice0 = $("#retail_price-0");
                    var $buyPrice0 = $("#buy_price-0");
                    var $warehouse0 = $("#warehouse-0");
                    var $productname = $("#productname");
                    var $barcode = $("#barcode");
                    var $amount0 = $("#amount-0");

                    var selectedLocation = $location.val();

                    var cuzName = $("#type").val();

                    function handleSuccess(data) {
                        var item = data['item'];
                        $itemName0.val(item['item_name']);
                        $description0.val(item['descriptions']);
                        $expDate0.val(item['expired_date']);
                        $barcode0.val(item['barcode']);
                        $price0.val(item['wholesale_price']);
                        $itemUnit0.val(item['item_unit']);
                        $retailPrice0.val(item['retail_price']);
                        $buyPrice0.val(item['buy_price']);
                        $warehouse0.val(item['warehouse_id']);
                        $productname.val('');
                        $barcode.val('');

                        if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                            alert(data.quantity + " quantity!");
                        }
                    }

                    function handleError(xhr, status, error) {
                        console.error(xhr.responseText);
                    }

                    if ($itemName0.val() === "") {


                        var $barcodeInput = $('.barcode-input');
                        var item_barcode = $barcodeInput.val();

                        if (item_barcode.length > 5) {
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('get.barcode.data-invoice') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    barcode: item,
                                    location: selectedLocation,
                                },
                                success: handleSuccess,
                                error: handleError
                            });
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('get.part.data-invoice') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    itemname: item,
                                    location: selectedLocation,
                                },
                                success: handleSuccess,
                                error: handleError
                            });

                        }


                    } else {
                        if ($itemName0.val() === $productname.val() || $barcode0.val() === $barcode.val()) {
                            var currentQuantity = parseInt($amount0.val());
                            $amount0.val(currentQuantity + 1);
                            $barcode.val('');
                            $productname.val('');

                        } else {

                            var $barcodeInput = $('.barcode-input');
                            var item_barcode = $barcodeInput.val();
                            if (item_barcode.length > 5) {
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('get.barcode.data-invoice') }}",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        barcode: item,
                                        location: selectedLocation,
                                    },
                                    success: function(data) {
                                        if (parseFloat(data.reorder_level_stock) >= parseFloat(data
                                                .quantity)) {
                                            alert(data.quantity + " quantity!");
                                        }
                                        addNewRow(data['item']);
                                        $barcode.val('');
                                        $productname.val('');
                                    },
                                    error: handleError
                                });
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('get.part.data-invoice') }}",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        itemname: item,
                                        location: selectedLocation,
                                    },
                                    success: function(data) {
                                        if (parseFloat(data.reorder_level_stock) >= parseFloat(data
                                                .quantity)) {
                                            alert(data.quantity + " quantity!");
                                        }
                                        addNewRow(data['item']);
                                        $barcode.val('');
                                        $productname.val('');
                                    },
                                    error: handleError
                                });
                            }

                        }
                    }
                }

                initializeTypeahead();


                function addNewRow(item) {
                    let cuz_name = $("#type").val();
                    let existingRow = $("#showitem123 input.productname[value='" + item['item_name'] + "']")
                        .closest(
                            'tr');

                    console.log(existingRow.length);
                    if (existingRow.length > 0) {
                        console.log('error');
                        // Item already exists, update quantity
                        let qtyInput = existingRow.find('.req.amnt');
                        let currentQty = parseInt(qtyInput.val()) || 0;
                        qtyInput.val(currentQty + 1);

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
                            '<td><input type="text" class="form-control description typeahead" name="part_description[]"  id="description-' +
                            count + '" autocomplete="off" value="' + (item['descriptions'] ? item['descriptions'] :
                                '') + '"></td>' +
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
                            '<td style="display: none;"><input type="text" class="form-control buy_price" name="buy_price[]" id="buy_price-' +
                            count + '" autocomplete="off" value="' + (item['buy_price'] ?? 0) +
                            '"></td>' +
                            '<td style="display: none;"><input type="text" class="form-control exp_date" name="exp_date[]" id="exp_date-' +
                            count + '" autocomplete="off" value="' + item['expired_date'] + '"></td>' +
                            '<td style="display: none;"><input  type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                            count + '" autocomplete="off" value="' + item['warehouse_id'] + '"></td>' +
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

                //Drop down list for item name


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

                $(document).on('change', '#barcode', function() {
                    let itemCode = $(this).val();
                    let row = $(this).closest('tr');
                    let cuz_name = $("#type").val();
                    updateItemName(itemCode, row, cuz_name);
                    $('#barcode').val('');
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
                    let totalTax = 0;
                    let total_purchase = 0;
                    let salePriceCategory = $('#sale_price_category').val();
                    console.log(salePriceCategory);

                    for (let i = 0; i < (count + 1); i++) {
                        var qty = parseInt($('#amount-' + i).val() || 0);
                        var item_name = $('#productname-' + i).val() || 0;
                        var sel = $('#focsel-' + i).val() || 0;
                        let taxRate = parseFloat($('#vat-' + i).val() || 0);
                        var buy_price = $('#buy_price-' + i).val() || 0;

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
                        total_purchase += buy_price * qty;
                    }

                    let taxt = total * 0.05; // Calculate tax based on the updated total
                    taxt = Math.ceil(taxt);
                    let total_total = total - totalTax;
                    $("#invoiceyoghtml").val(total);
                    $("#total_buy_price").val(total_purchase);
                    $("#commercial_text").val(
                        totalTax);
                    $("#total").val(total_total);
                    $('#total_total').val(total_total);
                    // $("#total_discount").val('');
                });

            });

            function paidFunction() {

                let paid = document.getElementById("paid").value;
                let total_p = document.getElementById("total_total").value;
                let balance = total_p - paid;
                $("#balance").val(balance);
            }

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

            $(document).on("input", "#total_discount", function() {
                let subtotal = parseFloat($("#invoiceyoghtml").val()) || 0;
                let discount = parseFloat($(this).val()) || 0;
                let total = subtotal - discount;
                $("#total_total").val(total);
            });


            document.getElementById("suspend").addEventListener("click", function() {
                setStatus("suspend");
            });

            document.getElementById("submitButton").addEventListener("click", function() {
                setStatus("pos");
            });

            function setStatus(status) {
                document.getElementById("status").value = status;
                document.getElementById("myForm").submit();
            }
        </script>


        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</HTML>
