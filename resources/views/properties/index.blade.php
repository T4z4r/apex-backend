@extends('layouts.app')
@section('content')
    <a href="{{ route('properties.create') }}" class="btn btn-primary mb-3">Add Property</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($properties as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->location }}</td>
                    <td>
                        <a href="{{ route('properties.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('properties.destroy', $p) }}" style="display:inline-block;">@csrf
                            @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
