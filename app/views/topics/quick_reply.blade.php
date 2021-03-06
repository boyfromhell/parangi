<form class="form unload-warning" method="post" action="/reply-to-topic/{{ $topic->id }}">

<div id="quick-reply" class="panel panel-info">

	<div class="panel-heading">Quick Reply</div>
	
	<div class="panel-body">

		<div class="quickedit">
		{{ BBCode::show_bbcode_controls() }}
		{{ Form::textarea('content', '', ['id' => 'bbtext', 'class' => 'form-control']) }}

		{{ Form::hidden('subscribe', $check_sub ? 1 : 0) }}
		{{ Form::hidden('attach_sig', $me->attach_sig) }}
		{{ Form::hidden('show_smileys', 1) }}
		</div>

	</div>

	<div class="panel-footer">

		<div class="text-center">
			{{ Form::submit('Post Reply', ['name' => 'addpost', 'class' => 'btn btn-primary btn-once', 'accesskey' => 'S', 'data-loading-text' => 'Posting...']) }}
			{{ Form::submit('Advanced', ['name' => 'preview', 'class' => 'btn btn-default', 'accesskey' => 'P']) }}
		</div>

	</div>
</div>

</form>

