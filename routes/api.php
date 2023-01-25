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
Route::get('/phpinfo', function() {
    return phpinfo();
});
Route::get('call-helper', function(){

    $mdY = convertYmdToMdy('2022-02-12');
    var_dump("Converted into 'MDY': " . $mdY);

    $ymd = convertMdyToYmd('02-12-2022');
    var_dump("Converted into 'YMD': " . $ymd);
});
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
            Route::get('/trader', 'trader')->name('newTraders.trader');
        });
        Route::resource('/', TraderController::class)->only('destroy', 'update', 'create', 'edit');
    });
    //______________________________________________________________________________________________________________________

    //-----------------------------------------------------------------------------------------------------------
    Route::prefix("items")->group(function(){
        Route::controller(ItemController::class)->group(function () {
            Route::put('/approved/{item}',     'approved')->name('items.approved');
        });
    });
    Route::resource('items', ItemController::class)->except('index', 'show', 'create', 'edit');
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
    Route::prefix("wishlists")->group(function(){
        Route::resource('/', WishlistController::class)->only('index', 'store');
            Route::controller(WishlistController::class)->group(function () {
                Route::delete('/destroy',         'destroy')->name('wishlists.destroy');
            });
        });
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

Route::resource('newTraders', TraderController::class)->only('index', 'show');
//-----------------------------------------------------------------------------------------------------------
Route::resource('traders', TraderController::class)->only('index', 'show', 'update', 'store');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('itemUnits', ItemUnitController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("mainProjects")->group(function(){
    Route::controller(MainProjectController::class)->group(function () {
        Route::get('/offers',         'offers')->name('mainProjects.offers');
        Route::get('/main_project_with_projects_with_units/{mainProject}', 'mainProjectWithProjectsWithUnits');
    });
});
Route::resource('mainProjects', MainProjectController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("projects")->group(function(){
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/streetsOffers', 'streetsOffers');
        Route::get('/project_with_out_levels/{project}', 'ProjectWithOutLevels');
    });
});
Route::resource('projects', ProjectController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("levels")->group(function(){
    Route::controller(LevelController::class)->group(function () {
        Route::get('/latest',         'latest')->name('levels.latest');
        Route::get('/client/{level}', 'client')->name('levels.client');
    });
});
Route::resource('levels', LevelController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("units")->group(function(){
    Route::controller(UnitController::class)->group(function () {
        Route::get('/latest',              'latest')->name('units.latest');
        Route::get('/show_online/{unit}', 'showOnline');
        Route::get('/toggle_update/{id}/{booleanName}', 'toggleUpdate');
        Route::get('/toggle_unit_online/{unit}', 'toggleUnitOnline');
        Route::get('/toggle_unit_offers/{unit}', 'toggleUnitOffers');
        Route::get('/units_for_all_conditions',  'unitsForAllConditions');
        Route::get('/unitOffers/{unit}', 'unitOffers')->name('items.unitOffers');
        Route::put('/categories/{unit}', 'categories');
    });
});
Route::resource('units', UnitController::class)->except('create', 'edit');

//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("items")->group(function(){
    Route::controller(ItemController::class)->group(function () {
        Route::get('/latest',             'latest');
        Route::get('/random',             'random');
        Route::get('/items_for_all_conditions', 'itemsForAllConditions');
        Route::get('/toggle_flash_sales/{item}',    'toggleFlashSales');
        Route::get('/flash_sales',    'flashSales');
        Route::get('/streetOffers/{id}', 'streetOffers');
        Route::get('/offer_items_of_categories_of_project/{project_id}/{category_id}', 'offerItemsOfCategoriesOfProject');
        Route::get('/',                    'index');
        Route::get('/{item}',               'show');
    });
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
Route::resource('status', StatuController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('sites', SiteController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('activities', ActivityController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories_of_projects/{project_id}', 'categoriesOfProject');
});
Route::resource('categories', CategoryController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('colors', ColorController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('sizes', SizeController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('stocks', StockController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('manufactories', ManufactoryController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('companies', CompanyController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('importers', ImporterController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::controller(AdvertisementController::class)->group(function () {
    Route::get('advertisements/advertisements_of_project/{project_id}', 'advertisementsOfProject');
    Route::get('advertisements/grade', 'grade');
});
Route::resource('advertisements', AdvertisementController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('volumes', VolumeController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________
