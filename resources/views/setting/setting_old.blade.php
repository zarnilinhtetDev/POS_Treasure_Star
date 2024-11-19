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




                            <div class="my-5 col-md-6 mx-auto">

                                <div class="card text-white" style="background-color: #1560BD">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-3">
                                            <h5 class="col-md-5">Invoice :</h5>
                                            @if ($invoice && $invoice->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $invoice->transaction->transaction_name ?? null }}
                                                </h5>


                                                <div class="col-md-2 d-flex justify-content-end">

                                                    <button type="button" class="btn btn-primary mr-2"
                                                        data-toggle="modal" data-target="#modal-expense">
                                                        Edit
                                                    </button>


                                                    <form action="{{ route('invoice_setting_delete') }}" method="POST"
                                                        style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Remove</button>
                                                    </form>

                                                </div>


                                                <div class="modal fade" id="modal-expense">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Invoice</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('invoice_setting_edit', $invoice->category) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id" class="form-control">
                                                                        <option value="{{ $invoice->transaction_id }}">
                                                                            {{ $invoice->transaction->transaction_name ?? null }}
                                                                        </option>
                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}">
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ route('invoice_setting') }}" method="post"
                                                    class="col-md-7">
                                                    @csrf
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction</option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit" class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>



                                <div class="card text-white" style="background-color: #1560BD">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-3">
                                            <h5 class="col-md-5">POS :</h5>
                                            @if ($point_of_sales && $point_of_sales->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $point_of_sales->transaction->transaction_name ?? null }}
                                                </h5>
                                                <div class="col-md-2 d-flex justify-content-end">

                                                    <button type="button" class="btn btn-primary mr-2"
                                                        data-toggle="modal" data-target="#modal-pos">
                                                        Edit
                                                    </button>

                                                    <form action="{{ route('pos_setting_delete') }}" method="POST"
                                                        style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Remove</button>
                                                    </form>
                                                </div>

                                                <div class="modal fade" id="modal-pos">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">POS</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('pos_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id"
                                                                        class="form-control">
                                                                        <option
                                                                            value="{{ $point_of_sales->transaction_id }}">
                                                                            {{ $point_of_sales->transaction->transaction_name ?? null }}
                                                                        </option>
                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}">
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ route('pos_setting') }}" method="post"
                                                    class="col-md-7">
                                                    @csrf
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction</option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card text-white" style="background-color: #1560BD">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-3">
                                            <h5 class="col-md-5">Purchase Order :</h5>
                                            @if ($purchase_orders && $purchase_orders->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $purchase_orders->transaction->transaction_name ?? null }}
                                                </h5>

                                                <div class="col-md-2 d-flex justify-content-end">

                                                    <button type="button" class="btn btn-primary mr-2"
                                                        data-toggle="modal" data-target="#modal-po">
                                                        Edit
                                                    </button>

                                                    <form action="{{ route('purchase_order_setting_delete') }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Remove</button>
                                                    </form>
                                                </div>


                                                <div class="modal fade" id="modal-po">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Purchase Order</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('purchase_order_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id"
                                                                        class="form-control">
                                                                        <option
                                                                            value="{{ $purchase_orders->transaction_id }}">
                                                                            {{ $purchase_orders->transaction->transaction_name ?? null }}
                                                                        </option>
                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}">
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ route('purchase_order_setting') }}" method="post"
                                                    class="col-md-7">
                                                    @csrf
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction</option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="card text-white" style="background-color: #1560BD">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-3">
                                            <h5 class="col-md-5">Purchase Return :</h5>
                                            @if ($purchase_returns && $purchase_returns->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $purchase_returns->transaction->transaction_name ?? null }}
                                                </h5>

                                                <div class="col-md-2 d-flex justify-content-end">

                                                    <button type="button" class="btn btn-primary mr-2"
                                                        data-toggle="modal" data-target="#modal-pr">
                                                        Edit
                                                    </button>

                                                    <form action="{{ route('purchase_return_setting_delete') }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Remove</button>
                                                    </form>
                                                </div>
                                                <div class="modal fade" id="modal-pr">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Purchase Return</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('purchase_return_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id"
                                                                        class="form-control">
                                                                        <option
                                                                            value="{{ $purchase_returns->transaction_id }}">
                                                                            {{ $purchase_returns->transaction->transaction_name ?? null }}
                                                                        </option>
                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}">
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ route('purchase_return_setting') }}" method="post"
                                                    class="col-md-7">
                                                    @csrf
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction</option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card text-white" style="background-color: #1560BD">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-3">
                                            <h5 class="col-md-5">Sale Return (Invoice):</h5>
                                            @if ($sale_return_invoices && $sale_return_invoices->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $sale_return_invoices->transaction->transaction_name ?? null }}
                                                </h5>

                                                <div class="col-md-2 d-flex justify-content-end">

                                                    <button type="button" class="btn btn-primary mr-2"
                                                        data-toggle="modal" data-target="#modal-sr_inv">
                                                        Edit
                                                    </button>

                                                    <form action="{{ route('sale_return_invoice_setting_delete') }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Remove</button>
                                                    </form>
                                                </div>

                                                <div class="modal fade" id="modal-sr_inv">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Sale Return (Invoice)</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('sale_return_invoice_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id"
                                                                        class="form-control">
                                                                        <option
                                                                            value="{{ $sale_return_invoices->transaction_id }}">
                                                                            {{ $sale_return_invoices->transaction->transaction_name ?? null }}
                                                                        </option>
                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}">
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ route('sale_return_invoice_setting') }}"
                                                    method="post" class="col-md-7">
                                                    @csrf
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction</option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card text-white" style="background-color: #1560BD">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-3">
                                            <h5 class="col-md-5">Sale Return (POS):</h5>
                                            @if ($sale_return_pos && $sale_return_pos->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $sale_return_pos->transaction->transaction_name ?? null }}
                                                </h5>

                                                <div class="col-md-2 d-flex justify-content-end">

                                                    <button type="button" class="btn btn-primary mr-2"
                                                        data-toggle="modal" data-target="#modal-sr_pos">
                                                        Edit
                                                    </button>

                                                    <form action="{{ route('sale_return_pos_setting_delete') }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Remove</button>
                                                    </form>
                                                </div>

                                                <div class="modal fade" id="modal-sr_pos">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Sale Return (POS)</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('sale_return_pos_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id"
                                                                        class="form-control">
                                                                        <option
                                                                            value="{{ $sale_return_pos->transaction_id }}">
                                                                            {{ $sale_return_pos->transaction->transaction_name ?? null }}
                                                                        </option>
                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}">
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ route('sale_return_pos_setting') }}" method="post"
                                                    class="col-md-7">
                                                    @csrf
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction</option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
    @include('layouts.footer')
