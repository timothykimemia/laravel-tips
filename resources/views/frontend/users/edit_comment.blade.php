@extends('layouts.app')
@section('content')

    <div class="col-lg-9 col-12">
        <a href="{{ route('users.show_comments') }}" class="btn btn-secondary mb-5">Back</a>
        <h3>Edit Comment on: ({{ $comment->post->title }})</h3>
        <hr>
        <form action="{{ route('users.comment.update', $comment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row pt-2">
                <div class="col-12">
                    <div class="form-group">
                        {{ $comment->name }} ( {{ $comment->email }} )
                    </div>
                </div>
            </div>

            <div class="row pt-2">
                <div class="col-12">
                    <div class="form-group">
                        {{ $comment->comment }}
                    </div>
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
{{--        @include('partial.frontend.users.edit_comment_form')--}}
    </div>
    <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
        @include('partial.frontend.users.sidebar')
    </div>

@endsection
