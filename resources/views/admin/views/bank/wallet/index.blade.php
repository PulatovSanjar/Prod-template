@extends('admin.layouts.master')

@section('content_header')
    <div class="row">

    </div>
@endsection

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Balance</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @foreach($wallets as $w)
            <tr>
                <td>{{ $w->id }}</td>
                <td>{{ $w->user->name ?? $w->user_id }}</td>
                <td>{{ $w->balance }}</td>
                <td>{{ $w->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $wallets->links() }}

@endsection
