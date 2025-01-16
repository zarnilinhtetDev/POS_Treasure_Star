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
                                <h1>Make Payment</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Make Payment
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
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="container-fluid">
                    <div class="row justify-content-center d-flex">
                        <div class="card col-8">

                            <form action="{{ url('custom_make_payment_store', $invoice->id) }}" method="POST"
                                class="p-5">
                                @csrf
                                <div class="row mb-4">

                                    <div class="col-md-4 form-group">
                                        <button type="button" class="btn btn-primary" style="width: 100%">Total
                                            -
                                            {{ number_format($invoice->total) }}
                                        </button>

                                    </div>
                                    <input type="hidden" id="location" value="{{ $invoice->branch }}">

                                    <div class="col-md-4 form-group">
                                        <button type="button" class="btn btn-primary" style="width: 100%">Deposit
                                            -
                                            {{ number_format($invoice->deposit) }}
                                        </button>

                                    </div>

                                    <div class="col-md-4 form-group">
                                        <input type="hidden" name="remain_balance"
                                            value="{{ $invoice->remain_balance }}">
                                        <button type="button" class="btn btn-primary" style="width: 100%">
                                            Remaining Balance -
                                            {{ number_format($invoice->remain_balance) }}
                                        </button>
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label for="invoice_no">Invoice Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="invoice_no"
                                            value="{{ $invoice->invoice_no }}" name="invoice_no" required readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="payment_date">Payment Date<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="payment_date"
                                            placeholder="Enter payment date" name="payment_date" required
                                            value="{{ date('Y-m-d') }}">

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="payment_method">Payment Method <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="payment_method" id="payment_method-0"
                                            required>
                                            <option value="" disabled selected>Select payment method</option>
                                            <option value="Kyats">Kyats</option>
                                            <option value="US($)">US($)</option>
                                            <option value="Yen">Yen</option>
                                            <option value="Baht">Baht</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6" id="total_amount">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            placeholder="Enter amount" required>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="note">Note</label>
                                        <textarea rows="3" class="form-control" id="note" name="note" placeholder="Enter note"></textarea>
                                    </div>


                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-2">Make Pyament</button>
                                    </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="container-fluid mt-5">
                    <div class="row justify-content-center d-flex">
                        <div class="card col-md-11">
                            <div class="card-header">
                                <h5>Payment List</h5>
                            </div>
                            <div class="card-body">
                                <table id="example" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Invoice Number</th>
                                            <th>Payment Method</th>
                                            <th>Amount</th>
                                            <th>Note</th>
                                            <th>Payment Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $key => $payment)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $payment->custom_invoice_no }}</td>
                                                <td>{{ $payment->payment_method }}</td>
                                                <td>{{ number_format($payment->amount) }}</td>
                                                <td>{{ $payment->note }}</td>
                                                <td>{{ $payment->payment_date }}</td>
                                                <td>
                                                    <a href="{{ url('custom_invoice_voucher', $payment->id) }}"
                                                        class="btn btn-primary">Print</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

        </div>



    </div>


    @include('layouts.footer')
    <script>
        $(function() {
            $("#example").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 10, // Set the default number of rows to display
                // "buttons": ["excel", "pdf", "print"]
            }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
