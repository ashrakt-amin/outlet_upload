<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\LoginUserController;
use App\Http\Controllers\Api\Auth\LoginClientController;
use App\Http\Controllers\Api\Auth\LoginTraderController;
use App\Http\Controllers\Api\Auth\RegisterUserController;
use App\Http\Controllers\Api\Auth\RegisterClientController;
use App\Http\Controllers\Api\Auth\RegisterTraderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

########################################################################################################################

//-----------------------------------------------------------------------------------------------------------
Route::prefix("register")->group(function(){
    Route::middleware('auth:sanctum')->group(function () {
        Route::post("users",  [RegisterUserController::class, "register"])->name("register.users");
    });
    Route::post("traders",[RegisterTraderController::class, "register"])->name("register.traders");
    Route::post("clients",[RegisterClientController::class, "register"])->name("register.clients");
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("login")->group(function(){
    Route::post("users",       [LoginUserController::class, "login"])->name("users.login");
    Route::post("traders", [LoginTraderController::class, "login"])->name("traders.login");
    Route::post("clients", [LoginClientController::class, "login"])->name("clients.login");
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("logout")->group(function(){
        Route::post("/",[LogoutController::class, "logout"])->name("logout");
    });
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
/**
 * middleware all
 */

Route::middleware('auth:sanctum')->group(function () {
    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("users")->group(function(){
        Route::controller(UserController::class)->group(function () {
            Route::get('/show', 'show')->name('users.show');
        });
    });
    Route::resource('/users', UserController::class)->except('show');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("newTraders")->group(function(){
        Route::controller(TraderController::class)->group(function () {
            Route::get('/trader', 'trader');
        });
        Route::resource('/', TraderController::class)->only('destroy', 'update', 'store', 'edit');
    });
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("traders")->group(function(){
        Route::controller(TraderController::class)->group(function () {
            Route::put('/forgetting_password', 'forgettingPassword');
        });
    });
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('levels', LevelController::class)->only('store', 'update', 'destroy');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::controller(UnitController::class)->group(function () {
        Route::put('units/categories/{unit}', 'categories');
    });
    Route::resource('units', UnitController::class)->only('store', 'update');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('items', ItemController::class)->except('index', 'show', 'store', 'edit');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("clients")->group(function(){
    Route::resource('/', ClientController::class);
        Route::controller(ClientController::class)->group(function () {
            Route::get('/client',         'client')->name('clients.client');
            Route::get('/clientGuest',    'clientGuest')->name('clients.clientGuest');
        });
    });
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("carts")->group(function(){
        Route::controller(CartController::class)->group(function () {
            Route::post('/search',            'search')->name('carts.search');
            Route::get('/{cart}/{client}', 'clientCart')->name('carts.client');
            Route::get('/destroyAll',     'destroyAll')->name('carts.destroyAll');
        });
    });
    Route::resource('carts', CartController::class);
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("orders")->group(function(){
        Route::controller(OrderController::class)->group(function () {
            Route::put('/cancel/{order}', 'cancel')->name('orders.cancel');
        });
    });
    Route::resource('orders', OrderController::class);
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("orderDetails")->group(function(){
        Route::controller(OrderDetailController::class)->group(function () {
            Route::get('/trader',                     'trader')->name('orderDetails.trader');
            Route::put('/cancel/{orderDetail}',       'cancel')->name('orderDetails.cancel');
            Route::put('/cancelAll/{orderDetail}', 'cancelAll')->name('orderDetails.cancelAll');
        });
    });
    Route::resource('orderDetails', OrderDetailController::class);
    //______________________________________________________________________________________________________________________
    //-----------------------------------------------------------------------------------------------------------
    Route::resource('rates', RateController::class)->except('create', 'edit');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('zones', ZoneController::class)->except('create', 'edit');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('projectImages', ProjectImageController::class)->only('store', 'update', 'destroy');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('levelImages', LevelImageController::class)->only('store', 'update', 'destroy');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('unitImages', UnitImageController::class)->only('store', 'update', 'destroy');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('traderImages', TraderImageController::class)->only('store', 'update', 'destroy');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('itemImages', ItemImageController::class)->only('store', 'update', 'destroy');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('advertisementImages', AdvertisementImageController::class)->only('store', 'update', 'destroy');
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::resource('coupons', CouponController::class)->except('create', 'edit');
    //______________________________________________________________________________________________________________________
});
//______________________________________________________________________________________________________________________
//______________________________________________________________________________________________________________________
//______________________________________________________________________________________________________________________
########################################################################################################################
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
/**
 * without middleware
 */

//-----------------------------------------------------------------------------------------------------------
Route::resource('newTraders', TraderController::class)->only('index', 'show');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('traders', TraderController::class)->only('index', 'show', 'update', 'store', 'destroy');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("mainProjects")->group(function(){
    Route::controller(MainProjectController::class)->group(function () {
        Route::get('/offers',         'offers');
        Route::get('/allConditons', 'allConditons');
        Route::get('/main_project_with_projects_with_units/{mainProject}', 'mainProjectWithProjectsWithUnits');
    });
});
Route::resource('mainProjects', MainProjectController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("projects")->group(function(){
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/all_conditons', 'allConditons');
        Route::get('/archived', 'archived');
        Route::get('/restore/{id}', 'restore');
        Route::get('/restore_all', 'restoreAll');
        Route::delete('/force_delete/{id}', 'forceDelete');
        Route::delete('/force_delete_all', 'forceDeleteAll');
        Route::get('/project_with_out_levels/{project}', 'ProjectWithOutLevels');
    });
});
Route::resource('projects', ProjectController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("levels")->group(function(){
    Route::controller(LevelController::class)->group(function () {
        Route::get('/all_conditons', 'allConditons');
        Route::get('/latest',         'latest')->name('levels.latest');
        Route::get('/client/{level}', 'client')->name('levels.client');
    });
});
Route::resource('levels', LevelController::class)->except('store', 'update', 'destroy', 'create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("units")->group(function(){
    Route::controller(UnitController::class)->group(function () {
        Route::get('/show_online/{unit}', 'showOnline');
        Route::get('/allConditons', 'allConditons');
        Route::get('/archived', 'archived');
        Route::get('/restore/{id}', 'restore');
        Route::get('/restore_all', 'restoreAll');
        Route::delete('/force_delete/{id}', 'forceDelete');
        Route::delete('/force_delete_all', 'forceDeleteAll');
        Route::get('/toggle_update/{id}/{booleanName}', 'toggleUpdate');
        Route::put('/categories/{unit}', 'categories');
    });
});
Route::resource('units', UnitController::class)->except('store', 'update', 'create', 'edit');

//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("items")->group(function(){
    Route::controller(ItemController::class)->group(function () {
        Route::get('/whats_app_click/{id}', 'whatsAppClick');
        Route::get('/restore/{id}', 'restore');
        Route::get('/restore_all', 'restoreAll');
        Route::delete('/force_delete/{id}', 'forceDelete');
        Route::delete('/force_delete_all', 'forceDeleteAll');
        Route::get('/toggle_update/{id}/{booleanName}',    'toggleUpdate');
        Route::get('/',                    'index');
        Route::get('/{item}',               'show');
    });
Route::resource('/', ItemController::class)->only('index', 'show', 'store', 'edit');
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("itemImages")->group(function(){
    Route::controller(ItemImageController::class)->group(function () {
        Route::post('/store2', 'store2');
    });
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories/categories_of_projects/{project_id}', 'categoriesOfProject');
    Route::get('categories/allConditons', 'allConditons');
});
Route::resource('categories', CategoryController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
// Route::resource('colors', ColorController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
// Route::resource('sizes', SizeController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
// Route::resource('stocks', StockController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::controller(AdvertisementController::class)->group(function () {
    Route::get('advertisements/grade', 'grade');
    Route::get('advertisements/', 'index');
});
Route::resource('advertisements', AdvertisementController::class)->except('index', 'create', 'edit');
//______________________________________________________________________________________________________________________
//-----------------------------------------------------------------------------------------------------------
Route::resource('privacies', PrivacyController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('termsAndConditions', TermsAndConditionController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("wishlists")->group(function(){
    Route::controller(WishlistController::class)->group(function () {
        Route::delete('/destroy',         'destroy')->name('wishlists.destroy');
    });
    Route::resource('/', WishlistController::class)->only('index', 'store');
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("views")->group(function(){
    Route::controller(ViewController::class)->group(function () {
        Route::get('/whats_app_click/{id}', 'whatsAppClick')->name('wishlists.destroy');
    });
    Route::resource('/', ViewController::class)->except('create', 'edit');
});
//______________________________________________________________________________________________________________________

