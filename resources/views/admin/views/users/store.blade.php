@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.' . $module . '.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf

                @include('admin.helpers.form')

            </form>
        </div>
    </div>
@endsection
