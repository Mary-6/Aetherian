<?php

namespace App\Providers;

use App\Models\ChatRoom;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
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
        $rootUrl = config('app.url');
        $host = $rootUrl ? parse_url($rootUrl, PHP_URL_HOST) : null;
        $isLocalDefault = ! $host || in_array($host, ['localhost', '127.0.0.1'], true);

        if (! $isLocalDefault) {
            URL::forceRootUrl($rootUrl);
        }

        View::composer('layouts.admin', function ($view) {
            $pendingChatCount = ChatRoom::sum('admin_unread_count');

            $view->with('pendingChatCount', $pendingChatCount);
        });

        $this->shareCompanySettings();
    }

    private function shareCompanySettings(): void
    {
        try {
            $name = Setting::get('company_name', config('app.name'));
            $email = Setting::get('company_email', 'Aetheriancargo@gmail.com');
            $phone = Setting::get('company_phone', '+1 (423) 277-8587');
            $address = Setting::get('company_address', 'Aetherian Cargo HQ');
        } catch (\Throwable $e) {
            $name = config('app.name');
            $email = 'Aetheriancargo@gmail.com';
            $phone = '+1 (423) 277-8587';
            $address = 'Aetherian Cargo HQ';
        }

        View::share('companyName', $name);
        View::share('companyEmail', $email);
        View::share('companyPhone', $phone);
        View::share('companyAddress', $address);
    }
}
