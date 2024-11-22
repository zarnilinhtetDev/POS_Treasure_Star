@include('layouts.header')
<style>
    .class-card {
        border: 1px solid #ddd;
        border-radius: 10px;

        background: linear-gradient(to right, #83ade8, #2270c9);
        color: rgb(245, 239, 239);
        transition: transform 0.2s ease-in-out;
    }

    .class-card:hover {
        transform: scale(1.1);
        color: black;
        background: linear-gradient(to left, #83ade8, #2270c9);
    }

    .class-title {
        font-size: 24px;
        font-weight: bold;
    }

    .class-info {
        font-size: 14px;
        color: #6c757d;
    }

    .class-actions {
        margin-top: 15px;
    }
</style>

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

                <li class="nav-item text-white">
                    <a class="nav-link text-white" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle text-white" data-toggle="dropdown"
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
                                {{-- <h1>Accounting
                                    Report
                                </h1> --}}
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Accounting Report
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="ml-2 container-fluid">






                    <!-- /.modal -->

                    <div class="mt-3 col-md-10 mx-auto">
                        <h5 class="class-title mx-auto mt-3 mb-5">Location : {{ $branch->name ?? 'All' }}</h5>
                        <div class="row d-flex justify-content-between">
                            {{-- <div class="class-card col-md-3"style="background-color: #cedce9">


                                <a href="{{ url('general_ledger', $branch->id) }}">
                                    <div class="card-body"style="text-align: center">

                                        <div class="col-md-12">General Ledger</div>
                                    </div>
                                </a>
                                <!-- /.card-body -->
                            </div> --}}
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                <a href="{{ url('general_ledger', $branch->id ?? 'All') }}">
                                    <div class="class-card p-3 shadow-sm">
                                        <div class="class-title text-center">
                                            General Ledger
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                <a href="{{ url('profit_loss', $branch->id ?? 'All') }}">
                                    <div class="class-card p-3 shadow-sm">
                                        <div class="class-title text-center">
                                            Profit & Loss
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                <a href="{{ url('balance_sheet', $branch->id ?? 'All') }}">
                                    <div class="class-card p-3 shadow-sm">
                                        <div class="class-title text-center">
                                            Balance Sheet
                                        </div>
                                    </div>
                                </a>
                            </div>
                            {{-- <div class="class-card col-md-3"style="background-color: #cedce9">


                                <a href="{{ url('profit_loss', $branch->id) }}">
                                    <div class="card-body" style="text-align: center">

                                        <div class="col-md-12">Profit & Loss</div>
                                    </div>
                                </a>
                                <!-- /.card-body -->
                            </div> --}}
                            {{-- <div class="class-card col-md-3"style="background-color: #cedce9">


                                <a href="{{ url('balance_sheet', $branch->id) }}">
                                    <div class="card-body" style="text-align: center">

                                        <div class="col-md-12">Balance Sheet</div>
                                    </div>
                                </a>
                                <!-- /.card-body -->
                            </div> --}}
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




</body>

</html>
