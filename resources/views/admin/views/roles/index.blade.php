@extends('admin.layouts.master')

@section('content_header')
    <div class="row">
        <a class="btn btn-success" href="{{ route('admin.' . $module . '.create') }}">
            {{ __('buttons.create') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="form-group row">
        <div class="table">
            <livewire:tables.role-table/>
        </div>
    </div>
@endsection
