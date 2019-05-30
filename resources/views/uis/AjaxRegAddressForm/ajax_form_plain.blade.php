    <div class="form-group">
        <div class="prev-home-addr-form">
            @if($index > 1)
            <div class="text-right">
                <button type="button" class="btn btn-danger remove-button" data-index="{{$index}}">Remove</button>
            </div>
            @endif
            @if(false)
            <label for="prev_home_unit_number_{{$index}}" class="control-label">Unit Number</label>
            <div class="">
                <input id="prev_home_unit_number_{{$index}}" type="text" class="form-control" name="prev_home_unit_number_{{$index}}" value="" autofocus>
            </div>

            <label for="prev_home_street_number_{{$index}}" class="control-label required">Street No.</label>
            <div class="">
                <input id="prev_home_street_number_{{$index}}" type="text" class="form-control" name="prev_home_street_number_{{$index}}" value="" required autofocus>
            </div>

            <label for="prev_home_street_name_{{$index}}" class="control-label required">Street Name</label>
            <div class="">
                <input id="prev_home_street_name_{{$index}}" type="text" class="form-control" name="prev_home_street_name_{{$index}}" value="" autofocus required>
            </div>
            @endif
            <label for="prev_home_address_line_1_{{$index}}" class="control-label required">Address Line 1</label>
            <div class="">
                <input id="prev_home_address_line_1_{{$index}}" type="text" class="form-control" name="prev_home_address_line_1_{{$index}}" value="" autofocus required>
            </div>

            <label for="prev_home_address_line_2_{{$index}}" class="control-label required">Address Line 2</label>
            <div class="">
                <input id="prev_home_address_line_2_{{$index}}" type="text" class="form-control" name="prev_home_address_line_2_{{$index}}" value="" autofocus>
            </div>

            <label for="prev_home_town_or_suburb_{{$index}}" class="control-label required">Town / Suburb</label>
            <div class="">
                <input id="prev_home_town_or_suburb_{{$index}}" type="text" class="form-control" name="prev_home_town_or_suburb_{{$index}}" value="" autofocus required>
            </div>

            <label for="prev_home_post_code_{{$index}}" class="control-label required">Post Code</label>
            <div class="">
                <input id="prev_home_post_code_{{$index}}" type="text" class="form-control" name="prev_home_post_code_{{$index}}" value="" autofocus required>
            </div>
            @if(false)
            <label for="prev_home_city_{{$index}}" class="control-label required">City</label>
            <div class="">
                <input id="prev_home_city_{{$index}}" type="text" class="form-control" name="prev_home_city_{{$index}}" value="" autofocus required>
            </div>
            @endif
            <label for="prev_home_state_{{$index}}" class="control-label required">State</label>
            <div class="">
                {{ Form::jInput('select', 'prev_home_state_'.$index, $state_options, null, ['class'=>'form-control', 'id'=>'prev_home_state_'.$index, 'required'=>true, 'autofocus'=>true]) }}
            </div>

            <label for="prev_home_country_{{$index}}" class="control-label required">Country</label>
            <div class="">
                <input id="prev_home_country_{{$index}}" type="text" class="form-control" name="prev_home_country_{{$index}}" value="" autofocus required>
            </div>
            <div class="form-group">
                <label for="prev_home_from_date_{{$index}}" class="control-label required">From</label>
                <div class="">
                    <div class="input-group input-append date" id="date_of_birth_cont">
                        <input id="prev_home_from_date_{{$index}}" type="text" class="form-control" name="prev_home_from_date_{{$index}}" value="" autofocus required><span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </div>

                <label for="prev_home_to_date_{{$index}}" class="control-label required">To</label>
                <div class="">
                    <div class="input-group input-append date" id="date_of_birth_cont">
                        <input id="prev_home_to_date_{{$index}}" type="text" class="form-control" name="prev_home_to_date_{{$index}}" value="" autofocus required><span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-left add-button-wrapper compress">
        <button type="button" class="btn btn-success add-button">Add address</button>
    </div>