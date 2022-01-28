<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\PassportAuthController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

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

Route::prefix('auth')->group(function () {
    Route::post('/register', [PassportAuthController::class, 'register']);
    Route::post('/login', [PassportAuthController::class, 'login']);
    Route::post('/client/login', [ClientController::class, 'login']);
    Route::middleware('auth:api')->delete('/logout', [PassportAuthController::class, 'logout']);
    Route::middleware('auth:api')->patch('/users/{id}', [PassportAuthController::class, 'updateUser']);
    Route::middleware('auth:api')->get('/me', function (Request $request) {
        if (\Illuminate\Support\Facades\Auth::check()) {
            return response()->json(['user' => new \App\Http\Resources\UserResource($request->user())], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
            ], Response::HTTP_UNAUTHORIZED);
        }
    });
    Route::middleware('auth:api')->patch('/me', [PassportAuthController::class, 'updateMe']);
    Route::get('/users', function (Request $request) {
        return \App\Http\Resources\UserResource::collection(\App\Models\User::all());
    });
});


Route::middleware([
    'auth:clientapi',
    'check_if_client'
])->prefix('/apppref')->group(function () {
    Route::get('/', [\App\Http\Controllers\AppPreferenceController::class, 'index'])->name('getAppPref');
    //TODO: require admin access to make cheanges to appwide prefrences.
    Route::patch('/{key}/', [\App\Http\Controllers\AppPreferenceController::class, 'update'])->name('updateAppPref');
    Route::get('/{key}', [\App\Http\Controllers\AppPreferenceController::class, 'show'])
        ->name('showAppPref')
        ->withoutMiddleware([
        'auth:clientapi',
        'check_if_client'
    ]);

});

Route::middleware([
    'auth:clientapi',
    'check_if_client'
])->prefix('services')->group(function () {
    Route::post('/transactionlogs/', [\App\Http\Controllers\TransactionLogController::class, 'store'])->name('createTransactionlog')//    ->middleware('auth.basic.once')
    ;
    Route::patch('/transactionlogs/{reference}/', [\App\Http\Controllers\TransactionLogController::class, 'update'])->name('updateTransactionlog')//    ->middleware('auth.basic.once')
    ;
    Route::delete('/transactionlogs/{reference}/', [\App\Http\Controllers\TransactionLogController::class, 'destroy'])->name('deleteTransactionlog')//    ->middleware('auth.basic.once')
    ;



    Route::get('/transactionlogs/', function (Request $request) {
        $logs = \App\Models\TransactionLog::latest()->get();
        return \App\Http\Resources\TransactionLogResource::collection($logs);
    })->name('allTransactionlogs');
    Route::get('/transactionlogs/search/', function (Request $request) {
        $q = $request->get('q');
        // Variable to check

    // Remove all illegal characters from email
        $ifEmail = filter_var($q, FILTER_SANITIZE_EMAIL);

    // Validate e-mail
        if (filter_var($ifEmail, FILTER_VALIDATE_EMAIL)) {
    //        echo("$ifEmail is a valid email address");
            $search = \App\Models\TransactionLog::where('email', 'LIKE', "%$q%")->get();
        } else {
    //        echo("$ifEmail is not a valid email address");
            $search = \App\Models\TransactionLog::where('reference', 'LIKE', "%$q%")->get();
        }
    //    $result = \App\Models\TransactionLog::all();
        $result = $search;
        return \App\Http\Resources\TransactionLogResource::collection($result);
    })->name('searchTransactionlogs');
    //\Illuminate\Support\Facades\Config::get('constants.pagination.per_page')
    Route::get('/transactionlogs/categories/{category}', function ($category) {
        $logs = \App\Models\TransactionLog::where('service_category_raw', $category)->latest()->paginate(15);
        return \App\Http\Resources\TransactionLogResource::collection($logs);
    })->name('transactionlogsByCategories');

    Route::get('/transactionlogs/{reference}', function ($reference) {
        try {
            $log = \App\Models\TransactionLog::where('reference', $reference)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'success' => false,
                'message' => "Transaction not found"
            ], Response::HTTP_NOT_FOUND);
        }
        return new \App\Http\Resources\TransactionLogResource($log);
    })->name('aTransactionLog');
    Route::post('/verify_account/', [\App\Http\Controllers\OrderController::class, 'verifyAccount']);//    ->name('buyService')->middleware('auth.basic.once')

})
//
;

Route::middleware([
    'auth:clientapi',
    'check_if_client'
])->prefix('/contacts')->group(function () {
    Route::post('/', [\App\Http\Controllers\ContactController::class, 'store'])->name('createContact');
    Route::patch('/{id}/', [\App\Http\Controllers\ContactController::class, 'update'])->name('updateContact');
    Route::delete('/{id}/', [\App\Http\Controllers\ContactController::class, 'destroy'])->name('deleteContact');
    Route::get('/', function (Request $request) {
        return \App\Http\Resources\ContactResource::collection(\App\Models\Contact::all());
    })->name('allContacts');
    Route::get('/categories/{category}', function ($category) {
        $logs = \App\Models\Contact::where('subject', $category)->paginate(15);
        return \App\Http\Resources\ContactResource::collection($logs);

    })->name('contactsByCategories');

    Route::get('/{id}', function ($id) {
        try {
            $log = \App\Models\Contact::whereId($id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'success' => false,
                'message' => "Contact not found"
            ], Response::HTTP_NOT_FOUND);
        }
        return new \App\Http\Resources\ContactResource($log);
    })->name('aContact');
});

Route::middleware([
    'auth:api,clientapi',
    'check_if_client'
])->prefix('/buy')->group(function () {
    Route::post('/airtime', [\App\Http\Controllers\OrderController::class, 'airtime'])->name('buyAirtime');
    Route::post('/databundle', [\App\Http\Controllers\OrderController::class, 'databundle'])->name('buyData');
    Route::post('/cabletv', [\App\Http\Controllers\OrderController::class, 'cabletv'])->name('buyCabletv');
    Route::post('/electricity', [\App\Http\Controllers\OrderController::class, 'electricity'])->name('buyElectricity');

});

Route::get('/balance', [\App\Http\Controllers\OrderController::class, 'balance'])
    ->name('checkBalance')->middleware([
        'auth:api,clientapi',
        'check_if_client'
    ]);
