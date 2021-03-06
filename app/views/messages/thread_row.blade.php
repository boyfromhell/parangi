<tr class="thread-row{{ !$thread->read ? ' unread' : '' }}" data-id="{{ $thread->id }}" data-all="1" data-read="{{ $thread->read }}" data-title="{{{ $thread->title }}}">

	@if ( $thread_mode != 'search' )
	<td class="icon">
		<input type="checkbox" name="threads[]" value="{{ $thread->id }}">
	</td>
	@endif
	
	<td class="icon">
		<div class="icon topic{{ !$thread->read ? '_unread' : '' }}"></div>
	</td>
	
	<td class="from" style="width:25%">
		@foreach ( $thread->users as $count => $user )
			<a href="{{ $user->url }}">{{{ $user->name }}}</a>{{ $count < count($thread->users)-1 ? ', ' : '' }}
		@endforeach
		@if ( $thread->replies > 1 ) ({{ $thread->replies }}) @endif
	</td>

	<td class="topic">
		<div class="thread-preview">
		@if ( $thread->attachments )
			<img src="{$skin}icons/attachment.png" alt="att">
		@endif
		
		{{-- @todo ideally link to the oldest unread message --}}
		@if ( !$thread->read )
			<a href="{{ $thread->url }}#{{ $thread->lastMessage->id }}"><img src="{{ $skin }}icons/newest_reply.png"></a>
		@endif
		
		<a href="{{ $thread->url }}#{{ $thread->lastMessage->id }}">{{{ $thread->title }}}</a>
		- {{ BBCode::simplify($thread->lastMessage->content) }}
		</div>
	</td>

	<td class="lastpost message nowrap">
		{{ $thread->lastMessage->date }}
	</td>
</tr>
