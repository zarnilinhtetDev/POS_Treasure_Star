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
                    <a class="nav-link text-white " href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


                <li class="nav-item ml-auto">
                    <a class="nav-link" href="#">


                    </a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">


                <div class="btn-group">
                    <button type="button" class="btn text-white dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu ">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn  p-1 changelogout " style="width: 157px">
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
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Monthly Net Profit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Monthly Net Profit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="my-5 container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ url('search') }}" method="get">
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

                <div class="ml-2 container-fluid">


                    <div class="mt-3 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Monthly Net Profit Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @php
                                    use Carbon\Carbon;
                                @endphp
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Total Sale</th>
                                            <th>Total Purchase</th>
                                            <th>Total Expense</th>
                                            <th>Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php

                                        @endphp
                                        <tr>
                                            <td>{{ $invoices->isNotEmpty() ? $invoices->first()->created_at->format('F /Y') : '' }}
                                            </td>

                                            <td>{{ $totalSum }}</td>
                                            <td>{{ $totalPurchase }}</td>
                                            <td>{{ $totalExpense }}</td>
                                            <td>{{ $totalSum - ($totalPurchase + $totalExpense) }}

                                            </td>

                                        </tr>


                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

            </section>

        </div>



    </div>


    @include('layouts.footer')
