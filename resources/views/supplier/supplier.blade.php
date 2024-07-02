@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
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
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn text-white dropdown-toggle" data-toggle="dropdown"
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
                                <h1>Supplier Management</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Supplier Management
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
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


                <div class="ml-2 container-fluid">

                    <!-- left column -->

                    <!-- general form elements -->



                    <div class="row">
                        <div class="mr-auto col"> <button type="button" class="mr-auto btn btn-primary "
                                data-toggle="modal" data-target="#modal-lg">
                                Register New Supplier
                        </div>





                    </div>



                    <div class="modal fade" id="modal-lg">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"> Register New Supplier</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('supplier_register') }}" method="POST">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name"
                                                    placeholder="Enter Name" required autofocus name="name">
                                            </div>

                                            <div class="form-group">
                                                <label for="phno">Phone Number <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="phone number"
                                                    placeholder="Enter Phone Number" name="phno" required>
                                            </div>

                                            @if (auth()->user()->is_admin == '1')
                                                <div class="form-group">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>

                                                    <select name="branch" id="branch" class="form-control"
                                                        required>
                                                        <option value="" selected disabled>Select Location
                                                        </option>
                                                        @foreach ($branchs as $branch)
                                                            <option value="{{ $branch->id }}">{{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>

                                                    <select name="branch" id="branch" class="form-control"
                                                        required>
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

                                            <div class="form-group">
                                                <label for="address">Address <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="phone number"
                                                    placeholder="Enter Address" name="address" required>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save </button>
                                </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Supplier Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Location</th>
                                            <th>Address</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = '1';
                                        @endphp
                                        @foreach ($suppliers as $supplier)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $supplier->name }}</td>
                                                <td>{{ $supplier->phno }}</td>
                                                <td>
                                                    @foreach ($branchs as $branch)
                                                        @if ($branch->id == $supplier->branch)
                                                            {{ $branch->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $supplier->address }}</td>



                                                <td>
                                                    <div class="row">



                                                        <!-- <a href="{{ url('supplier_view', $supplier->id) }}" class="btn btn-primary">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a> -->

                                                        <a href="{{ url('supplier_edit', $supplier->id) }}"
                                                            title="Supplier Edit" class="mx-2 btn btn-success"><i
                                                                class="fa-solid fa-pen-to-square"></i></a>
                                                        <a href="{{ url('supplier_delete', $supplier->id) }}"
                                                            title="Supplier Delete" class="mx-2 btn btn-danger"><i
                                                                class="fa-solid fa-trash"></i></a>


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
