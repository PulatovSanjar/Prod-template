<div class="row box-footer buttons-top mt-3 mb-3">
    <div class="col-md-6">
            <a href="{{ route('admin.' . $module . '.index') }}" class="btn btn-secondary">
                {{__('buttons.back')}}
            </a>
    </div>
    <div class="col-md-6 justify-content-end text-right">
        <button class="btn btn-success btn-flat button">
            {{ __('buttons.save') }}
        </button>
    </div>

</div>
