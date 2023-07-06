@php

$name    = $name ?? NULL;
$label   = $label ?? NULL;
$value   = $value ?? NULL;
$type    = $type ?? 'text';
$error_field = $error_field ?? $name;
$readonly = $readonly ?? false;
$livewireModel = $livewireModel ?? false;

@endphp

<div class="row">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <input class="form-control {{ $errors->has($error_field) ? 'is-invalid' : '' }}" type="{{ $type }}"
           name="{{ $name }}" value="{{ $value ?? old($name) }}" {{ $readonly ? 'disabled' : '' }} @if ($livewireModel) wire:model.defer="{{ $livewireModel }}" @endif>

    {!! $errors->first($error_field, '<span class="error" style="color: red">:message</span>') !!}

</div>
