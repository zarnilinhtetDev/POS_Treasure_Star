@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.nav')
        @include('layouts.sidebar')
        <div class="content-wrapper">


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Transactions Edit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/transaction') }}">Transactions</a></li>
                    <li class="breadcrumb-item active">Transactions Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-3 my-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Transaction Update</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ url('transaction_update', $transaction->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="accounts_id">Account Name<span
                                        style="color: red;">&nbsp;*</span></label>
                                <select name="account_id" class="form-control" id="account_id">
                                    <option value="">Select Accounts</option>

                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" selected>
                                            {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                           

                            <div class="form-group" style="display:none">
                                <label for="transaction_code">Amount<span
                                        style="color: red;">&nbsp;*</span></label>
                                <input type="text" class="form-control" id="transaction_code"
                                    name="transaction_code" value="{{ $transaction->transaction_code }}">
                            </div>
                            <div class="form-group">
                                <label for="transaction_name">Name<span
                                        style="color: red;">&nbsp;*</span></label>
                                <input type="text" class="form-control" id="transaction_name"
                                    name="transaction_name" value="{{ $transaction->transaction_name }}">
                            </div>
                            <div class="form-group"  style="display:none">
                                <label>Descriptions</label>
                                <textarea class="form-control" rows="3" style="border-color:#6B7280" name="description">{{ $transaction->description }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary"
                                style="background-color: #007BFF">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    </div>




   
    @include('layouts.footer')
