@extends('admin.layout.app')

@section('administration-content')

<p>
    <a class="btn btn-sm btn-default" href="{{ route('admin.channels.create') }}">
        New Channel
        <span class="glyphicon glyphicon-plus"></span>
    </a>
</p>

 <channel-view :channels="{{ $channels }}" v-cloak></channel-view>

@endsection
