@php

    $name        = $name ?? NULL;
    $label       = $label ?? NULL;
    $current     = $current ?? NULL;
    $with_no_set = $with_no_set ?? false;
    $multiple    = $multiple ?? false;
    $options     = $options ?? [];
    $error_field = $error_field ?? $name;
    $readonly    = $readonly ?? false;
    $livewireModel = $livewireModel ?? false;

@endphp

<div class="row">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

        <select
            class="form-control {{ $errors->has($error_field) ? 'is-invalid' : '' }}  {{ $multiple ? 'select2' : '' }}"
            name="{{ $name }}" {{ $readonly ? 'disabled' : '' }} {{ $multiple ? 'multiple' : ''}} @if ($livewireModel) wire:model.defer="{{ $livewireModel }}" @endif>
            @if ($with_no_set)
                <option value="">{{ __('labels.no_set') }}</option>
            @endif
            @foreach ($options as $key => $value)
                <option
                    value="{{ $key }}" {{ (is_array($current) ? in_array($key, $current) : $current == $key) ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>

    {!! $errors->first($error_field, '<span class="error" style="color: red">:message</span>') !!}

</div>
