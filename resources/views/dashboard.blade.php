@extends('layout.default')

<?php
$body_class = 'install';
?>

@section('content')
<main ui-view></main>
@endsection

@section('additional-scripts')
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.8/angular-ui-router.min.js"></script>
<script src="{{ elixir('assets/js/all.js') }}"></script>
@endsection