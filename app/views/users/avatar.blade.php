@extends('layout')

@section('content')

<script type="text/javascript">
function selectAvatar( id ) {
	var previd = $('#id').val();
	if( previd != id ) {
		$('#avatar'+previd).removeClass('sel');
	}
	$('#avatar'+id).addClass('sel');
	$('#id').val(id);
	$('form.unload-warning').data('changed', 1);
}
</script>

<form class="form-horizontal unload-warning" name="myform" method="post" action="/avatar">

<div class="panel panel-primary">

	<div class="panel-heading">Avatar Gallery</div>
	
	<div class="panel-body">
	<div id="avatars">
		{{ Form::hidden('id', $default, ['id' => 'id']) }}
		@foreach ( $avatars as $avatar )
		<div class="avat">

		<div id="avatar{{ $avatar->id }}" onClick="selectAvatar({{ $avatar->id }})" {{ $avatar->id == $default ? ' class="sel"' : '' }}>
		<img src="{{ $cdn }}/images/avatars/{{ $avatar->file }}" alt="Avatar"></div></div>
		@endforeach
		
		<div class="avat">
		<div id="avatar0" onClick="selectAvatar(0)" style="width:120px; height:120px;"{{ !$default ? ' class="sel"' : '' }}>
		<div id="avtext"><br><br><br>No avatar</div></div></div>
	</div>

	</div>

	<div class="panel-footer">

	<div class="text-center">
		{{ Form::submit('Select', ['name' => 'select', 'class' => 'btn btn-primary']) }}
		{{ Form::submit('Delete', ['name' => 'delete', 'class' => 'btn btn-danger']) }}
		{{ Form::reset('Reset', ['class' => 'btn btn-default', 'onClick' => 'selectAvatar('.$default.')']) }}
	</div>
	
	</div>
	
</div>
</form>

<form class="form-horizontal" enctype="multipart/form-data" method="post" action="/upload-avatar">

<div class="panel panel-info">

	<div class="panel-heading">Upload Avatar</div>
	
	<div class="panel-body">
	
	<div class="form-group">
		<label class="col-sm-4 control-label">
			Upload Avatar from your computer
		</label>
		<div class="col-sm-8">
			<p class="form-control-static">
			{{ Form::file('avatar', ['accept' => 'image/*']) }}<br>

			<small>GIF, JPG, and PNG files only. Anything larger than 150x150 pixels will be scaled down</small>
			</p>
		</div>
	</div>

	</div>

	<div class="panel-footer">

	<div class="text-center">
		{{ Form::submit('Upload', ['name' => 'upload', 'class' => 'btn btn-primary btn-once', 'data-loading-text' => 'Uploading...']) }}
	</div>
	
	</div>

</div>
</form>

@stop
