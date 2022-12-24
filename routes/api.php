<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StatuController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\TraderController;
use App\Http\Controllers\Api\VolumeController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ImporterController;
use App\Http\Controllers\Api\ItemUnitController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\ItemImageController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\ManufactoryController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\EskanCompanyController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\AdvertisementImageController;
use App\Http\Controllers\Api\Auth\LoginUserController;
use App\Http\Controllers\Api\Auth\LoginClientController;
use App\Http\Controllers\Api\Auth\LoginTraderController;
use App\Http\Controllers\Api\Auth\RegisterUserController;
use App\Http\Controllers\Api\Auth\RegisterClientController;
use App\Http\Controllers\Api\Auth\RegisterTraderController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\LevelImageController;
use App\Http\Controllers\Api\ProjectImageController;
use App\Http\Controllers\Api\TraderImageController;
use App\Http\Controllers\Api\UnitImageController;
use App\Http\Controllers\Api\UserController;

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

// Route::get('/', function () {
//     return redirect('/api/projects');
// });

// Route::get('/', (function() {
//     return auth()->user()
//         ? app()->make(\App\Http\Controllers\HomeController::class)->index()
//         : app()->make(\App\Http\Controllers\BlogController::class)->index();
// }));

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('items/', (function() {
//         return auth()->user()
//             ? app()->make(\App\Http\Controllers\ItemController::class)->index()
//             : app()->make(\App\Http\Controllers\ItemController::class)->random();
//     }));
// // });
////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
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
        Route::post("users",  [RegisterUserController::class, "register"])->name("users.register");
    });
    Route::post("traders",[RegisterTraderController::class, "register"])->name("traders.register");
    Route::post("clients",[RegisterClientController::class, "register"])->name("clients.register");
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
    Route::prefix("traders")->group(function(){
        Route::controller(TraderController::class)->group(function () {
            Route::get('/trader', 'trader')->name('traders.trader');
        });
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

//-----------------------------------------------------------------------------------------------------------
Route::resource('itemUnits', ItemUnitController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('eskanCompanies', EskanCompanyController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
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
        Route::put('/status/{unit}',       'status')->name('units.status');
        Route::post('/activities', 'activities')->name('units.activities');
        Route::put('/finance/{unit}',    'finance')->name('units.finance');
        Route::put('/deposit/{unit}',    'deposit')->name('units.deposit');
        Route::put('/rents/{unit}',          'rents')->name('units.rents');
        Route::put('/discount/{unit}', 'discount')->name('units.discount');
    });
});
Route::resource('units', UnitController::class)->except('create', 'edit');

//______________________________________________________________________________________________________________________
// $middleware = [];
// // $authorizationHeader = \request()->header('Authorization');
// if (\request()->header('Authorization'))
//    $middleware = array_merge($middleware, ['auth:sanctum']);
// Route::group(['prefix' => 'v1', 'namespace' => 'Api', 'middleware' => $middleware], function () {
//     Route::get('/items', (function() {
//         return auth()->user()
//             ? app()->make(ItemController::class)->index()
//             : app()->make(ItemController::class)->random();
//     }));
// });
    // Route::prefix("items")->group(function(){
    //     Route::controller(ItemController::class)->group(function () {
    //         Route::get('/',             'index')->name('items.index');
    //         Route::get('/latest',      'latest')->name('items.latest');
    //         Route::get('/{item}',        'show')->name('items.show');
    //         Route::post('/{item}', 'updateItem')->name('items.updateItem');
    //         Route::get('/random',      'random')->name('items.random');
    //     });
    // });

//-----------------------------------------------------------------------------------------------------------
Route::prefix("items")->group(function(){
    Route::controller(ItemController::class)->group(function () {
        Route::get('/',             'index')->name('items.index');
        Route::get('/latest',      'latest')->name('items.latest');
        Route::get('/{item}',        'show')->name('items.show');
        Route::post('/{item}', 'updateItem')->name('items.updateItem');
        Route::get('/random',      'random')->name('items.random');
    });
});
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('traders', TraderController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::prefix("itemImages")->group(function(){
    Route::controller(ItemImageController::class)->group(function () {
        Route::post('/store2',             'store2');
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
Route::resource('advertisements', AdvertisementController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

//-----------------------------------------------------------------------------------------------------------
Route::resource('volumes', VolumeController::class)->except('create', 'edit');
//______________________________________________________________________________________________________________________

