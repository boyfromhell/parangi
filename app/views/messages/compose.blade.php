@extends('layout')

@section('content')

<form class="form-horizontal unload-warning" method="post" action="{{ $action }}" enctype="multipart/form-data"{{ count($attachments) > 0 ? ' data-changed="1"' : '' }}>
<div class="panel panel-primary">

	<div class="panel-heading">{{ $thread->id ? 'Reply' : 'Compose Message' }}</div>
	
	<div class="panel-body">

	<input type="hidden" name="hash" value="{{ $hash }}">

	<div class="form-group">
		<label class="control-label col-sm-3">To</label>
		<div class="col-sm-7">
			{{ Form::textarea('recipients', $recipients, ['class' => 'form-control textarea-sm', 'required', 'autofocus']) }}<br>
			<small>Separate multiple recipients with a comma or new line</small>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-3">Subject</label>
		<div class="col-sm-7">
		@if ( $thread->id )
			<p class="form-control-static">Re: {{{ $thread->title }}}</p>
		@else
			{{ Form::text('subject', $subject, ['maxlength' => 70, 'class' => 'form-control', 'required']) }}
		@endif
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label hidden-xs">
			{{ BBCode::show_smiley_controls() }}
		</label>
		<div class="col-sm-7">
			{{ BBCode::show_bbcode_controls() }}
			{{ Form::textarea('content', $content, ['id' => 'bbtext', 'class' => 'form-control']) }}
		</div>
	</div>

	@include ('blocks.attachments')

	</div>

	<div class="panel-footer">

	<div class="form-group">
		<div class="col-sm-7 col-sm-offset-3">
			{{ Form::submit('Send Message', ['name' => 'send', 'class' => 'btn btn-primary btn-once', 'accesskey' => 'S', 'data-loading-text' => 'Sending...']) }}
			{{ Form::submit('Preview', ['name' => 'preview', 'class' => 'btn btn-default preview', 'accesskey' => 'P']) }}
		</div>
	</div>

	</div>

</div>

<div id="preview"></div>

@include ('blocks.edit_attachments')
</form>

@stop
