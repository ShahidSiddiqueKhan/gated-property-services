<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\FeeTierController;
use App\Http\Controllers\Admin\LeaseController;
use App\Http\Controllers\Admin\MaintenanceController as AdminMaintenanceController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\PropertyPackageController;
use App\Http\Controllers\Admin\RenovationMediaController;
use App\Http\Controllers\Admin\RenovationMilestoneController;
use App\Http\Controllers\Admin\RenovationProjectController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ServiceCatalogController;
use App\Http\Controllers\Admin\ServiceChargeController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DocumentController;
use App\Http\Controllers\Portal\FinanceController;
use App\Http\Controllers\Portal\MaintenanceController;
use App\Http\Controllers\Portal\MessageController;
use App\Http\Controllers\Portal\PaymentController;
use App\Http\Controllers\Portal\ProfileController;
use App\Http\Controllers\Portal\ReportController;
use App\Http\Controllers\Portal\TaskController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyRegistrationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Public marketing site
// ---------------------------------------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/offers', [PageController::class, 'promotions'])->name('promotions.index');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/register-property', [PropertyRegistrationController::class, 'create'])->name('property-registration.create');
Route::post('/register-property', [PropertyRegistrationController::class, 'store'])->name('property-registration.store');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// ---------------------------------------------------------------------------
// Payment gateway callbacks — hit by JazzCash/Stripe servers or a redirected
// browser rather than an authenticated session, so these sit outside the
// portal's auth middleware and are exempted from CSRF verification below
// (see bootstrap/app.php). Each handler independently verifies authenticity
// via a signed hash rather than trusting the request.
// ---------------------------------------------------------------------------
Route::post('/payments/jazzcash/return', [\App\Http\Controllers\Portal\JazzCashController::class, 'return'])->name('payments.jazzcash.return');
Route::post('/payments/safepay/return', [\App\Http\Controllers\Portal\SafepayController::class, 'return'])->name('payments.safepay.return');
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');

// ---------------------------------------------------------------------------
// Authentication
// ---------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/two-factor', [TwoFactorController::class, 'show'])->name('two-factor.show');
Route::post('/two-factor', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
Route::post('/two-factor/resend', [TwoFactorController::class, 'resend'])->name('two-factor.resend');

// Convenience redirects so bare /portal and /admin land on the right dashboard
// (each destination is still protected by its own auth/role middleware).
Route::redirect('/portal', '/portal/dashboard');
Route::redirect('/admin', '/admin/dashboard');

// ---------------------------------------------------------------------------
// Client Portal
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'client'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/properties', [\App\Http\Controllers\Portal\PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}', [\App\Http\Controllers\Portal\PropertyController::class, 'show'])->name('properties.show');

    Route::get('/finances', [FinanceController::class, 'index'])->name('finances.index');

    Route::get('/rent-payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/rent-payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/rent-payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');

    // Card payments via Stripe (overseas owners) and mobile wallet via JazzCash (local owners).
    Route::post('/rent-payments/{payment}/pay/stripe', [\App\Http\Controllers\Portal\StripePaymentController::class, 'checkout'])->name('payments.stripe.checkout');
    Route::get('/rent-payments/{payment}/pay/stripe/return', [\App\Http\Controllers\Portal\StripePaymentController::class, 'return'])->name('payments.stripe.return');
    Route::get('/rent-payments/{payment}/pay/jazzcash', [\App\Http\Controllers\Portal\JazzCashController::class, 'checkout'])->name('payments.jazzcash.checkout');

    // Safepay — admin-added third gateway (cards, wallets & bank rails).
    Route::post('/rent-payments/{payment}/pay/safepay', [\App\Http\Controllers\Portal\SafepayController::class, 'checkout'])->name('payments.safepay.checkout');

    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::get('/maintenance/{maintenanceRequest}', [MaintenanceController::class, 'show'])->name('maintenance.show');

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/security', [ProfileController::class, 'updateSecurity'])->name('profile.security');
});

