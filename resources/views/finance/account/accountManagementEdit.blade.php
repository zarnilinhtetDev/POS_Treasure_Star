@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.nav')
        @include('layouts.sidebar')
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Account Management</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Account Management
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
                                    <h4 class="cade-title text-center">Account Management Edit Page</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ url('account_update', $account->id) }}" method="POST">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Account Number <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="Account_Number"
                                                    placeholder="Enter Name" required autofocus name="account_number"
                                                    value="{{ $account->account_number }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="crc"> Account Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="Account_Name"
                                                    placeholder="Enter Phone Number" name="account_name"
                                                    value="{{ $account->account_name }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="account_name">Type <span
                                                        style="color: red;">&nbsp;*</span></label>
                                                <select name="account_type" id="type" class="form-control">

                                                    <option value="Asset"
                                                        @if ($account->account_type == 'Asset') selected @endif>Asset</option>
                                                    <option value="Liabilities"
                                                        @if ($account->account_type == 'Liabilities') selected @endif>Liabilities
                                                    </option>
                                                    <option value="Capital"
                                                        @if ($account->account_type == 'Capital') selected @endif>Capital
                                                    </option>
                                                    <option value="Income"
                                                        @if ($account->account_type == 'Income') selected @endif>Income
                                                    </option>
                                                    <option value="Revenue"
                                                        @if ($account->account_type == 'Revenue') selected @endif>Revenue
                                                    </option>
                                                    <option value="Expenses"
                                                        @if ($account->account_type == 'Expenses') selected @endif>Expenses
                                                    </option>
                                                    </option>
                                                </select>
                                                <input type="hidden" name="account_bl_pl" id="bl_pl"
                                                    value="{{ $account->account_bl_pl }}">
                                                @error('type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <a href="{{ route('finance#accountManagement') }}"><button
                                                        type="button" class="btn btn-dark">Back</button></a>
                                                <button type="submit" class="btn btn-primary">update </button>
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




    <script>
        const homeRadio = document.getElementById('home');
        const bankRadio = document.getElementById('bank');
        const wavepayRadio = document.getElementById('wavepay');
        const kpayRadio = document.getElementById('kpay');
        const bankCard = document.getElementById('bankCard');
        const wavepayCard = document.getElementById('wavepayCard');
        const kpayCard = document.getElementById('kpayCard');

        const bankNameInput = document.getElementById('bankName');
        const bankNumberInput = document.getElementById('bankAccountNumber');
        const payNameInput = document.getElementById('accountName');
        const payPhoneInput = document.getElementById('phoneNumber');

        const accountNamewave = document.getElementById('accountNamewave');
        const phoneNumberwave = document.getElementById('phoneNumberwave');

        // Function to hide all cards
        function hideAllCards() {
            bankCard.style.display = 'none';
            kpayCard.style.display = 'none';
            wavepayCard.style.display = 'none';
        }

        // Add event listeners to radio buttons
        homeRadio.addEventListener('change', function() {
            if (this.checked) {
                hideAllCards();
            }
        });

        // Function to hide all cards
        if (bankRadio.checked) {
            //const bankCard = document.getElementById('bankCard');
            bankCard.style.display = 'block';
        }
        if (kpayRadio.checked) {
            //const kpayCard = document.getElementById('kpayCard');
            kpayCard.style.display = 'block';
        }
        if (wavepayRadio.checked) {
            // const kpayCard = document.getElementById('wavepayCard');
            wavepayCard.style.display = 'block';
        }

        bankRadio.addEventListener('change', function() {
            if (this.checked) {
                bankCard.style.display = 'block';
                wavepayCard.style.display = 'none';
                kpayCard.style.display = 'none';
                payNameInput.value = '';
                payPhoneInput.value = '';
                bankNameInput.value = '';
                bankNumberInput.value = '';

                accountNamewave.value = '';
                phoneNumberwave.value = '';
            }
        });

        kpayRadio.addEventListener('change', function() {
            if (this.checked) {
                kpayCard.style.display = 'block';
                bankCard.style.display = 'none';
                wavepayCard.style.display = 'none';
                bankNameInput.value = '';
                bankNumberInput.value = '';
                payNameInput.value = '';
                payPhoneInput.value = '';
            }
        });

        wavepayRadio.addEventListener('change', function() {
            if (this.checked) {
                wavepayCard.style.display = 'block';
                kpayCard.style.display = 'none';
                bankCard.style.display = 'none';
                bankNameInput.value = '';
                bankNumberInput.value = '';
                payNameInput.value = '';
                payPhoneInput.value = '';
            }
        });
    </script>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            var selectedValue = this.value;
            var hiddenInput = document.getElementById('bl_pl');

            if (selectedValue === 'Asset' || selectedValue === 'Liabilities' || selectedValue === 'Capital') {
                hiddenInput.value = 'BL';
            } else {
                hiddenInput.value = 'PL';
            }
        });
    </script>

    @include('layouts.footer')
