@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Policy Details')

{{-- Page title --}}
@page_title('Policy Details')

{{-- Document/Body title --}}
@body_class('sidebar-mini skin-red rfq layout-box')

@jsblock('PolicyDetails.dl', 'dl')

@section('body')

<div class="panel-body offset1 org-policies">
    <div class="row">
        <div class="col-md-12">  
            <div id="policy-details">
                <div class="row">                    
                    <div class="col-md-12 required">
                        <div class="form-group">
                            <div class="col-md-5">
                                <label for="Certificate-Of-Currency">Certificate of Currency</label>
                                <select class="form-control" id="Certificate-Of-Currency">
                                    <option selected="selected">Select Policy</option>
                                    @foreach($policies as $key => $value)
                                    <option value="{{ $key }}:{{ $value['PolicyRefNum'] }}">{{ $value["DisplayText"] }}</option>
                                    @endforeach  
                                </select>
                                <div class="spacers-1">&nbsp;</div>
                                <a class="coc-download-button" href="{{ route('download-coc', ['']) }}">
                                    <button class="form-control btn btn-primary btn-orange">Download a certificate of currency</button>
                                </a>
                            </div>                                                   
                        </div>                                        
                    </div>                       
                </div>
                <hr class="divider" />
                  {!! Form::open(['route' => ['amend-policy']]) !!}
                <div class="row">
                    <div class="form-group col-md-5">
                        <div class="col-md-12">
                            <label for="Policy-Amendment-Request">Policy Amendment Request</label>
                        </div>
                        <div class="col-md-12">
                            <select class="form-control" id="Policy-Amendment-Request" name="policy_id">
                                <option selected="selected" value="">Select Policy</option>
                                @foreach($policies2 as $key => $value)
                                <option value="{{ $value['InsurancePolicyID'] }}">{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">  
                        <div class="col-md-12">
                            {{ Form::label('message_details', 'Provide details of the ammendments you would like to make your policy') }}   
                        </div> 
                        <div class="col-md-8">
                            {{ Form::textarea('message_details', null, ['rows'=>'6', 'class' => 'form-control message_details', 'style' => 'min-width: 100%']) }}  
                        </div>
                        <div class="col-md-4">
                            {{ Form::submit('Submit Request',['class' => 'form-control btn-outline-maroon btn btn-default submit-amend', 'style' => 'position: relative !important']) }}    
                        </div>                         
                    </div>                                     
                    {!! Form::close() !!}
                </div>
            </div>
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
        </div>
        </div>              
    </div>
</div>                  
@endsection


