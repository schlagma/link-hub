{{-- Instance specific icons dropped into public/ - nothing is linked when they
     are missing, so the browser falls back to its default instead of chasing a
     404 on every page load. --}}
@if(file_exists(public_path('favicon.ico')))
    <link rel="icon" sizes="any" href="{{ asset('favicon.ico') }}">
@endif
@if(file_exists(public_path('favicon.png')))
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
@endif
@if(file_exists(public_path('favicon.svg')))
    {{-- Listed after the raster icons: browsers that understand svg take it,
         the others stay on the png or the ico. --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
@endif
@if(file_exists(public_path('apple-touch-icon.png')))
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
@endif
