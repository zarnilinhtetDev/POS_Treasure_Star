@include('layouts.header')
<style>
    .img-thumbnail {
        border: 2px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        transition: transform 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
</style>

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
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1> Configuration</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"> Configuration
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

                <div class="mt-3 container-fluid">
                    <div class="row justify-content-center d-flex">
                        <!-- left column -->
                        <div class="col-md-8">
                            <!-- general form elements -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title " style="font-weight: bold;">Profile </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    @if (empty($user_profile->name))
                                        <form action="{{ asset('config_store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">

                                                <div class="form-group">
                                                    <label for="name">Company Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" required
                                                        autofocus name="name" value="">
                                                </div>



                                                <div class="form-group">
                                                    <label for="name">Company Logo</label>
                                                    <input type="file" class="form-control" id="name" autofocus
                                                        name="logos" value="">
                                                </div>

                                                <div class="form-group">
                                                    <label for="name">Address<span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="address" id="" cols="30" rows="5" class="form-control"></textarea required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Description</label>
                                    <textarea name="description" id="" cols="30" rows="2" class="form-control"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="phno1">Phone Number <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="tel" class="form-control"
                                                                id="phone_number1" name="phno1" value=""
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="phno2">Phone Number</label>
                                                            <input type="tel" class="form-control"
                                                                id="phone_number2" name="phno2" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="phno2">Email</label>
                                                            <input type="email" class="form-control"
                                                                id="phone_number2" name="email" value=""
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-end">
                                                <button type="submit" class="btn btn-primary">Save </button>
                                            </div>
                                        </form>
                                    @else
                                        <form action="{{ asset('config_edit') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">

                                                <div class="form-group">
                                                    <label for="name">Company Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name"
                                                        required autofocus name="name"
                                                        value="{{ $user_profile->name }}">
                                                </div>



                                                <div class="form-group">
                                                    <label for="name">Company Logo</label>
                                                    <div id="logoPreviewContainer" class="mt-3">
                                                        <a href="{{ asset('logos/' . $user_profile->logos) }}"
                                                            target="_blank" id="logoLink">
                                                            <img src="{{ asset('logos/' . $user_profile->logos) }}"
                                                                id="logoPreview" class="img-thumbnail"
                                                                style="max-width: 200px; max-height: 200px;"
                                                                alt="Company Logo Preview">
                                                        </a>
                                                    </div>
                                                    <input type="file" class="form-control mt-3" id="name"
                                                        autofocus name="logos" value="">

                                                </div>

                                                <div class="form-group">
                                                    <label for="name">Address<span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="address" id="" cols="30" rows="5" class="form-control" required>{{ $user_profile->address }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Description</label>
                                                    <textarea name="description" id="" cols="30" rows="2" class="form-control">{{ $user_profile->description }}</textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="phno1">Phone Number <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="tel" class="form-control"
                                                                id="phone_number1" name="phno1"
                                                                value="{{ $user_profile->phno1 }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="phno2">Phone Number</label>
                                                            <input type="tel" class="form-control"
                                                                id="phone_number2" name="phno2"
                                                                value="{{ $user_profile->phno2 }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="phno2">Email</label>
                                                            <input type="email" class="form-control"
                                                                id="phone_number2" name="email"
                                                                value="{{ $user_profile->email }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-end">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    @endif

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
