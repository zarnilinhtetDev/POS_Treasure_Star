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
                    <div class="container mt-3">
                        <ul class="nav nav-tabs">

                            @if (in_array('Invoice Report', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report') ? 'active' : '' }}"
                                        href="{{ url('report') }}">Invoices</a>
                                </li>
                            @endif

                            @if (in_array('Quotation Report', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report_quotation') ? 'active' : '' }}"
                                        href="{{ url('report_quotation') }}">Quotations</a>
                                </li>
                            @endif

                            @if (in_array('POS Report', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report_pos') ? 'active' : '' }}"
                                        href="{{ url('report_pos') }}">POS</a>
                                </li>
                            @endif

                            @if (in_array('Purchase Order Report', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report_po') ? 'active' : '' }}"
                                        href="{{ url('report_po') }}">Purchase Orders</a>
                                </li>
                            @endif

                            @if (in_array('Purchase Return', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report_purchase_return') ? 'active' : '' }}"
                                        href="{{ url('report_purchase_return') }}">Purchase Return</a>
                                </li>
                            @endif

                            @if (in_array('Sale Return (Invoice)', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report_sale_return') ? 'active' : '' }}"
                                        href="{{ url('report_sale_return') }}">Sale Return (Invoice)</a>
                                </li>
                            @endif

                            @if (in_array('Item Report', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report_item') ? 'active' : '' }}"
                                        href="{{ url('report_item') }}">Items</a>
                                </li>
                            @endif

                            @if (in_array('Sale Return (POS)', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('sale_return') ? 'active' : '' }}"
                                        href="{{ url('sale_return') }}">Sale Return (POS)</a>
                                </li>
                            @endif

                            @if (in_array('Expenses Report', $choosePermission) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report_expense') ? 'active' : '' }}"
                                        href="{{ url('report_expense') }}">Expenses</a>
                                </li>
                            @endif

                        </ul>
                    </div><!-- /.container-fluid -->
                </section>



                <div class="ml-2 container-fluid">


                    <div class="my-5 container-fluid">
                        <div class="row">
                            <div class="col-md-10">
                                <form action="{{ url('monthly_pos_search') }}" method="get">
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <label for="start_date">Date From:</label>
                                            <input type="date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="end_date">Date To:</label>
                                            <input type="date" name="end_date" class="form-control" required>
                                        </div>
                                        @if (auth()->user()->is_admin == '1')
                                            <div class="form-group col-md-3">
                                                <label for="branch">Location<span class="text-danger">*</span></label>

                                                <select name="branch" id="branch" class="form-control" required>

                                                    @foreach ($branchs as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="form-group col-md-3">
                                                <label for="branch">Location<span class="text-danger">*</span></label>

                                                <select name="branch" id="branch" class="form-control" required>
                                                    @php
                                                        $userPermissions = auth()->user()->level
                                                            ? json_decode(auth()->user()->level)
                                                            : [];
                                                    @endphp
                                                    <option value="" selected disabled>Select Location
                                                    </option>
                                                    @foreach ($branchs as $branch)
                                                        @if (in_array($branch->id, $userPermissions))
                                                            <option value="{{ $branch->id }}">
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="col-md-2 form-group">
                                            <label for="">&nbsp;</label>
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">POS Report</h3>
                                <div class="dropdown ml-auto mr-5">
                                    <div id="branchDropdown" class="dropdown ml-auto"
                                        style="display:inline-block; margin-left: 10px;">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ $currentBranchName }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{ route('report_pos_branch') }}" class="dropdown-item">All POS
                                            </a>
                                            @if (auth()->user()->is_admin == '1')
                                                @foreach ($branchs as $drop)
                                                    <a class="dropdown-item"
                                                        href="{{ route('report_pos_branch', ['branch' => $drop->id]) }}">{{ $drop->name }}</a>
                                                @endforeach
                                            @else
                                                @php
                                                    $userPermissions = auth()->user()->level
                                                        ? json_decode(auth()->user()->level)
                                                        : [];
                                                @endphp
                                                @foreach ($branchs as $drop)
                                                    @if (in_array($drop->id, $userPermissions))
                                                        <a class="dropdown-item"
                                                            href="{{ route('report_pos_branch', ['branch' => $drop->id]) }}">{{ $drop->name }}</a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>POS No.</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                            <th>Sub Total</th>
                                            <th>Discount</th>
                                            <th>Total Sale Amount</th>
                                            <th>Total Purchase</th>
                                            <th>Profit</th>
                                            <th>Sale By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                            $rowCount = count(!empty($search_pos) ? $search_pos : $pos_data);
                                            $subtotal = 0;
                                            $discounttotal = 0;
                                            $amounttotal = 0;
                                            $purchase = 0;
                                        @endphp

                                        @if (!empty($search_pos))
                                            @foreach ($search_pos as $pos)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td><a href="{{ url('report_pos_receipt', $pos->id) }}">
                                                            {{ $pos->invoice_no }}</a>
                                                    </td>
                                                    <td>
                                                        @foreach ($branchs as $branch)
                                                            @if ($branch->id == $pos->branch)
                                                                {{ $branch->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $pos->invoice_date }}</td>
                                                    <td>{{ number_format($pos->discount_total + $pos->total) }}
                                                    </td>

                                                    <td>{{ $pos->discount_total ?? 0 }}</td>
                                                    <td>{{ number_format($pos->total) }}</td>
                                                    <td>{{ number_format($pos->total_buy_price) }}</td>
                                                    <td>{{ number_format($pos->total - $pos->total_buy_price) }}</td>
                                                    <td>{{ $pos->sale_by }}</td>
                                                </tr>
                                                @php
                                                    $no++;
                                                    $subtotal += $pos->discount_total + $pos->total; // Add subtotal for each $discounttotal += $pos_datas->discount_total;
                                                    $discounttotal += $pos->discount_total;
                                                    $amounttotal += $pos->total;
                                                    $purchase += $pos->total_buy_price;

                                                @endphp
                                            @endforeach
                                        @else
                                            @foreach ($pos_data as $pos_datas)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td><a
                                                            href="{{ url('report_pos_receipt', $pos_datas->id) }}">{{ $pos_datas->invoice_no }}</a>
                                                    </td>
                                                    <td>
                                                        @foreach ($branchs as $branch)
                                                            @if ($branch->id == $pos_datas->branch)
                                                                {{ $branch->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $pos_datas->invoice_date }}</td>
                                                    <td>{{ number_format($pos_datas->discount_total + $pos_datas->total) }}
                                                    </td>

                                                    <td>{{ $pos_datas->discount_total ?? 0 }}</td>
                                                    <td>{{ number_format($pos_datas->total) }}</td>
                                                    <td>{{ number_format($pos_datas->total_buy_price) }}</td>
                                                    <td>{{ number_format($pos_datas->total - $pos_datas->total_buy_price) }}
                                                    </td>
                                                    <td>{{ $pos_datas->sale_by }}</td>
                                                </tr>
                                                @php
                                                    $no++;
                                                    $subtotal += $pos_datas->discount_total + $pos_datas->total;
                                                    $discounttotal += $pos_datas->discount_total;
                                                    $amounttotal += $pos_datas->total;
                                                    $purchase += $pos_datas->total_buy_price;
                                                @endphp
                                            @endforeach
                                        @endif

                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td colspan="3" style="text-align:right">Total</td>
                                            <td colspan="">{{ number_format($subtotal) }}</td>
                                            <td colspan="">{{ number_format($discounttotal) }}</td>
                                            <td colspan="">{{ number_format($amounttotal) }}</td>
                                            <td colspan="">{{ number_format($purchase) }}</td>
                                            <td colspan="">{{ number_format($amounttotal - $purchase) }}</td>
                                            <td></td>
                                        </tr>

                                    </tfoot>
                                    </tbody>



                                </table>
                                <table id="example1" class="table table-bordered table-striped my-3">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Sale By</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($sale_totals as $sale_total)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td colspan="">{{ $sale_total->sale_by }}</td>
                                            <td>{{ number_format($sale_total->sale_total) }}</td>
                                            <!-- Display the subtotal here -->
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Payment Method</th>
                                            <th>Total Sale</th>
                                            <th>Total Purchase</th>
                                            <th>Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Cash</td>
                                            <td>{{ number_format($totalCash) }}</td>
                                            <td>{{ number_format($totalCashPurchase) }}</td>
                                            <td>{{ number_format($totalCash - $totalCashPurchase) }}</td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>K Pay</td>
                                            <td>{{ number_format($totalKbz) }}</td>
                                            <td>{{ number_format($totalKbzPurchase) }}</td>
                                            <td>{{ number_format($totalKbz - $totalKbzPurchase) }}</td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Wave</td>
                                            <td>{{ number_format($totalCB) }}</td>
                                            <td>{{ number_format($totalCBPurchase) }}</td>
                                            <td>{{ number_format($totalCB - $totalCBPurchase) }}</td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Other</td>
                                            <td>{{ number_format($totalOther) }}</td>
                                            <td>{{ number_format($totalOtherPurchase) }}</td>
                                            <td>{{ number_format($totalOther - $totalOtherPurchase) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:right">Total</td>
                                            <td>{{ number_format($totalCash + $totalKbz + $totalCB + $totalOther) }}
                                            </td>
                                            <td>{{ number_format($totalCashPurchase + $totalKbzPurchase + $totalCBPurchase + $totalOtherPurchase) }}
                                            </td>
                                            <td>{{ number_format($totalCash + $totalKbz + $totalCB + $totalOther - ($totalCashPurchase + $totalKbzPurchase + $totalCBPurchase + $totalOtherPurchase)) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
                "pageLength": 30,
                "buttons": [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        filename: 'report_pos', // Set filename here
                    },
                    {
                        extend: 'pdfHtml5',
                        text: ' PDF'
                    }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>

</body>

</html>
