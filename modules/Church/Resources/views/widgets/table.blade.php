@php
    $align = $align ?? ['text-left', 'text-left', 'text-right'];
@endphp

<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-md-4 {{ $align[0] }}">{{ $columns[0] }}</th>
                        <th class="col-xs-4 col-md-4 {{ $align[1] }}">{{ $columns[1] }}</th>
                        <th class="col-xs-4 col-md-4 {{ $align[2] }}">{{ $columns[2] }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $item)
                        <tr class="row border-top-1 tr-py">
                            <td class="col-xs-4 col-md-4 {{ $align[0] }} long-texts" title="{{ $item[0] }}">
                                {{ $item[0] }}
                                @if(!empty($item['meta']))
                                    <div class="text-muted text-sm">{{ $item['meta'] }}</div>
                                @endif
                            </td>
                            <td class="col-xs-4 col-md-4 {{ $align[1] }} long-texts" title="{{ $item[1] }}">{{ $item[1] }}</td>
                            <td class="col-xs-4 col-md-4 {{ $align[2] }}">{{ $item[2] }}</td>
                        </tr>
                    @empty
                        <tr class="border-top-1">
                            <td colspan="3">
                                <div class="text-muted nr-py" role="status" aria-live="polite">
                                    {{ trans('general.no_records') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(!empty($url))
            <div class="card-footer text-right py-2">
                <a href="{{ $url }}" class="text-sm">{{ trans('church::widgets.view_all') }}</a>
            </div>
        @endif
    </div>
</div>
