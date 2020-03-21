@extends('layouts.app')

@section('content')
<div class="container bottom">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<avatar-form :user="{{$profileUser}}"></avatar-form>
			<hr>
			@forelse ($activities as $date => $activity)
			   <h3>{{ $date }}</h3>
				@foreach ($activity as $record)
				  @if (view()->exists("profile.activities.{$record->type}"))
					@include("profile.activities.{$record->type}", ['activity' => $record])
				  @endif
				@endforeach
		    @empty
		    	<h5>No activity at the moment</h5>
			@endforelse
	   </div>
	</div>
</div>

@endsection
