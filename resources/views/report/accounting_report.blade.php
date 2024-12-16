@include('layouts.header')
<style>
    .body {
        background: none;
    }

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
                                <h1>Accounting
                                    Report
                                </h1>
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


                <!-- /.modal -->
                <div class="mt-5 d-flex justify-content-center">
                    <div class="">

                        <div class="card-body justify-content-ceneter">


                            <div
                                class=" row d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">

                                <div class="col-md-5 mx-3 my-2">
                                    <a href="{{ url('accounting_report/All') }}">
                                        <div class="class-card p-3 shadow-sm">
                                            <div class="class-title text-center">
                                                All
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @foreach ($branches as $branch)
                                    <div class="col-md-5 mx-3 my-2">
                                        <a href="{{ url('accounting_report/' . $branch->id) }}">
                                            <div class="class-card p-3 shadow-sm">
                                                <div class="class-title text-center">
                                                    {{ $branch->name }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    {{-- <div class="card col-md-5" style="background-color: #cedce9"> <a
                                            href="{{ url('accounting_report/' . $branch->id) }}') }}">
                                            <div class="card-body" style="text-align: center">
                                                <span>{{ $branch->name }}</span>
                                            </div>
                                        </a>
                                    </div> --}}
                                @endforeach
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




</body>

</html>
