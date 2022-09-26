@php

$show   = $show ?? true;
$edit   = $edit ?? true;
$delete = $delete ?? true;

@endphp

@if ($show)
    <a href="{{ route('admin.' . $module . '.show', $model) }}"
       class="btn btn-xs btn-success">{{ __('labels.buttons.view') }}</a>
@endif

@if ($edit)
    <a href="{{ route('admin.' . $module . '.edit', $model) }}"
       class="btn btn-xs btn-warning">{{ __('labels.buttons.edit') }}</a>
@endif

@if ($delete)
    <form action="{{ route('admin.' . $module . '.destroy', $model) }}"
          method="POST" onsubmit="return confirm('{{ __('labels.are_u_sure_delete') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ __('labels.buttons.delete') }}">
    </form>
@endif
