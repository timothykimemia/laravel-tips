<form action="{{ route('users.comment.update', $comment->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row pt-2">
        <div class="col-12">
            <div class="form-group">
                <label for="name">Name:
                    <input type="text" name="name" value="{{ old('name', $comment->name) }}"
                           class="form-control">
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="email">Email:
                    <input type="email" name="email" value="{{ old('email', $comment->email) }}"
                           class="form-control">
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="url">URL:
                    <input type="text" name="url" value="{{ old('url', $comment->url ? $comment->url : 'No Website') }}"
                           class="form-control">
                    @error('url')<span class="text-danger">{{ $message }}</span>@enderror
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <label for="comment">
                Comment:
                <textarea name="comment" id="comment" cols="30" rows="10" class="form-control">
                        {{ old('comment', $comment->comment) }}
                    </textarea>
                @error('comment')<span class="text-danger">{{ $message }}</span>@enderror
            </label>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <div class="form-group">
                <label for="status">Status: </label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ old('status', $comment->status) == 1 ? 'selected' : '' }}>Show</option>
                    <option value="0" {{ old('status', $comment->status) == 0 ? 'selected' : '' }}>Disable</option>
                </select>
                @error('status')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <input type="submit" value="Save" class="btn btn-primary">
            </div>
        </div>
    </div>
</form>
