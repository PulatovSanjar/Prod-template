@php

$name    = $name ?? NULL;
$label   = $label ?? NULL;
$value   = $value ?? NULL;
$type    = $type ?? 'text';
$error_field = $error_field ?? $name;
$readonly = $readonly ?? false;

@endphp

<div class="row">
    <label for="{{ $name }}">{{ $label }}</label>

    <input class="form-control {{ $errors->has($error_field) ? 'is-invalid' : '' }}" type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" {{ $readonly ? 'disabled' : '' }}>

    {!! $errors->first($error_field, '<span class="error" style="color: red">:message</span>') !!}

</div>
