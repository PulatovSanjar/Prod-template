@extends('admin.layouts.master')

@section('content_header')
    <div class="row">

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.' . $module . '.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.helpers.save_buttons')

                <div class="table">
                    <table class="table">
                        <thead style="text-align: center">
                        <th>{{ __('fields.key') }}</th>
                        <th>{{ __('fields.value') }}</th>
                        </thead>
                        <tbody style="text-align: center">
                        @foreach($variables as $variable)
                            <tr>
                                <td>{{ $variable->key }}</td>
                                <td>
                                    @include('admin.helpers.elements.text', [
                                        'name' => 'key[' . $variable->key . '][value]',
                                        'value' => $variable->value ?? null
                                    ])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
