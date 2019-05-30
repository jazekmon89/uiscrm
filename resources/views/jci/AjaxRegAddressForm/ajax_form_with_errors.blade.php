<div class="prev-home-addr-form">
    @if ($i>1)
    <div class="form-group">
        <div class="text-right">
            <button type="button" class="btn btn-danger remove-button" data-index="{{$i}}">Remove</button>
        </div>
    </div>    
    @endif
    @if(false)
    <div class="form-group">
        <label for="prev_home_unit_number_{{$i}}" class="control-label">Unit Number: </label>
        <div class="@if(isset($form_errors['prev_home_unit_number_'.$i])){{' has-error'}}@else{{''}})@endif">
            <input id="prev_home_unit_number_{{$i}}" type="text" class="form-control" name="prev_home_unit_number_{{$i}}" value="@if(isset($all_details['prev_home_unit_number_'.$i])){{$all_details['prev_home_unit_number_'.$i]}}@else{{''}}@endif" autofocus>
            @if (isset($form_errors['prev_home_unit_number_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_unit_number_'.$i])){{$form_errors['prev_home_unit_number_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="prev_home_street_number_{{$i}}" class="control-label required">Street No.: </label>
        <div class="@if(isset($form_errors['prev_home_street_number_'.$i])){{' has-error'}}@else{{''}})@endif">
            <input id="prev_home_street_number_{{$i}}" type="text" class="form-control" name="prev_home_street_number_{{$i}}" value="@if(isset($all_details['prev_home_street_number_'.$i])){{$all_details['prev_home_street_number_'.$i]}}@else{{''}}@endif" required autofocus>
            @if (isset($form_errors['prev_home_street_number_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_street_number_'.$i])){{$form_errors['prev_home_street_number_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="prev_home_street_name_{{$i}}" class="control-label required">Street Name: </label>
        <div class="@if(isset($form_errors['prev_home_street_name_'.$i])){{' has-error'}}@else{{''}})@endif">
            <input id="prev_home_street_name_{{$i}}" type="text" class="form-control" name="prev_home_street_name_{{$i}}" value="@if(isset($all_details['prev_home_street_name_'.$i])){{$all_details['prev_home_street_name_'.$i]}}@else{{''}}@endif" autofocus required>
            @if (isset($form_errors['prev_home_street_name_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_street_name_'.$i])){{$form_errors['prev_home_street_name_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    @endif
    <div class="form-group">
        <div class="">
            <label for="prev_home_address_line_1_{{$i}}" class="control-label required">Address Line 1: </label>
            <div class="@if(isset($form_errors['prev_home_address_line_1_'.$i])){{' has-error'}}@else{{''}})@endif">
                <input id="prev_home_address_line_1_{{$i}}" type="text" class="form-control" name="prev_home_address_line_1_{{$i}}" value="@if(isset($all_details['prev_home_address_line_1_'.$i])){{$all_details['prev_home_address_line_1_'.$i]}}@else{{''}}@endif" autofocus required>
                @if (isset($form_errors['prev_home_address_line_1_'.$i]))
                    <span class="help-block">
                        <strong>@if(isset($form_errors['prev_home_address_line_1_'.$i])){{$form_errors['prev_home_address_line_1_'.$i]}}@else{{''}}@endif</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="">
            <label for="prev_home_address_line_2_{{$i}}" class="control-label">Address Line 2: </label>
            <div class="@if(isset($form_errors['prev_home_address_line_2_'.$i])){{' has-error'}}@else{{''}})@endif">
                <input id="prev_home_address_line_2_{{$i}}" type="text" class="form-control" name="prev_home_address_line_2_{{$i}}" value="@if(isset($all_details['prev_home_address_line_2_'.$i])){{$all_details['prev_home_address_line_2_'.$i]}}@else{{''}}@endif" autofocus>
                @if (isset($form_errors['prev_home_address_line_2_'.$i]))
                    <span class="help-block">
                        <strong>@if(isset($form_errors['prev_home_address_line_2_'.$i])){{$form_errors['prev_home_address_line_2_'.$i]}}@else{{''}}@endif</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="prev_home_town_or_suburb_{{$i}}" class="control-label required">Town / Suburb: </label>
        <div class="@if(isset($form_errors['prev_home_town_or_suburb_'.$i])){{' has-error'}}@else{{''}})@endif">
            <input id="prev_home_town_or_suburb_{{$i}}" type="text" class="form-control" name="prev_home_town_or_suburb_{{$i}}" value="@if(isset($all_details['prev_home_town_or_suburb_'.$i])){{$all_details['prev_home_town_or_suburb_'.$i]}}@else{{''}}@endif" autofocus required>
            @if (isset($form_errors['prev_home_town_or_suburb_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_town_or_suburb_'.$i])){{$form_errors['prev_home_town_or_suburb_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="prev_home_post_code_{{$i}}" class="control-label required">Post Code: </label>
        <div class="@if(isset($form_errors['prev_home_post_code_'.$i])){{' has-error'}}@else{{''}})@endif">
            <input id="prev_home_post_code_{{$i}}" type="text" class="form-control" name="prev_home_post_code_{{$i}}" value="@if(isset($all_details['prev_home_post_code_'.$i])){{$all_details['prev_home_post_code_'.$i]}}@else{{''}}@endif" autofocus required>
            @if (isset($form_errors['prev_home_post_code_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_post_code_'.$i])){{$form_errors['prev_home_post_code_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    @if(false)
    <div class="form-group">
        <label for="prev_home_city_{{$i}}" class="control-label required">City: </label>
        <div class="@if(isset($form_errors['prev_home_city_'.$i])){{' has-error'}}@else{{''}})@endif">
            <input id="prev_home_city_{{$i}}" type="text" class="form-control" name="prev_home_city_{{$i}}" value="@if(isset($all_details['prev_home_city_'.$i])){{$all_details['prev_home_city_'.$i]}}@else{{''}}@endif" autofocus required>
            @if (isset($form_errors['prev_home_city_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_city_'.$i])){{$form_errors['prev_home_city_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    @endif
    <div class="form-group">
        <label for="prev_home_state_{{$i}}" class="control-label required">State: </label>
        <div class="@if(isset($form_errors['prev_home_state_'.$i])){{' has-error'}}@else{{''}})@endif">
            {{ Form::jInput('select', 'prev_home_state_'.$i, $state_options, (!empty(old('prev_home_state_'.$i))?old('prev_home_state_'.$i):array_key_exists('prev_home_state_'.$i,$all_details)?$all_details['prev_home_state_'.$i]:''), ['class'=>'form-control', 'id'=>'prev_home_state_'.$i, 'required'=>true, 'autofocus'=>true]) }}
            @if (isset($form_errors['prev_home_state_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_state_'.$i])){{$form_errors['prev_home_state_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="prev_home_country_{{$i}}" class="control-label required">Country: </label>
        <div class="@if(isset($form_errors['prev_home_country_'.$i])){{' has-error'}}@else{{''}})@endif">
            <input id="prev_home_country_{{$i}}" type="text" class="form-control" name="prev_home_country_{{$i}}" value="@if(isset($all_details['prev_home_country_'.$i])){{$all_details['prev_home_country_'.$i]}}@else{{''}}@endif" autofocus required>
            @if (isset($form_errors['prev_home_country_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_country_'.$i])){{$form_errors['prev_home_country_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="prev_home_from_date_{{$i}}" class="control-label required">From: </label>
        <div class="@if(isset($form_errors['prev_home_from_date_'.$i])){{' has-error'}}@else{{''}})@endif">
            <div class="input-group input-append date" id="date_of_birth_cont">
                <input id="prev_home_from_date_{{$i}}" type="text" class="form-control" name="prev_home_from_date_{{$i}}" value="@if(isset($all_details['prev_home_from_date_'.$i])){{$all_details['prev_home_from_date_'.$i]}}@else{{''}}@endif" autofocus required><span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
            @if (isset($form_errors['prev_home_from_date_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_from_date_'.$i])){{$form_errors['prev_home_from_date_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif                    
        </div>
    </div>
    <div class="form-group">
        <label for="prev_home_to_date_{{$i}}" class="control-label required">To: </label>
        <div class="@if(isset($form_errors['prev_home_to_date_'.$i])){{' has-error'}}@else{{''}})@endif">
            <div class="input-group input-append date" id="date_of_birth_cont">
                <input id="prev_home_to_date_{{$i}}" type="text" class="form-control" name="prev_home_to_date_{{$i}}" value="@if(isset($all_details['prev_home_to_date_'.$i])){{$all_details['prev_home_to_date_'.$i]}}@else{{''}}@endif" autofocus required><span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
            @if (isset($form_errors['prev_home_to_date_'.$i]))
                <span class="help-block">
                    <strong>@if(isset($form_errors['prev_home_to_date_'.$i])){{$form_errors['prev_home_to_date_'.$i]}}@else{{''}}@endif</strong>
                </span>
            @endif
        </div>
    </div>
</div>
@if( $i == $prev_address_count)
<div class="form-group">
    <div class="text-left add-button-wrapper compress">
        <button type="button" class="btn btn-success add-button">Add address</button>
    </div>
</div>
@endif