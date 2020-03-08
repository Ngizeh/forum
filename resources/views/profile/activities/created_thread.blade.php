@component('profile.activities.activity')
@slot('heading')
	<h5>
		{{ $profileUser->name }} published
		<a href="{{ $activity->subject->path() }}">{{ str_limit($activity->subject->title, 20 )}}</a>
	</h5>
	<small>
		{{ $activity->subject->created_at->diffForHumans() }}
	</small>
@endslot
	@slot('body')
	{{ $activity->subject->body }}
	@endslot
@endcomponent

