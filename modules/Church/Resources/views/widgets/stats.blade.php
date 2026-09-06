<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card bg-gradient-{{ $color }} card-stats">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase text-white mb-0">{{ $class->model->name }}</h5>
                    <span class="font-weight-bold text-white mb-0">{{ $value }}</span>
                </div>

                <div class="col-auto">
                    <div class="icon icon-shape bg-white text-{{ $color }} rounded-circle shadow">
                        <i class="{{ $icon }}"></i>
                    </div>
                </div>
            </div>

            <div class="mt-3 mb-0 text-sm text-white d-flex justify-content-between align-items-start">
                <span class="pr-2">
                    {{ $left_label }}
                    <span class="font-weight-bold d-block text-truncate" title="{{ $left_value }}">{{ $left_value }}</span>
                </span>
                <span class="text-right">
                    {{ $right_label }}
                    <span class="font-weight-bold d-block text-truncate" title="{{ $right_value }}">{{ $right_value }}</span>
                </span>
            </div>

            @if(!empty($url))
                <a href="{{ $url }}" class="text-white text-sm d-inline-block mt-2">{{ trans('church::widgets.view_all') }}</a>
            @endif
        </div>
    </div>
</div>
