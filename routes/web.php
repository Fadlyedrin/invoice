<?php

use App\Models\Receipt;

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

// Route::get('/', function () {
//     return view('welcome');
// });

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReceiptController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ChangePassword;            
            

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	// Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	// Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout'); 
Route::group(['middleware' => ['isAdmin']], function () {

	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	
	Route::get('/invoice', [UserProfileController::class, 'index'])->name('invoice');
	Route::resource('permissions', PermissionController::class);
	Route::resource('roles', RoleController::class);
	Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
	Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);

	Route::resource('users', UserController::class);

	Route::resource('invoices', InvoiceController::class);
	
	});

	Route::post('/invoices/{invoice}/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');
	Route::post('/invoices/{invoice}/reject', [InvoiceController::class, 'reject'])->name('invoices.reject');
	Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
	
	
	Route::resource('receipts', ReceiptController::class);
	Route::post('/receipts/{receipt}/approve', [ReceiptController::class, 'approve'])->name('receipts.approve');
	Route::post('/receipts/{receipt}/reject', [ReceiptController::class, 'reject'])->name('receipts.reject');
	Route::get('/receipts/{receipt}/download', [ReceiptController::class, 'download'])->name('receipts.download');
