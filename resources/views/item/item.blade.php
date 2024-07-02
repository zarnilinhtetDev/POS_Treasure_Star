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
                    <a class="text-white nav-link " href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


                <li class="ml-auto nav-item">
                    <a class="nav-link" href="#">


                    </a>
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
                                <h1>Items</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>

                                    </li>
                                    <li class="breadcrumb-item">Items</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                @if (session('delete'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('delete') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif


                @if (session('excelimport'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('excelimport') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span dangeraria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if ($errors->has('file'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ $errors->first('file') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="container-fluid">
                    <div class="ml-2 row d-flex">
                        <form action="{{ route('file-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4 form-group" style="max-width: 500px; margin: 0 auto;">

                                <div class="text-left custom-file">

                                    @if (Auth::user()->is_admin == '1')
                                        <label for="warehouse">Choose Location</label>
                                        <select name="warehouse_id" id="warehouse" class="form-control" required>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <label for="warehouse">Choose Location</label>
                                        <select name="warehouse_id" id="warehouse" class="form-control" required>
                                            @php
                                                $userPermissions = auth()->user()->level
                                                    ? json_decode(auth()->user()->level)
                                                    : [];
                                            @endphp
                                            @foreach ($warehouses as $warehouse)
                                                @if (in_array($warehouse->id, $userPermissions))
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    @endif


                                    <div class="p-1 mt-2 text-left custom-file col"
                                        style="border:#d0d0db 1px solid;background-color: white">
                                        <input type="file" name="file" class="" id="customFile">
                                    </div>
                                    <button class="mt-3 btn btn-primary">Import </button>
                                    {{-- @endif --}}
                                    <a class="mt-3 btn btn-success" href="{{ route('file-export') }}">Export </a>
                                </div>
                            </div>
                            {{-- @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin' || Auth::user()->type == 'Warehouse') --}}
                            <a class="" href="{{ route('file-import-template') }}">Download
                                Import CSV Template</a>
                            {{-- @endif --}}

                        </form>
                    </div>
                    {{-- @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin' || Auth::user()->type == 'Warehouse') --}}
                    <div class="mt-5 mr-auto col">
                        <a href="{{ url('items_register') }}" type="button" class="mr-auto btn btn-primary ">
                            Item Register</a>
                    </div>
                    {{-- @endif --}}

                    <div class="container-fluid">

                        <!-- /.modal -->
                        <div class="mt-3 col-md-12">
                            <div class="bg-white">

                                <div class="card-header">
                                    <h3 class="card-title">Items List</h3>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1"
                                        class="table table-bordered table-striped table-responsive-lg">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <th>Location</th>
                                                <th>လက်လီစျေး</th>
                                                <th>လက်ကားစျေး</th>
                                                <th>ဝယ်စျေး</th>
                                                <th>Barcode</th>

                                                <th>Expired Date</th>
                                                <!-- <th>Market</th> -->
                                                <th style="background-color: rgb(221, 215, 215)">Quantity</th>
                                                <th>Bar Code
                                                </th>
                                                <th style="width: 15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                            @endphp
                                            {{-- @if (Auth::user()->is_admin == '1' || Auth::user()->type == 'Admin') --}}
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td>{{ $item->category }}</td>
                                                    <td>{{ $item->warehouse->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->retail_price ?? '0' }}</td>
                                                    <td>{{ $item->wholesale_price ?? '0' }}</td>
                                                    <td>{{ $item->buy_price ?? '0' }}</td>
                                                    <td>{{ $item->barcode }}</td>

                                                    <td>
                                                        @if ($item->expired_date)
                                                            {{ $item->expired_date }}
                                                        @else
                                                            No Expired Date
                                                        @endif
                                                    </td>

                                                    <td style="background-color: rgb(221, 215, 215)">
                                                        @if ($item->quantity <= $item->reorder_level_stock)
                                                            <span class="text-danger">{{ $item->quantity }}</span>
                                                        @else
                                                            {{ $item->quantity }}
                                                        @endif

                                                    </td>
                                                    <td><a href="{{ url('barcode', $item->id) }}"
                                                            class="mt-1 text-white btn btn-warning btn-sm">Generate</a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('item_details', $item->id) }}"
                                                            class="btn btn-primary btn-sm"><i
                                                                class="fa-solid fa-eye"></i></a>

                                                        <a href="{{ url('item_edit', $item->id) }}"
                                                            class="btn btn-success btn-sm"><i
                                                                class="fa-solid fa-pen-to-square"></i></a>
                                                        <a href="{{ url('item_delete', $item->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this Item ?')"><i
                                                                class="fa-solid fa-trash"></i></a>
                                                        {{-- <a href="{{ url('in_out', $item->id) }}"
                                                            class="mt-1 btn btn-info btn-sm">In/Out
                                                            History </a> --}}

                                                    </td>
                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                            {{-- @elseif (auth()->user()->type == 'Shop')
                                                @foreach ($items as $item)
                                                    @if ($item->warehouse_id == Auth::user()->level)
                                                        <tr>
                                                            <td>{{ $no }}</td>
                                                            <td>{{ $item->item_name }}</td>
                                                            <td>{{ $item->category }}</td>
                                                            <td>{{ $item->warehouse->name ?? 'N/A' }}</td>
                                                            <td>{{ $item->retail_price ?? '0' }}</td>
                                                            <td>{{ $item->wholesale_price ?? '0' }}</td>
                                                            <td>{{ $item->buy_price ?? '0' }}</td>
                                                            <td>{{ $item->barcode }}</td>

                                                            <td>
                                                                @if ($item->expired_date)
                                                                    {{ $item->expired_date }}
                                                                @else
                                                                    No Expired Date
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if ($item->quantity <= $item->reorder_level_stock)
                                                                    <span
                                                                        class="text-danger">{{ $item->quantity }}</span>
                                                                @else
                                                                    {{ $item->quantity }}
                                                                @endif

                                                            </td>
                                                            <td><a href="{{ url('barcode', $item->id) }}"
                                                                    class="mt-1 text-white btn btn-warning btn-sm">Generate</a>
                                                            </td>
                                                            <td>

                                                                <a href="{{ url('item_edit', $item->id) }}"
                                                                    class="btn btn-success btn-sm"><i
                                                                        class="fa-solid fa-pen-to-square"></i></a>

                                                                @if (Auth::user()->type == 'Warehouse')
                                                                    <a href="{{ url('item_details', $item->id) }}"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fa-solid fa-eye"></i></a>


                                                                    <a href="{{ url('item_delete', $item->id) }}"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Are you sure you want to delete this Item ?')"><i
                                                                            class="fa-solid fa-trash"></i></a>
                                                                    <a href="{{ url('in_out', $item->id) }}"
                                                                        class="mt-1 btn btn-info btn-sm">In/Out
                                                                        History </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $no++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @elseif (Auth::user()->type == 'Warehouse')
                                                @foreach ($items as $item)
                                                    <tr>
                                                        <td>{{ $no }}</td>
                                                        <td>{{ $item->item_name }}</td>
                                                        <td>{{ $item->category }}</td>
                                                        <td>{{ $item->warehouse->name ?? 'N/A' }}</td>
                                                        <td>{{ $item->retail_price ?? '0' }}</td>
                                                        <td>{{ $item->wholesale_price ?? '0' }}</td>
                                                        <td>{{ $item->buy_price ?? '0' }}</td>
                                                        <td>{{ $item->barcode }}</td>

                                                        <td>
                                                            @if ($item->expired_date)
                                                                {{ $item->expired_date }}
                                                            @else
                                                                No Expired Date
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($item->quantity <= $item->reorder_level_stock)
                                                                <span class="text-danger">{{ $item->quantity }}</span>
                                                            @else
                                                                {{ $item->quantity }}
                                                            @endif

                                                        </td>
                                                        <td><a href="{{ url('barcode', $item->id) }}"
                                                                class="mt-1 text-white btn btn-warning btn-sm">Generate</a>
                                                        </td>
                                                        <td>

                                                            <a href="{{ url('item_edit', $item->id) }}"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>

                                                            @if (Auth::user()->type == 'Warehouse')
                                                                <a href="{{ url('item_details', $item->id) }}"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="fa-solid fa-eye"></i></a>


                                                                <a href="{{ url('item_delete', $item->id) }}"
                                                                    class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Are you sure you want to delete this Item ?')"><i
                                                                        class="fa-solid fa-trash"></i></a>
                                                                <a href="{{ url('in_out', $item->id) }}"
                                                                    class="mt-1 btn btn-info btn-sm">In/Out
                                                                    History </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endforeach

                                            @endif --}}
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

    <!-- Page specific script -->
    <script>
        new DataTable('#example1', {

            "lengthChange": false,
            "paging": true,
            "pageLength": 100,

        });
    </script>
</body>

</html>
