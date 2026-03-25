<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\FranchisePartnerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\AdminFranchiseController;
use App\Http\Controllers\Admin\StoryReviewController;
use App\Http\Controllers\Admin\AdminSettingsController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/franchise', [FranchiseController::class, 'index'])->name('franchise');

Route::post('/franchise/partner', [FranchisePartnerController::class, 'store'])->name('franchise.partner.store');

Route::get('/order', [OrderController::class, 'index'])->name('order');

Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation');

Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

Route::post('/reservation/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservation.checkAvailability');

Route::get('/get-payment/{franchise_id}', [ReservationController::class, 'getPayment']);


Route::get('/reservation/tables/{franchise}', [ReservationController::class, 'getTables']);

Route::prefix('admin')->group(function () 
{

    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');

    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

    Route::get('/forgot-password', [AuthController::class,'forgotPasswordForm'])
        ->name('admin.forgot.password');

    Route::post('/send-reset-link', [AuthController::class,'sendResetLink'])
        ->name('admin.send.reset.link');

    Route::get('/reset-password/{token}', [AuthController::class,'resetPasswordForm'])
        ->name('admin.reset.password');

    Route::post('/update-password', [AuthController::class,'updatePassword'])
        ->name('admin.update.password');

    Route::middleware('admin.auth')->group(function () 
    {

            // 🔥 DEFAULT → RESERVATIONS
            Route::get('/', function () {return redirect()->route('reservations.index');});

            Route::post('/logout',[AuthController::class, 'logout'])->name('admin.logout');


            /*
            |--------------------------------------------------------------------------
            | ADMIN MODULES (Single Page Structure)
            |--------------------------------------------------------------------------
            */

            Route::resource('reservations', AdminReservationController::class);
            Route::resource('menu', AdminMenuController::class);

            Route::post('/menu/category/rename',
                [AdminMenuController::class, 'renameCategory'])
                ->name('menu.category.rename');

            Route::post('/menu/category/delete',
                [AdminMenuController::class, 'deleteCategory'])
                ->name('menu.category.delete');
           
            
            /*
            |--------------------------------------------------------------------------
            | TABLE MANAGEMENT (CUSTOM ROUTES FIRST)
            |--------------------------------------------------------------------------
            */
            
            Route::post('tables/bulk',
                [AdminTableController::class, 'bulk'])
                ->name('admin.tables.bulk');

            Route::patch('tables/{table}/status',
                [AdminTableController::class, 'status'])
                ->name('admin.tables.status');

            Route::patch('tables/{table}/assign',
                [AdminTableController::class, 'assign'])
                ->name('admin.tables.assign');

            Route::get('tables/layout',
                [AdminTableController::class, 'layout'])
                ->name('admin.tables.layout');
            Route::resource('tables', AdminTableController::class)->names('admin.tables');

            Route::post('/admin/tables/toggle-slot/{table}', [AdminTableController::class, 'toggleSlot'])
                ->name('admin.tables.toggleSlot');

            /*
            |--------------------------------------------------------------------------
            | RESERVATION MANAGEMENT (CUSTOM ROUTES FIRST)
            |--------------------------------------------------------------------------
            */
            // Update reservation status
            Route::patch('/reservation/{reservation}/status',
                [AdminReservationController::class, 'updateReservationStatus'])
                ->name('admin.reservation.status');

            Route::patch('/reservation/payment-toggle/{reservation}',
                [AdminReservationController::class, 'togglePayment'])
                ->name('admin.reservation.togglePayment');

            // Delete reservation
            Route::delete('/reservation/{reservation}',
                [AdminReservationController::class, 'deleteReservation'])
                ->name('admin.reservation.delete');

            // (Optional) Export PDF
            Route::get('/reservation/export/pdf',
                [AdminReservationController::class, 'exportReservationPdf'])
                ->name('admin.reservation.export.pdf');

            // (Optional) Export Excel
            Route::get('/reservation/export/excel',
                [AdminReservationController::class, 'exportReservationExcel'])
                ->name('admin.reservation.export.excel');

            Route::post('/reservation/store',
                [AdminReservationController::class, 'storeReservation'])
                ->name('admin.reservation.store');

            Route::post('/reservations/bulk', 
                [AdminReservationController::class, 'bulkUpdate'])
                ->name('admin.reservation.bulk');

            
            /*
            |--------------------------------------------------------------------------
            | ORDER MANAGEMENT 
            |--------------------------------------------------------------------------
            */
            Route::prefix('orders')->group(function () 
            {

                Route::get('/', [AdminOrderController::class, 'index'])
                    ->name('admin.orders.index');

                Route::get('/fetch', [AdminOrderController::class, 'fetch'])
                    ->name('admin.orders.fetch');

                Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])
                    ->name('admin.orders.status');

                Route::get('/export', [AdminOrderController::class, 'export'])
                    ->name('admin.orders.export');

                Route::patch('/bulk-status', [AdminOrderController::class, 'bulkUpdate'])
                    ->name('admin.orders.bulk');

            });

           /*
            |--------------------------------------------------------------------------
            | PAYMENT MANAGEMENT (CUSTOM ROUTES FIRST)
            |--------------------------------------------------------------------------
            */

            Route::post('/payment/store', [AdminPaymentController::class, 'store'])->name('admin.payment.store');
            
            Route::get('/payment', [AdminPaymentController::class, 'index'])
            ->name('admin.payment.index');

            
            /*
            |--------------------------------------------------------------------------
            | FRANCHISE MANAGEMENT (CUSTOM ROUTES FIRST)
            |--------------------------------------------------------------------------
            */

            Route::resource('franchise', AdminFranchiseController::class)
            ->names('admin.franchise');

            Route::get('franchise', [AdminFranchiseController::class, 'index'])
            ->name('admin.franchise.index');

            Route::delete(
                'franchise/partner/{id}',
                [AdminFranchiseController::class,'deletePartner']
            )->name('admin.franchise.partner.delete');

            Route::post('/franchise/bulk', [AdminFranchiseController::class, 'bulk'])
             ->name('admin.franchise.bulk');

            /*
            |--------------------------------------------------------------------------
            | STORY AND REVIEW MANAGEMENT (CUSTOM ROUTES FIRST)
            |--------------------------------------------------------------------------
            */

            Route::get('story-review', [StoryReviewController::class, 'index'])
                ->name('admin.story-review.index');

            Route::post('stories/store', [StoryReviewController::class, 'storeStory'])
                ->name('admin.stories.store');

            Route::post('reviews/store', [StoryReviewController::class, 'storeReview'])
                ->name('admin.reviews.store'); 

            Route::delete('stories/bulk-delete', [StoryReviewController::class,'bulkDeleteStories'])
                ->name('admin.stories.bulkDelete');

            Route::delete('reviews/bulk-delete', [StoryReviewController::class,'bulkDeleteReviews'])
                ->name('admin.reviews.bulkDelete');
            
            Route::delete('/stories/{id}', [StoryReviewController::class,'deleteStory'])
                ->name('admin.stories.delete');

            Route::delete('/reviews/{id}', [StoryReviewController::class,'deleteReview'])
                ->name('admin.reviews.delete');

           
            /*
            |--------------------------------------------------------------------------
            | SETTING MANAGEMENT (CUSTOM ROUTES FIRST)
            |--------------------------------------------------------------------------
            */

            Route::resource('settings', AdminSettingsController::class)->names('admin.settings');  
            
            Route::get('settings', [AdminSettingsController::class,'index'])->name('settings.index');

            Route::post('settings', [AdminSettingsController::class,'update'])->name('settings.update');

            Route::post('/change-password', [AdminSettingsController::class,'changePassword'])
                ->name('admin.change.password');

            Route::post('/change-email', [AdminSettingsController::class,'changeEmail'])
                ->name('admin.change.email');

            /*
            |--------------------------------------------------------------------------
            | TEAM MANAGEMENT (CUSTOM ROUTES FIRST)
            |--------------------------------------------------------------------------
            */
            Route::resource('team', AdminTeamController::class)
                ->names('admin.team'); 
            Route::get('/team', [AdminTeamController::class,'index'])->name('team.index');

            Route::post('/team/store', [AdminTeamController::class,'store'])->name('team.store');

            Route::post('/team/update/{id}', [AdminTeamController::class,'update'])->name('team.update');

            Route::delete('/team/delete/{id}', [AdminTeamController::class,'delete'])->name('team.delete');

            Route::post('/team/bulk',[AdminTeamController::class,'bulkAction'])->name('admin.team.bulk');
        });
    
    

});





