<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Setting;
use App\Models\LinkExternal;
use App\Models\AccountInvoice;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();
        
        $address = Contact::select('title', 'content')->whereType('address')->whereActived('1')->firstOr(function() {
            return json_decode(json_encode(['title'=>null, 'content'=>null]), FALSE);
        });
        $map = Contact::select('title', 'content')->whereType('map')->whereActived('1')->firstOr(function() {
            return json_decode(json_encode(['title'=>null, 'content'=>'no-map']), FALSE);
        });
        $email = Contact::select('title', 'content')->whereType('email')->whereActived('1')->get();
        $phone = Contact::select('title', 'content')->whereType('phone')->whereActived('1')->get();
        $whatsapp = Contact::select('title', 'content')->whereType('whatsapp')->whereActived('1')->get();
        $social = LinkExternal::select('brand', 'title', 'url', 'icon')->whereType('social')->whereActived('1')->get();
        $setting = Setting::select('title', 'content')->get();
        // $payment_waiting = 1;
        $payment_waiting = AccountInvoice::whereStatus('PENDING')->count();

		View::share('global', ['setting' => $setting, 'contact' => [$address, $map, $email, $phone, $whatsapp], 'social' => $social, 'admin' => ['payment_waiting' => $payment_waiting]]);
    }
}
