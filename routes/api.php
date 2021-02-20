<?php

use App\Http\Controllers\PassportAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Symfony\Component\HttpFoundation\Response;

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



Route::post('/services/transactionlogs/', [\App\Http\Controllers\TransactionLogController::class, 'store'])->name('createTransactionlog')
//    ->middleware('auth.basic.once')
;
Route::patch('/services/transactionlogs/{reference}/', [\App\Http\Controllers\TransactionLogController::class, 'update'])->name('updateTransactionlog')
//    ->middleware('auth.basic.once')
;
Route::delete('/services/transactionlogs/{reference}/', [\App\Http\Controllers\TransactionLogController::class, 'destroy'])->name('deleteTransactionlog')
//    ->middleware('auth.basic.once')
;


Route::get('/services/transactionlogs/', function (Request $request) {
    return \App\Http\Resources\TransactionLogResource::collection(\App\Models\TransactionLog::all());
})->name('allTransactionlogs');
Route::get('/services/transactionlogs/search/', function (Request $request) {
    $q = $request->get('q');
    // Variable to check
//    $ifEmail = "john.doe@example.com";

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
})->name('allTransactionlogs');
//\Illuminate\Support\Facades\Config::get('constants.pagination.per_page')
Route::get('/services/transactionlogs/categories/{category}', function ($category) {
    $logs = \App\Models\TransactionLog::where('service_category_raw', $category)->paginate(15);
    return \App\Http\Resources\TransactionLogResource::collection($logs);

})->name('transactionlogsByCategories');

Route::get('/services/transactionlogs/{reference}', function ($reference) {
    $log = \App\Models\TransactionLog::where('reference', $reference)->firstOrFail();
    return new \App\Http\Resources\TransactionLogResource($log);
})->name('aTransactionLog');


Route::post('/contacts/', [\App\Http\Controllers\ContactController::class, 'store'])->name('createContact');
Route::patch('/contacts/{id}/', [\App\Http\Controllers\ContactController::class, 'update'])->name('updateContact');
Route::delete('/contacts/{id}/', [\App\Http\Controllers\ContactController::class, 'destroy'])->name('deleteContact');
Route::get('/contacts/', function (Request $request) {
    return \App\Http\Resources\ContactResource::collection(\App\Models\Contact::all());
})->name('allContacts');
Route::get('/contacts/categories/{category}', function ($category) {
    $logs = \App\Models\Contact::where('subject', $category)->paginate(15);
    return \App\Http\Resources\ContactResource::collection($logs);

})->name('contactsByCategories');

Route::get('/contacts/{id}', function ($id) {
    $log = \App\Models\Contact::whereId($id)->firstOrFail();
    return new \App\Http\Resources\ContactResource($log);
})->name('aContact');