// ---------------------------------------------------------------------------
// Admin Portal
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');

    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/create', [AdminPropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [AdminPropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}', [AdminPropertyController::class, 'show'])->name('properties.show');
    Route::get('/properties/{property}/edit', [AdminPropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [AdminPropertyController::class, 'update'])->name('properties.update');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/toggle-featured', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
    Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');

    Route::post('/properties/{property}/service-charge', [ServiceChargeController::class, 'store'])->name('properties.service-charge');
    Route::post('/properties/{property}/package', [PropertyPackageController::class, 'store'])->name('properties.package.store');
    Route::put('/properties/{property}/package/{propertyPackage}', [PropertyPackageController::class, 'update'])->name('properties.package.update');
    Route::post('/properties/{property}/package/{propertyPackage}/cancel', [PropertyPackageController::class, 'cancel'])->name('properties.package.cancel');

    Route::get('/leases', [LeaseController::class, 'index'])->name('leases.index');
    Route::get('/leases/create', [LeaseController::class, 'create'])->name('leases.create');
    Route::post('/leases', [LeaseController::class, 'store'])->name('leases.store');
    Route::get('/leases/{lease}/edit', [LeaseController::class, 'edit'])->name('leases.edit');
    Route::put('/leases/{lease}', [LeaseController::class, 'update'])->name('leases.update');
    Route::delete('/leases/{lease}', [LeaseController::class, 'destroy'])->name('leases.destroy');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [AdminPaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [AdminPaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/confirm', [AdminPaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('/payments/{payment}/overdue', [AdminPaymentController::class, 'markOverdue'])->name('payments.overdue');
    Route::delete('/payments/{payment}', [AdminPaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('/maintenance', [AdminMaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/{maintenanceRequest}', [AdminMaintenanceController::class, 'show'])->name('maintenance.show');
    Route::put('/maintenance/{maintenanceRequest}', [AdminMaintenanceController::class, 'update'])->name('maintenance.update');
    Route::post('/maintenance/{maintenanceRequest}/bill', [AdminMaintenanceController::class, 'bill'])->name('maintenance.bill');

    Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [AdminDocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [AdminDocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/reply', [AdminMessageController::class, 'reply'])->name('messages.reply');
    Route::post('/messages/{message}/resolve', [AdminMessageController::class, 'resolve'])->name('messages.resolve');

    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [AdminTaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');

    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('blog', AdminBlogController::class)->except(['show'])->parameters(['blog' => 'post']);
    Route::resource('services', AdminServiceController::class)->except(['show']);
    Route::resource('promotions', PromotionController::class)->except(['show']);
    Route::resource('packages', AdminPackageController::class)->except(['show']);
    Route::resource('payment-methods', AdminPaymentMethodController::class)->except(['show']);
    Route::resource('fee-tiers', FeeTierController::class)->except(['show']);
    Route::resource('service-catalog', ServiceCatalogController::class)->except(['show']);

    Route::resource('renovations', RenovationProjectController::class)->except(['show'])->parameters(['renovations' => 'renovation']);
    Route::get('/renovations/{renovation}', [RenovationProjectController::class, 'show'])->name('renovations.show');
    Route::post('/renovations/{renovation}/approve', [RenovationProjectController::class, 'approve'])->name('renovations.approve');
    Route::post('/renovations/{renovation}/reject', [RenovationProjectController::class, 'reject'])->name('renovations.reject');
    Route::put('/renovations/{renovation}/status', [RenovationProjectController::class, 'updateStatus'])->name('renovations.status');
    Route::post('/renovations/{renovation}/milestones', [RenovationMilestoneController::class, 'store'])->name('renovations.milestones.store');
    Route::put('/renovations/{renovation}/milestones/{milestone}', [RenovationMilestoneController::class, 'update'])->name('renovations.milestones.update');
    Route::delete('/renovations/{renovation}/milestones/{milestone}', [RenovationMilestoneController::class, 'destroy'])->name('renovations.milestones.destroy');
    Route::post('/renovations/{renovation}/media', [RenovationMediaController::class, 'store'])->name('renovations.media.store');
    Route::delete('/renovations/{renovation}/media/{media}', [RenovationMediaController::class, 'destroy'])->name('renovations.media.destroy');

    Route::get('/leads', [ContactSubmissionController::class, 'index'])->name('leads.index');
    Route::post('/leads/{submission}/toggle-handled', [ContactSubmissionController::class, 'toggleHandled'])->name('leads.toggle-handled');
    Route::delete('/leads/{submission}', [ContactSubmissionController::class, 'destroy'])->name('leads.destroy');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
});
