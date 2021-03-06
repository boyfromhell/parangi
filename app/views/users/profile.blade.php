@extends('layout')

@section('content')

<div class="panel panel-primary">

	<div class="panel-heading">View Profile</div>

	<div class="panel-body row">
	
	<div class="col-sm-6">
	<h1>{{{ $user->name }}} <img src="/images/{{ $user->online }}.png" alt="{{{ $user->name }}} is {{ $user->online }}" title="{{{ $user->name }}} is {{ $user->online }}"></h1>
	<small>
	@if ( $user->level->image )
	<img src="/images/titles/{{ $user->level->image }}" style="vertical-align:middle">
	@endif
	{{{ $user->level->name }}}</small>

	@if ( $user->avatar->id )<br><br>
	<img id="profileavatar" src="{{ $cdn }}/images/avatars/{{ $user->avatar->file }}" alt="{{{ $user->name }}}'s avatar" title="{{{ $user->name }}}'s avatar">
	@endif
	<br><br>

	</div>

	<div class="col-sm-6">
	Last Visit: <b>{{ $user->last_online }}</b><br>
	Viewed <b>{{ number_format($user->views) }}</b> time{{ $user->views != 1 ? 's' : '' }}<br><br>
	@if ( $show_birthday )
	Birthday: <b>{{ $birthday }}</b><br>
	@endif
	Member Since: <b>{{ Helpers::local_date('F j, Y', $user->created_at) }}</b><br><br>
	
	Posts: <b>{{ number_format($user->total_posts) }}</b> ({{ $stats['posts_per_day'] }} posts per day, {{ $stats['posts_percent'] }}% of total)<br>
	Shoutbox Posts: <b>{{ number_format($stats['user_shouts']) }}</b> ({{ $stats['shouts_per_day'] }} per day, {{ $stats['shouts_percent'] }}% of total)
	@if ( $me->id )<br><br>
	<a href="/search?user={{ $user->id }}">Find all posts by {{{ $user->name }}}</a><br>
	<a href="/search?user={{ $user->id }}&amp;mode=topics">Find all topics started by {{{ $user->name }}}</a>
	@endif
	<br><br>

	<div class="btn-group btn-group-sm">
	@if ( $me->is_admin || $me->id == $user->id )<a href="{{ $edit_url }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a>@endif
	@if ( $me->is_mod )<a href="/lookup.php?user={{ $user->id }}" class="btn btn-danger">IP</a>@endif
	</div>

	</div>

	</div>

</div>

<div class="panel panel-info">

	<div class="panel-heading">Info</div>

	<div class="panel-body row">
	
	<div class="col-sm-6">
	@if ( count($user->groups) > 0 )<b>Groups:</b><br>
		@foreach ( $user->groups as $group )
			<img src="{{ $group->badge ? '/images/groups/'.$group->badge : $skin.'icons/group.png' }}"> <a href="{{ $group->url }}">{{{ $group->name }}}</a><br>
		@endforeach
		<br>
	@endif
	@foreach ( $custom as $field )
		<b>{{{ $field->name }}}:</b><br>
		{{{ $field->value }}}<br><br>
	@endforeach
	</div>

	<div class="col-sm-6">
	@if ( $me->id )
	<b>Contact:</b><br>

	@foreach ( $user->screennames as $im )
		<b>{{ $im->name }}</b> <img src="{{ $im->image }}" alt="{{ $im->name }} status" style="vertical-align:middle"> {{{ $im->screenname }}}<br>
	@endforeach

	@if ( $me->id != $user->id )
	{{--if $allow_email}<a href="/email.php?user={{ $user->id }}">Send {{{ $user->name }}} an email</a><br>--}}
	<a href="/messages/compose?user={{ $user->id }}">Send {{{ $user->name }}} a message</a>
	<br>
	@endif
	<br>
	@endif
	@if ( $website_url )<b>Website:</b><br><a href="{{{ $website_url }}}" rel="nofollow">{{{ $website_text }}}</a><br><br>
	@endif
	@if ( $user->victory_rank || $user->defeat_rank )
	<b>Honor Rolls:</b><br>
	@if( $user->victory_rank )
	#{{ $user->victory_rank->rank }} in victories - <strong>{{ number_format($user->victory_rank->score) }}</strong> ({{ $user->victory_rank->variant }})<br>
	@endif
	@if ( $user->defeat_rank )
	#{{ $user->defeat_rank->rank }} in defeats - <strong>{{ number_format($user->defeat_rank->score) }}</strong> ({{ $user->defeat_rank->variant }})<br>
	@endif
	<br>
	@endif

	@if ( $me->id && $me->id != $user->id )
		@if ( $me->buddies->contains($user->id) )
			<a href="/userlist.php?user={{ $user->id }}&amp;remove=1">Remove from buddy list</a>
		@elseif ( $me->ignoredUsers->contains($user->id) )
			<a href="/userlist.php?user={{ $user->id }}&amp;remove=1">Remove from ignore list</a>
		@else
			<a href="/userlist.php?user={{ $user->id }}&amp;buddy=1">Add to buddy list</a><br>
			@if ( $user->level == 0 )
			<a href="/userlist.php?user={{ $user->id }}@amp;ignore=1">Add to ignore list</a>
			@endif
		@endif
	@endif
	</div>

	</div>

	@if ( $user->sig )
	<div class="panel-footer sig">
		{{ BBCode::parse($user->sig) }}
	</div>
	@endif

</div>

@stop
