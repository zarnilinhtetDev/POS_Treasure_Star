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
                    <a class="nav-link text-white" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>

                <li class="ml-auto nav-item">

                    @php
                        use App\Models\Warehouse;
                        $branchs = Warehouse::all();
                    @endphp
                    <a class="nav-link text-white" href="#">
                        @foreach ($branchs as $branch)
                            @if ($branch->id == auth()->user()->level)
                                {{ $branch->name }}
                            @endif
                        @endforeach
                        {{-- {{ auth()->user()->level }} --}}
                    </a>

                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">
                <div class="btn-group">
                    <button type="button" class=" text-white btn dropdown-toggle" data-toggle="dropdown"
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
                        <div class="row mb-2">
                            <div class="col-sm-6 mt-3">
                                <h1>Expense Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Expense Edit
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

                @if (session('delete_success'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('delete_success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <section class="content container-fluid">

                    <div class="row">
                        <div class="col-6 offset-3">
                            <div class="card">
                                <div class="cade-header mt-2 mb-2">
                                    <h4 class="cade-title text-center">Expense Edit</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ url('expense_update', $expense->id) }}" method="POST">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name"
                                                    placeholder="Enter name" required autofocus name="name"
                                                    value="{{ $expense->name }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="category">Category<span class="text-danger">*</span></label>
                                                <select class="form-control" id="category" required autofocus
                                                    name="category">
                                                    @foreach ($categories as $key => $category)
                                                        <option value="{{ $category['name'] }}"
                                                            @if ($category->name == $expense->category) selected @endif>
                                                            {{ $category['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>



                                            <div class="form-group">
                                                <label for="amount">Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="amount"
                                                    placeholder="Enter amount" required autofocus name="amount"
                                                    value="{{ $expense->amount }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="date">Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="date" autofocus
                                                    name="date" value="{{ $expense->date }}">
                                            </div>

                                            {{-- @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin') --}}
                                            <div class="form-group">
                                                <label for="branch">Location<span
                                                        class="text-danger">*</span></label>
                                                <select name="branch" id="branch" class="form-control" required>
                                                    <option selected disabled>Select Location</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ $branch->id == $expense->branch ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- @else
                                                <div class="form-group" style="display: none;">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="branch"
                                                        id="branch" value="{{ auth()->user()->level }}">
                                                </div>
                                            @endif --}}

                                            <div class="form-group">
                                                <label for="amount">Description<span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" id="description" placeholder="Enter description" required autofocus
                                                    name="description" rows="5">{{ $expense->description }}</textarea>
                                            </div>

                                            <div class="modal-footer justify-content-between">
                                                <a href="{{ url('expense') }}"><button type="button"
                                                        class="btn btn-dark">Back</button></a>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- Modal End --}}
                </section>


            </section>

        </div>



    </div>




    @include('layouts.footer')
