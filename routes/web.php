<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InOutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\UserTypeController;

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

    Route::get('supplier', [SupplierController::class, 'index']);
    Route::post('supplier_register', [SupplierController::class, 'store']);
    Route::get('supplier_edit/{id}', [SupplierController::class, 'edit']);
    Route::post('supplier_update/{id}', [SupplierController::class, 'update']);
    Route::get('supplier_delete/{id}', [SupplierController::class, 'delete']);

    //Location
    Route::get('warehouse', [WarehouseController::class, 'index'])->name('warehouse');
    Route::post('warehouse_register', [WarehouseController::class, 'warehouse_register']);
    Route::get('warehouse_Delete/{id}', [WarehouseController::class, 'warehouse_delete']);
    Route::get('warehouse_Edit/{id}', [WarehouseController::class, 'warehouse_edit']);
    Route::post('warehouse_Update/{id}', [WarehouseController::class, 'warehouse_Update']);
    Route::get('transfer_item', [WarehouseController::class, 'transfer_item']);
    Route::post('store_transfer_item', [WarehouseController::class, 'store_transfer_item'])->name('store_transfer_item');
    Route::post('/autocomplete-part-code-location', [WarehouseController::class, 'autocompletePartCode'])->name('autocomplete.part-code-location');
    Route::post('/get-part-data-location', [WarehouseController::class, 'getPartData'])->name('get.part.data-location');
    Route::get('/show_transfer_history', [WarehouseController::class, 'show_history']);

    //Purchase Order
    Route::get('purchase_order_manage', [PurchaseOrderController::class, 'index'])->name('purchase_order');
    Route::get('purchase_order_register', [PurchaseOrderController::class, 'purchase_order_register'])->name('purchase_order_register');
    Route::post('purchase_order_store', [PurchaseOrderController::class, 'purchase_order_store'])->name('purchase_order_store');
    Route::get('purchase_order_delete/{id}', [PurchaseOrderController::class, 'po_delete'])->name('purchase_order_delete');
    Route::get('purchase_order_edit/{id}', [PurchaseOrderController::class, 'edit'])->name('purchase_order_edit');
    Route::post('purchase_order_update/{id}', [PurchaseOrderController::class, 'purchase_order_update'])->name('purchase_order_update');
    Route::get('purchase_order_details/{id}', [PurchaseOrderController::class, 'details'])->name('purchase_order_details');

    Route::post('/autocomplete_price', [App\Http\Controllers\PurchaseOrderController::class, 'autocomplete_price'])->name('autocomplete_price');
    Route::get('/supplier_service_search', [App\Http\Controllers\PurchaseOrderController::class, 'po_search'])->name('po_search');
    Route::post('/supplier_service_search_fill', [App\Http\Controllers\PurchaseOrderController::class, 'po_search_fill'])->name('po_search_fill');
    Route::post('/autocomplete-part-code', [PurchaseOrderController::class, 'autocompletePartCode'])->name('autocomplete.part-code');
    Route::post('/get-part-data', [PurchaseOrderController::class, 'getPartData'])->name('get.part.data');

    //Invoice
    Route::get('invoice', [InvoiceController::class, 'index']);
    Route::post('invoice_register', [InvoiceController::class, 'invoice_register']);
    Route::get('invoice_reg', [InvoiceController::class, 'invoice']);

    Route::get('invoice_edit/{id}', [InvoiceController::class, 'invoice_edit']);
    Route::get('invoice_delete/{id}', [InvoiceController::class, 'invoice_delete']);
    Route::post('invoice_update/{id}', [InvoiceController::class, 'invoice_update']);
    Route::get('invoice_detail/{invoice}', [InvoiceController::class, 'invoice_detail'])->name('invoice_detail');
    Route::get('invoice_receipt_print/{invoice}', [InvoiceController::class, 'invoice_receipt_print'])->name('invoice_receipt_print');
    Route::get('daily_sales', [InvoiceController::class, 'daily_sales'])->name('daily_sales');

    //Quotation
    Route::get('quotation', [InvoiceController::class, 'quotation']);
    Route::get('/quotation_detail/{id}', [InvoiceController::class, 'quotation_detail']);
    Route::get('quotation_register', [InvoiceController::class, 'quotation_register']);
    Route::get('/customer_service_search', [InvoiceController::class, 'customer_service_search'])->name('customer_service_search');
    Route::post('/customer_service_search_fill', [InvoiceController::class, 'customer_service_search_fill'])->name('customer_service_search_fill');
    Route::get('quotation_delete/{id}', [InvoiceController::class, 'quotation_delete']);
    Route::get('quotation_edit/{id}', [InvoiceController::class, 'quotation_edit']);
    Route::get('change_invoice/{id}', [InvoiceController::class, 'change_invoice']);
    // Route::post('/autocomplete-part-code-invoice', [InvoiceController::class, 'autocompletePartCode'])->name('autocomplete.part-code-invoice');
    // Route::post('/autocomplete-barcode-invoice', [InvoiceController::class, 'autocompleteBarCode'])->name('autocomplete.barcode-invoice');

    //invoice , quotation , purchase order
    Route::post('/autocomplete-part-code', [InvoiceController::class, 'autocompletePartCodeInvoice'])->name('autocomplete-part-code-invoice');
    Route::post('/get-part-data', [InvoiceController::class, 'getPartDataInvoice'])->name('get-part-data-invoice');

    //item
    Route::get('items', [ItemController::class, 'index']);
    Route::get('items_register', [ItemController::class, 'register']);
    Route::post('item_store', [ItemController::class, 'store']);
    Route::get('item_details/{id}', [ItemController::class, 'details']);
    Route::get('item_edit/{id}', [ItemController::class, 'edit']);
    Route::post('item_update/{id}', [ItemController::class, 'update']);
    Route::get('item_delete/{id}', [ItemController::class, 'delete']);
    Route::get('barcode/{id}', [ItemController::class, 'barcode']);
    Route::get('/item_filter', [ItemController::class, 'item_filter'])->name('items.filter');


    //inout
    Route::get('in_out/{id}', [ItemController::class, 'inout']);
    Route::post('in/{id}', [InOutController::class, 'in']);
    Route::post('out/{id}', [InOutController::class, 'out']);
    Route::get('/display_print/{items_id}/{id}', [InOutController::class, 'display_print'])->name('display_print');
    Route::get('invoice_record/{id}', [InOutController::class, 'invoice_record']);
    Route::get('purchase_record/{id}', [InOutController::class, 'purchase_order_reord']);
    Route::get('pos_record/{id}', [InOutController::class, 'pos_record']);

    //unit
    Route::get('unit', [UnitController::class, 'index']);
    Route::post('unit_store', [UnitController::class, 'unit_store']);
    Route::get('unit_edit/{id}', [UnitController::class, 'edit']);
    Route::post('unit_update/{id}', [UnitController::class, 'update']);
    Route::get('unit_delete/{id}', [UnitController::class, 'delete']);
    Route::get('get_part_data-unit', [UnitController::class, 'get_part_data_unit'])->name('get.part.data-unit');


    //expense
    Route::get('expense', [ExpenseController::class, 'index']);
    Route::post('expense_store', [ExpenseController::class, 'expenseStore']);
    Route::get('expense_edit/{expense}', [ExpenseController::class, 'edit']);
    Route::post('expense_update/{expense}', [ExpenseController::class, 'update']);
    Route::get('expense_delete/{expense}', [ExpenseController::class, 'delete'])->middleware('isBranchManager');
    Route::get('get_part_data-unit', [ExpenseController::class, 'get_part_data_unit'])->name('get.part.data-unit');

    Route::get('expense_category', [ExpenseCategoryController::class, 'index']);
    Route::post('expense_category_store', [ExpenseCategoryController::class, 'categoryStore']);
    Route::get('expense_category_edit/{id}', [ExpenseCategoryController::class, 'edit']);
    Route::post('expense_category_update/{id}', [ExpenseCategoryController::class, 'update']);
    Route::get('expense_category_delete/{id}', [ExpenseCategoryController::class, 'delete']);


    //POS
    Route::get('pos_register', [InvoiceController::class, 'pos_register']);
    Route::get('pos_daily_sales', [InvoiceController::class, 'pos_daily_sales'])->name('pos_daily_sales');
    Route::get('pos', [InvoiceController::class, 'pos']);
    Route::post('/autocomplete-part-code-invoice', [InvoiceController::class, 'autocompletePartCode'])->name('autocomplete.part-code-invoice');
    Route::post('/get-part-data-invoice', [InvoiceController::class, 'getPartData'])->name('get.part.data-invoice');
    Route::post('/autocomplete-barcode-invoice', [InvoiceController::class, 'autocompleteBarCode'])->name('autocomplete.barcode-invoice');
    Route::post('/get-barcode-data-invoice', [InvoiceController::class, 'getBarcodeData'])->name('get.barcode.data-invoice');
    Route::get('pos_delete/{id}', [InvoiceController::class, 'pos_delete']);
    Route::post('/suspended', [InvoiceController::class, 'suspended'])->name('suspended');
    Route::get('/suspend_delete/{id}', [InvoiceController::class, 'suspend_delete']);


    //report
    Route::get('report_expense', [ReportController::class, 'reportExpense']);
    Route::get('report', [ReportController::class, 'report_invoice']);
    Route::get('/report_invoice/{branch?}', [ReportController::class, 'report_invoice'])->name('report_invoice');
    Route::get('report_quotation', [ReportController::class, 'report_quotation']);
    Route::get('/report_quotation_branch/{branch?}', [ReportController::class, 'report_quotation'])->name('report_quotation_branch');
    Route::get('report_po', [ReportController::class, 'report_po']);
    Route::get('report_po_branch/{branch?}', [ReportController::class, 'report_po'])->name('report_po_branch');
    Route::get('report_item', [ReportController::class, 'report_item']);
    Route::get('monthly_item_search', [ReportController::class, 'monthly_item_search']);
    Route::get('report_pos', [ReportController::class, 'report_pos']);
    Route::get('report_pos_branch', [ReportController::class, 'report_pos'])->name('report_pos_branch');
    Route::get('report_purchase_return', [ReportController::class, 'report_purchase_return']);
    Route::get('report_sale_return', [ReportController::class, 'report_sale_return']);
    Route::get('monthly_purchase_return', [ReportController::class, 'monthly_purchase_return']);
    Route::get('expense_search', [ReportController::class, 'expenseSearch']);
    Route::get('monthly_invoice_search', [ReportController::class, 'monthly_invoice_search']);
    Route::get('monthly_sale_return', [ReportController::class, 'monthly_sale_return']);
    Route::get('monthly_quotation_search', [ReportController::class, 'monthly_quotation_search']);
    Route::get('monthly_po_search', [ReportController::class, 'monthly_po_search']);
    Route::get('monthly_pos_search', [ReportController::class, 'monthly_pos_search']);
    Route::get('sale_return', [InvoiceController::class, 'sale_return']);
    Route::get('sale_return_register', [InvoiceController::class, 'sale_return_register']);
    Route::get('sale_return_edit/{id}', [InvoiceController::class, 'sale_return_edit']);
    Route::get('sale_return_detail/{id}', [InvoiceController::class, 'sale_return_detail']);
    Route::get('sale_return_delete/{id}', [InvoiceController::class, 'sale_return_delete']);
    Route::get('sale_return_search', [InvoiceController::class, 'sale_return_search']);
    Route::get('report_pos_receipt/{invoice}', [ReportController::class, 'pos_receipt']);
    Route::get('report_invoice_details/{invoice}', [ReportController::class, 'report_invoice_detail']);

    //Profit
    Route::get('profit', [ProfitController::class, 'index']);
    Route::get('/index', [ProfitController::class, 'index'])->name('profit_report');
    Route::get('search', [ProfitController::class, 'search']);

    //Excel_Item_Export & Import
    Route::get('file-import-export', [ItemController::class, 'fileImportExport']);
    Route::post('file-import', [ItemController::class, 'fileImport'])->name('file-import');
    Route::post('file-update-import', [ItemController::class, 'fileUpdateImport'])->name('file-update-import');
    Route::get('file-export', [ItemController::class, 'fileExport'])->name('file-export');
    Route::get('file-import-template', [ItemController::class, 'fileImportTemplate'])->name('file-import-template');

    //User Type
    Route::get('user_type', [UserTypeController::class, 'index']);
    Route::post('type_store', [UserTypeController::class, 'store']);
    Route::get('user_type_edit/{id}', [UserTypeController::class, 'edit']);
    Route::post('user_type_update/{id}', [UserTypeController::class, 'update']);
    Route::get('user_type_delete/{id}', [UserTypeController::class, 'delete']);




    //User
    Route::get('user', [UserController::class, 'user_register'])->name('user');
    Route::post('User_Register', [UserController::class, 'user_store']);
    Route::get('/delete_user/{id}', [UserController::class, 'delete_user']);
    Route::get('/delete_user/{id}', [UserController::class, 'delete_user']);
    Route::get('/userShow/{id}', [UserController::class, 'userShow']);
    Route::post('/update_user/{id}', [UserController::class, 'update_user']);
    Route::get('user_permission/{id}', [UserController::class, 'permission']);
    Route::post('user_permission_store/{id}', [UserController::class, 'permissionStore']);
    Route::post('/drop_table', [ItemController::class, 'drop_table'])->name('drop.table');


    //UserProfile
    Route::get('config_manage', [UserProfileController::class, 'index']);
    Route::get('config', [UserProfileController::class, 'config'])->name('config');
    Route::post('config_store', [UserProfileController::class, 'config_store'])->name('config_store');
    Route::get('config_edit/{id}', [UserProfileController::class, 'edit']);
    Route::get('config_delete/{id}', [UserProfileController::class, 'delete']);
    Route::post('config_update/{id}', [UserProfileController::class, 'config_edit']);
    Route::get('config_details/{id}', [UserProfileController::class, 'details']);
});
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// Test
// Route::get('/update_barcode', [ItemController::class, 'update_barcode'])->name('update_barcode');

require __DIR__ . '/auth.php';
