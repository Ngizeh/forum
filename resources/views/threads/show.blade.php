@extends('layouts.app')
@section('header')
    <link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">
@endsection
@section('content')
<thread-view inline-template :thread="{{ $thread }}" v-cloak>
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
				@include('threads.question')
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<p>
							This thread was published by {{ $thread->created_at->diffForHumans()}} by
							<a href="/profile/{{$thread->creator->name}}">{{ $thread->creator->name }}</a> and currently has
							<span v-text="repliesCount" class="pr-1"></span>
							{{  $thread->pluralized() }}
						</p>
						<p class="d-flex justify-content-between">
							<subscribe-button :active="@json($thread->isSubscribedTo)"></subscribe-button>
							<button class="btn"
							:class="locked ? 'btn-link' : 'btn-danger'
							"v-if="authorize('isAdmin')"
							@click="toggleLock"
							v-text="locked ? 'Unlock Thread' : 'Lock'"></button>
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
