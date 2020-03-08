{{-- <reply :attributes="{{$reply}}" inline-template v-cloak>
	<div class="card my-4">
		<div class="card-header">
			<div class="d-flex justify-content-between">
				<p>
					<a href="/profile/{{$reply->owner->name }}">{{ $reply->owner->name }} </a> said {{ $reply->created_at->diffForHumans() }}...
				</p>
				@if(auth()->check())
				    <favorite :reply="{{$reply}}"></favorite>
				@endif
			</div>
		</div>
		<div class="card-body">
			<div class="from-group" v-if="editing">
				<textarea name="" id="" cols="30" rows="2" class="form-control mb-4" v-model="attributes.body"></textarea>
				<button class="btn btn-primary btn-sm" @click="update">Update</button>
				<button class="btn btn-link btn-sm" @click="editing = false ">Cancel</button>
			</div>

			<div v-else="show" v-text="attributes.body"></div>

		</div>
		@can('update', $reply)
		<div class="card-header d-flex">
			<button class="btn btn-secondary btn-sm mr-4" @click="editing = true">Edit</button>
			<button class="btn btn-danger btn-sm mr-4" @click="destroy">Delete</button>
		</div>
		@endcan
	</div>
</reply> --}}
