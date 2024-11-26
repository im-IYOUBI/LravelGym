<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppManager;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\PlansController;

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\PayTransactionsController;




Route::get('/form', function () {
    return view('form');
})->name('form');
Route::get('/', function () {
    return view('welcome'); 
})->name('welcome');
Route::get('/service', function () {
    return view('service');
})->name('service');
Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');







Route::get('/login', [AppManager::class, 'login'])->name('login');
Route::post('/home', [AppManager::class, 'loginPost'])->name('login.post');
Route::get('/registration', [AppManager::class, 'registration'])->name('registration');
Route::post('/registration', [AppManager::class, 'registrationPost'])->name('registration.post');
Route::get('/logout', [AppManager::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomepageController::class, 'showHomepage'])->name('homepage');

    Route::get('/plansview', [PlansController::class, 'plansView'])->name('plans');
    Route::get('/plandetailsform', [PlansController::class, 'createPlan'])->name('planform');
    Route::post('/storePlan', [PlansController::class, 'storePlan'])->name('storeplan');
    Route::get('/editplan/{id}', [PlansController::class, 'editPlan'])->name('editplan');
    Route::put('/updateplan/{id}', [PlansController::class, 'updatePlan'])->name('updateplan');
    Route::delete('/deleteplan/{id}', [PlansController::class, 'deletePlan'])->name('deleteplan');

    Route::get('/customersview', [CustomersController::class, 'customersView'])->name('customers');
    Route::get('/customerdetailsform', [CustomersController::class, 'createCustomer'])->name('customerform');
    Route::post('/storeCustomer', [CustomersController::class, 'storeCustomer'])->name('storecustomer');
    Route::get('/editcustomer/{id}', [CustomersController::class, 'editCustomer'])->name('editcustomer');
    Route::put('/updatecustomer/{id}', [CustomersController::class, 'updateCustomer'])->name('updatecustomer');
    Route::delete('/deletecustomer/{id}', [CustomersController::class, 'deleteCustomer'])->name('deletecustomer');

   

    Route::get('/paytransactionsview', [PayTransactionsController::class, 'paytransactionsView'])->name('paytransactions');
    Route::get('/paytransactiondetailsform', [PayTransactionsController::class, 'createPayTransaction'])->name('paytransactionform');
    Route::post('/storePayTransaction', [PayTransactionsController::class, 'storePayTransaction'])->name('storepaytransaction');
    Route::delete('/deletepaytransaction/{id}', [PayTransactionsController::class, 'deletePayTransaction'])->name('deletepaytransaction');
    Route::get('/paytransactions/pdf', [PayTransactionsController::class, 'downloadPDF'])->name('paytransactionspdf');

   
});
