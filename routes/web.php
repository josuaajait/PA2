<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Branding\HomeController;
use App\Http\Controllers\Branding\AboutController;
use App\Http\Controllers\Branding\MenuController;
use App\Http\Controllers\Branding\PoolController;
use App\Http\Controllers\Branding\ContactController;
use App\Http\Controllers\Branding\PromoController;
use App\Http\Controllers\Branding\TestimonialController;
use App\Http\Controllers\Branding\GalleryController as PublicGalleryController;
use App\Http\Controllers\Reservation\TableReservationController;
use App\Http\Controllers\Reservation\TicketController;
use App\Http\Controllers\Reservation\PaymentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuManagementController;
use App\Http\Controllers\Admin\ReservationManagementController;
use App\Http\Controllers\Admin\TicketManagementController;
use App\Http\Controllers\Admin\TestimonialManagementController;
use App\Http\Controllers\Admin\PromoManagementController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ReservationController as CustomerReservationController;
use App\Http\Controllers\Customer\TicketController as CustomerTicketController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Auth\GmailAuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Public - Guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Branding Module - Public Routes (Semua orang bisa akses)
|--------------------------------------------------------------------------
*/
Route::name('branding.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/menu/category/{category}', [MenuController::class, 'category'])->name('menu.category');
    Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');
    Route::get('/menu/search', [MenuController::class, 'search'])->name('menu.search');
    Route::get('/pool', [PoolController::class, 'index'])->name('pool');
    Route::get('/pool/check-capacity', [PoolController::class, 'checkCapacity'])->name('pool.capacity');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact/send-message', [ContactController::class, 'sendMessage'])->name('contact.send');
    Route::get('/promos', [PromoController::class, 'index'])->name('promos');
    Route::get('/promos/{slug}', [PromoController::class, 'detail'])->name('promos.detail');
    Route::get('/events', [PromoController::class, 'events'])->name('events');
    Route::get('/events/{slug}', [PromoController::class, 'eventDetail'])->name('events.detail');
    
    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials/store', [TestimonialController::class, 'store'])
    ->name('testimonials.store')
    ->middleware('throttle:3,1440'); // Maksimal 3 testimoni per IP per hari
    Route::get('/testimonials/{testimonial}', [TestimonialController::class, 'show'])->name('testimonials.show');
    
    Route::get('/gallery', [PublicGalleryController::class, 'index'])->name('gallery');
    Route::get('/gallery/category/{category}', [PublicGalleryController::class, 'category'])->name('gallery.category');
    Route::get('/gallery/{gallery}', [PublicGalleryController::class, 'show'])->name('gallery.show');
});

/*
|--------------------------------------------------------------------------
| Reservation Module - Hanya User yang Login
|--------------------------------------------------------------------------
*/
Route::prefix('reservation')->name('reservation.')->middleware(['auth'])->group(function () {
    Route::get('/table', [TableReservationController::class, 'create'])->name('table');
    Route::post('/table/check-availability', [TableReservationController::class, 'checkAvailability'])->name('table.check');
    Route::post('/table/store', [TableReservationController::class, 'store'])->name('table.store');
    Route::get('/table/success/{booking_code}', [TableReservationController::class, 'success'])->name('table.success');
    Route::get('/table/view/{booking_code}', [TableReservationController::class, 'view'])->name('table.view');
    Route::post('/table/cancel/{booking_code}', [TableReservationController::class, 'cancel'])->name('table.cancel');
    
    Route::get('/ticket', [TicketController::class, 'create'])->name('ticket');
    Route::post('/ticket/calculate', [TicketController::class, 'calculate'])->name('ticket.calculate');
    Route::post('/ticket/store', [TicketController::class, 'store'])->name('ticket.store');
    Route::get('/ticket/success/{ticket_code}', [TicketController::class, 'success'])->name('ticket.success');
    Route::get('/ticket/view/{ticket_code}', [TicketController::class, 'view'])->name('ticket.view');
    
    Route::post('/payment/upload-proof', [PaymentController::class, 'uploadProof'])->name('payment.upload');
    Route::get('/payment/status/{booking_code}', [PaymentController::class, 'status'])->name('payment.status');
});

