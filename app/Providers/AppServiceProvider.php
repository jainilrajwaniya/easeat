<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use DB;
use Log;
use Validator;
use Illuminate\Support\Facades\Input;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // To overcome from this error: Syntax error or access
        // violation: 1071 Specified key was too long; max key length is 767 bytes
        // Laravel uses the utf8mb4 character set by default, which includes support for
        // storing "emojis" in the database. If you are running a version of MySQL older than
        // the 5.7.7 release or MariaDB older than the 10.2.2 release, you may need to
        // manually configure the default string length generated by migrations in order for MySQL
        // to create indexes for them.
        Schema::defaultStringLength(191);
        
        // Log queries
        if (env('QUERY_DEBUG') === true) {
            DB::listen(function ($query) {
                Log::info($query->sql, $query->bindings, $query->time);
            });
        }

        Validator::extend('greater_than', function($attribute, $value, $parameters)
        {
            $other = Input::get($parameters[0]);

            return isset($other) and intval($value) >= intval($other);
        });

        Validator::replacer('greater_than', function($message, $attribute, $rule, $params) {
            return str_replace('_', ' ' , 'The '. $attribute .' must be greater than the ' .$params[0]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
