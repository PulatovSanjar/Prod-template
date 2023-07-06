@extends('adminlte::page')

@push('css')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

@endpush

@push('js')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ config('services.google-map-api-key') }}&callback=initMap"></script>
@endpush
