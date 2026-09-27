<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\DecorationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ServiceAreaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VideoController;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCancellationRequestController;
use App\Http\Controllers\Admin\AdminRescheduleRequestController;
use App\Http\Controllers\Admin\AdminDecorationController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminAddonController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminOfferController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminServiceAreaController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminQuoteController;
use App\Http\Controllers\Admin\AdminContactMessageController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminQuotationController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\CustomerQuotationController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CustomerInvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aditya Utsav (Phase 1 to 6 Platform)
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/decorations', [DecorationController::class, 'index'])->name('decorations.index');
Route::get('/decorations/{category}', [DecorationController::class, 'category'])->name('decorations.category');
Route::get('/decoration/{slug}', [DecorationController::class, 'show'])->name('decorations.show');

// Phase 3: Booking Request & Availability Workflow
Route::match(['get', 'post'], '/booking/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.checkAvailability');
Route::match(['get', 'post'], '/booking/availability', [BookingController::class, 'availabilityResults'])->name('booking.availability');
Route::match(['get', 'post'], '/booking/availability-results', [BookingController::class, 'availabilityResults'])->name('booking.availabilityResults');
Route::get('/booking/decoration/{decoration}', [BookingController::class, 'create'])->name('booking.createDecoration');
Route::get('/booking/package/{package}', [BookingController::class, 'createPackage'])->name('booking.createPackage');
Route::get('/booking/confirmation/{reference}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/{decoration}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/receipts/{receipt_number}', [CustomerPaymentController::class, 'receiptByNumber'])->name('receipts.public.show');

