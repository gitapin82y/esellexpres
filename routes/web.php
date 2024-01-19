<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryServicesController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGalleryController;
use App\Http\Controllers\TransactionBalanceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ShippingFeeController;

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

// user
Route::get('/',function(){
    return view('pages.homepage');
});
Route::post('send-mail',[MailController::class,'sendMail']);
Route::get('/join-seller', [UserController::class,'viewJoinSeller']);
Route::post('/join-seller', [UserController::class,'joinSeller'])->name('joinSeller');

Route::get('/login', [UserController::class,'viewLoginApp']);
Route::post('/login', [UserController::class,'loginApp'])->name('loginApp');
Route::get('/logout', [UserController::class,'logout'])->name('logoutApp');

Route::get('/reset-password', [UserController::class,'viewResetPassword']);
Route::post('/reset-password', [UserController::class,'storeResetPassword'])->name('reset-password');

Route::get('/register', [UserController::class,'viewRegisterApp']);
Route::post('/register', [UserController::class,'registerApp'])->name('registerApp');

// end user
Route::group(['middleware' => ['resellerseller']], function () {
// reseller
Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
Route::resource('category',CategoryController::class);
Route::resource('galleries',ProductGalleryController::class);
Route::resource('products',ProductController::class);
Route::resource('products-list',ProductController::class);
Route::get('products/{id}/take',[ProductController::class,'takeProduct']);
Route::get('products/{id}/delete',[ProductController::class,'deleteProduct']);
Route::get('products/{id}/gallery',[ProductController::class,'gallery'])->name('products.gallery');
//end middleware
});

// reseller
Route::group(['middleware' => ['reseller']], function () {
    
Route::patch('shipping-fee/update', [ShippingFeeController::class, 'update'])->name('shipping-fee.update');

Route::get('/topup-request', [TransactionBalanceController::class,'index'])->name('topup-request.index');
Route::get('/getTopupRequest', [TransactionBalanceController::class,'datatable'])->name('getTopupRequest');
Route::get('/topup-request/{id}/acc', [TransactionBalanceController::class,'accRequest']);
Route::get('/topup-request/{id}/reject', [TransactionBalanceController::class,'rejectRequest']);

Route::get('/withdraw-request', [TransactionBalanceController::class,'withdraw'])->name('withdraw-request.index');
Route::get('/getWithdrawRequest', [TransactionBalanceController::class,'datatableWithdrawRequest'])->name('getWithdrawRequest');
Route::get('/withdraw-request/{id}/acc', [TransactionBalanceController::class,'accRequestWithdaw']);
Route::get('/withdraw-request/{id}/reject', [TransactionBalanceController::class,'rejectRequestWithdaw']);

Route::get('/ecommerce', [StoreController::class,'ecommerce'])->name('ecommerce.index');
Route::post('/ecommerce', [StoreController::class,'ecommerceadd'])->name('ecommerce.add');

Route::resource('delivery-service',DeliveryServicesController::class);
Route::get('/getListDelivery', [DeliveryServicesController::class,'datatable'])->name('getListDelivery');

Route::resource('bank-account',BankAccountController::class);
Route::get('/getBankAccount', [BankAccountController::class,'datatable'])->name('getBankAccount');

Route::resource('kandidat-penjual',PenjualController::class);
Route::get('/getKandidatPenjual', [PenjualController::class,'datatable'])->name('getKandidatPenjual');
Route::get('/kandidat-penjual/{id}/acc', [PenjualController::class,'accKandidat']);
Route::get('/kandidat-penjual/{id}/tolak', [PenjualController::class,'tolakKandidat']);


Route::get('/list-penjual', [PenjualController::class,'indexListPenjual'])->name('list-penjual.index');
Route::get('/getListpenjual', [PenjualController::class,'datatableListPenjual'])->name('getListPenjual');
Route::get('/list-penjual/{id}/profit', [PenjualController::class,'editProfit']);
Route::post('/list-penjual/profit', [PenjualController::class,'updateProfit']);
});

// seller
Route::group(['middleware' => ['seller']], function () {
    Route::get('/stores', [StoreController::class,'stores'])->name('stores.index');
    Route::post('/stores', [StoreController::class,'storesadd'])->name('stores.add');

Route::get('/topup-balance', [TransactionBalanceController::class,'indextopup'])->name('topup-balance.index');
// Route::post('/topup-balance', [TransactionBalanceController::class,'storetopup']);
Route::get('/getTopup', [TransactionBalanceController::class,'datatabletopup'])->name('getTopup');

Route::get('/withdraw-balance', [TransactionBalanceController::class,'indexwithdraw'])->name('withdraw-balance.index');
Route::get('/getwithdraw', [TransactionBalanceController::class,'datatablewithdraw'])->name('getwithdraw');
});

Route::resource('/transaction',TransactionController::class);
Route::get('/getIncomingOrders', [TransactionController::class,'datatable'])->name('getIncomingOrders');
Route::get('transactions/{id}/set-status',[TransactionController::class,'setStatus'])->name('transactions.status');

Route::post('/topup-balance', [TransactionBalanceController::class,'storetopup']);
Route::post('/withdraw-balance', [TransactionBalanceController::class,'storewithdraw']);


Route::get('{name}/status-produk', [TransactionController::class,'statusProduct']);
Route::get('/{name}/shopping-cart',[TransactionController::class,'shoppingCart']);
Route::post('/{name}/shopping-cart/checkout',[TransactionController::class,'checkout']);
// Route::get('/{name}/login',function(){
//     return view('pages.penjual.login');
// });

// Route::get('/{name}/register',function(){
//     return view('pages.penjual.register');
// });
// penjual
Route::get('/{stores}/all-products', [ProductController::class,'allProduct']);
Route::get('/{stores}', [ProductController::class,'productSeller']);
Route::get('/{stores}/{product}', [ProductController::class,'detailProductSeller']);
// end penjual
