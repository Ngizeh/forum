@component('profile.activities.activity')
@slot('heading')
<h5>
	{{ $profileUser->name }} favorited this
	<a href="{{ $activity->subject->favoritable->thread->path() }}">reply</a>
</h5>
<small>
	{{ $activity->subject->created_at->diffForHumans() }}
</small>
@endslot
@slot('body')
{{ $activity->subject->favoritable->body }}
@endslot
@endcomponent
