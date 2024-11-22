@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.nav')
        @include('layouts.sidebar')
        <div class="content-wrapper">
            {{-- <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>DataTables</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">DataTables</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section> --}}

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">


                            <!-- Content Header (Page header) -->
                            <section class="content-header">
                                <div class="container-fluid">
                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <h1>Transactions</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Transaction</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div><!-- /.container-fluid -->
                            </section>

                            <div class="container-fluid mb-4 mr-auto">
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-default text-white" data-toggle="modal"
                                            data-target="#modal-lg" style="background-color: #007BFF">
                                            Transaction Register
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Content --}}
                            <div class="modal fade" id="modal-lg">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Transaction Register</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('/transaction_register') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="phno">Location <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="location" required
                                                        id="location">
                                                        <option value="" selected disabled>Choose Location
                                                        </option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}">{{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="accounts_id">Account Name <span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <select name="account_id" class="form-control" id="account_id"
                                                        required>
                                                        <option value="">Select Account</option>

                                                        @foreach ($account as $accounts)
                                                            <option value="{{ $accounts->id }}">
                                                                {{ $accounts->account_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('account_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group" style="display: none">
                                                    <label for="status">Status<span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <select name="status" class="form-control" id="status" required>
                                                        {{-- <option value="">Choose One</option> --}}

                                                        <option value="in">IN

                                                        </option>
                                                        <option value="out">OUT

                                                        </option>
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group" style="display: none;">
                                                    <label for="transaction_code">Opening Amount<span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <input type="text" class="form-control" id="transaction_code"
                                                        name="transaction_code" value="0" required>
                                                    @error('transaction_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction_name">Name<span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <input type="text" class="form-control" id="transaction_name"
                                                        name="transaction_name" placeholder="Enter Transaction Name"
                                                        required value="">
                                                    @error('transaction_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <label>Descriptions</label>
                                                    <textarea class="form-control" rows="3" placeholder="Enter ..." style="border-color:#6B7280" name="description"
                                                        value="no need"></textarea>
                                                </div>

                                                <!-- /.card-body -->
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        style="background-color: #007BFF">Register</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>


                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('deleteStatus'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('deleteStatus') }}
                                </div>
                            @endif
                            @if (session('updateStatus'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('updateStatus') }}
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">Transactions</h3>
                                    <div class="dropdown ml-auto mr-5">
                                        <!-- Dropdown Menu HTML -->
                                        @if (auth()->user()->is_admin == '1' || Auth::user()->type == 'Admin')
                                            <div id="branchDropdown" class="dropdown ml-auto"
                                                style="display:inline-block; margin-left: 10px;">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    {{ $currentBranchName }}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a href="{{ url('transactionManagement') }}"
                                                        class="dropdown-item">All
                                                        Accounts</a>
                                                    @foreach ($branch_drop as $drop)
                                                        <a class="dropdown-item"
                                                            href="{{ route('transactions', $drop->id) }}">{{ $drop->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Name</th>
                                                {{-- <th>Status</th> --}}


                                                <th>Name</th>
                                                <th>Location</th>
                                                <th>Amount</th>

                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                            @endphp
                                            @foreach ($transaction as $transactions)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $transactions->account->account_name }}</td>



                                                    <td>{{ $transactions->transaction_name }}</td>
                                                    <td>{{ $transactions->warehouse->name ?? '' }}</td>
                                                    <td>

                                                        <!-- {{ isset($diff[$transactions->id]) ? $diff[$transactions->id] : 0 }} -->
                                                        {{ isset($diff[$transactions->id]) ? $diff[$transactions->id] : 0 }}
                                                    </td>
                                                    </td>

                                                    <td>
                                                        <div class="row">
                                                            <a href="{{ route('finance#transactionManagementEdit', $transactions->id) }}"
                                                                title="Transaction Edit"
                                                                class="mx-2 btn btn-success"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                            <a href="{{ url('transaction_delete', $transactions->id) }}"
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Are you sure want to delete this transaction ?')"><i
                                                                    class="fa-solid fa-trash"></i></a>



                                                            <a href="{{ url('payment', $transactions->id) }}"
                                                                class="btn btn-warning ml-2">Add Payment</a>
                                                    </td>
                                </div>
                                </td>
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
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
        </div>

        </section>


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
        $(document).ready(function() {
            $('#location').on('change', function() {


                const selectedBranch = $(this).val();
                const account = document.getElementById('account_id');
                // Clear the doctor select options
                account.innerHTML = '<option value="">Select Account</option>';

                // Filter and add the doctors based on the selected branch
                @foreach ($account as $accounts)
                    if (selectedBranch === '{{ $accounts->location }}') {
                        const option = document.createElement('option');
                        option.value = '{{ $accounts->id }}';
                        option.textContent = '{{ $accounts->account_name }}';
                        account.appendChild(option);
                    }
                @endforeach
            });
            $('#location').trigger('change');
        });
    </script>

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
