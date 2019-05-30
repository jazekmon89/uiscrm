@extends('layouts.Frontend-client-jci-login')

{{-- Document title not page title --}}
@title('Policy Details')

{{-- Page title --}}
@page_title('JCI - Policy Details')

{{-- Document/Body title --}}
@body_class('rfq')

@jsblock('PolicyDetails.dl', 'dl')

@section('content')
<div class="bem-page__container-grid bem-page__container-white bem-form__container-rounded bem-container__center">                   
    <div class="required"> 
    @if(!$policies || (is_array($policies) && count($policies) == 0))
        <div class="container-fluid">
            <div class="row">       
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="Certificate-Of-Currency">Certificate of Currency</label>
                        <div class="text-center">You currently don't have downloadable certificate of currency for your insurance policies.</div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container-fluid">
            <div class="row">       
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="Certificate-Of-Currency">Certificate of Currency</label>
                        <select class="form-control" id="Certificate-Of-Currency">
                            <option selected="selected">Select Policy</option>
                            @foreach($policies as $key => $value)
                            <option value="{{ $key }}:{{ $value['PolicyRefNum'] }}">{{ $value["DisplayText"] }}</option>
                            @endforeach  
                        </select>
                    </div>           
                </div>
                <div class="col-md-7">&nbsp;</div> 
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">       
                <div class="col-md-5">     
                    <div class="form-group">                   
                        <a class="coc-download-button" href="{{ route('download-coc', ['']) }}">
                            <button class="form-control btn btn-light-orange">Download a certificate of currency</button>
                        </a>
                    </div>                                                     
                </div> 
                <div class="col-md-7">&nbsp;</div>  
            </div>
        </div>
    @endif
    </div>                               
    <hr class="divider" />
    @if(!$policies2 || (is_array($policies2) && count($policies2) == 0))
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="Policy-Amendment-Request">Policy Amendment Request</label>
                </div>
            </div>
            <div class="col-md-7">&nbsp;</div>
        </div>
    </div>
    <div class="container-fluid"> 
        <div class="row"> 
            <div class="col-md-5">       
                <div class="text-center">You don't have insurance policies as of now.</div>
            </div>
        </div>
    </div>        
    @else
    {!! Form::open(['route' => ['amend-policy']]) !!}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="Policy-Amendment-Request">Policy Amendment Request</label>
                </div>
            </div>
            <div class="col-md-7">&nbsp;</div>
        </div>
    </div>
    <div class="container-fluid"> 
        <div class="row"> 
            <div class="col-md-5">       
                <div class="form-group">
                    <select class="form-control" id="Policy-Amendment-Request" name="policy_id">
                        <option selected="selected" value="">Select Policy</option>
                        @foreach($policies2 as $key => $value)
                        <option value="{{ $value['InsurancePolicyID'] }}">{{ $key }}</option>
                        @endforeach
                    </select>
                </div>    
            </div>
            <div class="col-md-7">&nbsp;</div>
        </div>
    </div>   
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-md-12">  
                <div class="form-group">
                    {{ Form::label('message_details', 'Provide details of the ammendments you would like to make your policy') }}   
                </div> 
            </div>
        </div>
    </div>
    <div class="container-fluid"> 
        <div class="row">            
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::textarea('message_details', null, ['rows'=>'6', 'class' => 'form-control message_details', 'style' => 'min-width: 100%']) }}  
                </div>
            </div>    
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::submit('Submit Request',['class' => 'form-control btn btn-maroon submit-amend', 'style' => 'position: relative !important']) }}    
                </div>                         
            </div>                                     
            {!! Form::close() !!}
        </div>
    </div>
    @endif
</div>
@if($policies2 && (is_array($policies2) && count($policies2) > 0))
<!-- Modal -->      
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Request Submitted!</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection


