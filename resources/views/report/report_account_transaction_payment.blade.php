@include('layouts.header')
<style>
    #tableSelect {
        background-color: #6c757d;
        color: white;
    }

    #tableSelect option {
        background-color: white;
        color: black;
    }


    #tableSelect:focus {
        background-color: #6c757d;
        color: white;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.nav')
        @include('layouts.sidebar')
        <div class="content-wrapper">


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
                                            <h1>Transaction Add Payment</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Transaction Add Payment</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div><!-- /.container-fluid -->
                            </section>
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

                            {{-- @dd($transaction); --}}

                            <h5 class="my-3"> Transaction
                                Name -{{ $transaction->transaction_name }}</h5>


                            {{-- <h5 class="mb-4">Amount:
                                    {{ $TotalInvoice[$transaction->id] + $TotalDepositInvoice[$transaction->id] + $TotalRemainInvoice[$transaction->id] + $TotalDepositPoReturn[$transaction->id] + $TotalDepositPointOfSale[$transaction->id] - ($TotalDepositSaleReturnPos[$transaction->id] + $TotalPurchaseOrder[$transaction->id] + $TotalDepositPurchaseOrder[$transaction->id] + $TotalDepositSaleReturnInvoice[$transaction->id] + $TotalRemainPurchaseOrder[$transaction->id] + $TotalExpense[$transaction->id]) }}
                                </h5> --}}
                            {{-- <h5 class="mb-4">Amount:
                                    {{ number_format($TotalInvoice[$transaction->id] + $TotalDepositInvoice[$transaction->id] + $TotalRemainInvoice[$transaction->id] + $TotalDepositPoReturn[$transaction->id] + $TotalDepositPointOfSale[$transaction->id] - ($TotalPurchaseOrder[$transaction->id] + $TotalDepositPurchaseOrder[$transaction->id] + $TotalDepositSaleReturnInvoice[$transaction->id] + $TotalDepositSaleReturn[$transaction->id] + $TotalExpense[$transaction->id] + $TotalRemainPurchaseOrder[$transaction->id])) }}
                                </h5> --}}

                            {{-- <h5 class="my-3"> Transaction
                                Name -{{ $transaction->transaction_name }}</h5>
--}}

                            <h5 class="mb-4">Amount:
                                {{ number_format($total_invoices + $total_deposit_invoices + $total_remain_invoices + $total_deposit_po_returns + $total_deposit_point_of_sales + $totalIn - ($total_purchase_orders + $total_deposit_purchase_orders + $total_deposit_sale_return_invoices + $total_deposit_sale_return_pos + $total_expense + $total_remain_purchase_orders + $totalOut)) }}
                            </h5>




                            {{-- Modal Content --}}
                            <div class="modal fade" id="modal-lg">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Payment Register</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('/transaction_payment_register', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $transaction->id }}"
                                                    name="transaction_id">
                                                <input type="hidden" value="{{ $transaction->account->id }}"
                                                    name="account_id">

                                                <div class="form-group">
                                                    <label for="status">Status <span
                                                            style="color: red;">*</span></label>
                                                    <select name="payment_status" class="form-control" id="status"
                                                        required>
                                                        <option value="">Choose One</option>
                                                        <option value="IN">IN</option>
                                                        <option value="OUT">OUT</option>
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction_code">Amount <span
                                                            style="color: red;">*</span></label>
                                                    <input type="number" class="form-control" id="transaction_code"
                                                        name="amount" placeholder="Enter Amount" required>
                                                    @error('transaction_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" id="description" name="note" rows="3" placeholder="Enter ..."></textarea>
                                                    @error('description')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
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



                            <!-- Nav tabs -->

                            {{-- <div class="col-md-3 col-6 col-lg-2 mb-5">
                                <select class="form-control shadow-sm p-2 bg-secondary text-white border-0 rounded-pill"
                                    id="tableSelect">
                                    <option value="payment" selected>Payment Table</option>
                                    <option value="invoice">Invoice Table</option>
                                    <option value="pos">POS Table</option>
                                    <option value="po">PO Table</option>
                                    <option value="pr">PO Return Table</option>
                                    <option value="sr-inv">Sale Return (Invoice) Table</option>
                                    <option value="sr-pos">Sale Return (POS) Table</option>
                                    <option value="expense">Expense Table</option>
                                </select>
                            </div> --}}



                            <!-- Tab content -->
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="payment" role="tabpanel"
                                    aria-labelledby="payment-tab">
                                    <div class="my-5 container-fluid">
                                        {{-- <div class="row">
                                            <div class="col-md-10">
                                                <form
                                                    action="{{ url('report_account_transaction_payment_search', $id) }}"
                                                    method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" class="form-control"
                                                                required>
                                                        </div>


                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Account Name</th>
                                                            <th>Transaction Name</th>
                                                            <th>Source</th>
                                                            <th>Status</th>
                                                            <th>Amount</th>

                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                            $total = 0;
                                                        @endphp

                                                        @foreach ($payment as $index => $payments)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>
                                                                    {{ $payments->account->account_name }}
                                                                </td>
                                                                <td>{{ $payments->transaction->transaction_name }}</td>
                                                                <td>Add Payment</td>
                                                                <td>

                                                                    {{ $payments->payment_status }}


                                                                </td>
                                                                <td>
                                                                    {{-- $TotalInvoice = [];
                                                                            $TotalDepositInvoice = [];
                                                                            $TotalRemainInvoice = [];
                                                                            $TotalDepositPurchaseOrder = [];
                                                                            $TotalPurchaseOrder = [];
                                                                            $TotalRemainPurchaseOrder = [];
                                                                            $TotalDepositSaleReturn = [];
                                                                            $TotalDepositPointOfSale = [];
                                                                            $TotalExpense = []; --}}

                                                                    {{ number_format($payments->amount ?? 0, 2) }}
                                                                </td>

                                                                <td>{{ $payments->note ?? 'N/A' }}</td>

                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $payments->amount;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($invoices as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $invoice->transaction->transaction_name }}</td>
                                                                <td><a
                                                                        href="{{ url('invoice_detail', $invoice->id) }}">{{ $invoice->invoice_no }}</a>
                                                                </td>
                                                                {{-- <td>{{ $invoice->invoice_no }}</td> --}}

                                                                <td> IN</td>

                                                                <td>{{ number_format($invoice->total) }}</td>

                                                                <td>{{ $invocie->remark ?? 'N/A' }}</td>

                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $invoice->total;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($deposit_invoices as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $invoice->transaction->transaction_name }}</td>
                                                                {{-- <td>{{ $invoice->invoice_no }}</td> --}}
                                                                <td><a
                                                                        href="{{ url('invoice_detail', $invoice->id) }}">{{ $invoice->invoice_no }}</a>
                                                                </td>
                                                                <td> IN</td>

                                                                <td>{{ number_format($invoice->deposit) }}</td>


                                                                <td>{{ $invocie->remark ?? 'N/A' }}</td>

                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $invoice->deposit;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($point_of_sales as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $invoice->transaction->transaction_name }}</td>
                                                                {{-- <td>{{ $invoice->invoice_no }}</td> --}}
                                                                <td><a
                                                                        href="{{ url('invoice_detail', $invoice->id) }}">{{ $invoice->invoice_no }}</a>
                                                                </td>
                                                                <td> IN</td>

                                                                <td>{{ number_format($invoice->deposit) }}</td>


                                                                <td>{{ $invocie->remark ?? 'N/A' }}</td>

                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $invoice->deposit;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($sale_return_pos as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $invoice->transaction->transaction_name }}</td>
                                                                {{-- <td>{{ $invoice->invoice_no }}</td> --}}
                                                                <td><a
                                                                        href="{{ url('purchase_order_details', $invoice->id) }}">{{ $invoice->quote_no }}</a>
                                                                </td>

                                                                <td> OUT</td>

                                                                <td>{{ number_format($invoice->deposit) }}</td>


                                                                <td>{{ $invocie->remark ?? 'N/A' }}</td>

                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $invoice->deposit;
                                                            @endphp
                                                        @endforeach

                                                        @foreach ($invoices_remain as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $invoice->transaction->transaction_name }}</td>
                                                                {{-- <td>{{ $invoice->invoice_no }}</td> --}}
                                                                <td><a
                                                                        href="{{ url('invoice_detail', $invoice->id) }}">{{ $invoice->invoice_no }}</a>
                                                                </td>
                                                                <td> IN</td>

                                                                <td>{{ number_format($invoice->remain_balance) }}</td>


                                                                <td>{{ $invocie->remark ?? 'N/A' }}</td>

                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $invoice->remain_balance;
                                                            @endphp
                                                        @endforeach

                                                        @foreach ($po_returns as $po_return)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $po_return->transaction->transaction_name }}
                                                                </td>
                                                                <td><a
                                                                        href="{{ url('invoice_detail', $po_return->id) }}">{{ $po_return->invoice_no }}</a>
                                                                </td>
                                                                <td>OUT</td>

                                                                <td>{{ number_format($po_return->total) }}</td>

                                                                <td>{{ $po_return->remark ?? 'N/A' }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $po_return->total;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($purchase_orders as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $po->transaction->transaction_name }}</td>
                                                                <td><a
                                                                        href="{{ url('purchase_order_details', $po->id) }}">{{ $po->quote_no }}</a>
                                                                </td>
                                                                <td>OUT</td>

                                                                <td>{{ number_format($po->total) }}</td>

                                                                <td>{{ $po->remark ?? 'N/A' }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $po->total;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($purchase_orders_remain as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $po->payable_transaction->transaction_name }}
                                                                </td>
                                                                <td><a
                                                                        href="{{ url('purchase_order_details', $po->id) }}">{{ $po->quote_no }}</a>
                                                                </td>
                                                                <td>OUT</td>

                                                                <td>{{ number_format($po->remain_balance) }}</td>

                                                                <td>{{ $po->remark ?? 'N/A' }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $po->remain_balance;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($purchase_orders_deposit as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $po->transaction->transaction_name }}</td>
                                                                <td><a
                                                                        href="{{ url('purchase_order_details', $po->id) }}">{{ $po->quote_no }}</a>
                                                                </td>
                                                                <td>OUT</td>

                                                                <td>{{ number_format($po->deposit) }}</td>

                                                                <td>{{ $po->remark ?? 'N/A' }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $po->deposit;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($sale_return_invoices as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $po->transaction->transaction_name }}</td>
                                                                <td><a
                                                                        href="{{ url('purchase_order_details', $po->id) }}">{{ $po->quote_no }}</a>
                                                                </td>
                                                                <td>OUT</td>

                                                                <td>{{ number_format($po->deposit) }}</td>

                                                                <td>{{ $po->remark ?? 'N/A' }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $po->deposit;
                                                            @endphp
                                                        @endforeach

                                                        @foreach ($expense as $exp)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $account->account_name }}</td>
                                                                <td>{{ $exp->transaction->transaction_name }}</td>
                                                                <td>Expense </td>
                                                                <td>OUT</td>
                                                                <td>{{ number_format($exp->amount) }}</td>

                                                                <td>{{ $exp->description ?? 'N/A' }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $total += $exp->amount;
                                                            @endphp
                                                        @endforeach

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-right">Total</td>
                                                            <td>{{ number_format($total) }}</td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="invoice" role="tabpanel"
                                    aria-labelledby="invoice-tab">

                                    <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="invoiceSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-6 col-md-4 col-lg-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date" id="start_date"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-6 col-md-4 col-lg-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="end_date"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-4 col-md-4 col-lg-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="button" id="invoicesearchButton"
                                                                class="btn btn-primary form-control" value="Search"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example2" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Invoice No.</th>
                                                            {{-- <th>Location</th> --}}
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>Payment Status</th>
                                                            <th>Invoice Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($invoices as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $invoice->invoice_no }}</td>

                                                                <td>{{ number_format($invoice->deposit) }}</td>
                                                                <td>{{ number_format($invoice->remain_balance) }}</td>

                                                                <td>{{ number_format($invoice->total) }}</td>

                                                                @if ($invoice->total == $invoice->deposit)
                                                                    <td> <span class="badge badge-success">Paid</span>
                                                                    </td>
                                                                @elseif($invoice->total > $invoice->deposit && $invoice->deposit > 0)
                                                                    <td><span class="badge badge-warning">Partial
                                                                            Paid</span></td>
                                                                @else
                                                                    <td><span class="badge badge-danger">Unpaid</span>
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    {{ $invoice->invoice_date }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($invoices_remain as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $invoice->invoice_no }}</td>

                                                                <td>{{ number_format($invoice->deposit) }}</td>
                                                                <td>{{ number_format($invoice->remain_balance) }}</td>

                                                                <td>{{ number_format($invoice->total) }}</td>

                                                                @if ($invoice->total == $invoice->deposit)
                                                                    <td> <span class="badge badge-success">Paid</span>
                                                                    </td>
                                                                @elseif($invoice->total > $invoice->deposit && $invoice->deposit > 0)
                                                                    <td><span class="badge badge-warning">Partial
                                                                            Paid</span></td>
                                                                @else
                                                                    <td><span class="badge badge-danger">Unpaid</span>
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    {{ $invoice->invoice_date }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($deposit_invoices as $invoice)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $invoice->invoice_no }}</td>

                                                                <td>{{ number_format($invoice->deposit) }}</td>
                                                                <td>{{ number_format($invoice->remain_balance) }}</td>

                                                                <td>{{ number_format($invoice->total) }}</td>

                                                                @if ($invoice->total == $invoice->deposit)
                                                                    <td> <span class="badge badge-success">Paid</span>
                                                                    </td>
                                                                @elseif($invoice->total > $invoice->deposit && $invoice->deposit > 0)
                                                                    <td><span class="badge badge-warning">Partial
                                                                            Paid</span></td>
                                                                @else
                                                                    <td><span class="badge badge-danger">Unpaid</span>
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    {{ $invoice->invoice_date }}
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
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pos" role="tabpanel" aria-labelledby="pos-tab">

                                    <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="posSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                id="pos_start_date" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="pos_end_date"
                                                                class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="posSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">

                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example3" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>POS No.</th>
                                                            <th>Location</th>
                                                            <th>Date</th>
                                                            <th>Cash</th>
                                                            <th>Change Due</th>
                                                            <th>Total Amount</th>
                                                            <th>Sale By</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($point_of_sales as $pos)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $pos->invoice_no }}</td>
                                                                <td>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if ($warehouse->id == $pos->branch)
                                                                            {{ $warehouse->name }}
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td>{{ $pos->invoice_date }}</td>
                                                                <td>{{ number_format($pos->deposit) }}
                                                                </td>
                                                                <td>{{ number_format($pos->remain_balance) }}
                                                                </td>
                                                                <td>{{ number_format($pos->total) }}</td>
                                                                <td>{{ $pos->sale_by }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="po" role="tabpanel" aria-labelledby="po-tab">

                                    <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="poSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                class="form-control" id="po_start_date" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="po_end_date"
                                                                class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="poSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example4" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>PO No.</th>
                                                            {{-- <th>Location</th> --}}
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>PO Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($purchase_orders as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $po->quote_no }}</td>
                                                                {{-- <td>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if ($warehouse->id == $po->branch)
                                                                            {{ $warehouse->name }}
                                                                        @endif
                                                                    @endforeach
                                                                </td> --}}
                                                                <td>{{ number_format($po->remain_balance) }}</td>
                                                                <td>{{ number_format($po->deposit) }}</td>
                                                                <td>{{ number_format($po->total) }}</td>

                                                                <td>
                                                                    {{ $po->po_date }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($purchase_orders_deposit as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $po->quote_no }}</td>
                                                                {{-- <td>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if ($warehouse->id == $po->branch)
                                                                            {{ $warehouse->name }}
                                                                        @endif
                                                                    @endforeach
                                                                </td> --}}
                                                                <td>{{ number_format($po->remain_balance) }}</td>
                                                                <td>{{ number_format($po->deposit) }}</td>
                                                                <td>{{ number_format($po->total) }}</td>

                                                                <td>
                                                                    {{ $po->po_date }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($purchase_orders_remain as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $po->quote_no }}</td>
                                                                {{-- <td>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if ($warehouse->id == $po->branch)
                                                                            {{ $warehouse->name }}
                                                                        @endif
                                                                    @endforeach
                                                                </td> --}}
                                                                <td>{{ number_format($po->remain_balance) }}</td>
                                                                <td>{{ number_format($po->deposit) }}</td>
                                                                <td>{{ number_format($po->total) }}</td>

                                                                <td>
                                                                    {{ $po->po_date }}
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
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pr" role="tabpanel" aria-labelledby="pr-tab">

                                    <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="prSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                class="form-control" id="pr_start_date" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date" id="pr_end_date"
                                                                class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="prSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example5" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>PO Return No.</th>
                                                            <th>Location</th>
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>Invoice Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($po_returns as $po)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $po->invoice_no }}</td>
                                                                <td>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if ($warehouse->id == $po->branch)
                                                                            {{ $warehouse->name }}
                                                                        @endif
                                                                    @endforeach
                                                                </td>

                                                                <td>{{ number_format($po->deposit) }}</td>
                                                                <td>{{ number_format($po->remain_balance) }}</td>
                                                                <td>{{ number_format($po->total) }}</td>

                                                                <td>
                                                                    {{ $po->invoice_date }}
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
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="sr-inv" role="tabpanel"
                                    aria-labelledby="sr-inv-tab">

                                    <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="saleReturnInvoiceForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                id="sr_inv_start_date" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date"
                                                                id="sr_inv_end_date" class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="saleReturnInvoiceSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example6" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Sale Return No.</th>
                                                            <th>Location</th>
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($sale_return_invoices as $sr_inv)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $sr_inv->quote_no }}</td>
                                                                <td>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if ($warehouse->id == $sr_inv->branch)
                                                                            {{ $warehouse->name }}
                                                                        @endif
                                                                    @endforeach
                                                                </td>

                                                                <td>{{ number_format($sr_inv->deposit) }}</td>
                                                                <td>{{ number_format($sr_inv->remain_balance) }}</td>
                                                                <td>{{ number_format($sr_inv->total) }}</td>

                                                                <td>
                                                                    {{ $sr_inv->po_date }}
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
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="sr-pos" role="tabpanel"
                                    aria-labelledby="sr-pos-tab">

                                    <div class="my-5 container-fluid">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <form id="saleReturnPosSearchForm" method="get">
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="start_date">Date From:</label>
                                                            <input type="date" name="start_date"
                                                                id="sr_pos_start_date" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="end_date">Date To:</label>
                                                            <input type="date" name="end_date"
                                                                id="sr_pos_end_date" class="form-control" required>
                                                        </div>
                                                        <!-- Add your existing branch selection code here -->

                                                        <div class="col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="submit" class="btn btn-primary form-control"
                                                                value="Search" id="saleReturnPosSearchButton"
                                                                style="background-color: #218838">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example7" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Sale Return No.</th>
                                                            <th>Location</th>
                                                            <th>Deposit</th>
                                                            <th>Balance</th>
                                                            <th>Total</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = '1';
                                                        @endphp
                                                        @foreach ($sale_return_pos as $sr_pos)
                                                            <tr>
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $sr_pos->quote_no }}</td>
                                                                <td>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        @if ($warehouse->id == $sr_pos->branch)
                                                                            {{ $warehouse->name }}
                                                                        @endif
                                                                    @endforeach
                                                                </td>

                                                                <td>{{ number_format($sr_pos->deposit) }}</td>
                                                                <td>{{ number_format($sr_pos->remain_balance) }}</td>
                                                                <td>{{ number_format($sr_pos->total) }}</td>

                                                                <td>
                                                                    {{ $sr_pos->po_date }}
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
                                        <!-- /.card-body -->
                                    </div>
                                </div>
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
            $('#invoicesearchButton').on('click', function() {
                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();
                let transactionId = {{ $transaction->id }};
                let accountId = {{ $account->id }};
                $.ajax({
                    url: "{{ route('account_invoice_search', ['id' => $transaction->id, 'account_id' => $account->id]) }}",
                    type: 'GET',
                    data: {
                        start_date: $('input[name="start_date"]').val(),
                        end_date: $('input[name="end_date"]').val(),
                    },
                    success: function(response) {
                        let tbody = '';
                        let no = 1;
                        response.invoices.forEach(function(invoice) {
                            let paymentStatus = (invoice.total == invoice.deposit) ?
                                'Paid' :
                                (invoice.total > invoice.deposit && invoice.deposit >
                                    0) ? 'Partial Paid' :
                                'Unpaid';

                            let warehouseName = '';
                            response.warehouses.forEach(function(warehouse) {
                                if (warehouse.id == invoice.branch) {
                                    warehouseName = warehouse.name;
                                }
                            });

                            tbody += `<tr>
                                        <td>${no}</td>
                                        <td>${invoice.invoice_no}</td>
                                        <td>${warehouseName}</td>
                                        <td>${Number(invoice.remain_balance).toLocaleString()}</td>
                                        <td>${Number(invoice.deposit).toLocaleString()}</td>
                                        <td>${Number(invoice.total).toLocaleString()}</td>
                                        <td>${paymentStatus}</td>
                                        <td>${invoice.invoice_date}</td>
                                    </tr>`;
                            no++;
                        });
                        $('#example2 tbody').html(tbody);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#posSearchButton').on('click', function(e) {
                e.preventDefault();
                posformSearch();
            });

            function posformSearch() {
                let startDate = $('#pos_start_date').val();
                let endDate = $('#pos_end_date').val();
                let transactionId = {{ $transaction->id }};

                $.ajax({
                    url: "{{ url('account_pos_search', $transaction->id) }}",
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        let tbody = '';
                        let no = 1;

                        response.point_of_sales.forEach(function(pos) {
                            let warehouseName = '';
                            response.warehouses.forEach(function(warehouse) {
                                if (warehouse.id == pos.branch) {
                                    warehouseName = warehouse.name;
                                }
                            });

                            tbody += `<tr>
                                <td>${no}</td>
                                <td>${pos.invoice_no}</td>
                                <td>${warehouseName}</td>
                                <td>${pos.invoice_date}</td>
                                <td>${Number(pos.deposit).toLocaleString()}</td>
                                <td>${Number(pos.remain_balance).toLocaleString()}</td>
                                <td>${Number(pos.total).toLocaleString()}</td>
                                <td>${pos.sale_by}</td>
                            </tr>`;
                            no++;
                        });

                        $('#example3 tbody').html(tbody);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });


        $(document).ready(function() {
            $('#poSearchButton').on('click', function(e) {
                e.preventDefault();

                let startDate = $('#po_start_date').val();
                let endDate = $('#po_end_date').val();
                let transactionId = {{ $transaction->id }};

                $.ajax({
                    url: "{{ url('account_po_search', $transaction->id) }}",
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        let tbody = '';
                        let no = 1;

                        response.purchase_orders.forEach(function(po) {
                            let warehouseName = '';
                            response.warehouses.forEach(function(warehouse) {
                                if (warehouse.id == po.branch) {
                                    warehouseName = warehouse.name;
                                }
                            });

                            tbody += `<tr>
                                <td>${no}</td>
                                <td>${po.quote_no}</td>
                                <td>${warehouseName}</td>
                                <td>${Number(po.remain_balance).toLocaleString()}</td>
                                <td>${Number(po.deposit).toLocaleString()}</td>
                                <td>${Number(po.total).toLocaleString()}</td>
                                <td>${po.po_date}</td>
                              </tr>`;
                            no++;
                        });

                        $('#example4 tbody').html(tbody);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });


        $(document).ready(function() {
            $('#prSearchButton').on('click', function(e) {
                e.preventDefault();

                let startDate = $('#pr_start_date').val();
                let endDate = $('#pr_end_date').val();
                let transactionId = {{ $transaction->id }};

                $.ajax({
                    url: "{{ url('purchase_return_invoice_search', $transaction->id) }}",
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        let tbody = '';
                        let no = 1;

                        response.po_returns.forEach(function(po) {
                            let warehouseName = '';
                            response.warehouses.forEach(function(warehouse) {
                                if (warehouse.id == po.branch) {
                                    warehouseName = warehouse.name;
                                }
                            });



                            tbody += `<tr>
                        <td>${no}</td>
                        <td>${po.invoice_no}</td>
                        <td>${warehouseName}</td>
                        <td>${Number(po.deposit).toLocaleString()}</td>
                        <td>${Number(po.remain_balance).toLocaleString()}</td>
                        <td>${Number(po.total).toLocaleString()}</td>
                        <td>${po.invoice_date}</td>
                      </tr>`;
                            no++;
                        });

                        $('#example5 tbody').html(tbody);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });


        $(document).ready(function() {
            $('#saleReturnInvoiceSearchButton').on('click', function(e) {
                e.preventDefault();

                let startDate = $('#sr_inv_start_date').val();
                let endDate = $('#sr_inv_end_date').val();
                let transactionId = {{ $transaction->id }};

                $.ajax({
                    url: "{{ url('sale_return_invoice_search', $transaction->id) }}",
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        let tbody = '';
                        let no = 1;

                        response.sale_return_invoices.forEach(function(sr_inv) {
                            let warehouseName = '';
                            response.warehouses.forEach(function(warehouse) {
                                if (warehouse.id == sr_inv.branch) {
                                    warehouseName = warehouse.name;
                                }
                            });

                            tbody += `<tr>
                        <td>${no}</td>
                        <td>${sr_inv.quote_no}</td>
                        <td>${warehouseName}</td>
                        <td>${Number(sr_inv.deposit).toLocaleString()}</td>
                        <td>${Number(sr_inv.remain_balance).toLocaleString()}</td>
                        <td>${Number(sr_inv.total).toLocaleString()}</td>
                        <td>${sr_inv.po_date}</td>
                      </tr>`;
                            no++;
                        });

                        $('#example6 tbody').html(tbody);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });


        $(document).ready(function() {
            $('#saleReturnPosSearchButton').on('click', function(e) {
                e.preventDefault();

                let startDate = $('#sr_pos_start_date').val();
                let endDate = $('#sr_pos_end_date').val();
                let transactionId = {{ $transaction->id }};

                $.ajax({
                    url: "{{ url('sale_return_pos_search', $transaction->id) }}",
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        let tbody = '';
                        let no = 1;

                        response.sale_return_pos.forEach(function(sr_pos) {
                            let warehouseName = '';
                            response.warehouses.forEach(function(warehouse) {
                                if (warehouse.id == sr_pos.branch) {
                                    warehouseName = warehouse.name;
                                }
                            });

                            tbody += `<tr>
                        <td>${no}</td>
                        <td>${sr_pos.quote_no}</td>
                        <td>${warehouseName}</td>
                        <td>${Number(sr_pos.deposit).toLocaleString()}</td>
                        <td>${Number(sr_pos.remain_balance).toLocaleString()}</td>
                        <td>${Number(sr_pos.total).toLocaleString()}</td>
                        <td>${sr_pos.po_date}</td>
                    </tr>`;
                            no++;
                        });

                        $('#example7 tbody').html(tbody);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example2").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example3").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example4").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example5").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example6").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });

        $(function() {
            $("#example7").DataTable({
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            });
        });
    </script>


    <script>
        // JavaScript
        function updateModels() {
            var selectedAccountId = document.getElementById('account_id').value;
            var transactionDropdown = document.getElementById('transaction_id');
            var transactionOptions = transactionDropdown.getElementsByTagName('option');

            // Reset the dropdown
            transactionDropdown.selectedIndex = 0;

            // Hide all options initially
            for (var i = 0; i < transactionOptions.length; i++) {
                transactionOptions[i].hidden = true;
            }

            // Show options that belong to the selected account
            for (var i = 0; i < transactionOptions.length; i++) {
                if (selectedAccountId === "" || transactionOptions[i].getAttribute('data-account') === selectedAccountId) {
                    transactionOptions[i].hidden = false;
                }
            }
        }

        // Call updateModels() when the page is loaded
        window.addEventListener('load', updateModels);
    </script>

    <script>
        $(document).ready(function() {
            $('#tableSelect').on('change', function() {
                const selectedValue = $(this).val();

                $('.tab-pane').removeClass('show active');

                $('#' + selectedValue).addClass('show active');
            });

            $('#tableSelect').trigger('change');
        });
    </script>
</body>

</html>
