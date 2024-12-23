@include('layouts.header')
<style>
    .img-thumbnail {
        border: 2px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        transition: transform 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
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
                    <div class="row">
                        <div class="ml-2 col row d-flex">
                            <form action="{{ route('file-import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4 form-group" style="max-width: 500px; margin: 0 auto;">

                                    <div class="text-left custom-file">

                                        @if (Auth::user()->is_admin == '1')
                                            <label for="warehouse">Choose Location</label>
                                            <select name="warehouse_id" id="warehouse" class="form-control" required>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                    </option>
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

                        {{-- Permission --}}
                        @php
                            $choosePermission = [];
                            if (auth()->user()->permission) {
                                $decodedPermissions = json_decode(auth()->user()->permission, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $choosePermission = $decodedPermissions;
                                }
                            }
                        @endphp
                        {{-- End Permission --}}

                        {{-- <div class="ml-2 col row d-flex">
                            <form action="{{ route('file-update-import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4 form-group" style="max-width: 500px; margin: 0 auto;">

                                    <div class="text-left custom-file">


                                        @if (Auth::user()->is_admin == '1')
                                            <label for="warehouse">Choose Location</label>
                                            <select name="warehouse_id" id="warehouse" class="form-control" required>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                    </option>
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
                                        <button class="mt-3 btn btn-primary">Update Price</button>


                                    </div>
                                </div>


                            </form>
                        </div> --}}
                    </div>



                    @if (in_array('Item Register', $choosePermission) || auth()->user()->is_admin == '1')
                        <div class="mt-5 mr-auto col">
                            <a href="{{ url('items_register') }}" type="button" class="mr-auto btn btn-primary ">
                                Item Register</a>
                        </div>
                    @endif


                    <div class="container-fluid">

                        <!-- /.modal -->
                        <div class="mt-3 col-md-12">
                            <div class="bg-white">

                                <div class="card-header">
                                    <h3 class="card-title">Items List</h3>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="d-flex justify-content-end mb-3">
                                        <input type="text" id="search" class="form-control col-2 ms-auto"
                                            placeholder="Search items">
                                    </div>
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-striped items-table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Item Image 1</th>
                                                    <th>Item Image 2</th>
                                                    <th>Item Name</th>
                                                    <th>Category</th>
                                                    <th>Location</th>
                                                    <th>လက်လီစျေး</th>
                                                    <th>လက်ကားစျေး</th>
                                                    <th>Barcode</th>
                                                    <th>Expired Date</th>
                                                    <th style="background-color: rgb(221, 215, 215)">Quantity</th>
                                                    <th>Bar Code</th>
                                                    <th style="width: 15%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp

                                                @foreach ($items as $item)
                                                    <tr>
                                                        <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ asset($item->item_image ? 'item_images/' . $item->item_image : 'img/default.png') }}"
                                                                target="_blank" id="logoLink">
                                                                <img src="{{ asset($item->item_image ? 'item_images/' . $item->item_image : 'img/default.png') }}"
                                                                    id="logoPreview" class="img-thumbnail"
                                                                    style="max-width: 150px; max-height: 100px;"
                                                                    alt="Item Image Preview">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ asset($item->item_image_2 ? 'item_images/' . $item->item_image_2 : 'img/default.png') }}"
                                                                target="_blank" id="logoLink">
                                                                <img src="{{ asset($item->item_image_2 ? 'item_images/' . $item->item_image_2 : 'img/default.png') }}"
                                                                    id="logoPreview" class="img-thumbnail"
                                                                    style="max-width: 150px; max-height: 100px;"
                                                                    alt="Item Image Preview">
                                                            </a>
                                                        </td>
                                                        <td>{{ $item->item_name }}</td>
                                                        <td>{{ $item->category }}</td>
                                                        <td>{{ $item->warehouse->name ?? 'N/A' }}</td>
                                                        <td>{{ number_format($item->retail_price ?? 0, 2) }}
                                                        </td>
                                                        <td>{{ number_format($item->wholesale_price ?? 0, 2) }}</td>
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
                                                                @if ($item->item_type == 'Service')
                                                                    <span class="text-danger">0</span>
                                                                @else
                                                                    <span
                                                                        class="text-danger">{{ $item->quantity }}</span>
                                                                @endif
                                                            @else
                                                                @if ($item->item_type == 'Service')
                                                                    <span class="text-danger">0</span>
                                                                @else
                                                                    {{ $item->quantity }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ url('barcode', $item->id) }}"
                                                                class="mt-1 text-white btn btn-warning btn-sm">Generate</a>
                                                        </td>
                                                        <td>
                                                            @if (in_array('Item Details', $choosePermission) || auth()->user()->is_admin == '1')
                                                                <a href="{{ url('item_details', $item->id) }}"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="fa-solid fa-eye"></i></a>
                                                            @endif
                                                            @if (in_array('Item Edit', $choosePermission) || auth()->user()->is_admin == '1')
                                                                <a href="{{ url('item_edit', $item->id) }}"
                                                                    class="btn btn-success btn-sm"><i
                                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                            @endif
                                                            @if (in_array('Item Delete', $choosePermission) || auth()->user()->is_admin == '1')
                                                                <a href="{{ url('item_delete', $item->id) }}"
                                                                    class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Are you sure you want to delete this Item ?')"><i
                                                                        class="fa-solid fa-trash"></i></a>
                                                            @endif
                                                            @if (in_array('Item In/Out', $choosePermission) || auth()->user()->is_admin == '1')
                                                                <a href="{{ url('in_out', $item->id) }}"
                                                                    class="mt-1 btn btn-info btn-sm">In/Out History</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="pagination-info text-start">
                                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of
                                                {{ $items->total() }} results
                                            </div>
                                            <div>
                                                {{ $items->links('pagination::bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>




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

    <script>
        $(document).ready(function() {

            $('#search').on('keyup', function() {
                var query = $(this).val();
                var no = 1;

                $.ajax({
                    url: "{{ route('items.filter') }}",
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        var rows = '';

                        $.each(response.data, function(index, item) {
                            rows += '<tr>';
                            rows += '<td>' + no +
                                '</td>';
                            rows += '<td><a href="' + (item.item_image ?
                                    '{{ asset('item_images') }}/' + item.item_image :
                                    '{{ asset('img/default.png') }}') +
                                '" target="_blank" id="logoLink">' +
                                '<img src="' + (item.item_image ?
                                    '{{ asset('item_images') }}/' + item.item_image :
                                    '{{ asset('img/default.png') }}') +
                                '" class="img-thumbnail" style="max-width: 150px; max-height: 100px;" alt="Item Image Preview">' +
                                '</a></td>';
                            rows += '<td><a href="' + (item.item_image_2 ?
                                    '{{ asset('item_images') }}/' + item.item_image_2 :
                                    '{{ asset('img/default.png') }}') +
                                '" target="_blank" id="logoLink">' +
                                '<img src="' + (item.item_image_2 ?
                                    '{{ asset('item_images') }}/' + item.item_image_2 :
                                    '{{ asset('img/default.png') }}') +
                                '" class="img-thumbnail" style="max-width: 150px; max-height: 100px;" alt="Item Image Preview">' +
                                '</a></td>';
                            rows += '<td>' + item.item_name + '</td>';
                            rows += '<td>' + item.category + '</td>';
                            rows += '<td>' + (item.warehouse ? item.warehouse.name :
                                'N/A') + '</td>';
                            rows += '<td>' + (item.retail_price ? item.retail_price :
                                '0') + '</td>';
                            rows += '<td>' + (item.wholesale_price ? item
                                .wholesale_price : '0') + '</td>';
                            rows += '<td>' + item.barcode + '</td>';
                            rows += '<td>' + (item.expired_date ? item.expired_date :
                                'No Expired Date') + '</td>';


                            if (item.quantity <= item.reorder_level_stock) {
                                rows +=
                                    '<td style="background-color: rgb(221, 215, 215)"><span class="text-danger">' +
                                    item.quantity + '</span></td>';
                            } else {
                                rows +=
                                    '<td style="background-color: rgb(221, 215, 215)">' +
                                    item.quantity + '</td>';
                            }


                            rows += '<td><a href="{{ url('barcode') }}/' +
                                item.id +
                                '" class="mt-1 text-white btn btn-warning btn-sm">Generate</a></td>';


                            rows += '<td>';
                            if (item.can_view) {
                                rows += '<a href="{{ url('item_details') }}/' + item
                                    .id +
                                    '" class="btn btn-primary btn-sm"><i class="fa-solid fa-eye"></i></a> ';
                            }
                            if (item.can_edit) {
                                rows += '<a href="{{ url('item_edit') }}/' + item.id +
                                    '" class="btn btn-success btn-sm"><i class="fa-solid fa-pen-to-square"></i></a> ';
                            }
                            if (item.can_delete) {
                                rows += '<a href="{{ url('item_delete') }}/' + item
                                    .id +
                                    '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this Item ?\')"><i class="fa-solid fa-trash"></i></a> ';
                            }
                            if (item.can_in_out) {
                                rows += '<a href="{{ url('in_out') }}/' + item
                                    .id +
                                    '" class="mt-1 btn btn-info btn-sm">In/Out History</a> ';
                            }

                            rows += '</td>';
                            rows += '</tr>';

                            no++;
                        });

                        $('.items-table tbody').html(
                            rows);
                    }
                });
            });
        });


        // $(document).ready(function() {
        //     new DataTable('#example1', {
        //         "lengthChange": false,
        //         "paging": false,
        //         "pageLength": 100,
        //         "searching": false,
        //         "dom": 'Bfrtip',
        //         "buttons": [{
        //             extend: 'excelHtml5',
        //             text: 'Change Price Excel',
        //             exportOptions: {
        //                 modifier: {
        //                     selected: null,
        //                     search: 'applied',
        //                 },
        //                 columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
        //             }
        //         }],
        //         "language": {
        //             "info": "",
        //             "infoEmpty": "",
        //             "infoFiltered": ""
        //         }
        //     });
        // });
        $(document).ready(function() {
            new DataTable('#example1', {
                "lengthChange": false,
                "paging": false,
                "pageLength": 100,
                "searching": false,
                "dom": 'Bfrtip',
                "buttons": [],
                "language": {
                    "info": "",
                    "infoEmpty": "",
                    "infoFiltered": ""
                }
            });
        });
    </script>
</body>

</html>
