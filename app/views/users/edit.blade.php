@extends('layout')

@section('buttons')
<a href="/profile" class="btn btn-primary">View Profile</a>
@stop

@section('content')
<form class="form-horizontal unload-warning" method="post" action="/users/edit">

<div class="panel panel-primary">

	<div class="panel-heading">Account</div>

	<div class="panel-body">

	<div class="form-group">
		<label class="col-sm-3 control-label">Username</label>
		<div class="col-sm-4">
			<p class="form-control-static"><b>{{{ $me->name }}}</b></p>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">E-mail</label>
		<div class="col-sm-4">
			<p class="form-control-static email-text"><b>{{{ $me->email }}}</b></p>

			{{ Form::email('email', $me->email, ['id' => 'email_input', 'tabindex' => 1, 'maxlength' => 255, 'style' => 'display:none', 'class' => 'form-control']) }}
		</div>
		<div class="col-sm-4">
			<p class="form-control-static email-text"><a href="" onClick="$('.email-text').hide(); $('#email_input').show(); $('#email_input').focus(); $('#password_text').hide(); $('#password_input').show(); return false">change</a></p>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">Current Password</label>
		<div class="col-sm-4">
			<p class="form-control-static" id="password_text"><b>********</b></p>

			{{ Form::password('old_password', ['id' => 'password_input', 'class' => 'form-control', 'tabindex' => 1, 'style' => 'display:none']) }}
		</div>
		<div class="col-sm-4">
			<p class="form-control-static"><a id="password_link" href="" onClick="$('#password_text').hide(); $('#password_link').hide(); $('#password_input').show(); $('#password_input').focus(); $('#new_password').show(); $('#confirm_password').show(); return false">change</a></p>
		</div>
	</div>

	<div id="new_password" class="form-group" style="display:none">
		<label class="col-sm-3 control-label">New Password</label>
		<div class="col-sm-4">
			{{ Form::password('password', ['class' => 'form-control', 'tabindex' => 1]) }}
		</div>
	</div>

	<div id="confirm_password" class="form-group" style="display:none">
		<label class="col-sm-3 control-label">Confirm Password</label>
		<div class="col-sm-4">
			{{ Form::password('confirm', ['class' => 'form-control', 'tabindex' => 1]) }}
		</div>
	</div>

	</div>
</div>

<div class="panel panel-primary">

	<div class="panel-heading">Personal</div>

	<div class="panel-body">

	<div class="form-group">
		<label class="col-sm-3 control-label">Birthday</label>
		<div class="col-sm-2">
			{{ Form::select('year', $years, $year, ['class' => 'form-control', 'tabindex' => 1]) }}
		</div>
		<div class="col-sm-2">
			{{ Form::select('month', $months, $month, ['class' => 'form-control', 'tabindex' => 1]) }}
		</div>
		<div class="col-sm-1">
			{{ Form::select('day', $days, $day, ['class' => 'form-control', 'tabindex' => 1]) }}
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">Show in profile</label>
		<div class="col-sm-3">
		<select class="form-control" name="bdaypref" tabindex="1">
			<option value="0"{{ $me->bdaypref == 0 ? ' selected' : '' }}>Full birthday</option>
			<option value="1"{{ $me->bdaypref == 1 ? ' selected' : '' }}>Month and Day</option>
			<option value="2"{{ $me->bdaypref == 2 ? ' selected' : '' }}>Nothing</option>
		</select>
		</div>
	</div>

	</div>
</div>

<div class="panel panel-primary">

	<div class="panel-heading">Contact</div>

	<div class="panel-body">

	<div class="form-group">
		<label class="col-sm-3 control-label">Website</label>
		<div class="col-sm-5">
		{{ Form::text('website', $me->website, ['class' => 'form-control', 'tabindex' => 1, 'maxlength' => 255]) }}
		</div>
	</div>

	</div>
</div>

<div class="panel panel-primary">

	<div class="panel-heading">Profile</div>

	<div class="panel-body">

	@foreach ( $customs as $field )
	<div class="form-group">
		<label class="col-sm-3 control-label">{{{ $field->name }}}</label>
		<div class="col-sm-5">
		{{ Form::text('custom'.$field->id, $field->value, ['class' => 'form-control', 'tabindex' => 1, 'maxlength' => ( $field->maxlength ? $field->maxlength : 255 )]) }}
		</div>
	</div>
	@endforeach

	<div class="form-group">
		<label class="col-sm-3 control-label">Signature</label>
		<div class="col-sm-5">
			{{ BBCode::show_bbcode_controls() }}<br>
			{{ Form::textarea('sig', $me->sig, ['id' => 'bbtext', 'class' => 'form-control', 'tabindex' => 1]) }}
			<br>
			<small>512 character limit</small>
		</div>
	</div>

	</div>

	<div class="panel-footer">

	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-3">
			<input class="btn btn-primary" tabindex="1" name="update" type="submit" value="Save Profile">
			<input type="reset" class="btn btn-default" value="Reset">
		</div>
	</div>

	</div>
</div>
</form>

@stop
