<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="0;url={{ $url }}">
    </head>
    <body>
        @php($nonce = Vite::cspNonce())
        <script{!! $nonce ? ' nonce="'.e($nonce).'"' : '' !!}>window.location.href = @json($url);</script>
        <noscript><a href="{{ $url }}">Continue to login</a></noscript>
    </body>
</html>
