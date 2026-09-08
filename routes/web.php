<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ConsolidatedController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\LockerController;
use App\Http\Controllers\Admin\PickupController;
use App\Http\Controllers\Admin\RecipientController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/track', [TrackController::class, 'index'])->name('track');
Route::post('/track', [TrackController::class, 'lookup'])->name('track.lookup');
Route::get('/track/{tracking_number}', [TrackController::class, 'show'])->name('track.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');
Route::get('/support', [PageController::class, 'support'])->name('support');
Route::post('/support', [PageController::class, 'supportStore'])->name('support.store');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/cookies', [PageController::class, 'cookies'])->name('cookies');

Route::redirect('/admin/login', '/login');

Route::get('/chat/messages', [ChatController::class, 'messages'])->name('chat.messages');
Route::post('/chat/messages', [ChatController::class, 'store'])->name('chat.store');
Route::get('/chat/unread', [ChatController::class, 'unread'])->name('chat.unread');
Route::post('/chat/subscribe', [ChatController::class, 'subscribe'])->name('chat.subscribe');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('shipments', ShipmentController::class);
    Route::get('shipments/{shipment}/invoice', [ShipmentController::class, 'invoice'])->name('shipments.invoice');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('support-tickets', SupportTicketController::class);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    Route::get('chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('chat/count', [AdminChatController::class, 'count'])->name('chat.count');
    Route::post('chat/subscribe', [AdminChatController::class, 'subscribe'])->name('chat.subscribe');
    Route::get('chat/{room}', [AdminChatController::class, 'show'])->name('chat.show');
    Route::post('chat/{room}/reply', [AdminChatController::class, 'reply'])->name('chat.reply');

    Route::get('locker-packages', [LockerController::class, 'index'])->name('locker.index');
    Route::get('pickups', [PickupController::class, 'index'])->name('pickups.index');
    Route::patch('pickups/{shipment}/status', [PickupController::class, 'updateStatus'])->name('pickups.update-status');
    Route::get('consolidated', [ConsolidatedController::class, 'index'])->name('consolidated.index');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('recipients', [RecipientController::class, 'index'])->name('recipients.index');
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
