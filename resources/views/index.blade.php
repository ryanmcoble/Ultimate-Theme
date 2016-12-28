@extends('layout.no-auth')

<?php
$body_class = 'install';
?>

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h2 class="text-center">Ultimate Shopify Suite</h2>
    </div>
</div>

<div class="row install-container">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Installation</h3>
            </div>
            <div class="panel-body">
                <div class="error-container">
                    @if(count($errors))
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Error!</strong> {{ $error }}
                            </div>
                        @endforeach
                    @endif
                </div>
                <form method="post" action="/install" id="install_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    
                    @if(env('APP_DEBUG'))
                        <div class="form-group">
                            @if(count($apps))
                                <label for="app">App</label>
                                <select class="form-control" name="app_id" id="app">
                                    @foreach($apps as $app)
                                        <option value="{{ $app->id }}">{{ $app->name }}</option>
                                    @endforeach
                                </select>
                                <p class="help-block">App to install</p>
                            @else
                                <p class="help-block">No apps to install!</p>
                            @endif
                        </div>
                    @else
                        @if(count($apps))
                            <input type="hidden" name="app_id" value="{{ $apps[0]->id }}">
                        @else
                            <p class="help-block">No apps to install!</p>
                        @endif
                    @endif
                    
                    <div class="form-group">
                        <label for="shop_url">Shopify Shop URL</label>
                        <input type="text" class="form-control" name="shop_url" id="shop_url" placeholder="Shopify URL">
                        <p class="help-block">Just enter your Shopify shop url (https://example.myshopify.com) to install the app</p>
                    </div>

                    <button type="submit" class="btn btn-primary">Install</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection