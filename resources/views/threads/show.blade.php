@extends('layouts.app')
@section('header')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
@endsection
@section('content')
<thread-view inline-template :initial-reply-count="{{ $thread->reply_count }}">
	<div class="container">
		<div class="row mt-4">
			<div class="col-md-8">
				@if(count($errors))
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<span> {{ $error }}</span>
						@endforeach
					</div>
				@endif
				<div class="card mb-4">
					<div class="card-header d-flex justify-content-between">
						<span>
                            <img src="{{ asset($thread->creator->avatar_path) }}" alt="{{ $thread->creator->name }}"
                                 width="30" height="30" class="mr-1 rounded">
                            <a href="/profile/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:
							{{ $thread->title }}
						</span>
						@can('update', $thread)
							<form action="/threads/{{ $thread->channel->id}}/{{$thread->id}}" method="post">
								@csrf
								@method('delete')
								<button class="btn btn-link" type="submit">Delete thread</button>
							</form>
						@endcan
					</div>
					<div class="card-body">
						{{ $thread->body }}
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<p>
							This thread was published by {{ $thread->created_at->diffForHumans()}} by
							<a href="/profile/{{$thread->creator->name}}">{{ $thread->creator->name }}</a> and currently has
							<span v-text="repliesCount" class="pr-1"></span>
							{{ str_plural('reply', $thread->replies_count) }}
						</p>
						<p>
							<subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<replies @added="repliesCount++" @removed="repliesCount--"></replies>
			</div>
		</div>
	</div>
</thread-view>

@endsection
