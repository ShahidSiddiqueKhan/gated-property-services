<?php

namespace App\Providers;

use App\Models\ContactSubmission;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.portal', function ($view) {
            $count = 0;

            if (Auth::check()) {
                $count = Message::where('user_id', Auth::id())
                    ->where('sender', 'staff')
                    ->where('is_read', false)
                    ->count();
            }

            $view->with('unreadMessagesCount', $count);
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('adminPendingApprovals', Property::where('status', 'pending_review')->count());
            $view->with('adminNewLeads', ContactSubmission::where('is_handled', false)->count());
            $view->with('adminNewMessages', Message::where('sender', 'client')->where('created_at', '>=', now()->subDays(7))->count());
        });
    }
}
