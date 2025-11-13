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
                        @foreach($languages as $language)
                            <th>{{ __('tabs.' . $language) }}</th>
                        @endforeach
                        </thead>
                        <tbody class="text-align: center">
                        @foreach($translations as $field => $translation)
                            <tr>
                                <td>{{ $field }}</td>
                                @foreach($translation as $lang => $value)

                                    <td>
                                        @include('admin.helpers.elements.text', [
                                            'name' => 'key[' . $field . '][' . $lang . '][value]',
                                            'value' => is_string($value) ? $value : NULL
                                        ])
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @include('admin.helpers.save_buttons')
            </form>
        </div>
    </div>
@endsection
