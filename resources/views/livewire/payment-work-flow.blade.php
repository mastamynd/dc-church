@push('css')
    <style type="text/css" rel="stylesheet">
        .payment-workflow{
            max-width: 480px;
            margin: auto;  
            background-color: #fff;
            box-shadow: 0px 0px 5px #aaa;
            border-radius: 7px;
            border: 1px solid rgba(0,0,0,0.08);
            overflow-x: hidden;
        }

        .payment-workflow .header{
            border-bottom: 2px solid rgba(0,0,0,0.1);
        }

        .payment-workflow .pages{
            padding: 20px 10px;
            display: flex;
        }

        .payment-workflow .pages .page{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-content: center;
            width: 100%;
        }

        .payment-workflow .header .title{
            font-size: 18px;
            text-transform: uppercase;
            color: #232323;
            padding: 10px 15px 0px;
            font-weight: 800;
        }

        .payment-workflow .header .sub-title{
            font-size: 14px;
            text-transform: uppercase;
            color: #454545;
            padding: 0px 15px 10px;
            font-weight: 700;
        }

        .payment-workflow .header .tabs{
            font-weight: 600;
            font-size: 14px;
        }

        .payment-workflow .header .tabs .tab{
            padding: 20px;
            font-size: 13px;
            background-color: rgba(0,0,0,0.04);
            border-bottom: 2px solid rgba(0,0,0,0.0.8);
            border-radius: 50%;
        }

        .payment-workflow .footer{
            display: flex;
            width: 100%;
            padding: 30px 0px 10px;
        }

        .payment-workflow .footer .clickable{
            padding: 10px 15px;
            font-weight: 200;
            color: #565656;
            flex-grow: 1;
            text-align: center;
        }

        .payment-workflow .footer .clickable:hover{
            color: #232323;
            cursor: pointer;
            background-color: rgba(0,0,0,0.09);
        }
    </style>
@endpush
@php($showNextBtn=false)
<div class="payment-workflow">
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="sub-title">{{ $subtitle }}</div>
    </div>
    <div class="pages">
        @if ($current_page == "first-screen-options")
            <div class="page first-screen-options">
                @foreach ($options as $o=>$p)
                    <div wire:click="set_option('{{$o}}')" class="btn btn-transparent btn-block" style="width: 100%">{{ $o }}</div>
                @endforeach
            </div>
        @endif
        @if ($current_page == "church-code")
            <div class="page church-code">
                <div class="row">
                    {{ Form::textGroup('church_code', "Enter Church Code", 'barcode', ['wire:model.defer'=>'church_code'], null, 'col-md-12') }}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="btn btn-transparent btn-block text-success" wire:click='fetch_church_details'>Verify Church Details</span>
                        </div>
                    </div>
                </div>
                @if ($church_details)
                    @php($showNextBtn=true)
                    <hr />
                    <div style="font-size: 15px; font-weight: 600"><i class="fas fa-church"></i> Church Details</div>
                    @if (isset($church_details->name))
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset($church_details->photo) }}" style="width: 100%; padding: 10px" />
                            </div>
                            <div class="col-8 pt-3">
                                <h3>{{ $church_details->name }} <small class="badge badge-pill badge-dark">{{ $church_details->code }}</small></h3>
                                <h4><i class="fas fa-user"></i> {{ isset($church_details->senior_pastor->pastor) ? $church_details->senior_pastor->pastor->name : '' }}</h4>
                                <h5><i class="fas fa-map-marker"></i> {{ isset($church_details->subregion) ? $church_details->subregion->demarcation->name : '' }} Sub Region</h5>
                            </div>
                        </div>
                    @endif
                @else
                    @if ($church_details != false)
                        <div class="alert alert-warning text-align-center">
                            <i class="fas fa-info-circle"></i> No church matching the code found.
                        </div>
                    @endif
                @endif
            </div>
        @endif
        @if ($current_page == "select-year")
            <div class="page select-year">
                @if (isset($church_details->name))
                    <div class="row">
                        <div class="col-4">
                            <img src="{{ asset($church_details->photo) }}" style="width: 100%; max-height:60px padding: 10px" />
                        </div>
                        <div class="col-8 pt-3">
                            <h3>{{ $church_details->name }} <small class="badge badge-pill badge-dark">{{ $church_details->code }}</small></h3>
                            <h4><i class="fas fa-user"></i> {{ isset($church_details->senior_pastor->pastor) ? $church_details->senior_pastor->pastor->name : '' }}</h4>
                            <h5><i class="fas fa-map-marker"></i> {{ isset($church_details->subregion) ? $church_details->subregion->demarcation->name : '' }} Sub Region</h5>
                        </div>
                    </div>
                @endif
                <div class="pt-1 pb-1">
                    <h5 style="font-size: 15px; font-weight: 600">Select the year</h5>
                    <div wire:click="set_year({{$prev_year}})" class="btn btn-transparent btn-block" style="width: 100%">Previous Year <small>({{$prev_year}})</small></div>
                    <div wire:click="set_year({{$current_year}})" class="btn btn-transparent btn-block" style="width: 100%">Current Year <small>({{$current_year}})</small></div>
                </div>
            </div>
        @endif
        @if ($current_page == "select-month")
            <div class="page select-month"></div>
        @endif
        @if ($current_page == "select-week")
            <div class="page select-week"></div>
        @endif
        @if ($current_page == "enter-amount")
            <div class="page enter-amount"></div>
        @endif
        @if ($current_page == "enter-topup")
            <div class="page enter-topup"></div>
        @endif
        @if ($current_page == "processing-payment")
            <div class="page processing-payment"></div>
        @endif
        @if ($current_page == "payment-complete")
            <div class="page payment-complete"></div>
        @endif
    </div>
    @if ($current_page != 'first-screen-options')
        <div class="footer">
            <div class="clickable" wire:click='jump_to_page("{{ $this->prev_page }}")'><i class="far fa-long-arrow-left"></i> Back</div>
            <div class="clickable" wire:click='jump_to_page("first-screen-options")'><i class="fas fa-menu"></i> Main Menu</div>
            @if($showNextBtn==true)
                <div class="clickable" wire:click='jump_to_page("{{ $this->next_page }}")'><i class="far fa-long-arrow-right"></i> Next</div>
            @endif
        </div>
    @endif
</div>
@push('scripts_end')
<script type="text/javascript">
    
</script>
@endpush