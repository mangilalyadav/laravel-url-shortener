<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionGroupController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GenerateUrlController;




Route::get('/', function () {
    return to_route('admin.dashboard');
});

//public accessablr short urls
Route::get('/s/{code}', [GenerateUrlController::class, 'resolve'])->name('short-url.resolve');

Route::prefix('admin')->name('admin.')->group(function () {

    // Authentication Routes
    Route::prefix('/auth')->name('auth.')->middleware('guest')->group(function () {

        Route::get('/', function () {
            return to_route('admin.auth.login');
        });

        // Login
        Route::get('/login', [AuthenticateController::class, 'login'])->name('login');
        Route::post('/login', [AuthenticateController::class, 'postLogin'])->name('postLogin');

    });

    // Authenticated Routes
    Route::middleware('auth')->group(function () {

        // Default redirect to dashboard
        Route::get('/', function(){
            return to_route('admin.dashboard');
        });

        // Logout
        Route::post('/logout', [AuthenticateController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // Profile
        Route::get('/edit-profile', [AuthenticateController::class, 'editProfile'])->name('editProfile');
        Route::put('/{user}/update-profile', [AuthenticateController::class, 'storeUpdateProfile'])->name('updateProfile');



        // Client(Company) Management
        Route::delete('/clients/bulk-destroy', [ClientController::class, 'bulkDestroy'])->name('clients.bulkDestroy');
        Route::put('/clients/update-status', [ClientController::class, 'updateSelectedStatus'])->name('clients.updateStatus');
        Route::resource('clients', ClientController::class);


        // Team  Management
        Route::delete('/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulkDestroy');
        Route::put('/users/update-status', [UserController::class, 'updateSelectedStatus'])->name('users.updateStatus');
        Route::resource('users', UserController::class);


         // Generated URls Management
         Route::get('/generated_urls/download-pdf',[GenerateUrlController::class, 'downloadPdf'])->name('generated_urls.downloadPdf');
        Route::delete('/generated_urls/bulk-destroy', [GenerateUrlController::class, 'bulkDestroy'])->name('generated_urls.bulkDestroy');
        Route::put('/generated_urls/update-status', [GenerateUrlController::class, 'updateSelectedStatus'])->name('generated_urls.updateStatus');
        Route::resource('generated_urls', GenerateUrlController::class);


        // Role & Permission
        Route::get('/roles-permissions', [RoleController::class, 'rolePermission'])->name('rolePermission');
        // Route::get('/get-permission-groups', [RoleController::class, 'getPermissionGroups'])->name('getPermissionGroups');

        // Role Management
        Route::delete('/roles/bulk-destroy', [RoleController::class, 'bulkDestroy'])->name('roles.bulkDestroy');
        Route::resource('roles', RoleController::class);

        // Permission Group Management
        Route::delete('/permission-groups/bulk-destroy', [PermissionGroupController::class, 'bulkDestroy'])->name('permission_groups.bulkDestroy');
        Route::resource('permission-groups', PermissionGroupController::class)->names([
            'index' => 'permission_groups.index',
            'create' => 'permission_groups.create',
            'store' => 'permission_groups.store',
            'edit' => 'permission_groups.edit',
            'update' => 'permission_groups.update',
            'destroy' => 'permission_groups.destroy',
        ]);

        // Permission Management
        Route::delete('/permissions/bulk-destroy', [PermissionController::class, 'bulkDestroy'])->name('permissions.bulkDestroy');
        Route::resource('permissions', PermissionController::class);

    });

});

