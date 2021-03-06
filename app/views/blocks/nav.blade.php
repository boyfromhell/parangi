<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
<div class="container-fluid">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>

		<a class="navbar-brand" href="/">
			<span class="hidden-xs"><img src="/images/custom/logo_white.png" alt="{{{ Config::get('app.forum_name') }}}"></span>
			<span class="visible-xs">{{{ Config::get('app.short_name') }}}</span>
		</a>
	</div>

	<div class="collapse navbar-collapse" id="nav">
	<ul class="nav navbar-nav">
	@foreach ( $main_menu as $app )
		<li class="{{ $_PAGE['category'] == $app->page ? 'active' : '' }} {{ $app->class }}"><a href="{{ $app->url }}">{{ $app->name }}</a></li>
	@endforeach
	</ul>
	</div>

	<ul id="user-nav" class="nav navbar-nav navbar-right">
		<li class="dropdown">
@if ($me->id)
			<a href="{{ $me->url }}" class="dropdown-toggle" data-toggle="dropdown">
			<img class="avatar" src="{{ $me->avatar_url }}">
			<span class="hidden-xs">{{{ $me->name }}}</span> <span class="caret"></span>
			</a>
@else
			<a href="/signin" class="dropdown-toggle" data-toggle="dropdown">
			<img class="avatar" src="/images/custom/default-avatar.png">
			<span class="hidden-xs">Guest</span> <span class="caret"></span>
			</a>
@endif
			<ul class="dropdown-menu no-collapse" role="menu">
			@if ( $me->id )
				<li><a href="/profile"><span class="glyphicon glyphicon-user"></span> My profile</a></li>
				<li><a href="/edit-profile"><span class="glyphicon glyphicon-pencil"></span> Edit profile</a></li>
				<li><a href="/settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
				<li><a href="/signout"><span class="glyphicon glyphicon-off"></span> Sign out</a></li>
			@else
				<li><a href="/signin"><span class="glyphicon glyphicon-log-in"></span> Sign in</a></li>
				<li><a href="/signup"><span class="glyphicon glyphicon-user"></span> Register</a></li>
			@endif
			</ul>
		</li>
	</ul>
</div>
</nav>
