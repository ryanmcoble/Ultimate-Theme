<!DOCTYPE html>
<html>
    <head>
        <title>Ultimate Shopify App Suite</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="//fonts.googleapis.com/css?family=Lato:100">
        <link href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link href="{{ elixir('assets/css/app.css') }}">
    </head>
    <body class="{{ $body_class }}">

        <div class="container" ng-app="ultimateShopifySuite">
            @yield('content')
        </div>

        <!-- Shopify Embedded API-->
        <script src="//cdn.shopify.com/s/assets/external/app.js"></script>
        <script>
        ShopifyApp.init({
            apiKey: '{{ isset($api_key) ? $api_key : '' }}',
            shopOrigin: 'https://{{ $shop->permanent_domain }}',
            debug: true
        });
        ShopifyApp.Bar.loadingOff();
        </script>
        @include('partials.scripts')

        @yield('additional-scripts')
    </body>
</html>