// Phase 5 & 8: Packages, Offers, Gallery, Reels & Videos, Custom Quote, Pages & Policies
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/package/{slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
Route::get('/offer/{slug}', [OfferController::class, 'show'])->name('offers.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Phase 8: Reels & Videos
Route::get('/reels', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos', [VideoController::class, 'index'])->name('videos.list');
Route::get('/video/{slug}', [VideoController::class, 'show'])->name('videos.show');

Route::get('/quote', [QuoteController::class, 'create'])->name('quote');
Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.store');
Route::get('/service-areas', [ServiceAreaController::class, 'index'])->name('service-areas.index');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/cancellation-policy', [PageController::class, 'cancellationPolicy'])->name('cancellation-policy');
Route::get('/booking-guide', [PageController::class, 'bookingGuide'])->name('booking-guide');


// ==================== AUTHENTICATION ROUTES ====================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== CUSTOMER ACCOUNT ROUTES (AUTH PROTECTED) ====================
Route::middleware('auth')->group(function () {
    // Dashboard & Profile
    Route::get('/account', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/account/dashboard', function() { return redirect()->route('account.dashboard'); });
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::patch('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/password', [AccountController::class, 'password'])->name('account.password');
    Route::patch('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    // Customer Bookings Management
    Route::get('/account/bookings', [CustomerBookingController::class, 'index'])->name('account.bookings');
    Route::get('/account/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('account.bookings.show');
    Route::post('/account/bookings/{booking}/cancel-request', [CustomerBookingController::class, 'cancelRequest'])->name('account.bookings.cancel');
    Route::post('/account/bookings/{booking}/reschedule-request', [CustomerBookingController::class, 'rescheduleRequest'])->name('account.bookings.reschedule');

    // Phase 7: Customer Quotations, Payments & Invoices
    Route::get('/account/quotations', [CustomerQuotationController::class, 'index'])->name('account.quotations.index');
    Route::get('/account/quotations/{id}', [CustomerQuotationController::class, 'show'])->name('account.quotations.show');
    Route::post('/account/quotations/{id}/accept', [CustomerQuotationController::class, 'accept'])->name('account.quotations.accept');
    Route::post('/account/quotations/{id}/reject', [CustomerQuotationController::class, 'reject'])->name('account.quotations.reject');

    Route::get('/account/payments', [CustomerPaymentController::class, 'index'])->name('account.payments.index');
    Route::post('/account/payments', [CustomerPaymentController::class, 'store'])->name('account.payments.store');
    Route::get('/account/payments/{id}/receipt', [CustomerPaymentController::class, 'receipt'])->name('account.payments.receipt');
    Route::get('/account/receipts/{receipt_number}', [CustomerPaymentController::class, 'receiptByNumber'])->name('account.receipts.show');

    Route::get('/account/invoices', [CustomerInvoiceController::class, 'index'])->name('account.invoices.index');
    Route::get('/account/invoices/{id}', [CustomerInvoiceController::class, 'show'])->name('account.invoices.show');
});

// ==================== ADMIN PANEL ROUTES (PHASE 6 & 7) ====================
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Session (Accessible to all active staff/admin roles)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // ==================== BOOKING OPERATIONS GROUP (Super Admin, Admin, Booking Manager) ====================
    Route::middleware('admin:bookings')->group(function () {
        // Bookings Management
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{id}/accept', [AdminBookingController::class, 'acceptBooking'])->name('bookings.accept');
        Route::post('/bookings/{id}/reject', [AdminBookingController::class, 'rejectBooking'])->name('bookings.reject');
        Route::patch('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');

        // Phase 7: Quotation Workflow
        Route::get('/quotations', [AdminQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/create', [AdminQuotationController::class, 'create'])->name('quotations.create');
        Route::post('/quotations', [AdminQuotationController::class, 'store'])->name('quotations.store');
        Route::get('/quotations/{id}', [AdminQuotationController::class, 'show'])->name('quotations.show');
        Route::get('/quotations/{id}/edit', [AdminQuotationController::class, 'edit'])->name('quotations.edit');
        Route::put('/quotations/{id}', [AdminQuotationController::class, 'update'])->name('quotations.update');
        Route::post('/quotations/{id}/send', [AdminQuotationController::class, 'send'])->name('quotations.send');
        Route::post('/quotations/{id}/cancel', [AdminQuotationController::class, 'cancel'])->name('quotations.cancel');

        // Phase 7: Payment Tracking & Verification
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/requests', [AdminPaymentController::class, 'requests'])->name('payments.requests');
        Route::get('/payments/create', [AdminPaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [AdminPaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{id}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{id}/accept', [AdminPaymentController::class, 'acceptPayment'])->name('payments.accept');
        Route::post('/payments/{id}/reject', [AdminPaymentController::class, 'rejectPayment'])->name('payments.reject');
        Route::get('/payments/{id}/receipt', [AdminPaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('/receipts/{receipt_number}', [AdminPaymentController::class, 'receiptByNumber'])->name('receipts.show');

        // Phase 7: Invoices & Receipts
        Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');
        Route::post('/invoices/generate/{booking_id}', [AdminInvoiceController::class, 'generate'])->name('invoices.generate');
        Route::post('/invoices/create-from-booking/{booking_id}', [AdminInvoiceController::class, 'generate'])->name('invoices.createFromBooking');
        Route::get('/invoices/{id}', [AdminInvoiceController::class, 'show'])->name('invoices.show');

        // Phase 7: Event Calendar & Availability
        Route::get('/calendar', [AdminCalendarController::class, 'index'])->name('calendar.index');

        // Cancellation & Reschedule Requests
        Route::get('/cancellation-requests', [AdminCancellationRequestController::class, 'index'])->name('cancellation-requests.index');
        Route::post('/cancellation-requests/{id}/approve', [AdminCancellationRequestController::class, 'approve'])->name('cancellation-requests.approve');
        Route::post('/cancellation-requests/{id}/reject', [AdminCancellationRequestController::class, 'reject'])->name('cancellation-requests.reject');

        Route::get('/reschedule-requests', [AdminRescheduleRequestController::class, 'index'])->name('reschedule-requests.index');
        Route::post('/reschedule-requests/{id}/approve', [AdminRescheduleRequestController::class, 'approve'])->name('reschedule-requests.approve');
        Route::post('/reschedule-requests/{id}/reject', [AdminRescheduleRequestController::class, 'reject'])->name('reschedule-requests.reject');

        // Customer Accounts
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');
        Route::post('/customers/{id}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggleStatus');

        // Quotes & Inquiries
        Route::get('/quotes', [AdminQuoteController::class, 'index'])->name('quotes.index');
        Route::get('/quotes/{id}', [AdminQuoteController::class, 'show'])->name('quotes.show');
        Route::patch('/quotes/{id}/status', [AdminQuoteController::class, 'updateStatus'])->name('quotes.updateStatus');

        // Messages
        Route::get('/messages', [AdminContactMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [AdminContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('/messages/{id}/status', [AdminContactMessageController::class, 'updateStatus'])->name('messages.updateStatus');
        Route::delete('/messages/{id}', [AdminContactMessageController::class, 'destroy'])->name('messages.destroy');

        // Phase 10: Reports & Native CSV Exports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/bookings', [AdminReportController::class, 'exportBookings'])->name('reports.export.bookings');
        Route::get('/reports/export/payments', [AdminReportController::class, 'exportPayments'])->name('reports.export.payments');
        Route::get('/reports/export/quotations', [AdminReportController::class, 'exportQuotations'])->name('reports.export.quotations');
    });

    // ==================== CONTENT & CATALOG GROUP (Super Admin, Admin, Content Manager) ====================
    Route::middleware('admin:content')->group(function () {
        Route::resource('decorations', AdminDecorationController::class);
        Route::post('/decorations/{id}/images', [AdminDecorationController::class, 'uploadImage'])->name('decorations.images.upload');
        Route::delete('/decorations/images/{id}', [AdminDecorationController::class, 'deleteImage'])->name('decorations.images.delete');
        
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('addons', AdminAddonController::class);
        Route::resource('packages', AdminPackageController::class);
        Route::resource('offers', AdminOfferController::class);
        Route::resource('gallery', AdminGalleryController::class);
        Route::resource('videos', AdminVideoController::class);
        Route::resource('faqs', AdminFaqController::class);
        Route::resource('service-areas', AdminServiceAreaController::class);

        // Media Library
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::delete('/media', [AdminMediaController::class, 'destroy'])->name('media.destroy');
    });

    // ==================== SUPER ADMIN ONLY: Staff Management, Activity Logs & Site Settings ====================
    Route::middleware('admin:super_admin')->group(function () {
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::resource('users', AdminUserController::class);
        Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    });
});

