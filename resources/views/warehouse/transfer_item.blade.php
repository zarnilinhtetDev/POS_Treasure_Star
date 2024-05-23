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

</head>
@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <div class="btn-group">
                    <button type="button" class="btn text-white dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu ">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn  p-1 changelogout " style="width: 157px">
                                <i class="fa-solid fa-right-from-bracket "></i> Logout</button>

                        </form>


                    </div>
                </div>



            </ul>
        </nav>
        @include('layouts.sidebar')
        <div class="container-fluid">
            <form action=" {{ url('store_transfer_item') }}" method="POST">
                @csrf
                <div class="content-wrapper">
                    <div class="content-body">
                        <div class="card col-md-11 mx-auto my-4">
                            <div class="card-header">
                                <h3 class="card-title">Transfer Items</h3>
                            </div>
                            <div class="card-content">

                                <div class="card-body">
                                    <div class="col-sm-6 cmp-pnl">
                                        <div id="customerpanel" class="inner-cmp-pnl">

                                            <div class="form-group row">
                                                <div class="frmSearch col-sm-12">
                                                    <div class="row">
                                                        @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin')
                                                            <div class="frmSearch col-sm-3">
                                                                <label for="from" style="font-weight:bolder">From
                                                                    Location</label>
                                                                <select name="from_location" id="from_location"
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Choose
                                                                        Location
                                                                    </option>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        <option value="{{ $warehouse->id }}">
                                                                            {{ $warehouse->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @else
                                                            <div class="frmSearch col-sm-3">
                                                                <label for="from" style="font-weight:bolder">From
                                                                    Location</label>
                                                                <select name="from_location" id="from_location"
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Choose
                                                                        Location
                                                                    </option>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if (auth()->user()->level == $warehouse->id)
                                                                            <option value="{{ $warehouse->id }}">
                                                                                {{ $warehouse->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                        <div class="frmSearch col-sm-3">
                                                            <label for="to" style="font-weight:bolder">To
                                                                Location</label>
                                                            <select name="to_location" id="to_location"
                                                                class="form-control">
                                                                <option value="" selected disabled>Choose Location
                                                                </option>
                                                                @foreach ($warehouses as $warehouse)
                                                                    <option value="{{ $warehouse->id }}">
                                                                        {{ $warehouse->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="frmSearch col-sm-3">
                                                            <label for="invoice_date"
                                                                style="font-weight:bolder">{{ trans(' Date') }}</label>

                                                            <div class="input-group mb-2">
                                                                <div class="input-group-addon"><span
                                                                        class="icon-calendar4"
                                                                        aria-hidden="true"></span>
                                                                </div>

                                                                <input type="date" name="date" id="date"
                                                                    class="form-control round " autocomplete="off"
                                                                    max="<?= date('Y-m-d') ?>" required>


                                                            </div>
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
                                        <table class="">
                                            <thead
                                                style="background-color:#0047AA;color:white; border: 1px solid white;">
                                                <tr class="item_header bg-gradient-directional-blue white"
                                                    style="margin-bottom:10px;">
                                                    <th width="5%" class="text-center">{{ trans('No') }}</th>
                                                    <th width="18%" class="text-center">{{ trans('Item Name') }}
                                                    </th>
                                                    <th width="18%" class="text-center">
                                                        {{ trans('Total Quantity') }}
                                                    </th>

                                                    <th width="8%" class="text-center">{{ trans('Quantity') }}
                                                    </th>


                                                </tr>

                                            </thead>
                                            <tbody id="showitem123">
                                                <tr>
                                                    <td class="text-center" id="count">1</td>
                                                    <td><input type="text"
                                                            class="form-control productname typeahead"
                                                            name="part_number[]"
                                                            placeholder="{{ trans('Enter Part Number') }}"
                                                            id='productname-0' autocomplete="off">
                                                    </td>
                                                    <td><input type="text" class="form-control total_product_qty"
                                                            name="total_product_qty[]" id="total_amount-0"
                                                            autocomplete="off">
                                                    <td><input type="text" class="form-control req amnt"
                                                            name="product_qty[]" id="amount-0" autocomplete="off"
                                                            value="1"><input type="hidden" id="alert-0"
                                                            value="" name="alert[]"></td>


                                                    <input type="hidden" class="form-control vat "
                                                        name="product_tax[]" id="vat-0" value="0">
                                                    <input type="hidden" name="total_tax[]" id="taxa-0"
                                                        value="0">
                                                    <input type="hidden" name="total_discount[]" id="disca-0"
                                                        value="0">
                                                    <input type="hidden" class="ttInput" name="product_subtotal[]"
                                                        id="total-0" value="0">
                                                    <input type="hidden" class="pdIn" name="product_id[]"
                                                        id="pid-0" value="0">

                                                    <input type="hidden" name="unit_m[]" id="unit_m-0"
                                                        value="1">
                                                    <input type="hidden" name="code[]" id="hsn-0"
                                                        value="">
                                                    <input type="hidden" name="serial[]" id="serial-0"
                                                        value="">
                                                    {{-- <td></td> --}}
                                                </tr>
                                            </tbody>

                                            <tr class="last-item-row sub_c">
                                                <td></td>
                                                <td class="add-row">
                                                    <button type="button" class="btn btn-success" id="addproduct"
                                                        style="margin-top:30px;margin-bottom:20px;">
                                                        <i class="fa fa-plus-square"></i> {{ trans('Add row') }}
                                                    </button>

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
                                            <tbody id="showitem">
                                                <tr style="display: table-row;">
                                                    <td></td>
                                                    <td colspan="">
                                                    </td>
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




                                                </tr>

                                                <!-- <tr class="sub_c " style="display: table-row;">
                                                <td colspan="12"> <label for="remark">Remark</label>
                                                    <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                                </td>
                                            </tr> -->
                                                <tr class="sub_c " style="display: table-row;">


                                                    <td align="right" colspan="9">

                                                        <button id="submitButton" class="mt-3 btn btn-danger"
                                                            type="submit">Transfer</button>


                                                        <a href="{{ url('create_vehicle') }}" type="submit"
                                                            class="mt-3 btn btn-warning">Cancel
                                                        </a>

                                                    </td>
                                                </tr>
                                            </tbody>
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
    </div>
    </div>
    </div>

    </div>
    <script>
        function handleKeyUp(event) {
            console.log(`Key pressed: ${event.key}`);
            // You can add more logic here to respond to the event
        }
    </script>


    <script>
        $(document).ready(function() {
            $('#item-0').typeahead({
                source: function(query, process) {
                    return $.ajax({
                        url: "{{ route('get.part.data-location') }}",
                        method: 'POST',
                        data: {
                            query: query
                        },
                        dataType: 'json',
                        success: function(data) {
                            process(data);
                        }
                    });
                }
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            let count = 0;

            function initializeTypeahead(count) {

                var previousSelection = ''; // Variable to store the previously selected option
                $('#productname-' + count).typeahead({

                    source: function(query, process) {
                        // Get the selected value from the select box
                        var selectedLocation = $('#from_location').val();

                        return $.ajax({
                            url: "{{ url('autocomplete-part-code-location') }}",
                            method: 'POST',
                            data: {
                                query: query,
                                location: selectedLocation // Pass the selected location to the server
                            },
                            dataType: 'json',
                            success: function(data) {
                                process(data);
                            }
                        });
                    }
                });
                $('#from_location').change(function() {
                    var selectedValue = $(this).val();

                    // Clear the selected value in the 'to_location' select box
                    $('#to_location').val('');

                    // Re-show the previously hidden option (if any)
                    if (previousSelection !== '') {
                        $('#to_location option[value="' + previousSelection + '"]').show();
                    }

                    // Hide the newly selected option
                    $('#to_location option[value="' + selectedValue + '"]').hide();

                    // Update the previous selection to the current one
                    previousSelection = selectedValue;
                });
            }

            function initializeTypeaheads() {
                for (let i = 0; i <= count; i++) {
                    initializeTypeahead(i);
                }
            }

            function updateItemName(item_name, row) {
                let itemNameInput = row.find('.price');
                let toal_quantity = row.find('.total_product_qty');
                var selectedLocation = $('#from_location').val();


                $.ajax({

                    type: 'POST',
                    url: "{{ url('get-part-data-location') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        item_name: item_name,
                        location: selectedLocation
                    },
                    success: function(data) {
                        itemNameInput.val(data.retail_price);
                        toal_quantity.val(data.quantity);

                        // alert(data.expired_date);

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
                    '<td style="width:30%"><input type="text" class="form-control productname typeahead" name="part_number[]" id="productname-' +
                    count + '" autocomplete="off"></td>' +
                    '<td style=""><input type="text" class="form-control  total_product_qty" name="total_product_qty[]" id="total_amount-' +
                    count +
                    '"   autocomplete="off" ></td>' +

                    '<td style="width:30%"><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                    count +
                    '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +




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

    <!--Enter Key click add row-->
    <script>
        $(document).on('keydown', '.form-control', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#addproduct').click();

            }
        });
    </script>


    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="../../dist/js/demo.js"></script> --}}
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
                // "buttons": ["excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 30,
                "buttons": ["excel", "pdf", "print"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</HTML>
