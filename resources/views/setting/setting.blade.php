@include('layouts.header')



<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
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

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">



                            <section class="content-header">
                                <div class="container-fluid">
                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <h1>Settings</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Settings</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>{{ session('success') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="mr-auto col my-2"> <button type="button" class="mr-auto btn btn-primary "
                                    data-toggle="modal" data-target="#modal-lg">
                                    Register Setting </button>

                            </div>
                            <div class="modal fade" id="modal-lg">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"> Register Setting</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('setting_store') }}" method="POST">
                                                @csrf
                                                <div class="card-body">

                                                    <div class="form-group">
                                                        <label for="name">Category Name <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" name="category">
                                                            <option value="" selected disabled>Choose Category
                                                            </option>
                                                            <option value="Invoice">Invoice</option>
                                                            <option value="Sale Return (Invoice) ">Sale Return (Invoice)
                                                            </option>
                                                            <option value="POS">POS</option>
                                                            <option value="Sale Return (POS)">Sale Return (POS)</option>
                                                            <option value="Purchase Order">Purchase Order</option>
                                                            <option value="Purchase Order Return">Purchase Order Return
                                                            </option>
                                                            <option value="Payable (Purchase Order)">Payable (Purchase
                                                                Order)</option>
                                                            <option value="Receivable (Invoice)">Receivable (Invoice)
                                                            </option>
                                                            <option value="Expense">Expense
                                                            </option>
                                                            {{-- <option value="Account Receivable">Account Receivable
                                                            </option> --}}
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phno">Location <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" name="location" id="location">
                                                            <option value="" selected disabled>Choose Location
                                                            </option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">{{ $branch->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phno">Transaction Name <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" name="transaction_id"
                                                            id="transaction">
                                                            <option value="" selected disabled>Choose Choose
                                                            </option>
                                                            @foreach ($transactions as $transaction)
                                                                <option value="{{ $transaction->id }}">
                                                                    {{ $transaction->transaction_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
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
                            <div class="card">

                                <div class="card-body">

                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>

                                                <th> Category Name</th>
                                                <th>Location</th>

                                                <th>Transaction Name</th>


                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                            @endphp
                                            @foreach ($settings as $setting)
                                                <tr>
                                                    <td>{{ $no }}</td>


                                                    <td>{{ $setting->category }}</td>
                                                    <td>{{ $setting->warehouse->name ?? '' }}</td>



                                                    <td>{{ $setting->transaction->transaction_name ?? '' }}</td>
                                                    <td>
                                                        <div class="row"><a
                                                                href="{{ url('setting_edit/' . $setting->id) }}"
                                                                class="btn btn-success mx-2"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                            <a href="{{ url('setting_delete/' . $setting->id) }}"
                                                                class="btn btn-danger"><i
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
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </section>

    </div>
    </div>
    {{-- <script src="{{ asset('backend/js/jquery-3.6.0.js') }}"></script> --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js ') }}"></script>

    <script>
        $(document).ready(function() {
            $('#location').on('change', function() {


                const selectedBranch = $(this).val();
                const account = document.getElementById('transaction');
                // Clear the doctor select options
                account.innerHTML = '<option value="">Select Transaction</option>';

                // Filter and add the doctors based on the selected branch
                @foreach ($transactions as $tran)
                    if (selectedBranch === '{{ $tran->location }}') {
                        const option = document.createElement('option');
                        option.value = '{{ $tran->id }}';
                        option.textContent = '{{ $tran->transaction_name }}';
                        account.appendChild(option);
                    }
                @endforeach
            });
            $('#location').trigger('change');
        });
    </script>

    @include('layouts.footer')
