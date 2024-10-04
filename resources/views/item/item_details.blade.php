<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Item Details</title>
    <link rel="stylesheet" href="{{ asset('locallink/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<style>
    @media print {
        #calculate {
            display: none;
        }
    }

    @media print {
        body {
            color: black;
            /* Set text color for printing */
        }

        /* Add any other styles you want to modify for printing */
    }

    @media print {

        #test,
        #printButton,
        .excelButton {
            display: none;
        }

        @page {
            size: auto;
            margin: 0;
        }
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<style>
    @media print {
        body {
            font-size: 12px;
            color: #333;
            text-align: center;
            /* Center the content horizontally */
        }

        .container {
            width: 100%;
            margin: 0 auto;
            /* Center the container horizontally */
            padding: 0;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: none;
            text-align: left;
            /* Reset text alignment for card content */
        }

        .card-header {
            background-color: #f0f0f0;
            border-bottom: 1px solid #ccc;
            padding: 10px 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .btn {
            display: none;
        }
    }

    .user-name {
        /* Add your styling here */
        color: red;
        /* For example, set the text color to red */
        font-weight: bold;
        /* Set the font weight to bold */
        /* Add more styles as needed */
    }

    .fw-large {
        /* font-weight: bold; */
        font-size: larger;
        /* Add any other styles you want */
    }

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




<body>
    <div class="container mt-3">
        <div class="card p-4">
            <div class="card-header" style="">
                <h4 style="font-size: 18px" class="fw-semibold">Item Details</h4>
            </div>
            {{-- Permission --}}
            @php
                $choosePermission = [];
                if (auth()->user()->permission) {
                    $decodedPermissions = json_decode(auth()->user()->permission, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $choosePermission = $decodedPermissions;
                    }
                }
            @endphp
            {{-- End Permission --}}
            <div style="display: flex;">
                <div style="flex: 4;">
                    <table class='table table-bordered mt-5' style="font-size: 20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if (in_array('Item Edit', $choosePermission) || auth()->user()->is_admin == '1')
                                    <a href="{{ url('item_edit', $items->id) }}" class="btn btn-success mt-3"><i
                                            class="fa-solid fa-pen-to-square"></i>Edit</a>
                                @endif

                                {{-- @if (in_array('Inout History', $choosePermission) || auth()->user()->is_admin == '1')
                                    <a href="{{ url('in_out', $items->id) }}" class="btn btn-info mt-3 mx-1">In/Out
                                        History</a>
                                @endif --}}
                            </div>
                            <a href="{{ asset($items->item_image ? 'item_images/' . $items->item_image : 'img/default.png') }}"
                                target="_blank" id="logoLink">
                                <img src="{{ asset($items->item_image ? 'item_images/' . $items->item_image : 'img/default.png') }}"
                                    id="logoPreview" class="img-thumbnail mt-3"
                                    style="max-width: 200px; max-height: 150px;" alt="Item Image Preview">
                            </a>
                        </div>



                        {{-- <tr>
                            <td class="fw-light" style="width:200px">Remark</td>
                            <td class="fw-normal">{{ $parts->description }}</td>
                        </tr> --}}
                        <tr>
                            <td class="fw-light" style="width:200px">Item Name</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->item_name }}

                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Barcode</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->barcode }}

                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Item Descriptions
                            </td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->descriptions }}
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-light" style="width:200px">Expired Date</td>
                            <td class="fw-normal" style="width: 200px;">
                                @if ($items->expired_date)
                                    {{ \Carbon\Carbon::parse($items->expired_date)->format('d / F / Y') }}
                                @else
                                    N/A
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Item Category</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->category }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Item Type</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->item_type }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Item Unit</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->item_unit }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Warehouse</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->warehouse->name ?? 'N/A' }}
                            </td>
                        </tr>


                        <tr>
                            <td class="fw-light" style="width:200px">Quantity</td>
                            <td class="fw-normal" style="width: 200px;">
                                @if ($items->item_type == 'Service')
                                    <span class="text-danger">0</span>
                                @else
                                    {{ $items->quantity }}
                                @endif
                            </td>
                        </tr>


                        <tr>
                            <td class="fw-light" style="width:200px">Reorder Level Stock</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->reorder_level_stock }}
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-light" style="width:200px">လက်လီ‌စျေး</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->retail_price }}

                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">လက်ကားစျေး</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->wholesale_price }}

                            </td>
                        </tr>

                        <tr>
                            <td class="fw-light" style="width:200px">ဝယ်စျေး</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items->buy_price }}

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <a type="button" id="printButton" class="btn btn-primary m-2" onclick="printPage()">Print</a>
                <a type="button" id="printButton" class="m-2 excelButton btn btn-danger"
                    href="{{ url('items') }}">Back</a>
            </div>
        </div>
    </div>

    </div>

    <script src="{{ asset('locallink/js/ajax_jquery.js') }}"></script>
    <script src="{{ asset('locallink/js/typehead.min.js') }}"></script>
    <script src="{{ asset('locallink/js/moment.min.js') }}"></script>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</body>

</html>
