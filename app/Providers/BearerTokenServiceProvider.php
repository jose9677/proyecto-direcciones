<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BearerTokenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    public static function getUserByBearerToken($token)
    {
        if(! str_contains($token, '.'))
            return false;

        list($header, $payload, $signature) = explode('.', $token);
        $jsonToken = base64_decode($payload);
        $arrayToken = json_decode($jsonToken, true);
    
        return strval($arrayToken['login']);   
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
