@include('layouts.header')

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
                                <h1>Customer Due</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Customer Due</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="container-fluid">
                    <div class="row justify-content-center d-flex">


                    </div>

                    <div class="row ml-2">
                        <div class="mr-auto col"> 
                            <button type="button" class="mr-auto btn btn-danger" id="due-alert">Due Alert</button>
                        </div>
                    </div>

                    <!-- /.modal -->
                    <div class="mt-3 col-md-12">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('delete'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>{{ session('delete') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Customer Due List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Customer Name</th>
                                            <th>Customer Phone</th>
                                            <th>Invoice No.</th>
                                            <th>Remain Balance</th>
                                            <th>Total</th>
                                            <th>Payment Status</th>
                                            <th> Date</th>
                                            <!-- <th>Action</th> -->

                                        </tr>
                                    </thead>
                                    <tbody class="invoice-data">
                                        @php
                                            $no = '1';
                                            $today = \Carbon\Carbon::today()->toDateString(); // Get today's date in 'YYYY-MM-DD' format
                                        @endphp
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
                                        @foreach ($invoices as $invoice)
                                            <tr>

                                                <td>{{ $no }}</td>
                                                <td>{{ $invoice->customer_name }}</td>
                                                <td>{{ $invoice->phno }}</td>
                                                <td> {{ $invoice->invoice_no }}</td>
                                                <td>{{ $invoice->remain_balance }}</td>

                                                <td>{{ number_format($invoice->total ?? 0) }}</td>
                                                @if ($invoice->total == $invoice->deposit)
                                                    <td><span class="badge badge-success">Paid</span></td>
                                                @elseif($invoice->total > $invoice->deposit && $invoice->deposit > 0)
                                                    <td><span class="badge badge-warning">Partial Paid</span></td>
                                                @else
                                                    <td><span class="badge badge-danger">Unpaid</span></td>
                                                @endif
                                                <td class="@if($invoice->overdue_date < $today) bg-danger @endif">{{ $invoice->overdue_date }}</td>
                                                <!-- <td>

                                                    @if (in_array('Invoice Details', $choosePermission) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('/invoice_receipt_print', $invoice->id) }}"
                                                            class="btn btn-info btn-sm"><i
                                                                class="fa-solid fa-print"></i></a>

                                                        <a href="{{ url('/invoice_detail', $invoice->id) }}"
                                                            class="btn btn-primary btn-sm"><i
                                                                class="fa-solid fa-eye"></i></a>
                                                    @endif

                                                    @if (in_array('Invoice Edit', $choosePermission) || auth()->user()->is_admin == '1')
                                                        @if ($invoice->status == 'invoice')
                                                            <a href="{{ url('invoice_edit', $invoice->id) }}"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                        @else
                                                        @endif
                                                    @endif


                                                    @if (in_array('Invoice Delete', $choosePermission) || auth()->user()->is_admin == '1')
                                                        <a href="{{ url('invoice_delete', $invoice->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this Invoice ?')"><i
                                                                class="fa-solid fa-trash"></i></a>
                                                    @endif

                                                </td> -->


                                            </tr>
                                            @php
                                                $no++;
                                            @endphp
                                        @endforeach

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


    <script>
        $(document).on('click','#due-alert',function(){
            let date = new Date().toISOString().split('T')[0];
            let url = window.location.href;
            $.ajax({
                url : url,
                method : 'GET' ,
                data: {
                    date: date,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                     // Destroy the DataTable instance
                    if ($.fn.DataTable.isDataTable('#example1')) {
                        $('#example1').DataTable().destroy();
                    }

                    $('.invoice-data').html($(response).find('.invoice-data').html());

                     // Reinitialize the DataTable
                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "pageLength": 30,
                        // "buttons": ["excel", "pdf", "print"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $('.dataTables_paginate').html($(response).find('.dataTables_paginate').html());

                    alert('Due Alert Updated!');

                },
                error: function(xhr, status, error) {
                    console.error("Error during AJAX request:", error);
                }
            });
        });
    </script>
</body>

</html>
