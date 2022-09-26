@php

    $name    = $name ?? NULL;
    $label   = $label ?? NULL;
    $value   = $value ?? 'https://placehold.co/250';
    $image   = $image ?? false;
    $error_field = $error_field ?? $name;

@endphp

<div class="row">
    <label for="{{ $name }}">{{ $label }}</label>

    <div class="col-xs-12 col-sm-7 col-md-10 col-lg-2">
        @if ($image)
            <img src="{{ $value }}" height="150" width="150" class="img-thumbnail mb-1 lg-1">
        @endif

        <input class="{{ $errors->has($error_field) ? 'is-invalid' : '' }}" type="file" name="{{ $name }}">
    </div>
    {!! $errors->first($error_field, '<span class="error" style="color: red">:message</span>') !!}

</div>
