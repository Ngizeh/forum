@component('profile.activities.activity')
@slot('heading')
<h5>
	{{ $profileUser->name }} replied to
	<a href="{{ $activity->subject->thread->path() }}"> {{  str_limit($activity->subject->thread->title , 20) }} </a>
</h5>
<small>
	{{ $activity->subject->created_at->diffForHumans() }}
</small>
@endslot
@slot('body')
{{ $activity->subject->body }}
@endslot
@endcomponent
