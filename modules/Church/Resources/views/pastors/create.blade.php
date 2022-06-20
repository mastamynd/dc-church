@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('church::general.pastor', 1)]))
<style>
    .hidden{
        display: none !important;
    }
    
</style>

@section('content')
<form action="{{ route('church.pastors.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('general.name')}} <small class="text-danger">*</small></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-user"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="name" required placeholder="Enter Name...">

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('general.email')}}</label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-envelope"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="email" placeholder="Enter {{trans('general.email')}}...">

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('general.phone')}} <small class="text-danger">*</small></label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-phone"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="phone" required placeholder="Enter {{trans('general.phone')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('church::general.phone_2')}}</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-phone"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="phone2" placeholder="Enter {{trans('general.phone_2')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">

                        <label for="" class="control-label">{{trans('general.address')}}</label>
                        <textarea name="address" class="form-control" placeholder="Enter {{trans('general.address')}}..." id="" cols="30" rows="3"></textarea>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('church::general.gender')}}</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-mars"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="gender" placeholder="Enter {{trans('general.gender')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('church::general.passport')}}<small class="text-danger">*</small></label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-id-card"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="id_or_passport" required placeholder="Enter {{trans('church::general.passport')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('church::general.ukombozi')}} </label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-list-ol"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="ukumbozi_number" placeholder="Enter {{trans('general.ukombozi')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('church::general.birth_date')}}</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-calendar"></i>
                                </span>
                            </div>
                            <input type="date" class="form-control" name="date_of_birth" placeholder="Enter {{trans('church::general.ukombozi')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('church::general.country')}}</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-calendar"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="country" placeholder="Enter {{trans('church::general.country')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans_choice('general.cities', 1)}}</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-city"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="city" placeholder="Enter {{trans_choice('general.cities', 1)}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('general.state')}}</label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-city"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="state" placeholder="Enter {{trans('general.state')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('general.zip_code')}}</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-city"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" autocomplete="off" name="zip_code" placeholder="Enter {{trans('general.zip_code')}}...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">{{trans('church::general.photo')}}</label>
                        <input type="file" class="form-control" name="logo" placeholder="Enter {{trans('church::general.photo')}}...">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label"> <input type="checkbox" value=1 checked name="enabled" id=""> {{trans('general.enabled')}}</label>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label"> <input type="checkbox" name="can_login" id=""> Can Login</label>
                    </div>
                </div>
            </div>
            <div class="row pass-area hidden">
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">Password</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-solid fa-key"></i>
                                </span>
                            </div>

                            <input type="password" name="password" placeholder="Enter Password..." class="form-control pass-test">
                        </div>

                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="" class="control-label">Password</label>
                        <div class="input-group input-group-merge">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fas fa-solid fa-key"></i>
                                </span>
                            </div>

                            <input type="password" name="confirm_password" placeholder="Confirm Password..." class="form-control pass-test">
                        </div>
                        <b>
                            <small class="password-mismatch text-danger hidden"><i class="fas fa-solid fa-thumbs-down"></i> Password do not match</small>
                            <small class="password-success text-success hidden"><i class="fas fa-solid fa-thumbs-up"></i> Password match</small>

                        </b>

                    </div>
                </div>
            </div>


        </div>
        <div class="modal-footer">
            <a href="{{route('church.pastors.index')}}" class="btn btn-outline-default float-right">Cancel</a>
            <button class="btn btn-success float-right" type="submit">Save</button>

        </div>
    </div>
</form>
@endsection

@push('scripts_start')
<script>
    $(()=>{
        $('input[name="can_login"]').on('change',(e)=>{
            console.log('test');
            if($(e.currentTarget).is(':checked')){
                $('.pass-area').removeClass('hidden');
            }else{
                $('.pass-area').addClass('hidden'); 
            }
        });
        $('.pass-test').on('keyup',(e)=>{
            var passcode = $('input[name="password"]').val()
            var confirmPasscode = $('input[name="confirm_password"]').val()
            console.log(`passcode ${passcode} confirm passcode ${confirmPasscode}`)
            if(passcode != confirmPasscode && confirmPasscode != ''){
                $('.password-mismatch').removeClass('hidden');
                $('.password-success').addClass('hidden');
            }else if(passcode == confirmPasscode){
                $('.password-mismatch').addClass('hidden');
                $('.password-success').removeClass('hidden');
            }else{
                $('.password-mismatch').addClass('hidden');
                $('.password-success').addClass('hidden');
            }
        })
    })
</script>


@endpush