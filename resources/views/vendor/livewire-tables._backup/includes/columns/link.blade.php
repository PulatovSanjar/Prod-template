@if (in_array('delete-button', $attributes))
    <form action="{{ $path }}"
          method="POST" onsubmit="return confirm('{{ __('admin.are_u_sure_delete') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" {!! count($attributes) ? $column->arrayToAttributes($attributes) : '' !!} value="{{ $title }}">
    </form>

@else
<a href="{{ $path }}" {!! count($attributes) ? $column->arrayToAttributes($attributes) : '' !!}>{{ $title }}</a>
@endif
