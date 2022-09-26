@php

    $name    = $name ?? NULL;
    $label   = $label ?? NULL;
    $value   = $value ?? NULL;
    $rows    = $rows ?? 3;
    $error_field = $error_field ?? $name;

@endphp

<div class="row">
    <label for="{{ $name }}">{{ $label }}</label>

    <textarea class="form-control {{ $errors->has($error_field) ? 'is-invalid' : '' }}" name="{{ $name }}" rows="{{ $rows }}">{{ $value }}</textarea>

    {!! $errors->first($error_field, '<span class="error" style="color: red">:message</span>') !!}

</div>
