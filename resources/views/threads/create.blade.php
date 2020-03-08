@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a Thread</div>
                <div class="card-body">
                    @if(count($errors))
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <span> {{ $error }}</span>
                        @endforeach
                    </div>
                    @endif
                   <form action="/threads" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label>
                        <select name="channel_id" id="" class="form-control">
                            <option value="" disabled selected>Choose a channel</option>
                            @foreach ($channels as $channel)
                                <option value="{{ $channel->id}}" {{ old('channel_id') == $channel->id ? 'selected' : ''}}>
                                    {{ $channel->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Thread title" value="{{ old('title') }}">
                    </div>
                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea type="text" class="form-control" name="body" placeholder="Thread body" cols="30" rows="8">{{ old('body') }}</textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Publish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
