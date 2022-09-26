@include('admin.helpers.save_buttons')

@php

    $translatable = $translatable ?? false;

    $tabs = scandir(resource_path('views/admin/views/' . $module . '/tabs'));
    unset($tabs[0], $tabs[1]);

@endphp

<div class="card">
    <div class="card-header">
        {{ __('admin.modules.' . (empty($model) ? 'store' : 'update'), ['module' => $module]) }}
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">

            @if ($translatable)
                @foreach(config('translatable.locales') as $locale)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                           data-toggle="pill" href="#tab_{{ $locale }}" role="tab"
                           aria-controls="tab_{{ $locale }}" aria-selected="true">
                            {{ __('tabs.' . $locale) }}</a>
                @endforeach
            @endif

            @foreach($tabs as $tab)
                @if($translatable && substr($tab, 0, -10) == 'locale')
                    @continue
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ !$translatable && $loop->first ? 'active' : '' }}"
                       data-toggle="pill" href="#tab_{{ substr($tab, 0, -10) }}" role="tab"
                       aria-controls="tab_{{ substr($tab, 0, -10) }}" aria-selected="true">
                        {{__('tabs.' . substr($tab, 0, -10))}}</a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content pt-4 pb-4 pl-2 pr-2">

            @if ($translatable)
                @foreach(config('translatable.locales') as $locale)
                    <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" id="tab_{{ $locale }}" role="tabpanel">
                        @include('admin.views.' . $module . '.tabs.locale')
                    </div>
                @endforeach
            @endif

            @foreach($tabs as $tab)
                @if($translatable && substr($tab, 0, -10) == 'locale')
                    @continue
                @endif
                <div class="tab-pane fade {{ !$translatable && $loop->first ? 'active show' : '' }}" id="tab_{{ substr($tab, 0, -10) }}" role="tabpanel">
                    @include('admin.views.' . $module . '.tabs.' . substr($tab, 0, -10))
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('admin.helpers.save_buttons')
