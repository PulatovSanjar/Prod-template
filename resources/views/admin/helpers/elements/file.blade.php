@php

    $name    = $name ?? NULL;
    $label   = $label ?? NULL;
    $value   = $value ?? 'https://placehold.co/250';
    $image   = $image ?? false;
    $multiple = $multiple ?? false;
    $error_field = $error_field ?? $name;
    $mimeTypes = $mimeTypes ?? [];
    $livewireModel = $livewireModel ?? false;

@endphp

<div class="row">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <div class="col-xs-12 col-sm-7 col-md-10 col-lg-2">
        @if ($image)
            @if ($multiple && is_array($value) && !empty($value))
                @foreach($value as $item)
                    <img src="{{ $item }}" height="150" width="150" class="img-thumbnail mb-1 lg-1">
                @endforeach
            @else
                <img src="{{ is_string($value) ? $value : 'https://placehold.co/250' }}" height="150" width="150" class="img-thumbnail mb-1 lg-1">
            @endif
        @endif

        <input class="{{ $errors->has($error_field) ? 'is-invalid' : '' }}" {{ $multiple ? 'multiple' : '' }} type="file" name="{{ $name }}" accept="{{ implode(', ', $mimeTypes) }}" @if ($livewireModel) wire:model.defer="{{ $livewireModel }}" @endif>
    </div>
    {!! $errors->first($error_field, '<span class="error" style="color: red">:message</span>') !!}

</div>
