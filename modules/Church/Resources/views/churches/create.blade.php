@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('church::general.title', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => ['church.store', 'create'],
            'id' => 'church',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'autocomplete' => "off",
            'class' => 'form-loading-button needs-validation',
            'novalidate' => 'true'
        ]) !!}

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'church', ['required'=>'required' ]) }}

                {{ Form::textGroup('email', trans('general.email'), 'at', ['type'=>'email']) }}

                {{ Form::textGroup('code', trans('church::general.code'), 'file', ['autocomplete' => 'off', 'readonly'=>true]) }}

                <div class="form-group col-md-6">
                    <label for="name" class="form-control-label">{{ trans('church::general.founded_on') }}</label>
                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                        <input class="form-control" placeholder="Enter {{ trans('church::general.founded_on') }}" name="founded_on" type="date">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="name" class="form-control-label">{{ trans('church::general.image') }}</label>
                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                        <input class="form-control" placeholder="Enter {{ trans('church::general.image') }}" name="photo" type="file">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('church.index') }}
            </div>
        </div>

        {{ Form::hidden('type', 'church') }}

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    {{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush