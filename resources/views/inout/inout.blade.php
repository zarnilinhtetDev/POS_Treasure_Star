@include('layouts.header')
<style>
    .changelogout:hover {
        background-color: whitesmoke;
        color: red;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="text-white nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="text-white nav-link" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="text-white btn dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu ">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-1 btn changelogout " style="width: 157px">
                                <i class="fa-solid fa-right-from-bracket "></i> Logout</button>

                        </form>


                    </div>
                </div>
            </ul>
        </nav>
        @include('layouts.sidebar')
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1>In-Out</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">In-Out
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

            </section>
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('out-success'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ session('out-success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row justify-content-center">

                    <div class="mb-0 col-md-11">

                        <div class="row col-md-11" style="width: 80%">
                            <div class="mb-3 "> <a href="{{ url('invoice_record', $items->id) }}" type="button"
                                    class="btn btn-primary">
                                    Invoice Record</a>
                            </div>

                            <div class="mx-2 mb-3"> <a href="{{ url('purchase_record', $items->id) }}" type="button"
                                    class="btn btn-primary">
                                    Purchase Order Record</a>
                            </div>
                            <div class="mb-3"> <a href="{{ url('pos_record', $items->id) }}" type="button"
                                    class="btn btn-primary">
                                    POS Record</a>
                            </div>
                        </div>

                        <table class="table table-bordered" style="background-color: #0B5ED7">
                            <tr>
                                <th style="width: 50%">
                                    <a href="{{ url('item_details', $items->id) }}">
                                        <div class="text-center text-white " style="font-weight: bold;color: black">
                                            Item Name - {{ $items->item_name }}


                                        </div>
                                    </a>
                                </th>
                                <th style="width: 50%">
                                    <a href="{{ url('item_details', $items->id) }}">
                                        <div class="text-center text-white " style="font-weight: bold;color :black">
                                            Quantity -
                                            ( {{ $items->quantity }} )
                                        </div>
                                    </a>
                                </th>
                            </tr>
                        </table>

                    </div>

                    <!-- inout close-->
                    <div class="mt-3 card col-md-5" style="margin: 3%">
                        <div class="col-md-12">
                            <form action="{{ url('in', $id) }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <input type="text" class="mt-3 form-control" id="items_id"
                                            value="{{ $id }}" name="items_id" style="display: none">
                                        <input type="text" class="mt-3 form-control" id="in_out" value="in"
                                            name="in_out" style="display: none">


                                        <div class="form-group col-md-6" style="display: none">
                                            <label for="warehouse_id">Location<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="warehouse_id" id="warehouse_id"
                                                class="form-control" value="{{ $items->warehouse_id }} " readonly>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label for="quantity1">Quantity</label>
                                            <input type="number" class="form-control" id="quantity1"
                                                name="quantity" required min="1">
                                        </div>


                                        <div class="form-group" style="display: none">
                                            <label for="total_quantity1">Total Quantity</label>
                                            <input type="text" class="form-control" id="total_quantity1"
                                                name="total_quantity">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="retail_price">လက်လီစျေး</label>
                                            <input type="number" class="form-control" id="retail_price"
                                                name="retail_price" step="0.01" required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="wholesale_price">လက်ကားစျေး</label>
                                            <input type="number" class="form-control" id="wholesale_price"
                                                name="wholesale_price" step="0.01" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="buy_price">ဝယ်စျေး</label>
                                            <input type="number" class="form-control" id="buy_price"
                                                name="buy_price" step="0.01" required>
                                        </div>

                                        {{-- <div class="mt-3 form-group col-md-4">
                                            <label for="mingalar_market">Mingalar Market</label>
                                            <input type="checkbox" id="mingalar_market" name="mingalar_market"
                                                value="Mingalar Market" class="option-checkbox">
                                        </div>

                                        <div class="mt-3 form-group col-md-4">
                                            <label for="mingalar_market">Company Price</label>
                                            <input type="checkbox" class="option-checkbox" id="company_price"
                                                name="mingalar_market" value="Company">
                                        </div>


                                        <div class="mt-3 form-group col-md-2">
                                            <label for="mingalar_market">Other</label>
                                            <input type="checkbox" class="option-checkbox" id="other"
                                                name="mingalar_market" value="Other">
                                        </div> --}}




                                        <div class="form-group col-md-12">
                                            <label for="date">Date</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                max="{{ date('Y-m-d') }}" required value="{{ date('Y-m-d') }}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="remark">Remark</label>
                                            <textarea rows="4" class="form-control" id="remark" name="remark"></textarea>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <button type="submit" class="mb-3 btn btn-success btn-sm "
                                                style="width: 20%">IN</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>

                    <div class="mt-3 card card-primary col-md-5" style="margin: 3%;">

                        <form action="{{ url('out', $id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <input type="text" class="mt-3 form-control" id="items_id"
                                        value="{{ $id }}" name="items_id" style="display: none">
                                    <input type="text" class="mt-3 form-control" id="in_out" value="out"
                                        name="in_out" style="display: none">



                                    <div class="form-group col-md-6" style="display: none">
                                        <label for="warehouse_id">Location<span class="text-danger">*</span></label>
                                        <input type="text" name="warehouse_id" id="warehouse_id"
                                            class="form-control" value="{{ $items->warehouse_id }}" readonly>
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            required min="1">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="form-group" style="display: none">
                                        <label for="c">Total Quantity</label>
                                        <input type="text" class="form-control" id="total_quantity"
                                            name="total_quantity">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="remark">Remark</label>
                                        <textarea rows="4" class="form-control" id="remark" name="remark"></textarea>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <button type="submit" class="mb-3 btn btn-danger btn-sm "
                                            style="width: 20%">OUT</button>
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>


                    <div class="card col-md-11">
                        <div class="card-body">

                            <table id="example1" class="table table-bordered table-striped table-responsive-lg">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Branch</th>
                                        <th>Total Quantity</th>
                                        <th>In/Out Quantity</th>
                                        {{-- <th>Market</th> --}}
                                        <th style="width: 80px">လက်လီစျေး</th>
                                        <th style="width: 100px">လက်ကားစျေး</th>
                                        <th style="width: 80px"> ဝယ်စျေး</th>
                                        <th>Date</th>
                                        <th>Remark</th>
                                        <th>In/Out</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @php
                                        $no = '1';
                                    @endphp

                                    @foreach ($inouts as $inout)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                @if ($inout->warehouse)
                                                    {{ $inout->warehouse->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                {{ $inout->total_quantity }}
                                            </td>
                                            <td>
                                                {{ $inout->quantity }}
                                            </td>

                                            {{-- <td>
                                                {{ $inout->mingalar_market }}
                                            </td> --}}
                                            <td>{{ number_format($inout->retail_price ?? 0, 2) }}</td>
                                            <td>{{ number_format($inout->wholesale_price ?? 0, 2) }}</td>
                                            <td>{{ number_format($inout->buy_price ?? 0, 2) }}</td>
                                            <td>
                                                {{ $inout->date }}
                                            </td>
                                            <td>
                                                {{ $inout->remark }}
                                            </td>
                                            @if ($inout->in_out === 'in')
                                                <td class="text-center text-white" style="background-color: #51cf86;">
                                                    {{ $inout->in_out }}
                                                </td>
                                            @endif
                                            @if ($inout->in_out === 'out')
                                                <td class="text-center text-white" style="background-color: #c4555b;">
                                                    {{ $inout->in_out }}
                                                </td>
                                            @endif
                                            <td><a href="{{ url('display_print/' . $inout->id, $inout->items_id) }}"
                                                    class="btn btn-primary">Print</a></td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>



                </div>


            </div>
        </div>
    </div>
</body>

<script src="{{ asset('plugins/jquery/jquery.min.js ') }}"></script>
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

<script>
    new DataTable('#example1', {
        // "scrollX": true,
        "lengthChange": false,
        "paging": true,
        "pageLength": 5,

    });
</script>
<script>
    $(document).ready(function() {



        $(document).on("keyup", '#exchange_rate', function(e) {
            e.preventDefault();
            let dolar = $('#purchase_price_in_us').val();
            let rate = $('#exchange_rate').val();
            let result = parseFloat(dolar * rate);
            $('#purchase_price_in_mmk').val(result.toFixed(2));
            // $('#retail_price_in_mmk').val(result.toFixed(2));

        });

    });

    $(document).ready(function() {

        $(document).on("keyup", '#profit_margin', function(e) {
            e.preventDefault();
            let profit_margin = $('#profit_margin').val();
            let retail_price = $('#purchase_price_in_mmk').val();
            let result = (profit_margin * retail_price) / 100;
            let total_result = parseInt(retail_price) + parseInt(result);
            // console.log(total_result);
            $('#retail_price_in_mmk').val((total_result).toFixed(2));
        });
    });




    $(document).ready(function() {



        $(document).on("keyup", '#exchange_rate_yuan', function(e) {
            e.preventDefault();
            let yuan = $('#purchase_price_in_yuan').val();
            let rate = $('#exchange_rate_yuan').val();
            let result = parseFloat(yuan * rate);
            $('#purchase_price_in_mmk').val(result.toFixed(2));
            // $('#retail_price_in_mmk').val(result.toFixed(2));
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let exchangeRateInput = document.getElementById('exchange_rate');

        exchangeRateInput.addEventListener('input', function() {
            // Remove leading zeros
            if (this.value.length > 1 && this.value[0] === '0') {
                this.value = this.value.slice(1);
            }
        });

        exchangeRateInput.addEventListener('blur', function() {
            // If the input is empty, set the value back to "0"
            if (this.value === '') {
                this.value = '0';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        let purchasePriceInput = document.getElementById('purchase_price_in_us');

        purchasePriceInput.addEventListener('input', function() {
            // Remove leading zeros
            if (this.value.length > 1 && this.value[0] === '0') {
                this.value = this.value.slice(1);
            }
        });

        purchasePriceInput.addEventListener('blur', function() {
            // If the input is empty, set the value back to "0"
            if (this.value === '') {
                this.value = '0';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        let purchasePriceInput = document.getElementById('purchase_price_in_yuan');

        purchasePriceInput.addEventListener('input', function() {
            // Remove leading zeros
            if (this.value.length > 1 && this.value[0] === '0') {
                this.value = this.value.slice(1);
            }
        });

        purchasePriceInput.addEventListener('blur', function() {
            // If the input is empty, set the value back to "0"
            if (this.value === '') {
                this.value = '0';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        let exchangeRateYuanInput = document.getElementById('exchange_rate_yuan');

        exchangeRateYuanInput.value = '0'; // Set default value to "0"

        exchangeRateYuanInput.addEventListener('input', function() {
            // Remove leading zeros
            if (this.value.length > 1 && this.value[0] === '0') {
                this.value = this.value.slice(1);
            }
        });

        exchangeRateYuanInput.addEventListener('blur', function() {
            // If the input is empty, set the value back to "0"
            if (this.value === '') {
                this.value = '0';
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.option-checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('.option-checkbox').not(this).prop('checked', false);
            }
        });
    });
</script>
