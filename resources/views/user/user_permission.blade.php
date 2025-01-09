@include('layouts.header')
<style>
    .custom-control-input:checked~.custom-control-label::before {
        background-color: #007bff;
        border-color: #007bff;
    }

    .custom-control-input:checked~.custom-control-label::after {
        color: #fff;
    }

    .custom-control-label::before {
        border-radius: 0.25rem;
    }

    /* Custom checkbox styles */
    .form-check-input {
        /* Custom styling */
        /* Example: Increase size */
        width: 1.25rem;
        height: 1.25rem;
    }

    .form-check-label {
        /* Custom label styling */
        /* Example: Add margin or padding */
        margin-left: 0.5rem;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars text-white"></i></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">


                <li class="nav-item">



                <li>

                    <div class="btn-group ">
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
                </li>
                </li>
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
                                <h1>User Permission</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">User Permission
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <div class="container-fluid">
                    <div class="row  justify-content-center d-flex">
                        <div class="col-md-12 mt-5">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>{{ session('success') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if (session('delete'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>{{ session('delete') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="card ">
                                <div class="card-header">
                                    <h3 class="card-title">User Permission Form</h3>
                                </div>

                                <div class="card-body">
                                    <form action="{{ url('user_permission_store', $userShow->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="card-body">
                                                <div class="row">



                                                    <div id="formContainer" class="col-md-6">
                                                        <div class="form-group">

                                                            @if (auth()->user()->is_admin == '1')

                                                                <label for="warehouse_id">Location<span
                                                                        class="text-danger">*</span></label>
                                                                @php
                                                                    $levelIds = json_decode($userShow->level, true);
                                                                @endphp
                                                                @if (is_array($levelIds) && count($levelIds) > 0)
                                                                    @foreach ($levelIds as $key => $levelId)
                                                                        <div class="form-group">
                                                                            <label for="warehouse_id"
                                                                                style="display: none;">Location<span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="level[]" class="form-control"
                                                                                required>
                                                                                <option value="" selected>Select
                                                                                    Location</option>
                                                                                <option value="Default">Default</option>
                                                                                @foreach ($branchs as $branch)
                                                                                    <option value="{{ $branch->id }}"
                                                                                        @if ($levelId == $branch->id) selected @endif>
                                                                                        {{ $branch->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <label for="warehouse_id"
                                                                        style="display: none;">Location<span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="level[]" class="form-control"
                                                                        required>
                                                                        <option value="" selected>Select Location
                                                                        </option>
                                                                        <option value="Default">Default</option>
                                                                        @foreach ($branchs as $branch)
                                                                            <option value="{{ $branch->id }}">
                                                                                {{ $branch->name }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                @endif
                                                            @else
                                                                <label for="warehouse_id">Location<span
                                                                        class="text-danger">*</span></label>
                                                                @php
                                                                    $levelIds = json_decode($userShow->level, true);
                                                                    $userPermissions = auth()->user()->level
                                                                        ? json_decode(auth()->user()->level)
                                                                        : [];
                                                                @endphp
                                                                @if (is_array($levelIds) && count($levelIds) > 0)
                                                                    @foreach ($levelIds as $key => $levelId)
                                                                        <div class="form-group">
                                                                            <label for="warehouse_id"
                                                                                style="display: none;">Location<span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="level[]" class="form-control"
                                                                                required>
                                                                                <option value="" selected>Select
                                                                                    Location</option>
                                                                                <option value="Default">Default</option>
                                                                                @foreach ($branchs as $branch)
                                                                                    @if (in_array($branch->id, $userPermissions))
                                                                                        <option
                                                                                            value="{{ $branch->id }}"
                                                                                            @if ($levelId == $branch->id) selected @endif>
                                                                                            {{ $branch->name }}
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach

                                                                            </select>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    {{ $userShow->level }}
                                                                @endif
                                                            @endif

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-sm-6" style="margin-top: 30px;">
                                                        <button id="addRowBtn" type="button" class="btn btn-primary "
                                                            title="Add Location Row"><i
                                                                class="fa-solid fa-plus"></i></button>

                                                        <button id="removeRowBtn" type="button"
                                                            title="Remove Location Row" class="btn btn-danger "><i
                                                                class="fa-solid fa-xmark"></i></button>
                                                    </div>




                                                    <div class="form-group col-md-6 mt-3">
                                                        <label for="user_type_id">Type</label>
                                                        <select class="form-control" name="user_type_id"
                                                            id="user_type_id" required>
                                                            <option value="">Select user Type</option>
                                                            @foreach ($userTypes as $usertype)
                                                                <option value="{{ $usertype->id }}"
                                                                    {{ $userShow->user_type_id == $usertype->id ? 'selected' : '' }}>
                                                                    {{ $usertype->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-6">

                                                    </div>



                                                    <div class="form-group col-md-12">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="is_admin" name="is_admin" value="1"
                                                                @if ($userShow->is_admin) checked @endif>
                                                            <label class="custom-control-label" for="is_admin">Is
                                                                Admin</label>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $permissions = $userShow->permission
                                                            ? json_decode($userShow->permission)
                                                            : [];
                                                    @endphp


                                                </div>

                                                <div class="table-responsive">
                                                    <table id=""
                                                        class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Permission</th>
                                                                <th>Register</th>
                                                                <th>Edit</th>
                                                                <th>Delete</th>
                                                                <th>Others</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="permission-item"
                                                                                name="permission[]" value="Item"
                                                                                @if (in_array('Item', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission-item">
                                                                                Item
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-item-other-1"
                                                                                name="permission[]"
                                                                                value="Item Register"
                                                                                @if (in_array('Item Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission-item">
                                                                                Item Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-item-other-2"
                                                                                name="permission[]" value="Item Edit"
                                                                                @if (in_array('Item Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission-item">
                                                                                Item Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-item-other-3"
                                                                                name="permission[]"
                                                                                value="Item Delete"
                                                                                @if (in_array('Item Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission-item">
                                                                                Item Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>


                                                                    <div class="form-group">
                                                                        <div
                                                                            class="form-check form-check-inline d-flex align-items-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-item-other-4"
                                                                                name="permission[]"
                                                                                value="Item Details"
                                                                                @if (in_array('Item Details', $permissions)) checked @endif>
                                                                            <label class="form-check-label ms-2"
                                                                                for="permission-item-other-4">
                                                                                Item Details
                                                                            </label>
                                                                        </div>
                                                                        <div
                                                                            class="form-check form-check-inline d-flex align-items-center ms-4 mt-3">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-item-other-5"
                                                                                name="permission[]"
                                                                                value="Item In/Out"
                                                                                @if (in_array('Item In/Out', $permissions)) checked @endif>
                                                                            <label class="form-check-label ms-2"
                                                                                for="permission-item-other-5">
                                                                                Item In/Out
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-customer"
                                                                                name="permission[]" value="Customer"
                                                                                @if (in_array('Customer', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Customer
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-customer-other-1"
                                                                                name="permission[]"
                                                                                value="Customer Register"
                                                                                @if (in_array('Customer Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Customer Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-customer-other-2"
                                                                                name="permission[]"
                                                                                value="Customer Edit"
                                                                                @if (in_array('Customer Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Customer Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-customer-other-3"
                                                                                name="permission[]"
                                                                                value="Customer Delete"
                                                                                @if (in_array('Customer Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Customer Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{-- <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-customer-other-4"
                                                                                name="permission[]"
                                                                                value="Customer Credit"
                                                                                @if (in_array('Customer Credit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Customer Credit
                                                                            </label>
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="form-check form-check-inline d-flex align-items-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-customer-other-4"
                                                                                name="permission[]"
                                                                                value="Customer Credit"
                                                                                @if (in_array('Customer Credit', $permissions)) checked @endif>
                                                                            <label class="form-check-label ms-2"
                                                                                for="permission-quotation-other-4">
                                                                                Customer Credit
                                                                            </label>
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-customer-due"
                                                                                name="permission[]"
                                                                                value="Customer Due"
                                                                                @if (in_array('Customer Due', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Customer Due
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="permission-pos"
                                                                                name="permission[]" value="POS"
                                                                                @if (in_array('POS', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                POS
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-pos-other-1"
                                                                                name="permission[]"
                                                                                value="POS Register"
                                                                                @if (in_array('POS Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Issue POS
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-pos-other-2"
                                                                                name="permission[]" value="Suspend"
                                                                                @if (in_array('Suspend', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Suspend
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-pos-other-3"
                                                                                name="permission[]" value="POS Delete"
                                                                                @if (in_array('POS Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                POS Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-pos-other-4"
                                                                                name="permission[]"
                                                                                value="POS Details"
                                                                                @if (in_array('POS Details', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                POS Details
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-invoice"
                                                                                name="permission[]" value="Invoice"
                                                                                @if (in_array('Invoice', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Invoice
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-invoice-other-1"
                                                                                name="permission[]"
                                                                                value="Invoice Register"
                                                                                @if (in_array('Invoice Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Issue Invoice
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-invoice-other-2"
                                                                                name="permission[]"
                                                                                value="Invoice Edit"
                                                                                @if (in_array('Invoice Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Invoice Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-invoice-other-3"
                                                                                name="permission[]"
                                                                                value="Invoice Delete"
                                                                                @if (in_array('Invoice Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Invoice Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-invoice-other-4"
                                                                                name="permission[]"
                                                                                value="Invoice Details"
                                                                                @if (in_array('Invoice Details', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Invoice Details
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-invoice-other-5"
                                                                                name="permission[]"
                                                                                value="Invoice Payment"
                                                                                @if (in_array('Invoice Payment', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Invoice Payment
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-cinvoice"
                                                                                name="permission[]"
                                                                                value="Custom Invoice"
                                                                                @if (in_array('Custom Invoice', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Custom Invoice
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-cinvoice-other-1"
                                                                                name="permission[]"
                                                                                value="Custom Invoice Register"
                                                                                @if (in_array('Custom Invoice Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Issue Customer Invoice
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-cinvoice-other-2"
                                                                                name="permission[]"
                                                                                value="Custom Invoice Edit"
                                                                                @if (in_array('Custom Invoice Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Custom Invoice Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-cinvoice-other-3"
                                                                                name="permission[]"
                                                                                value="Custom Invoice Delete"
                                                                                @if (in_array('Custom Invoice Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Custom Invoice Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-cinvoice-other-4"
                                                                                name="permission[]"
                                                                                value="Custom Invoice Details"
                                                                                @if (in_array('Custom Invoice Details', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Custom Invoice Details
                                                                            </label>
                                                                        </div>
                                                                    </div>


                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-quotation"
                                                                                name="permission[]" value="Quotation"
                                                                                @if (in_array('Quotation', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Quotation
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-quotation-other-1"
                                                                                name="permission[]"
                                                                                value="Quotation Register"
                                                                                @if (in_array('Quotation Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Issue Quotation
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-quotation-other-2"
                                                                                name="permission[]"
                                                                                value="Quotation Edit"
                                                                                @if (in_array('Quotation Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Quotation Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-quotation-other-3"
                                                                                name="permission[]"
                                                                                value="Quotation Delete"
                                                                                @if (in_array('Quotation Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Quotation Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="form-check form-check-inline d-flex align-items-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-quotation-other-4"
                                                                                name="permission[]"
                                                                                value="Change Invoice"
                                                                                @if (in_array('Change Invoice', $permissions)) checked @endif>
                                                                            <label class="form-check-label ms-2"
                                                                                for="permission-quotation-other-4">
                                                                                Change Invoice
                                                                            </label>
                                                                        </div>
                                                                        <div
                                                                            class="form-check form-check-inline d-flex align-items-center ms-4 mt-3">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-quotation-other-5"
                                                                                name="permission[]"
                                                                                value="Quotation Details"
                                                                                @if (in_array('Quotation Details', $permissions)) checked @endif>
                                                                            <label class="form-check-label ms-2"
                                                                                for="permission-quotation-other-5">
                                                                                Quotation Details
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>



                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-purchaseOrder"
                                                                                name="permission[]"
                                                                                value="Purchase Order"
                                                                                @if (in_array('Purchase Order', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Purchase Order
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-purchaseOrder-other-1"
                                                                                name="permission[]"
                                                                                value="Purchase Order Register"
                                                                                @if (in_array('Purchase Order Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Issue Purchase Order
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-purchaseOrder-other-2"
                                                                                name="permission[]"
                                                                                value="Purchase Order Edit"
                                                                                @if (in_array('Purchase Order Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Purchase Order Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-purchaseOrder-other-3"
                                                                                name="permission[]"
                                                                                value="Purchase Order Delete"
                                                                                @if (in_array('Purchase Order Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Purchase Order Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-purchaseOrder-other-4"
                                                                                name="permission[]"
                                                                                value="Purchase Order Details"
                                                                                @if (in_array('Purchase Order Details', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Purchase Order Details
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-location"
                                                                                name="permission[]" value="Location"
                                                                                @if (in_array('Location', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Location
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-location-other-1"
                                                                                name="permission[]"
                                                                                value="Location Register"
                                                                                @if (in_array('Location Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Location Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-location-other-2"
                                                                                name="permission[]"
                                                                                value="Location Edit"
                                                                                @if (in_array('Location Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Location Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-location-other-3"
                                                                                name="permission[]"
                                                                                value="Location Delete"
                                                                                @if (in_array('Location Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Location Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-transfer"
                                                                                name="permission[]" value="Transfer"
                                                                                @if (in_array('Transfer', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Transfer
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-transfer-other-1"
                                                                                name="permission[]"
                                                                                value="Transfer Item"
                                                                                @if (in_array('Transfer Item', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Transfer Item
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expense"
                                                                                name="permission[]" value="Expenses"
                                                                                @if (in_array('Expenses', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Expenses
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expense-other-1"
                                                                                name="permission[]"
                                                                                value="Expenses Register"
                                                                                @if (in_array('Expenses Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Expenses Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expense-other-2"
                                                                                name="permission[]"
                                                                                value="Expenses Edit"
                                                                                @if (in_array('Expenses Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Expenses Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expense-other-3"
                                                                                name="permission[]"
                                                                                value="Expenses Delete"
                                                                                @if (in_array('Expenses Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Expenses Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expneseCategory"
                                                                                name="permission[]"
                                                                                value="Expense Category"
                                                                                @if (in_array('Expense Category', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Expense Category
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expneseCategory-other-1"
                                                                                name="permission[]"
                                                                                value="Expense Category Register"
                                                                                @if (in_array('Expense Category Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Expense Category Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expneseCategory-other-2"
                                                                                name="permission[]"
                                                                                value="Expense Category Edit"
                                                                                @if (in_array('Expense Category Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Expense Category Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-expneseCategory-other-3"
                                                                                name="permission[]"
                                                                                value="Expense Category Delete"
                                                                                @if (in_array('Expense Category Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Exnpense Category Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="permission-unit"
                                                                                name="permission[]" value="Unit"
                                                                                @if (in_array('Unit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Unit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-unit-other-1"
                                                                                name="permission[]"
                                                                                value="Unit Register"
                                                                                @if (in_array('Unit Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Unit Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-unit-other-2"
                                                                                name="permission[]" value="Unit Edit"
                                                                                @if (in_array('Unit Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Unit Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-unit-other-3"
                                                                                name="permission[]"
                                                                                value="Unit Delete"
                                                                                @if (in_array('Unit Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Unit Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-supplier"
                                                                                name="permission[]" value="Supplier"
                                                                                @if (in_array('Supplier', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Supplier
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-supplier-other-1"
                                                                                name="permission[]"
                                                                                value="Supplier Register"
                                                                                @if (in_array('Supplier Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Supplier Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-supplier-other-2"
                                                                                name="permission[]"
                                                                                value="Supplier Edit"
                                                                                @if (in_array('Supplier Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Supplier Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-supplier-other-3"
                                                                                name="permission[]"
                                                                                value="Supplier Delete"
                                                                                @if (in_array('Supplier Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Supplier Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td></td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="permission-profit"
                                                                                name="permission[]" value="Profit"
                                                                                @if (in_array('Profit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Profit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="accounq"
                                                                                name="permission[]" value="Accounting"
                                                                                @if (in_array('Accounting', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Accounting
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr> --}}
                                                            {{-- <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="accounq"
                                                                                name="permission[]"
                                                                                value="Accounting Report"
                                                                                @if (in_array('Accounting Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Accounting Report
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="permission-user"
                                                                                name="permission[]" value="User"
                                                                                @if (in_array('User', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-other-1"
                                                                                name="permission[]"
                                                                                value="User Register"
                                                                                @if (in_array('User Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-other-2"
                                                                                name="permission[]" value="User Edit"
                                                                                @if (in_array('User Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-other-3"
                                                                                name="permission[]"
                                                                                value="User Delete"
                                                                                @if (in_array('User Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-other-4"
                                                                                name="permission[]"
                                                                                value="User Permission"
                                                                                @if (in_array('User Permission', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Permission
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-type"
                                                                                name="permission[]" value="User Type"
                                                                                @if (in_array('User Type', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Type
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-type-other-1"
                                                                                name="permission[]"
                                                                                value="User Type Register"
                                                                                @if (in_array('User Type Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Type Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-type-other-2"
                                                                                name="permission[]"
                                                                                value="User Type Edit"
                                                                                @if (in_array('User Type Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Type Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-user-type-other-3"
                                                                                name="permission[]"
                                                                                value="User Type Delete"
                                                                                @if (in_array('User Type Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                User Type Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-configuration"
                                                                                name="permission[]"
                                                                                value="Configuration"
                                                                                @if (in_array('Configuration', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Configuration
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-configuration-other-1"
                                                                                name="permission[]"
                                                                                value="Configuration Register"
                                                                                @if (in_array('Configuration Register', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Configuration Register
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-configuration-other-2"
                                                                                name="permission[]"
                                                                                value="Configuration Edit"
                                                                                @if (in_array('Configuration Edit', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Configuration Edit
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-configuration-other-3"
                                                                                name="permission[]"
                                                                                value="Configuration Delete"
                                                                                @if (in_array('Configuration Delete', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Configuration Delete
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-configuration-other-4"
                                                                                name="permission[]"
                                                                                value="Configuration Details"
                                                                                @if (in_array('Configuration Details', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">
                                                                                Configuration Details
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        </tbody>

                                                    </table>
                                                </div>

                                                <div class="table-responsive mt-5">
                                                    <table id=""
                                                        class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 10%;">Report</th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                                <th style="width: 10%;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="permission-report"
                                                                                name="permission[]" value="Report"
                                                                                @if (in_array('Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Report</label>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-invoice"
                                                                                name="permission[]"
                                                                                value="Invoice Report"
                                                                                @if (in_array('Invoice Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Invoice</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-quotation"
                                                                                name="permission[]"
                                                                                value="Quotation Report"
                                                                                @if (in_array('Quotation Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Quotaion</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-pos"
                                                                                name="permission[]" value="POS Report"
                                                                                @if (in_array('POS Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">POS</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-purchaseOrder"
                                                                                name="permission[]"
                                                                                value="Purchase Order Report"
                                                                                @if (in_array('Purchase Order Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Purchase Order</label>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-purchaseReturn"
                                                                                name="permission[]"
                                                                                value="Purchase Return"
                                                                                @if (in_array('Purchase Return', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Purchase
                                                                                Return</label>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-saleReturn-invoice"
                                                                                name="permission[]"
                                                                                value="Sale Return (Invoice)"
                                                                                @if (in_array('Sale Return (Invoice)', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Sale Return
                                                                                (Invoice)</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-item"
                                                                                name="permission[]"
                                                                                value="Item Report"
                                                                                @if (in_array('Item Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Item</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-saleReturn-pos"
                                                                                name="permission[]"
                                                                                value="Sale Return (POS)"
                                                                                @if (in_array('Sale Return (POS)', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Sale Return
                                                                                (POS)</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission-report-expense"
                                                                                name="permission[]"
                                                                                value="Expenses Report"
                                                                                @if (in_array('Expenses Report', $permissions)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="permission">Expenses</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <a type="button" class="btn btn-default"
                                                href="{{ url('user') }}">Back</a>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>



    </div>


    @include('layouts.footer')
    <script>
        $(document).ready(function() {
            var rowCount = 1; // Initial row count

            $('#addRowBtn').on('click', function() {
                var $lastFormGroup = $('#formContainer .form-group:last');
                var $newFormGroup = $lastFormGroup.clone();

                rowCount++;
                $newFormGroup.find('select').val('');
                $newFormGroup.find('label').hide();
                $('#formContainer').append($newFormGroup);
            });

            $('#removeRowBtn').on('click', function() {
                if ($('#formContainer .form-group').length > 1) {
                    $('#formContainer .form-group:last').remove();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isAdminCheckbox = document.getElementById('is_admin');
            const permissionCheckboxes = document.querySelectorAll(
                'input[name="permission[]"]'
            );
            isAdminCheckbox.addEventListener('change', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = isAdminCheckbox.checked;
                });
            });
            if (isAdminCheckbox.checked) {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#permission-item').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-item-other-1').prop('checked', true);
                    $('#permission-item-other-2').prop('checked', true);
                    $('#permission-item-other-3').prop('checked', true);
                    $('#permission-item-other-4').prop('checked', true);
                    $('#permission-item-other-5').prop('checked', true);
                } else {
                    $('#permission-item-other-1').prop('checked', false);
                    $('#permission-item-other-2').prop('checked', false);
                    $('#permission-item-other-3').prop('checked', false);
                    $('#permission-item-other-4').prop('checked', false);
                    $('#permission-item-other-5').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-customer').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-customer-due').prop('checked', true);
                    $('#permission-customer-other-1').prop('checked', true);
                    $('#permission-customer-other-2').prop('checked', true);
                    $('#permission-customer-other-3').prop('checked', true);
                    $('#permission-customer-other-4').prop('checked', true);
                    $('#permission-customer-other-5').prop('checked', true);
                } else {
                    $('#permission-customer-due').prop('checked', false);
                    $('#permission-customer-other-1').prop('checked', false);
                    $('#permission-customer-other-2').prop('checked', false);
                    $('#permission-customer-other-3').prop('checked', false);
                    $('#permission-customer-other-4').prop('checked', false);
                    $('#permission-customer-other-5').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-pos').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-pos-other-1').prop('checked', true);
                    $('#permission-pos-other-2').prop('checked', true);
                    $('#permission-pos-other-3').prop('checked', true);
                    $('#permission-pos-other-4').prop('checked', true);
                } else {
                    $('#permission-pos-other-1').prop('checked', false);
                    $('#permission-pos-other-2').prop('checked', false);
                    $('#permission-pos-other-3').prop('checked', false);
                    $('#permission-pos-other-4').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-invoice').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-invoice-other-1').prop('checked', true);
                    $('#permission-invoice-other-2').prop('checked', true);
                    $('#permission-invoice-other-3').prop('checked', true);
                    $('#permission-invoice-other-4').prop('checked', true);
                    $('#permission-invoice-other-5').prop('checked', true);
                } else {
                    $('#permission-invoice-other-1').prop('checked', false);
                    $('#permission-invoice-other-2').prop('checked', false);
                    $('#permission-invoice-other-3').prop('checked', false);
                    $('#permission-invoice-other-4').prop('checked', false);
                    $('#permission-invoice-other-5').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-cinvoice').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-cinvoice-other-1').prop('checked', true);
                    $('#permission-cinvoice-other-2').prop('checked', true);
                    $('#permission-cinvoice-other-3').prop('checked', true);
                    $('#permission-cinvoice-other-4').prop('checked', true);
                    $('#permission-cinvoice-other-5').prop('checked', true);
                } else {
                    $('#permission-cinvoice-other-1').prop('checked', false);
                    $('#permission-cinvoice-other-2').prop('checked', false);
                    $('#permission-cinvoice-other-3').prop('checked', false);
                    $('#permission-cinvoice-other-4').prop('checked', false);
                    $('#permission-cinvoice-other-5').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-quotation').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-quotation-other-1').prop('checked', true);
                    $('#permission-quotation-other-2').prop('checked', true);
                    $('#permission-quotation-other-3').prop('checked', true);
                    $('#permission-quotation-other-4').prop('checked', true);
                    $('#permission-quotation-other-5').prop('checked', true);
                } else {
                    $('#permission-quotation-other-1').prop('checked', false);
                    $('#permission-quotation-other-2').prop('checked', false);
                    $('#permission-quotation-other-3').prop('checked', false);
                    $('#permission-quotation-other-4').prop('checked', false);
                    $('#permission-quotation-other-5').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-purchaseOrder').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-purchaseOrder-other-1').prop('checked', true);
                    $('#permission-purchaseOrder-other-2').prop('checked', true);
                    $('#permission-purchaseOrder-other-3').prop('checked', true);
                    $('#permission-purchaseOrder-other-4').prop('checked', true);
                } else {
                    $('#permission-purchaseOrder-other-1').prop('checked', false);
                    $('#permission-purchaseOrder-other-2').prop('checked', false);
                    $('#permission-purchaseOrder-other-3').prop('checked', false);
                    $('#permission-purchaseOrder-other-4').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-location').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-location-other-1').prop('checked', true);
                    $('#permission-location-other-2').prop('checked', true);
                    $('#permission-location-other-3').prop('checked', true);
                    $('#permission-location-other-4').prop('checked', true);
                } else {
                    $('#permission-location-other-1').prop('checked', false);
                    $('#permission-location-other-2').prop('checked', false);
                    $('#permission-location-other-3').prop('checked', false);
                    $('#permission-location-other-4').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-transfer').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-transfer-other-1').prop('checked', true);
                } else {
                    $('#permission-transfer-other-1').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-expense').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-expense-other-1').prop('checked', true);
                    $('#permission-expense-other-2').prop('checked', true);
                    $('#permission-expense-other-3').prop('checked', true);
                } else {
                    $('#permission-expense-other-1').prop('checked', false);
                    $('#permission-expense-other-2').prop('checked', false);
                    $('#permission-expense-other-3').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-siteList').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-siteList-other-1').prop('checked', true);
                    $('#permission-siteList-other-2').prop('checked', true);
                    $('#permission-siteList-other-3').prop('checked', true);
                } else {
                    $('#permission-siteList-other-1').prop('checked', false);
                    $('#permission-siteList-other-2').prop('checked', false);
                    $('#permission-siteList-other-3').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-expneseCategory').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-expneseCategory-other-1').prop('checked', true);
                    $('#permission-expneseCategory-other-2').prop('checked', true);
                    $('#permission-expneseCategory-other-3').prop('checked', true);
                } else {
                    $('#permission-expneseCategory-other-1').prop('checked', false);
                    $('#permission-expneseCategory-other-2').prop('checked', false);
                    $('#permission-expneseCategory-other-3').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-payment').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-payment-other-1').prop('checked', true);
                    $('#permission-payment-other-2').prop('checked', true);
                    $('#permission-payment-other-3').prop('checked', true);
                } else {
                    $('#permission-payment-other-1').prop('checked', false);
                    $('#permission-payment-other-2').prop('checked', false);
                    $('#permission-payment-other-3').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-unit').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-unit-other-1').prop('checked', true);
                    $('#permission-unit-other-2').prop('checked', true);
                    $('#permission-unit-other-3').prop('checked', true);
                } else {
                    $('#permission-unit-other-1').prop('checked', false);
                    $('#permission-unit-other-2').prop('checked', false);
                    $('#permission-unit-other-3').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-supplier').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-supplier-other-1').prop('checked', true);
                    $('#permission-supplier-other-2').prop('checked', true);
                    $('#permission-supplier-other-3').prop('checked', true);
                } else {
                    $('#permission-supplier-other-1').prop('checked', false);
                    $('#permission-supplier-other-2').prop('checked', false);
                    $('#permission-supplier-other-3').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-user').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-user-other-1').prop('checked', true);
                    $('#permission-user-other-2').prop('checked', true);
                    $('#permission-user-other-3').prop('checked', true);
                    $('#permission-user-other-4').prop('checked', true);
                } else {
                    $('#permission-user-other-1').prop('checked', false);
                    $('#permission-user-other-2').prop('checked', false);
                    $('#permission-user-other-3').prop('checked', false);
                    $('#permission-user-other-4').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-user-type').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-user-type-other-1').prop('checked', true);
                    $('#permission-user-type-other-2').prop('checked', true);
                    $('#permission-user-type-other-3').prop('checked', true);
                } else {
                    $('#permission-user-type-other-1').prop('checked', false);
                    $('#permission-user-type-other-2').prop('checked', false);
                    $('#permission-user-type-other-3').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-configuration').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-configuration-other-1').prop('checked', true);
                    $('#permission-configuration-other-2').prop('checked', true);
                    $('#permission-configuration-other-3').prop('checked', true);
                    $('#permission-configuration-other-4').prop('checked', true);
                } else {
                    $('#permission-configuration-other-1').prop('checked', false);
                    $('#permission-configuration-other-2').prop('checked', false);
                    $('#permission-configuration-other-3').prop('checked', false);
                    $('#permission-configuration-other-4').prop('checked', false);
                }
            });
        });
        $(document).ready(function() {
            $('#permission-profit').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-profit-other-1').prop('checked', true);
                    $('#permission-profit-other-2').prop('checked', true);

                } else {
                    $('#permission-profit-other-1').prop('checked', false);
                    $('#permission-profit-other-2').prop('checked', false);
                }
            });
        });

        $(document).ready(function() {
            $('#permission-report').change(function() {
                var isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#permission-report-invoice').prop('checked', true);
                    $('#permission-report-quotation').prop('checked', true);
                    $('#permission-report-pos').prop('checked', true);
                    $('#permission-report-purchaseOrder').prop('checked', true);
                    $('#permission-report-purchaseReturn').prop('checked', true);
                    $('#permission-report-saleReturn-invoice').prop('checked', true);
                    $('#permission-report-item').prop('checked', true);
                    $('#permission-report-saleReturn-pos').prop('checked', true);
                    $('#permission-report-expense').prop('checked', true);
                } else {
                    $('#permission-report-invoice').prop('checked', false);
                    $('#permission-report-quotation').prop('checked', false);
                    $('#permission-report-pos').prop('checked', false);
                    $('#permission-report-purchaseOrder').prop('checked', false);
                    $('#permission-report-purchaseReturn').prop('checked', false);
                    $('#permission-report-saleReturn-invoice').prop('checked', false);
                    $('#permission-report-item').prop('checked', false);
                    $('#permission-report-saleReturn-pos').prop('checked', false);
                    $('#permission-report-expense').prop('checked', false);
                }
            });
        });
    </script>
