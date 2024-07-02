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
                                                        <button id="addRowBtn" type="button"
                                                            class="btn btn-primary ">Add Row</button>

                                                        <button id="removeRowBtn" type="button"
                                                            class="btn btn-danger ">Remove Row</button>
                                                    </div>




                                                    <div class="form-group col-md-6 mt-3">
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

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Item"
                                                                @if (in_array('Item', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Item
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Customer"
                                                                @if (in_array('Customer', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Customer
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="POS"
                                                                @if (in_array('POS', $permissions)) checked @endif>
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
                                                                id="permission" name="permission[]" value="Invoice"
                                                                @if (in_array('Invoice', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Invoice
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Quotation"
                                                                @if (in_array('Quotation', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Quotation
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Purchase Order"
                                                                @if (in_array('Purchase Order', $permissions)) checked @endif>
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
                                                                id="permission" name="permission[]" value="Location"
                                                                @if (in_array('Location', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Location
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Transfer Item"
                                                                @if (in_array('Transfer Item', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Transfer Item
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Expenses"
                                                                @if (in_array('Expenses', $permissions)) checked @endif>
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
                                                                id="permission" name="permission[]" value="Unit"
                                                                @if (in_array('Unit', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Unit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Supplier"
                                                                @if (in_array('Supplier', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Supplier
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="Report"
                                                                @if (in_array('Report', $permissions)) checked @endif>
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
                                                                value="Net Profit"
                                                                @if (in_array('Net Profit', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                Net Profit
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]" value="User"
                                                                @if (in_array('User', $permissions)) checked @endif>
                                                            <label class="form-check-label" for="permission">
                                                                User
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="permission" name="permission[]"
                                                                value="Configuration"
                                                                @if (in_array('Configuration', $permissions)) checked @endif>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            const permissionCheckboxes = document.querySelectorAll('input[name="permission[]"]');

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
