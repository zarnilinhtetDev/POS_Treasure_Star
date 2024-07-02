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

                <section class="content-header">
                    <div class="container mt-3">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report') ? 'active' : '' }}"
                                    href="{{ url('report') }}">Invoices</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report_quotation') ? 'active' : '' }}"
                                    href="{{ url('report_quotation') }}">Quotations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report_po') ? 'active' : '' }}"
                                    href="{{ url('report_po') }}">Purchase Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report_purchase_return') ? 'active' : '' }}"
                                    href="{{ url('report_purchase_return') }}">Purchase Return</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report_sale_return') ? 'active' : '' }}"
                                    href="{{ url('report_sale_return') }}">Sale Return (Invoice)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report_item') ? 'active' : '' }}"
                                    href="{{ url('report_item') }}">Items</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report_pos') ? 'active' : '' }}"
                                    href="{{ url('report_pos') }}">POS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('sale_return') ? 'active' : '' }}"
                                    href="{{ url('sale_return') }}">Sale Return (POS)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('report_expense') ? 'active' : '' }}"
                                    href="{{ url('report_expense') }}">Expenses</a>
                            </li>
                        </ul>
                    </div><!-- /.container-fluid -->
                </section>


                <div class="ml-2 container-fluid">

                    <!-- left column -->

                    <!-- general form elements -->

                    <!-- /.modal -->

                    <div class="my-5 container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ url('monthly_po_search') }}" method="get">
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
                                <h3 class="card-title">Purchase Report</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Purchase Order No.</th>
                                            <th>Location</th>
                                            <th>Supplier Name</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>Purchase Return</th>
                                            <th>Total Amount</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = '1';
                                        @endphp

                                        @if (!empty($search_pos))
                                            @foreach ($search_pos as $po)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $po->quote_no }}</td>
                                                    <td>
                                                        @foreach ($branchs as $branch)
                                                            @if ($branch->id == $po->branch)
                                                                {{ $branch->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                                                    <td>{{ $po->supplier->phno ?? 'N/A' }}</td>
                                                    <td>{{ $po->supplier->address ?? 'N/A' }}</td>
                                                    <td>{{ $po->balance_due }}</td>
                                                    <td>{{ $po->total }}</td>
                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                        @else
                                            @foreach ($pos as $po)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $po->quote_no }}</td>
                                                    <td>
                                                        @foreach ($branchs as $branch)
                                                            @if ($branch->id == $po->branch)
                                                                {{ $branch->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                                                    <td>{{ $po->supplier->phno ?? 'N/A' }}</td>
                                                    <td>{{ $po->supplier->address ?? 'N/A' }}</td>
                                                    <td>{{ $po->balance_due }}</td>
                                                    <td>{{ $po->total }}</td>
                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach

                                        @endif


                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td colspan="6" style="text-align:right">Total</td>
                                            <td colspan="">
                                                @if (!empty($search_pos))
                                                    {{ $search_total }}
                                                @else
                                                    {{ $total }}
                                                @endif
                                            </td>
                                        </tr>
                                    </tfoot>
                                    </tbody>

                                </table>
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
                "pageLength": 30,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
