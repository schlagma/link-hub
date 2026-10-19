<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | The policy is sent by the ContentSecurityPolicy middleware. Disable it
    | entirely with CSP_ENABLED=false, or set CSP_REPORT_ONLY=true to have
    | browsers report violations without blocking anything, which is the safe
    | way to try out a change in production.
    |
    */

    'enabled' => env('CSP_ENABLED', true),

    'report_only' => env('CSP_REPORT_ONLY', false),

    /*
    | Optional endpoint that browsers post violation reports to.
    */
    'report_uri' => env('CSP_REPORT_URI'),

    /*
    | The directives the policy is built from. The middleware adds the
    | per-request nonce, the origin of the configured OIDC provider and, while
    | Vite runs hot, the dev server origin on top of these.
    */

    'directives' => [

        'default-src' => ["'self'"],

        // A per-request nonce is added automatically. Livewire, Flux and the
        // Vite tags pick it up from Vite::cspNonce() on their own.
        //
        // 'unsafe-eval' is required: Alpine, which Livewire bundles, compiles
        // its attribute expressions with new Function(). Livewire ships a
        // CSP-safe build that avoids this, but it only understands simple
        // expressions and would break the Flux components used here.
        'script-src' => ["'self'", "'unsafe-eval'"],

        // Flux and the Flux editor write inline styles. A nonce does not cover
        // style attributes, so inline styles have to be allowed outright.
        'style-src' => ["'self'", "'unsafe-inline'"],

        'img-src' => ["'self'", 'data:'],

        'font-src' => ["'self'", 'data:'],

        'connect-src' => ["'self'"],

        'form-action' => ["'self'"],

        'frame-ancestors' => ["'none'"],

        'frame-src' => ["'none'"],

        'base-uri' => ["'self'"],

        'object-src' => ["'none'"],

        'manifest-src' => ["'self'"],

    ],

];
