@extends('layout')

@section('content')

@if ( $me->id )
<div class="panel panel-danger">

	<div class="panel-heading">Oops</div>

	<div class="panel-body">

		<p>Sorry, you are forbidden from accessing this page.</p>

		<p><a href="/contact">Contact us</a> if you need help.</p>

	</div>

</div>
@else

@include ('users.signin_form')

@endif

@stop
