<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoicesReport;
use App\Http\Controllers\CustomerReports;

use App\Http\Controllers\Test;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});




Auth::routes();
// Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/invoices', InvoicesController::class);

Route::resource('/Archive', ArchiveController::class);

Route::resource('/department', DepartmentsController::class);

Route::resource('/products', ProductsController::class);

Route::get('/departments/{id}', [InvoicesController::class , 'GetProducts']);

Route::post('/departments/update', [DepartmentsController::class , 'update']);

Route::get('/InvoicesDetails/{id}', [InvoicesController::class , 'GetInvoiceDetails']);

Route::get('/View_file/{invoice_number}/{file_name}' , [InvoicesController::class , 'open_file']);

Route::get('/download/{invoice_number}/{file_name}' , [InvoicesController::class , 'get_file']);

Route::post('delete_file' , [InvoicesController::class , 'destroy'])->name('delete_file');

Route::post('InvoiceAttachments' , [InvoicesController::class , 'add_new_attachment']);

Route::get('/edit_invoice/{id}', [InvoicesController::class , 'edit']);

Route::get('/Status_Update/{id}' , [InvoicesController::class , 'status_edit']);

Route::post('/update_invoice_status' , [InvoicesController::class , 'update_invoice_status']);

Route::post('/delete_invoice' , [InvoicesController::class , 'delete_invoice']);

Route::post('/archive_invoice' , [InvoicesController::class , 'archive_invoice']);
 
Route::get('/paid_invoices' , [InvoicesController::class , 'paid_invoices']);

Route::get('/unpaid_invoices' , [InvoicesController::class , 'unpaid_invoices']);

Route::get('/partial_paid_invoices' , [InvoicesController::class , 'partial_paid_invoices']);

Route::get('/archived_invoices' , [ArchiveController::class , 'index']);

Route::get('/Print_invoice/{id}' , [InvoicesController::class , 'Print_invoice']);

Route::get('/export_invoices', [InvoicesController::class , 'export']);

Route::group(['middleware' => ['auth']], function() {

    Route::resource('/roles','App\Http\Controllers\RoleController');
    
    Route::resource('/users','App\Http\Controllers\UserController');
    
   });

Route::get('/invoices_report' , [InvoicesReport::class , 'index']);

Route::post('/Search_invoices' , [InvoicesReport::class , 'Search_invoices']);

Route::get('/customers_report' , [CustomerReports::class , 'index']);

Route::post('/Search_customers' , [CustomerReports::class , 'Search_customers']);

Route::get('MarkAsReadAll' , [InvoicesController::class , 'MarkAsRead_all']);

Route::get('/{page}', [AdminController::class , 'index']);

