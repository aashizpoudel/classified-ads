<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'dashboard/u/posts/checkout/*/payment-success',
        'dashboard/u/posts/checkout/*/paypal-notify',
        'dashboard/slider/create',
        'dashboard/slider/crop',
    ];
}
