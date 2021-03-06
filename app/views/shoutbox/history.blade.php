@extends('layout')

@section('header')
<div class="row">
<div class="col-md-4 col-sm-6">
<div class="panel panel-default panel-sm">

	<div class="panel-heading">Search Shoutbox History</div>

	<form method="GET" action="/shoutbox/history">
	<div class="panel-body">

	<div class="input-group input-group-sm">
	{{ Form::text('search', $search, ['class' => 'form-control', 'maxlength' => 50]) }}
	<div class="input-group-btn">
	{{ Form::button('<span class="glyphicon glyphicon-search"></span>', ['class' => 'btn btn-primary']) }}
	</div>
	</div>

	</div>
	</form>
</div>
</div>
</div>
@stop

@section('buttons')
<div class="pull-right">
	{{ $shouts->appends(['search' => $search])->links() }}
</div>
@stop

@section('content')

@if ( count($shouts) > 0 )
<div class="shoutbox">

	@include ('shoutbox.embed')

</div>
@else
<div class="panel panel-primary">

	<div class="panel-heading">Search Results</div>

	<div class="panel-body">

		<p>No search results matched your critera.</p>

	</div>
</div>
@endif

@stop
