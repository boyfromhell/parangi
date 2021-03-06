@extends('layout')

@section('buttons')

@if ( $project->user_id == $me->id || $me->is_admin )
<a href="/projects/upload?id={{ $project->id }}" class="btn btn-primary"><span class="glyphicon glyphicon-open"></span> Upload</a>
@endif

@stop

@section('content')

<div class="panel panel-primary">

	<div class="panel-heading">{{{ $project->name }}}</div>
	
	<div class="panel-body">

	@if ( $project->category != 1 )
		@if ( $project->user_id )
			Author: <a href="{{ $project->user->url }}">{{{ $project->user->name }}}</a><br><br>
		@elseif ( $project->author )
			Author: {{{ $project->author }}}<br><br>
		@endif
	@endif

	{{ BBCode::parse($project->description) }}

	@if ( $project->user_id == $me->id || $me->is_admin )
	<br><br>
	<a href="/admin/projects/{{ $project->id }}/edit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
	@endif
	</div>

</div>

<div class="panel panel-info">

	<div class="panel-heading">Files</div>

	<table class="table table-hover">
	<thead>
	<tr>
		<th class="icon">&nbsp;</th>
		<th>File</th>
		<th class="posts" style="width:15%">Version</th>
		<th class="posts">Size</th>
		<th class="posts">Platform</th>
		<th class="posts">Type</th>
		<th class="posts">Downloads</th>
		<th class="date" style="width:15%">Date</th>
	</tr>
	</thead>
	<tbody>
@foreach ( $project->downloads as $download )
	<tr>
		<td class="icon">
			<img src="{{ $skin }}icons/download{{-- if $download->views > 100}_hot{/if --}}.png">
		</td>
		<td>
			<a href="{{ $download->url }}" rel="nofollow">{{{ $download->file }}}</a>
		</td>
		<td class="posts" style="width:15%">{{{ $download->version }}}</td>
		<td class="posts">{{ $download->size }}</td>
		<td class="posts">{{{ $download->platform }}}</td>
		<td class="posts">{{{ $download->type }}}</td>
		<td class="posts">{{ number_format($download->views) }}</td>
		<td class="date">{{ Helpers::local_date('M j, Y', $download->created_at) }}</td>
	</tr>
@endforeach
	</tbody>
	</table>

</div>

@stop
