@include('layouts.header')

<style>
    .nav-tabs .nav-link {
        color: #007FFF;
        transition: color 0.3s, background-color 0.3s;
    }

    .nav-tabs .nav-link:hover {
        color: #0056b3;
        background-color: #e6f7ff;
    }

    .nav-tabs .nav-link.active {
        color: #ffffff;
        background-color: #007FFF;
        border-color: #007FFF;
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
        @include('layouts.sidebar') <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
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

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Item Report</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Item Report</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>



                <div class="ml-2 container-fluid">

                    <div class="my-5 container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ url('monthly_item_search') }}" method="get">
                                    <div class="row">
                                        <div class="col-md-5 form-group">
                                            <label for="">Date From :</label>
                                            <input type="date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label for="">Date To :</label>
                                            <input type="date" name="end_date" class="form-control" required>
                                        </div>
                                        <div class="mt-3 col-md-3 form-group">
                                            <input type="submit" class="btn btn-primary form-control" value="Search"
                                                style="background-color: #218838">
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Item Reports</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            @foreach ($warehouses as $warehouse)
                                                <th>{{ $warehouse->name }}</th>
                                            @endforeach
                                            <th>Total Quantity</th>
                                            <th>Total Wholesale Price</th>
                                            <th>Total Retail Price</th>
                                            <th>Total Purchase Price</th>
                                            <th>Profit</th>
                                        </tr>
                                    </thead>

                                    @php
                                        $groupedSells = [];
                                        $totalWholesalePrice = 0;
                                        $totalRetailPrice = 0;
                                        $totalPurchasePrice = 0;
                                        $totalBuyPrice = 0;
                                        $totalProfit = 0;
                                        $totalAmountprice = 0;
                                        $totalAmountwholesaleprice = 0;
                                        $totalAmountbuyprice = 0;
                                        $totalAmountprofit = 0;

                                    @endphp
                                    <tbody>
                                        @php
                                            $groupedSells = [];

                                            foreach ($invoices as $invoice) {
                                                foreach ($invoice->sells as $sell) {
                                                    if (isset($groupedSells[$sell->part_number])) {
                                                        $groupedSells[$sell->part_number]['product_qty'] +=
                                                            $sell->product_qty;
                                                        $groupedSells[$sell->part_number]['sell_price'] =
                                                            $sell->retail_price;
                                                        $groupedSells[$sell->part_number]['wholesale_price'] +=
                                                            $sell->product_price * $sell->product_qty;
                                                        $groupedSells[$sell->part_number]['total_price'] +=
                                                            $sell->retail_price * $sell->product_qty;
                                                        $groupedSells[$sell->part_number]['total_buyprice'] +=
                                                            $sell->buy_price * $sell->product_qty;
                                                    } else {
                                                        // Initialize the entry for this part_number
                                                        $groupedSells[$sell->part_number] = [
                                                            'part_number' => $sell->part_number,
                                                            'product_qty' => $sell->product_qty,
                                                            'total_buyprice' => $sell->buy_price * $sell->product_qty,
                                                            'wholesale_price' =>
                                                                $sell->product_price * $sell->product_qty,
                                                            'total_price' => $sell->retail_price * $sell->product_qty,
                                                        ];
                                                    }
                                                }
                                            }
                                            $no = 1;
                                            $totalAmountprice = 0;
                                        @endphp

                                        @foreach ($groupedSells as $sell)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td> <a
                                                        href="{{ url('report_item_show', ['part_number' => $sell['part_number']]) }}">
                                                        {{ $sell['part_number'] }}
                                                    </a></td>
                                                <td>{{ $sell['product_qty'] }}</td>
                                                <td>{{ number_format($sell['wholesale_price']) }}</td>

                                                <td>{{ number_format($sell['total_price']) }}</td>
                                                <td>{{ number_format($sell['total_buyprice']) }}</td>
                                                <td>{{ number_format($sell['total_buyprice'] - $sell['total_price']) }}
                                                </td>
                                            </tr>
                                            @php
                                                $no++;
                                                $totalAmountprice += $sell['total_price'];
                                                $totalAmountbuyprice += $sell['total_buyprice'];
                                                $totalAmountwholesaleprice += $sell['wholesale_price'];
                                                $totalAmountprofit += $sell['total_buyprice'] - $sell['total_price'];
                                            @endphp
                                        @endforeach



                                    </tbody>
                                    <tfoot>
                                        <tr></tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ number_format($totalAmountwholesaleprice) }}</td>
                                        <td>{{ number_format($totalAmountprice) }}</td>
                                        <td>{{ number_format($totalAmountbuyprice) }}</td>
                                        <td>{{ number_format($totalAmountprofit) }}</td>
                                        </tr>
                                    </tfoot>
                                    {{-- @foreach ($invoices as $invoice)
                                            @foreach ($invoice->sells as $sell)
                                                @php
                                                    if (!isset($groupedSells[$sell->item_id])) {
                                                        $groupedSells[$sell->item_id] = [
                                                            'product_name' => $sell->part_number,
                                                            'quantities' => array_fill(0, count($warehouses), 0),
                                                            'profit' => 0,
                                                        ];
                                                    }

                                                    $warehouseIndex = $warehouses->search(
                                                        fn($w) => $w->id == $sell->warehouse,
                                                    );

                                                    if (
                                                        $warehouseIndex !== false &&
                                                        $sell->stock_and_service != 'Service'
                                                    ) {
                                                        $groupedSells[$sell->item_id]['quantities'][$warehouseIndex] +=
                                                            $sell->product_qty;

                                                        $profitPerSell =
                                                            ($sell->retail_price - $sell->buy_price) *
                                                            $sell->product_qty;
                                                        $groupedSells[$sell->item_id]['profit'] += $profitPerSell;
                                                    }
                                                @endphp
                                            @endforeach
                                        @endforeach

                                        @foreach ($groupedSells as $sellData)
                                            <tr>
                                                <td>{{ $sellData['product_name'] }}</td>
                                                @php
                                                    $totalQuantity = array_sum($sellData['quantities']);
                                                    $wholesalePrice = $sell->product_price * $totalQuantity;
                                                    $retailPrice = $sell->retail_price * $totalQuantity;
                                                    $purchasePrice = $sell->buy_price * $totalQuantity;

                                                    $totalWholesalePrice += $wholesalePrice;
                                                    $totalRetailPrice += $retailPrice;
                                                    $totalPurchasePrice += $purchasePrice;
                                                    $totalProfit += $sellData['profit'];
                                                @endphp
                                                @foreach ($sellData['quantities'] as $quantity)
                                                    <td>{{ $quantity }}</td>
                                                @endforeach
                                                <td>{{ $totalQuantity }}</td>
                                                <td>{{ number_format($wholesalePrice, 2) }}</td>
                                                <td>{{ number_format($retailPrice, 2) }}</td>
                                                <td>{{ number_format($purchasePrice, 2) }}</td>
                                                <td>{{ number_format($sellData['profit'], 2) }}</td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>

                                </table>

                                {{--
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            @foreach ($warehouses as $warehouse)
                                                <th>{{ $warehouse->name }}</th>
                                            @endforeach
                                            <th>Total Quantity</th>
                                            <th>လက်လီစျေး</th>
                                            <th>လက်ကားစျေး</th>
                                            <th>Total Amount (လက်လီ)</th>
                                            <th>Total Amount (လက်ကား)</th>
                                            <th>Total Profit (လက်လီ)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalRetailPrice = 0;
                                            $totalWholesalePrice = 0;
                                            $totalRetailAmount = 0;
                                            $totalWholesaleAmount = 0;
                                            $totalRetailProfit = 0;
                                        @endphp
                                        @foreach ($items as $item)
                                            @php
                                                $totalRetailPrice += (float) $item['retail_price'];
                                                $totalWholesalePrice += (float) $item['wholesale_price'];

                                                if ($item['item_type'] == 'Service') {
                                                    $totalRetailAmount += (float) 1 * (float) $item['retail_price'];
                                                } else {
                                                    $totalRetailAmount +=
                                                        (float) $item['total_quantity'] * (float) $item['retail_price'];
                                                }

                                                if ($item['item_type'] == 'Service') {
                                                    $totalWholesaleAmount +=
                                                        (float) 1 * (float) $item['wholesale_price'];
                                                } else {
                                                    $totalWholesaleAmount +=
                                                        (float) $item['total_quantity'] *
                                                        (float) $item['wholesale_price'];
                                                }

                                                $retailProfit =
                                                    ((float) $item['retail_price'] - (float) $item['buy_price']) *
                                                    (float) $item['total_quantity'];
                                                $totalRetailProfit += $retailProfit;
                                            @endphp
                                            <tr>
                                                <td>{{ $item['item_name'] }}</td>
                                                @foreach ($warehouses as $warehouse)
                                                    <td>
                                                        @if ($item['item_type'] == 'Service')
                                                            0
                                                        @else
                                                            {{ $item['warehouse_quantities'][$warehouse->id] ?? 0 }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td>
                                                    @if ($item['item_type'] == 'Service')
                                                        0
                                                    @else
                                                        {{ $item['total_quantity'] }}
                                                    @endif

                                                </td>
                                                <td>{{ number_format((float) $item['retail_price'] ?? 0, 2) }}</td>
                                                <td>{{ number_format((float) $item['wholesale_price'] ?? 0, 2) }}</td>
                                                <td>
                                                    @if ($item['item_type'] == 'Service')
                                                        {{ number_format((float) 1 * (float) $item['retail_price'] ?? 0, 2) }}
                                                    @else
                                                        {{ number_format((float) $item['total_quantity'] * (float) $item['retail_price'] ?? 0, 2) }}
                                                    @endif

                                                </td>
                                                <td>

                                                    @if ($item['item_type'] == 'Service')
                                                        {{ number_format((float) 1 * (float) $item['wholesale_price'] ?? 0, 2) }}
                                                    @else
                                                        {{ number_format((float) $item['total_quantity'] * (float) $item['wholesale_price'] ?? 0, 2) }}
                                                    @endif


                                                </td>

                                                <td>{{ number_format($retailProfit ?? 0, 2) }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>

                                            <td colspan="{{ count($warehouses) + 2 }}" class="text-right">
                                                Total</td>

                                            <td>{{ number_format($totalRetailPrice ?? 0, 2) }}</td>
                                            <td>{{ number_format($totalWholesalePrice ?? 0, 2) }}</td>
                                            <td>{{ number_format($totalRetailAmount ?? 0, 2) }}</td>
                                            <td>{{ number_format($totalWholesaleAmount ?? 0, 2) }}</td>
                                            <td>{{ number_format($totalRetailProfit ?? 0, 2) }}</td>

                                        </tr>
                                    </tfoot>
                                </table> --}}

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
        </div>

        </section>

    </div>



    </div>
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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,

                "autoWidth": false,
                "pageLength": 10,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
