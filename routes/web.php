<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InOutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('items', [ItemController::class, 'index']);
    Route::post('items_register', [ItemController::class, 'register']);
    //for item search to add
    Route::get('/item_search', [InvoiceController::class, 'item_search'])->name(
        'search_item_name_for_add'
    );
    Route::post('/item_search_fill', [InvoiceController::class, 'item_data_search_fill'])->name('item_data_search_fill');

    //
    //Customer
    Route::get('customer', [CustomerController::class, 'index']);
    Route::get('customer_credit/{id}', [CustomerController::class, 'credit']);
    Route::post('customer_register', [CustomerController::class, 'store']);
    Route::get('customer_edit/{id}', [CustomerController::class, 'edit']);
    Route::post('customer_update/{id}', [CustomerController::class, 'update']);
    Route::get('customer_delete/{id}', [CustomerController::class, 'delete']);

    //Supplier

    Route::get('supplier', [SupplierController::class, 'index'])->middleware('isWarehouse');
    Route::post('supplier_register', [SupplierController::class, 'store'])->middleware('isWarehouse');
    Route::get('supplier_edit/{id}', [SupplierController::class, 'edit'])->middleware('isWarehouse');
    Route::post('supplier_update/{id}', [SupplierController::class, 'update'])->middleware('isWarehouse');
    Route::get('supplier_delete/{id}', [SupplierController::class, 'delete'])->middleware('isWarehouse');

    //Location
    Route::get('warehouse', [WarehouseController::class, 'index'])->name('warehouse')->middleware('isShop');
    Route::post('warehouse_register', [WarehouseController::class, 'warehouse_register'])->middleware('isAdmin');
    Route::get('warehouse_Delete/{id}', [WarehouseController::class, 'warehouse_delete'])->middleware('isAdmin');
    Route::get('warehouse_Edit/{id}', [WarehouseController::class, 'warehouse_edit'])->middleware('isAdmin');
    Route::post('warehouse_Update/{id}', [WarehouseController::class, 'warehouse_Update'])->middleware('isAdmin');
    Route::get('transfer_item', [WarehouseController::class, 'transfer_item'])->middleware('isShop');
    Route::post('store_transfer_item', [WarehouseController::class, 'store_transfer_item'])->name('store_transfer_item')->middleware('isShop');
    Route::post('/autocomplete-part-code-location', [WarehouseController::class, 'autocompletePartCode'])->name('autocomplete.part-code-location');
    Route::post('/get-part-data-location', [WarehouseController::class, 'getPartData'])->name('get.part.data-location');
    Route::get('/show_transfer_history', [WarehouseController::class, 'show_history'])->middleware('isShop');

    //Purchase Order
    Route::get('purchase_order_manage', [PurchaseOrderController::class, 'index'])->name('purchase_order')->middleware('isWarehouse');
    Route::get('purchase_order_register', [PurchaseOrderController::class, 'purchase_order_register'])->name('purchase_order_register')->middleware('isWarehouse');
    Route::post('purchase_order_store', [PurchaseOrderController::class, 'purchase_order_store'])->name('purchase_order_store')->middleware('isWarehouse');
    Route::get('purchase_order_delete/{id}', [PurchaseOrderController::class, 'po_delete'])->name('purchase_order_delete')->middleware('isWarehouse');
    Route::get('purchase_order_edit/{id}', [PurchaseOrderController::class, 'edit'])->name('purchase_order_edit')->middleware('isWarehouse');
    Route::post('purchase_order_update/{id}', [PurchaseOrderController::class, 'purchase_order_update'])->name('purchase_order_update')->middleware('isWarehouse');
    Route::get('purchase_order_details/{id}', [PurchaseOrderController::class, 'details'])->name('purchase_order_details')->middleware('isWarehouse');

    Route::post('/autocomplete_price', [App\Http\Controllers\PurchaseOrderController::class, 'autocomplete_price'])->name('autocomplete_price');
    Route::get('/customer_service_search', [App\Http\Controllers\PurchaseOrderController::class, 'po_search'])->name('po_search');
    Route::post('/customer_service_search_fill', [App\Http\Controllers\PurchaseOrderController::class, 'po_search_fill'])->name('po_search_fill');
    Route::post('/autocomplete-part-code', [PurchaseOrderController::class, 'autocompletePartCode'])->name('autocomplete.part-code');
    Route::post('/get-part-data', [PurchaseOrderController::class, 'getPartData'])->name('get.part.data');

    //Invoice
    Route::get('invoice', [InvoiceController::class, 'index'])->middleware('isAdmin');
    Route::post('invoice_register', [InvoiceController::class, 'invoice_register'])->middleware('isCashier');
    Route::get('invoice_reg', [InvoiceController::class, 'invoice'])->middleware('isCashier');

    Route::get('invoice_edit/{id}', [InvoiceController::class, 'invoice_edit'])->middleware('isCashier');
    Route::get('invoice_delete/{id}', [InvoiceController::class, 'invoice_delete'])->middleware('isAdmin');
    Route::post('invoice_update/{id}', [InvoiceController::class, 'invoice_update'])->middleware('isCashier');
    Route::get('invoice_detail/{invoice}', [InvoiceController::class, 'invoice_detail'])->name('invoice_detail')->middleware('isCashier');
    Route::get('daily_sales', [InvoiceController::class, 'daily_sales'])->name('daily_sales')->middleware('isAdmin');

    //Quotation
    Route::get('quotation', [InvoiceController::class, 'quotation'])->middleware('isAdmin');
    Route::get('/quotation_detail/{id}', [InvoiceController::class, 'quotation_detail'])->middleware('isAdmin');
    Route::get('quotation_register', [InvoiceController::class, 'quotation_register'])->middleware('isAdmin');
    Route::get('/customer_service', [InvoiceController::class, 'customer_service_search'])->name('customer_service_search');
    Route::post('/customer_service', [InvoiceController::class, 'customer_service_search_fill'])->name('customer_service_search_fill');
    Route::get('quotation_delete/{id}', [InvoiceController::class, 'quotation_delete'])->middleware('isAdmin');
    Route::get('quotation_edit/{id}', [InvoiceController::class, 'quotation_edit'])->middleware('isAdmin');
    Route::get('change_invoice/{id}', [InvoiceController::class, 'change_invoice'])->middleware('isAdmin');
    Route::post('/autocomplete-part-code-invoice', [InvoiceController::class, 'autocompletePartCode'])->name('autocomplete.part-code-invoice');
    Route::post('/get-part-data-invoice', [InvoiceController::class, 'getPartData'])->name('get.part.data-invoice');
    Route::post('/autocomplete-barcode-invoice', [InvoiceController::class, 'autocompleteBarCode'])->name('autocomplete.barcode-invoice');
    Route::post('/get-barcode-data-invoice', [InvoiceController::class, 'getBarcodeData'])->name('get.barcode.data-invoice');
    Route::post('/autocomplete-part-code', [InvoiceController::class, 'autocompletePartCodeInvoice'])->name('autocomplete-part-code-invoice');
    Route::post('/get-part-data', [InvoiceController::class, 'getPartDataInvoice'])->name('get-part-data-invoice');

    //item
    Route::get('items', [ItemController::class, 'index'])->middleware('isShop');
    Route::get('items_register', [ItemController::class, 'register'])->middleware('isWarehouse');
    Route::post('item_store', [ItemController::class, 'store'])->middleware('isWarehouse');
    Route::get('item_details/{id}', [ItemController::class, 'details'])->middleware('isShop');
    Route::get('item_edit/{id}', [ItemController::class, 'edit'])->middleware('isShop');
    Route::post('item_update/{id}', [ItemController::class, 'update'])->middleware('isShop');
    Route::get('item_delete/{id}', [ItemController::class, 'delete'])->middleware('isWarehouse');
    Route::get('barcode/{id}', [ItemController::class, 'barcode'])->middleware('isShop');

    //inout
    Route::get('in_out/{id}', [ItemController::class, 'inout'])->middleware('isWarehouse');
    Route::post('in/{id}', [InOutController::class, 'in'])->middleware('isWarehouse');
    Route::post('out/{id}', [InOutController::class, 'out'])->middleware('isWarehouse');
    Route::get('/display_print/{items_id}/{id}', [InOutController::class, 'display_print'])->name('display_print')->middleware('isWarehouse');
    Route::get('invoice_record/{id}', [InOutController::class, 'invoice_record'])->middleware('isWarehouse');
    Route::get('purchase_record/{id}', [InOutController::class, 'purchase_order_reord'])->middleware('isWarehouse');
    Route::get('pos_record/{id}', [InOutController::class, 'pos_record'])->middleware('isWarehouse');

    //unit
    Route::get('unit', [UnitController::class, 'index'])->middleware('isWarehouse');
    Route::post('unit_store', [UnitController::class, 'unit_store'])->middleware('isWarehouse');
    Route::get('unit_edit/{id}', [UnitController::class, 'edit'])->middleware('isWarehouse');
    Route::post('unit_update/{id}', [UnitController::class, 'update'])->middleware('isWarehouse');
    Route::get('unit_delete/{id}', [UnitController::class, 'delete'])->middleware('isWarehouse');
    Route::get('get_part_data-unit', [UnitController::class, 'get_part_data_unit'])->name('get.part.data-unit');

    //POS

    Route::get('pos_register', [InvoiceController::class, 'pos_register'])->middleware('isCashier');
    Route::get('pos_daily_sales', [InvoiceController::class, 'pos_daily_sales'])->name('pos_daily_sales');
    Route::get('pos', [InvoiceController::class, 'pos'])->middleware('isCashier');
    Route::post('/autocomplete-part-code-invoice', [InvoiceController::class, 'autocompletePartCode'])->name('autocomplete.part-code-invoice');
    Route::post('/get-part-data-invoice', [InvoiceController::class, 'getPartData'])->name('get.part.data-invoice');
    Route::post('/autocomplete-barcode-invoice', [InvoiceController::class, 'autocompleteBarCode'])->name('autocomplete.barcode-invoice');
    Route::post('/get-barcode-data-invoice', [InvoiceController::class, 'getBarcodeData'])->name('get.barcode.data-invoice');
    Route::get('pos_delete/{id}', [InvoiceController::class, 'pos_delete']);
    Route::post('/suspended', [InvoiceController::class, 'suspended'])->name('suspended')->middleware('isCashier');
    Route::get('/suspend_delete/{id}', [InvoiceController::class, 'suspend_delete'])->middleware('isCashier');
    //report
    Route::get('report', [ReportController::class, 'report_invoice'])->middleware('isAdmin');
    Route::get('report_quotation', [ReportController::class, 'report_quotation'])->middleware('isAdmin');
    Route::get('report_po', [ReportController::class, 'report_po'])->middleware('isAdmin');
    Route::get('report_item', [ReportController::class, 'report_item'])->middleware('isShop');
    Route::get('report_pos', [ReportController::class, 'report_pos'])->middleware('isAdmin');
    Route::get('report_purchase_return', [ReportController::class, 'report_purchase_return'])->middleware('isAdmin');
    Route::get('report_sale_return', [ReportController::class, 'report_sale_return'])->middleware('isAdmin');
    Route::get('monthly_purchase_return', [ReportController::class, 'monthly_purchase_return'])->middleware('isAdmin');
    Route::get('monthly_invoice_search', [ReportController::class, 'monthly_invoice_search'])->middleware('isAdmin');
    Route::get('monthly_sale_return', [ReportController::class, 'monthly_sale_return'])->middleware('isAdmin');
    Route::get('monthly_quotation_search', [ReportController::class, 'monthly_quotation_search'])->middleware('isAdmin');
    Route::get('monthly_po_search', [ReportController::class, 'monthly_po_search'])->middleware('isAdmin');
    Route::get('monthly_pos_search', [ReportController::class, 'monthly_pos_search'])->middleware('isAdmin');



    //Excel_Item_Export & Import
    Route::get('file-import-export', [ItemController::class, 'fileImportExport']);
    Route::post('file-import', [ItemController::class, 'fileImport'])->name('file-import');
    Route::get('file-export', [ItemController::class, 'fileExport'])->name('file-export');
    Route::get('file-import-template', [ItemController::class, 'fileImportTemplate'])->name('file-import-template');

    Route::get('user', [UserController::class, 'user_register'])->name('user')->middleware('isAdmin');
    Route::post('User_Register', [UserController::class, 'user_store'])->middleware('isAdmin');
    Route::get('/delete_user/{id}', [UserController::class, 'delete_user'])->middleware('isAdmin');
    Route::get('/delete_user/{id}', [UserController::class, 'delete_user'])->middleware('isAdmin');
    Route::get('/userShow/{id}', [UserController::class, 'userShow'])->middleware('isAdmin');
    Route::post('/update_user/{id}', [UserController::class, 'update_user'])->middleware('isAdmin');
    Route::post('/drop_table', [ItemController::class, 'drop_table'])->name('drop.table');
});
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

require __DIR__ . '/auth.php';
