@include('layouts.header')
<style>
    .changelogout:hover {
        background-color: whitesmoke;
        color: red;
    }

    input[type="date"]::-webkit-datetime-edit-text {
        color: transparent;
    }

    input[type="date"]::-webkit-inner-spin-button,
    input[type="date"]::-webkit-clear-button {
        color: #fff;
        position: relative;
    }

    input[type="date"]::-webkit-datetime-edit-year-field {
        position: absolute !important;
        border-left: 1px solid #8c8c8c;
        padding: 2px;
        padding-left: 10px;
        color: #000;
        left: 130px;
    }

    input[type="date"]::-webkit-datetime-edit-month-field {
        position: absolute !important;
        border-left: 1px solid #8c8c8c;
        padding: 2px;
        padding-left: 10px;
        color: #000;
        left: 78px;
    }


    input[type="date"]::-webkit-datetime-edit-day-field {
        position: absolute !important;
        color: #000;
        padding: 2px;
        padding-left: 10px;
        left: 30px;

    }
</style>

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
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1>Item Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Item Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

            </section>
            <div class="content-body">
                <div class="container-fluid justify-content-center d-flex">
                    <div class="card card-default col-md-12">
                        <!-- <div class="card-header">
                            <h3 class="card-title">Item Edit</h3>
                        </div> -->
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ url('item_update', $items->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">


                                <div class="row">
                                    <div class="frmSearch col-sm-6 mt-2">
                                        <label>
                                            <input type="radio" name="radio_category" value="ဆေး"
                                                id="radio_category"
                                                {{ $items->radio_category == 'ဆေး' ? 'checked' : '' }}>
                                            ဆေး
                                        </label>

                                        <label>
                                            <input type="radio" class="ml-3" name="radio_category"
                                                id="radio_category" value="Lenses"
                                                {{ $items->radio_category == 'Lenses' ? 'checked' : '' }}>
                                            Lenses
                                        </label>

                                        <label>
                                            <input type="radio" class="ml-3" name="radio_category"
                                                id="radio_category" value="Frame"
                                                {{ $items->radio_category == 'Frame' ? 'checked' : '' }}>
                                            Frame
                                        </label><br>
                                    </div>
                                </div>

                                <div class="row mt-3" id="madeInDropdown" style="display: none;">
                                    <div class="form-group col-md-6">
                                        <label for="madeIn">Choose Made In Country:</label>
                                        <select id="madeIn" name="madeIn" class="form-control">
                                            <option value="">Choose One</option>
                                            <option value="China" {{ $items->madeIn == 'China' ? 'selected' : '' }}>
                                                China</option>
                                            <option value="Thai" {{ $items->madeIn == 'Thai' ? 'selected' : '' }}>Thai
                                            </option>
                                            <option value="Korea" {{ $items->madeIn == 'Korea' ? 'selected' : '' }}>
                                                Korea</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row mt-3" id="lensDropdown" style="display:none;">
                                    <div class="form-group col-md-6">
                                        <label for="lensOptions">Choose Category:</label>
                                        <select id="lensOptions" name="lense" class="form-control">
                                            <option value="" {{ $items->lense == '' ? 'selected' : '' }}>Choose
                                                One</option>
                                            <option value="CR" {{ $items->lense == 'CR' ? 'selected' : '' }}>CR
                                            </option>
                                            <option value="MC" {{ $items->lense == 'MC' ? 'selected' : '' }}>MC
                                            </option>
                                            <option value="BB" {{ $items->lense == 'BB' ? 'selected' : '' }}>BB
                                            </option>
                                            <option value="PG" {{ $items->lense == 'PG' ? 'selected' : '' }}>PG
                                            </option>
                                            <option value="BBPG" {{ $items->lense == 'BBPG' ? 'selected' : '' }}>BBPG
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="lensOptions">Choose Degree Category:</label>
                                        <select id="degree" name="degree" class="form-control ">
                                            <option value="" selected>Choose One</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3" id="near_and_far_dropdown" style="display: none;">
                                    <div class="form-group col-md-6">
                                        <label for="lensOptions">Lense Category:</label>
                                        <select id="near_and_far" name="near_and_far" class="form-control">
                                            <option value="">
                                                Choose One</option>
                                            <option value="near"
                                                {{ $items->near_and_far == 'near' ? 'selected' : '' }}>အနီး</option>
                                            <option value="far"
                                                {{ $items->near_and_far == 'far' ? 'selected' : '' }}>အ‌‌ဝေး</option>
                                            <option value="near_and_far"
                                                {{ $items->near_and_far == 'near_and_far' ? 'selected' : '' }}>
                                                အနီး/အ‌‌ဝေး
                                            </option>
                                            <option value="sun_glass"
                                                {{ $items->near_and_far == 'sun_glass' ? 'selected' : '' }}>နေကာ
                                            </option>
                                            <option value="normal"
                                                {{ $items->near_and_far == 'normal' ? 'selected' : '' }}>ရိုးရိုး
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6" style="display: none"
                                        id="near_and_far_degree_dropdown">
                                        <label for="near_and_far_degree">Choose Lense Degree:</label>
                                        <select id="near_and_far_degree" name="near_and_far_degree"
                                            class="form-control ">
                                            <option value="" selected>Choose One</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6" style="display: none;" id="cylinder_dropdown">
                                        <label for="lensOptions">Cylinder Degree:</label>
                                        <select id="cylinder" name="cylinder" class="form-control ">
                                            <option value="" selected>Choose Cylinder</option>
                                        </select>
                                    </div>

                                </div>


                                <div class="row">



                                    <div class="form-group col-md-6">
                                        <label for="item_name">Item Name</label>
                                        <input type="text" class="form-control" id="item_name" name="item_name"
                                            placeholder="Enter Item Name" value="{{ $items->item_name }}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="item_image">Item Image</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <img id="imagePreview"
                                                        src="{{ asset('item_images/' . $items->item_image) }}"
                                                        style="max-width: 50px; max-height: 50px;"
                                                        alt="Item Image Preview">
                                                </span>
                                            </div>
                                            <input type="file" class="form-control" id="item_image"
                                                name="item_image" accept="image/*" onchange="previewImage(event)">
                                        </div>
                                        <span class="text-danger">Old: {{ $items->item_image }}</span>
                                        @error('item_image')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" class="form-control" id="barcode" name="barcode"
                                            placeholder="Enter BarCode" value="{{ $items->barcode }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="descriptions">Item Desctriptions</label>
                                        <input type="text" class="form-control" id="descriptions"
                                            placeholder="Enter Item Descriptions" name="descriptions"
                                            value="{{ $items->descriptions }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="expired_date">Expired Date</label>
                                        <input type="date" class="form-control" id="expired_date"
                                            name="expired_date" value="{{ $items->expired_date }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="category">Item Category</label>
                                        <input type="text" class="form-control" id="category" name="category"
                                            value="{{ $items->category }}" placeholder="Enter Item Category"
                                            required>
                                    </div>

                                    @if (auth()->user()->is_admin == '1')
                                        <div class="form-group col-md-6">
                                            <label for="warehouse_id">Location<span
                                                    class="text-danger">*</span></label>
                                            <input type="hidden" id="warehouse_id_from" name="warehouse_id_from">
                                            <select name="warehouse_id" id="warehouse_id" class="form-control"
                                                required>
                                                <option value="{{ $items->warehouse_id }}" selected>
                                                    {{ $items->warehouse->name }}
                                                </option>
                                                @foreach ($branchs as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="form-group col-md-6">
                                            <label for="warehouse_id">Location<span
                                                    class="text-danger">*</span></label>
                                            <input type="hidden" id="warehouse_id_from" name="warehouse_id_from">
                                            <select name="warehouse_id" id="warehouse_id" class="form-control"
                                                required>
                                                @php
                                                    $userPermissions = auth()->user()->level
                                                        ? json_decode(auth()->user()->level)
                                                        : [];
                                                @endphp
                                                <option value="{{ $items->warehouse_id }}" selected>
                                                    {{ $items->warehouse->name }}
                                                </option>
                                                @foreach ($branchs as $branch)
                                                    @if (in_array($branch->id, $userPermissions))
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif


                                </div>

                                <hr>

                                <div class="row mt-3">
                                    <div class="form-group col-md-4">
                                        <label for="quantity">
                                            Quantity
                                        </label>
                                        <input type="number" class="form-control" name="quantity" id="quantity"
                                            value="{{ $items->quantity }}" placeholder="Enter Quantity">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="item_unit">
                                            Unit
                                        </label>
                                        <select name="item_unit" id="item_unit" class="form-control">
                                            <option value="{{ $items->item_unit }}" selected>{{ $items->item_unit }}
                                            </option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->unit }}">{{ $unit->unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6" style="display: none">
                                        <label for="price">
                                            Price
                                        </label>
                                        <input type="number" class="form-control" name="price" id="price"
                                            value="{{ $items->price }}" placeholder="Enter Price" step="0.1">
                                    </div>



                                    <div class="form-group col-md-4">
                                        <label for="reorder_level_stock">Reorder Level Stock</label>
                                        <input type="number" class="form-control" id="reorder_level_stock"
                                            name="reorder_level_stock" value="{{ $items->reorder_level_stock }}"
                                            placeholder="Enter Reorder Level Stock">
                                    </div>




                                </div>

                                <hr>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="retail_price">လက်လီ‌စျေး</label>
                                        <input type="number" class="form-control" id="retail_price"
                                            name="retail_price" value="{{ $items->retail_price }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="wholesale_price">လက်ကားစျေး</label>
                                        <input type="number" class="form-control" id="wholesale_price"
                                            name="wholesale_price" placeholder="Enter Wholesale_Price"
                                            value="{{ $items->wholesale_price }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="buy_price">ဝယ်စျေး</label>
                                        <input type="number" class="form-control" id="buy_price" name="buy_price"
                                            placeholder="Enter Buy Price" value="{{ $items->buy_price }}">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="mb-3 ml-3 ">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>



    </div>


    @include('layouts.footer')

    <script src="{{ asset('locallink/js/ajax_jquery.js') }}"></script>
    <script src="{{ asset('locallink/js/typehead.min.js') }}"></script>
    <script src="{{ asset('locallink/js/moment.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            function toggleDropdownVisibility() {
                let selectedValue = $('input[name="radio_category"]:checked').val();

                if (selectedValue === 'Lenses') {
                    $('#madeInDropdown').hide();
                    $('#madeIn').val('');
                    $('#cylinder_dropdown').show();
                    $('#lensDropdown').show();
                    $('#near_and_far_dropdown').show();

                } else if (selectedValue === 'Frame') {
                    $('#madeInDropdown').show();
                    $('#lensDropdown').hide();
                    $('#cylinder_dropdown').hide();
                    $('#cylinder').val('');
                    $('#lensOptions').val('');
                    $('#degree').val('');
                    $('#near_and_far_dropdown').show();

                } else {
                    $('#madeInDropdown').hide();
                    $('#lensDropdown').hide();
                    $('#madeIn').val('');
                    $('#lensOptions').val('');
                    $('#near_and_far_dropdown').hide();
                    $('#near_and_far').val('');
                    $('#cylinder_dropdown').hide();
                    $('#cylinder').val('');

                }
            }

            toggleDropdownVisibility();

            $('input[name="radio_category"]').change(function() {
                toggleDropdownVisibility();
            });

            let dropdown = $('#degree');
            let startValue = 25;
            let endValue = 500;
            let step = 25;
            let selectedDegree = "{{ $items->degree }}";

            for (let i = startValue; i <= endValue; i += step) {
                dropdown.append($('<option>', {
                    value: i,
                    text: i,
                    selected: i == selectedDegree
                }));
            }
        });

        $(document).ready(function() {
            let dropdownCylinder = $('#cylinder');
            let dropdownNearAndFarDegree = $('#near_and_far_degree');
            let startValue = 25;
            let endValue = 500;
            let step = 25;

            let selectedCylinder = "{{ $items->cylinder ?? '' }}";
            let selectedNearAndFarDegree = "{{ $items->near_and_far_degree ?? '' }}";

            for (let i = startValue; i <= endValue; i += step) {
                dropdownCylinder.append($('<option>', {
                    value: i,
                    text: i
                }));
            }

            for (let i = startValue; i <= endValue; i += step) {
                dropdownNearAndFarDegree.append($('<option>', {
                    value: i,
                    text: i
                }));
            }

            if (selectedCylinder) {
                dropdownCylinder.val(selectedCylinder);
            }

            if (selectedNearAndFarDegree) {
                dropdownNearAndFarDegree.val(selectedNearAndFarDegree);
            }
        });

        $(document).ready(function() {
            function toggleDegreeDropdown() {
                var selectedValue = $('#near_and_far').val();
                if (selectedValue === 'near_and_far') {
                    $('#near_and_far_degree_dropdown').show();
                } else {
                    $('#near_and_far_degree_dropdown').hide();
                    $('#near_and_far_degree').val('');
                }
            }

            toggleDegreeDropdown();
            $('#near_and_far').on('change', function() {
                toggleDegreeDropdown();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.option-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.option-checkbox').not(this).prop('checked', false);
                }
            });
        });
    </script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result; // Set the image source to the base64 encoded string
            };
            reader.readAsDataURL(event.target.files[0]); // Read the file as a data URL
        }
    </script>
