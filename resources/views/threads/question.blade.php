<div class="card mb-4" v-if="!editing">
    <div class="card-header d-flex justify-content-between">
        <span>
            <img src="{{ asset($thread->creator->avatar_path) }}" alt="{{ $thread->creator->name }}"
            width="30" height="30" class="mr-1 rounded">
            <a href="/profile/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:
            <span v-text="title"></span>
        </span>
        <form action="{{$thread->path()}}" method="post" v-if="authorize('owns', thread)">
            @csrf
            @method('delete')
            <button class="btn btn-link" type="submit">Delete thread</button>
        </form>
    </div>
    <div class="card-body">
        <span v-text="body"></span>
    </div>
    <div class="card-footer"  v-if="authorize('owns', thread)">
        <button class="btn btn-sm btn-secondary" @click="editing = true">Edit</button>
    </div>
</div>
<div class="card mb-4" v-else>
    <div class="card-header">
        <h4>Editing the Thread</h4>
      <div class="form-group">
         <label for="title">Title</label>
          <input type="text" class="form-control" v-model="form.title">
      </div>
    </div>
    <div class="card-body">
        <div class="form-group">
             <label for="title">Body</label>
            <textarea cols="10" class="form-control" rows="5" v-model="form.body"></textarea>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <button class="btn btn-sm btn-primary mr-2" @click="updateForm">Update</button>
        <button class="btn btn-sm btn-danger" @click="resetForm">Cancel</button>
    </div>
</div>
