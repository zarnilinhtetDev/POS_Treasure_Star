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
            right: 12px;
            color: black;
            opacity: 1;
        }
    </style>
    </style>
</head>

<body>

    <div class="container-fluid">

        <h1 class="mt-3">
            Sale Return
        </h1>
        <form method="post" id="data_form" action=" {{ URL('purchase_order_store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">


                <div class="mt-3 col-md-3">

                    <label for="invocieno" class="mt-1 caption"
                        style="font-weight:bolder">{{ trans('Sale Return Number') }}</label>

                    <div class="input-group">
                        <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span>
                        </div>
                        <input type="text" id="invoice_number" name="po_number" class="form-control round"
                            value="{{ $po_no }}" readonly>
                    </div>
                </div>




                <div class="mt-3 col-md-3">
                    <label for="invociedate" class="mt-1 caption"
                        style="font-weight:bolder">{{ trans('Sale Return Date') }}</label>

                    <div class="mb-2 input-group">
                        <div class="input-group-addon"><span class="icon-calendar4" aria-hidden="true"></span>
                        </div>

                        <input type="date" name="po_date" id="invoice_date" class="form-control round "
                            autocomplete="off" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>


                    </div>
                </div>

                <div class="mt-3 col-md-3">
                    <label for="overdue" class="mt-1 caption"
                        style="font-weight:bolder">{{ trans('Payment OverDue Date') }}</label>
                    <div class="mb-2 input-group">
                        <div class="input-group-addon"><span class="icon-calendar4" aria-hidden="true"></span>
                        </div>

                        <input type="date" name="overdue_date" id="overdue_date" class="form-control round "
                            autocomplete="off" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">


                    </div>
                </div>

                <div class="mt-3 col-md-3">
                    <label for="remark" class="mt-1" style="font-weight:bolder">{{ trans('Payment Method') }}
                    </label>
                    <select class="mb-4 form-control round" aria-label="Default select example" name="payment_method"
                        required>

                        <option value="Cash">Cash</option>
                        <option value="Credit">Credit</option>
                        <option value="Consignment Terms">Consignment Terms</option>
                    </select>
                </div>




            </div>

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

            <div class="content-wrapper" style="background-color:aqua">
                <div class="content-body">
                    <div class="card">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="col-sm-6 cmp-pnl">
                                    <div id="customerpanel" class="inner-cmp-pnl">

                                        <div class="form-group row">
                                            @if (auth()->user()->is_admin == '1')
                                                <div class="frmSearch col-md-4">
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
                                                <div class="frmSearch col-md-4">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst"
                                                                class="caption">{{ trans('Location') }}&nbsp;</label>

                                                            <select name="branch" id="location"
                                                                class="form-control location" required>
                                                                @php
                                                                    $userPermissions = auth()->user()->level
                                                                        ? json_decode(auth()->user()->level)
                                                                        : [];
                                                                @endphp
                                                                <option value="" selected disabled>Select
                                                                    Location
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
                                                </div>
                                            @endif


                                            <div class="frmSearch col-sm-4">
                                                <div class="frmSearch col-sm-12">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst" class="caption">Receiving Mode
                                                            </label>
                                                        </span>
                                                        <select name="balance_due" id="balance_due"
                                                            class="mb-4 form-control balance_due" required>


                                                            <option value="Sale Return">Sale Return</option>

                                                        </select>

                                                        <div id="customer-box-result"></div>
                                                    </div>


                                                </div>



                                            </div>



                                        </div>
                                    </div>
                                    <div class="col-sm-6 cmp-pnl">

                                        <div class="inner-cmp-pnl">

                                        </div>
                                    </div>
                                </div>



                                <input type="hidden" value="invoice" name="status">

                                <div class="row " style="margin-top:1vh;">
                                    <!-- <table class="table-responsive tfr my_stripe"> -->
                                    <table class="table mt-3">
                                        <thead style="background-color:#0047AA;color:white; border: 1px solid white;">
                                            <tr class="item_header bg-gradient-directional-blue white"
                                                style="margin-bottom:10px;">
                                                <th width="5%" class="text-center">{{ trans('No') }}</th>
                                                <th width="18%" class="text-center">{{ trans('Item Name') }}
                                                </th>
                                                <th width="23%" class="text-center">
                                                    {{ trans('Item Descriptions') }}
                                                </th>
                                                <th width="8%" class="text-center">{{ trans('Quantity') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Unit') }}
                                                </th>

                                                <th width="7%" class="text-center">{{ trans('Unit Price') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Expiry') }}
                                                </th>


                                                <th width="14%" class="text-center">{{ trans('Amount') }}
                                                    ({{ config('currency.symbol') }})
                                                </th>

                                            </tr>

                                        </thead>
                                        <tbody id="showitem123">
                                            <tr>
                                                <td class="text-center" id="count">1</td>
                                                <td><input type="text" class="form-control productname typeahead"
                                                        name="part_number[]"
                                                        placeholder="{{ trans('Enter Part Number') }}"
                                                        id='productname-0' autocomplete="off">
                                                </td>
                                                <td><input type="text" class="form-control description typeahead"
                                                        name="part_description[]" placeholder="{{ trans('') }}"
                                                        id='description-0' autocomplete="off"></td>
                                                <td><input type="text" class="form-control req amnt"
                                                        name="product_qty[]" id="amount-0" autocomplete="off"
                                                        value="1"><input type="hidden" id="alert-0"
                                                        value="" name="alert[]"></td>
                                                <!-- <td><input type="text" class="form-control unit " name="unit[]" id="unit-0" autocomplete="off">  </td> -->
                                                <td>
                                                    <input type="text" name="item_unit[]"
                                                        class="form-control item_unit" id="item_unit-0">
                                                </td>



                                                <td><input type="text" class="form-control price"
                                                        name="product_price[]" id="price-0" autocomplete="off"
                                                        value="0">
                                                </td>
                                                <td><input type="text" class="form-control exp_date "
                                                        name="exp_date[]" id="exp_date-0" autocomplete="off">
                                                </td>
                                                <td style="display: none;"><input type="text"
                                                        class="form-control warehouse " name="warehouse[]"
                                                        id="warehouse-0" autocomplete="off">
                                                </td>


                                                <td style="text-align:center">
                                                    <span class='ttlText' id="foc-0"></span>
                                                    <span class="currenty">{{ config('currency.symbol') }}</span>
                                                    <strong>
                                                        <span class='ttlText' id="result-0"></span>
                                                    </strong>
                                                </td>
                                                <input type="hidden" class="form-control vat " name="product_tax[]"
                                                    id="vat-0" value="0">
                                                <input type="hidden" name="total_tax[]" id="taxa-0"
                                                    value="0">
                                                <input type="hidden" name="total_discount[]" id="disca-0"
                                                    value="0">
                                                <input type="hidden" class="ttInput" name="product_subtotal[]"
                                                    id="total-0" value="0">
                                                <input type="hidden" class="pdIn" name="product_id[]"
                                                    id="pid-0" value="0">

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
                                                    <button type="button" class="btn btn-success" id="addproduct"
                                                        style="margin-top:20px;margin-bottom:20px;">
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
                                                        id="total_total" readonly style="background-color: #E9ECEF">

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


                                                    <a href="{{ url('sale_return') }}" type="submit"
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

        </form>
    </div>

    </div>
    <script>
        function handleKeyUp(event) {
            console.log(`Key pressed: ${event.key}`);
            // You can add more logic here to respond to the event
        }
    </script>
    <script></script>







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

            // alert("Text:sdfgsdf"+ qty + "count is ;" + count);

        });






        function paidFunction() {

            let paid = document.getElementById("paid").value;
            let total_p = document.getElementById("total_total").value;
            let balance = total_p - paid;
            $("#balance").val(balance); //update balance
        }
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
        $(document).on('keydown', '.form-control', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#addproduct').click();

            }
        });
    </script>
    <script>
        $(document).ready(function() {
            let count = 0;

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

            function updateItemName(item_name, row) {
                let itemNameInput = row.find('.price');
                let partDesc = row.find('.description');
                let exp_date = row.find('.exp_date');
                let item_unit = row.find('.item_unit');
                var Selectedlocation = $('#location').val();
                let warehouse = row.find('.warehouse');


                $.ajax({

                    type: 'POST',
                    url: "{{ route('get-part-data-invoice') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        item_name: item_name,
                        location: Selectedlocation,
                    },
                    success: function(data) {
                        itemNameInput.val(data.buy_price);
                        partDesc.val(data.descriptions);
                        exp_date.val(data.expired_date);
                        item_unit.val(data.item_unit);
                        warehouse.val(data.warehouse_id);

                        // alert(data.expired_date);

                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }


            $("#addproduct").click(function(e) {
                e.preventDefault();


                // Assuming {{ $units }} is a string representation of an array



                count++;
                let rowCount = $("#showitem123 tr").length;
                let newRow = '<tr>' +
                    '<td class="text-center">' + (rowCount + 1) + '</td>' +
                    '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="productname-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control description typeahead" name="part_description[]"  id="description-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                    count +
                    '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                    '<td><input type="text" class="form-control item_unit" name="item_unit[]" id="item_unit-' +
                    count +
                    '"   autocomplete="off"></td>' +



                    '<td><input type="text" class="form-control price" name="product_price[]" value="0" id="price-' +
                    count + '"   autocomplete="off"></td>' +

                    '<td><input type="text" class="form-control exp_date " name="exp_date[]" id="exp_date-' +
                    count +
                    '"   autocomplete="off"></td>' +
                    '<td style="display : none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                    count +
                    '"   autocomplete="off"></td>' +
                    // '<td class="text-center" id="texttaxa-0">0</td>'

                    //   '<td></td>' +
                    //   '<td><input type="text" class="form-control discount" name="product_discount[]"id="discount-' +
                    //   count + '"  autocomplete="off"></td>' +
                    '<td style="text-align:center"><span class="currenty"></span><strong><span id="result-' +
                    count + '">0</span></strong></td>' +
                    '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                    '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                    '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' +
                    count + '" value="0">' +
                    '<input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">' +

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
                updateItemName(itemCode, row);
            });

            // Initialize typeahead for the first row
            initializeTypeahead(count);

            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                let totalTax = 0;

                for (let i = 0; i < (count + 1); i++) {
                    var qty = parseInt($('#amount-' + i).val()) || 0;
                    var item_name = $('#productname-' + i).val() || 0;
                    let price = parseInt($('#price-' + i).val()) || 0;
                    let taxRate = parseFloat($('#vat-' + i).val()) || 0;

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
                $("#commercial_text").val(totalTax); // Update tax value
                $("#total").val(total_total);
                $("#extra_discount").val('');
                $("#paid").val('');
                $("#balance").val('');
                $('#total_total').val(total_total);
                $('#total_discount').val('');
            });

            function paidFunction() {
                let paid = document.getElementById("paid").value;
                let total_p = document.getElementById("invoiceyoghtml").value;
                let balance = total_p - paid;
                $("#balance").val(balance);
            }
            document.getElementById('submitButton').addEventListener('click', function() {
                var serviceType = document.getElementById('unit-' + count).value;
                var serviceTypeError = document.getElementById('uniterror');
                if (serviceType === 'Choose Unit') {
                    serviceTypeError.style.display = 'block';
                } else {
                    serviceTypeError.style.display = 'none';
                    // Add your code to handle the submission without a form
                }
            });

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