<div id="shoutbox" class="row" data-last-id="{{ $shouts[0]->id }}" data-last-time="{{ $shouts[0]->created_at }}">
<div class="col-sm-3 text-center">

	<div class="panel panel-info panel-sm">
	
		<div class="panel-heading">Shoutbox</div>
		
		<div class="panel-body">

    <form class="form" method="post" onsubmit="saveData(); return false;">
	<div class="input-group">
		{{ Form::text('message', '', ['class' => 'form-control input-sm', 'placeholder' => 'type message here', 'autocomplete' => 'off']) }}
		<div class="input-group-btn">
		{{ Form::submit('Send', ['class' => 'btn btn-primary btn-sm', 'data-loading-text' => 'Send']) }}
		</div>
	</div>
    </form>

	</div></div>

	@if ( $mini )
	<small><a href="/shoutbox/history">History</a> - <a id="sb-toggle" href="">Disable</a></small>
	@endif
	
</div>

<div class="col-sm-9">

	<table class="table table-condensed">
	<tbody>
	@foreach ( $shouts as $shout )
	
		@include ('shoutbox.row')
	
	@endforeach
	</tbody>
	</table>

</div>
</div>

