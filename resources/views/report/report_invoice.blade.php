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

                    <!-- left column -->

                    <!-- general form elements -->

                    <!-- /.modal -->


                    <div class="my-5 container-fluid">
                        <div class="row">
                            <div class="col-md-10">
                                <form action="{{ url('monthly_invoice_search') }}" method="get">
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
                                <h3 class="card-title">Invoices Report</h3>
                                <div class="dropdown ml-auto mr-5">
                                    <div id="branchDropdown" class="dropdown ml-auto"
                                        style="display:inline-block; margin-left: 10px;">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ $currentBranchName }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{ url('report') }}" class="dropdown-item">All Invoices</a>
                                            @if (auth()->user()->is_admin == '1')

                                                @foreach ($branchs as $drop)
                                                    <a class="dropdown-item"
                                                        href="{{ route('report_invoice', $drop->id) }}">{{ $drop->name }}</a>
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
                                                            href="{{ route('report_invoice', $drop->id) }}">{{ $drop->name }}</a>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Invoice No.</th>
                                                <th>Location</th>
                                                <th>Customer Name</th>
                                                <th>Phone Number</th>
                                                <th>Customer Type</th>
                                                <th>Address</th>
                                                <th>Date</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                            @endphp
                                            @if (!empty($search_invoices))
                                                @foreach ($search_invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $no }}</td>
                                                        <td> <a href="{{ url('/invoice_detail', $invoice->id) }}">
                                                                {{ $invoice->invoice_no }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @foreach ($branchs as $branch)
                                                                @if ($branch->id == $invoice->branch)
                                                                    {{ $branch->name }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $invoice->customer_name }}</td>
                                                        <td>{{ $invoice->phno }}</td>
                                                        <td>{{ $invoice->type }}</td>
                                                        <td>{{ $invoice->address }}</td>
                                                        <td>{{ $invoice->invoice_date }}</td>
                                                        <td>{{ number_format($invoice->total) }}</td>
                                                    </tr>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endforeach
                                            @else
                                                @foreach ($invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $no }}</td>
                                                        <td> <a href="{{ url('/invoice_detail', $invoice->id) }}">
                                                                {{ $invoice->invoice_no }}
                                                            </a></td>
                                                        <td>
                                                            @foreach ($branchs as $branch)
                                                                @if ($branch->id == $invoice->branch)
                                                                    {{ $branch->name }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $invoice->customer_name }}</td>
                                                        <td>{{ $invoice->phno }}</td>
                                                        <td>{{ $invoice->type }}</td>
                                                        <td>{{ $invoice->address }}</td>
                                                        <td>{{ $invoice->invoice_date }}</td>
                                                        <td>{{ number_format($invoice->total) }}</td>
                                                    </tr>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endforeach
                                            @endif

                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td colspan="7" style="text-align:right">Total</td>
                                                <td colspan="">
                                                    @if (!empty($search_invoices))
                                                        {{ number_format($search_total) }}@else{{ number_format($total) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tfoot>
                                        </tbody>

                                    </table>
                                </div>
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
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Cash</td>
                                            <td>{{ number_format($totalCash) }}</td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>K Pay</td>
                                            <td>{{ number_format($totalKbz) }}</td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Wave</td>
                                            <td>{{ number_format($totalCB) }}</td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Other</td>
                                            <td>{{ number_format($totalOther) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:right">Total</td>
                                            <td>{{ number_format($totalCash + $totalKbz + $totalCB + $totalOther) }}
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
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
                "buttons": [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        filename: 'report_invoices', // Set filename here
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
