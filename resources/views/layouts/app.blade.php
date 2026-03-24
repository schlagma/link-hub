<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
    <head>
        @if (request()->is('inventory*'))
        <title>{{ __('inventory.inventoryManagement') }} | {{ config('app.name') }}</title>
        @elseif (request()->is('lending*'))
        <title>{{ __('lending.lending') }} | {{ config('app.name') }}</title>
        @elseif (request()->is('imprint'))
        <title>{{ __('common.imprint') }} | {{ config('app.name') }}</title>
        @elseif (request()->is('privacy'))
        <title>{{ __('common.privacyPolicy') }} | {{ config('app.name') }}</title>
        @elseif (request()->is('accessibility'))
        <title>{{ __('common.accessibility') }} | {{ config('app.name') }}</title>
        @else
        <title>{{ config('app.name') }}</title>
        @endif
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="theme-color" content="#27272a">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        @livewireStyles
        @fluxAppearance
        @vite('resources/css/app.css')
        @vite('resources/css/theme.css')
        @vite('resources/js/app.js')
    </head>
    <body class="flex w-full h-full">
        <div class="grid grid-rows-[auto_1fr] w-full h-full">
            @include('header')
            <main class="h-full flex-1 overflow-x-hidden overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

        @include('info')

        @persist('toast')
            <flux:toast.group position="top end">
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @livewireScripts
        @fluxScripts
    </body>
</html>