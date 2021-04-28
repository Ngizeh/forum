@forelse($threads as $thread)
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h4>
                        @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong>
                                <a href="{{ $thread->path()}}" >{{ $thread->title }}</a>
                            </strong>
                        @else
                            <a href="{{ $thread->path()}}" >{{ $thread->title }}</a>
                        @endif
                    </h4>
                    <h5>Posted by <a href="/profile/{{$thread->creator->name}}">{{ $thread->creator->name }}</a></h5>
                </div>
                <a href="{{ $thread->path() }}">
                    {{ $thread->reply_count }} {{ $thread->pluralized() }}
                </a>
            </div>
        </div>
        <div class="card-body">
            {!! $thread->body !!}
        </div>
        <div class="card-footer">
            {{ $thread->visits() }} Visits
         </div>
    </div>
@empty
    <h5>There's no activity at the moment</h5>
@endforelse
<p>
    <a href="{{ url()->previous() }}">Back</a>
</p>
