@include('layouts.header')

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
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form action="{{ url('user_permission_store', $userShow->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="user_type_id">Type</label>
                                                        <select class="form-control" name="user_type_id"
                                                            id="user_type_id">
                                                            <option>Select user Type</option>
                                                            @foreach ($userTypes as $usertype)
                                                                <option value="{{ $usertype->id }}"
                                                                    {{ $userShow->user_type_id == $usertype->id ? 'selected' : '' }}>
                                                                    {{ $usertype->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="warehouse_id">Location<span
                                                                class="text-danger">*</span></label>
                                                        <input type="hidden" id="level" name="level">
                                                        <select name="level" id="level" class="form-control"
                                                            required>
                                                            <option value="" selected>Select Location</option>
                                                            <option value="Default"
                                                                {{ $userShow->level == 'Default' ? 'selected' : '' }}>
                                                                Default</option>
                                                            @foreach ($branchs as $branch)
                                                                <option value="{{ $branch->id }}"
                                                                    {{ $userShow->level == $branch->id ? 'selected' : '' }}>
                                                                    {{ $branch->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Item"
                                                                @if (in_array('Item', json_decode($userShow->permission))) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Item
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Customer"
                                                                @if (in_array('Customer', json_decode($userShow->permission))) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Customer
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="POS"
                                                                @if (in_array('POS', json_decode($userShow->permission))) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                POS
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Invoice">
                                                            <label class="form-check-label" for="permission">
                                                                Invoice
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Quotation">
                                                            <label class="form-check-label" for="permission">
                                                                Quotation
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Purchase Order">
                                                            <label class="form-check-label" for="permission">
                                                                Purchase Order
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Location">
                                                            <label class="form-check-label" for="permission">
                                                                Location
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Transfer Item">
                                                            <label class="form-check-label" for="permission">
                                                                Transfer Item
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Expenses">
                                                            <label class="form-check-label" for="permission">
                                                                Expenses
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Unit">
                                                            <label class="form-check-label" for="permission">
                                                                Unit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Supplier">
                                                            <label class="form-check-label" for="permission">
                                                                Supplier
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Report">
                                                            <label class="form-check-label" for="permission">
                                                                Report
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Net Profit">
                                                            <label class="form-check-label" for="permission">
                                                                Net Profit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="User">
                                                            <label class="form-check-label" for="permission">
                                                                User
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Configuration">
                                                            <label class="form-check-label" for="permission">
                                                                Configuration
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <a type="button" class="btn btn-default"
                                                href="{{ url('user') }}">Cancel</a>
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
