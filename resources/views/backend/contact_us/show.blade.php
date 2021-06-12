@extends('layouts.admin')
@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">{{ $contact->title }}</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Messages</span>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Title</th>
                        <td>{{ $contact->title }}</td>
                    </tr>
                    <tr>
                        <th>From</th>
                        <td>{{ $contact->name }} <{{ $contact->email }}></td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>{!! $contact->message !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