/*
|--------------------------------------------------------------------------
| Customer Module - Hanya User yang Login
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.change');
    
    Route::get('/reservations', [CustomerReservationController::class, 'index'])->name('reservations');
    Route::get('/reservations/{bookingCode}', [CustomerReservationController::class, 'show'])->name('reservation.show');
    Route::post('/reservations/{bookingCode}/cancel', [CustomerReservationController::class, 'cancel'])->name('reservation.cancel');
    
    Route::get('/tickets', [CustomerTicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/{ticketCode}', [CustomerTicketController::class, 'show'])->name('ticket.show');
});

/*
|--------------------------------------------------------------------------
| Admin Module - Hanya Admin/Staff
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (tanpa middleware auth)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Protected routes (dengan middleware auth dan admin)
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('menus', MenuManagementController::class);
        Route::post('/menus/{menu}/toggle-availability', [MenuManagementController::class, 'toggleAvailability'])->name('menus.toggle');
        Route::resource('promos', PromoManagementController::class);
        Route::get('/reservations', [ReservationManagementController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/{reservation}', [ReservationManagementController::class, 'show'])->name('reservations.show');
        Route::post('/reservations/{reservation}/confirm', [ReservationManagementController::class, 'confirm'])->name('reservations.confirm');
        Route::post('/reservations/{reservation}/cancel', [ReservationManagementController::class, 'cancel'])->name('reservations.cancel');
        Route::get('/reservations/export', [ReservationManagementController::class, 'export'])->name('reservations.export');
        Route::get('/tickets', [TicketManagementController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [TicketManagementController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/verify', [TicketManagementController::class, 'verify'])->name('tickets.verify');
        Route::get('/tickets/check-capacity', [TicketManagementController::class, 'checkCapacity'])->name('tickets.capacity');
        Route::get('/testimonials', [TestimonialManagementController::class, 'index'])->name('testimonials.index');
        Route::get('/testimonials/{id}', [TestimonialManagementController::class, 'show'])->name('testimonials.show');
        Route::post('/testimonials/{id}/approve', [TestimonialManagementController::class, 'approve'])->name('testimonials.approve');
        Route::post('/testimonials/{id}/reject', [TestimonialManagementController::class, 'reject'])->name('testimonials.reject');
        Route::post('/testimonials/{id}/toggle-featured', [TestimonialManagementController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');
        Route::delete('/testimonials/{id}', [TestimonialManagementController::class, 'destroy'])->name('testimonials.destroy');
        Route::post('/testimonials/bulk-approve', [TestimonialManagementController::class, 'bulkApprove'])->name('testimonials.bulk-approve');
        Route::post('/testimonials/bulk-delete', [TestimonialManagementController::class, 'bulkDelete'])->name('testimonials.bulk-delete');
        Route::get('/testimonials/export', [TestimonialManagementController::class, 'export'])->name('testimonials.export');
        Route::resource('galleries', AdminGalleryController::class);
        Route::post('/galleries/{gallery}/toggle-featured', [AdminGalleryController::class, 'toggleFeatured'])->name('galleries.toggle-featured');
        Route::post('/galleries/bulk-upload', [AdminGalleryController::class, 'bulkUpload'])->name('galleries.bulk-upload');
        Route::post('/galleries/update-sort-order', [AdminGalleryController::class, 'updateSortOrder'])->name('galleries.update-sort-order');
        
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/reservations', [ReportController::class, 'reservations'])->name('reports.reservations');
        Route::get('/reports/tickets', [ReportController::class, 'tickets'])->name('reports.tickets');
        Route::get('/reports/income', [ReportController::class, 'income'])->name('reports.income');
        Route::get('/reports/export-reservations', [ReportController::class, 'exportReservations'])->name('reports.export-reservations');
        Route::get('/reports/export-tickets', [ReportController::class, 'exportTickets'])->name('reports.export-tickets');
        Route::get('/reports/export-income', [ReportController::class, 'exportIncome'])->name('reports.export-income');
    });
});

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('branding.home');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link verifikasi baru telah dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Google OAuth Routes
Route::prefix('google')->name('google.')->group(function () {
    Route::get('/auth', [GmailAuthController::class, 'redirectToGoogle'])->name('auth');
    Route::get('/callback', [GmailAuthController::class, 'handleGoogleCallback'])->name('callback');
});

Route::get('/test-email', function () {
    $user = App\Models\User::where('email', 'your-email@gmail.com')->first();
    
    if (!$user) {
        return 'User tidak ditemukan';
    }
    
    try {
        $user->sendEmailVerificationNotification();
        return 'Email verifikasi telah dikirim ke ' . $user->email;
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});