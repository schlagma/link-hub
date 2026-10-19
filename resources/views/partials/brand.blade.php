{{-- Instance specific logos dropped into public/. Both variants have to be
     there, otherwise the application name is used instead. --}}
@if(file_exists(public_path('logo.svg')) && file_exists(public_path('logo-small.svg')))
    <img class="h-8 w-auto hidden sm:inline" src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }}">
    <img class="h-8 w-auto sm:hidden" src="{{ asset('logo-small.svg') }}" alt="{{ config('app.name') }}">
@else
    <span class="text-zinc-800 dark:text-white text-xl font-semibold">{{ config('app.name') }}</span>
@endif
