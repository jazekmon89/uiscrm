@inject('Policy', 'App\Helpers\PolicyHelper')
@inject('RFQRequest', 'App\Http\Requests\Quotes\RFQRequest')
<div class="bem-container__center">
	<div id="requester-fields">
		<div class="grid-100">
			<div class="row">
				<div class="col-md-6 required">
					<div class="form-group">
						{{ Form::jLabel("{$current->Name}.RFQ.RequesterName", 'Contact Person:') }}
						{{ Form::jInput('text', "{$current->Name}.RFQ.RequesterName", null, ['class' => 'form-control']) }}
					</div>
				</div>
				<div class="col-md-6 required">
					<div class="form-group">
						{{ Form::jLabel("{$current->Name}.RFQ.EmailAddress", 'Email Address:') }}
						{{ Form::jInput("email", "{$current->Name}.RFQ.EmailAddress", null, ['class' => 'form-control']) }}
					</div>
				</div>			
				<div class="col-md-6 required">
					<div class="form-group">
						{{ Form::jLabel("{$current->Name}.RFQ.PhoneNumber", 'Phone Number/Mobile:') }}
						{{ Form::jInput('text', "{$current->Name}.RFQ.PhoneNumber", null, ['class' => 'form-control']) }}
					</div>
				</div>
				<div class="col-md-6">&nbsp;</div>	
			</div>
		</div>											
		{{-- UIS FORMS ONLY --}}
		@if($RFQRequest->forUIS())	
		<div class="grid-100">
			<div class="row">		
				<div class="col-md-6 required">
					<div class="form-group">
						{{ Form::jLabel("{$current->Name}.RFQ.BirthDate", 'Birth Date:', [], false) }}
						<div class='input-group datetimepicker'>
							{{ Form::jInput("text", "{$current->Name}.RFQ.BirthDate", null, ['class' => 'form-control']) }}
							<div class='input-group-addon'>
								<span class='glyphicon glyphicon-calendar'></span>
							</div>
						</div>
					</div>	
				</div>	
			</div>		
		</div>	
		<div class="grid-100">
			<div class="row">	
				<div class="form-group">
					<div class="col-md-12 address-fields" id="home-address" data-address='home_address'>	
						<h4>Home Address<i class="glyphicon-asterisk"></i></h4>
						@include("Quotes.Form.partials.address-form", ['baseKey' => $current->Name . ".home_addr"])
					</div>					
				</div>
			</div>
		</div>			
		@endif
	</div>
	@if($RFQRequest->hasBusinessFields())
	<div id="business-credentials">
		<h5 class="bem-text_left">Business Details</h5>
		{{ Form::jInput("hidden", "{$current->Name}.Business.InsurableBusinessID") }}
		<div class="grid-100">
			<div class="row">
				<div class="col-md-6 required">	
					<div class="form-group">					
						{{ Form::jLabel("{$current->Name}.Business.BusinessStructureTypeID", 'Business Structure:') }}
						{{ Form::jInput("select", "{$current->Name}.Business.BusinessStructureTypeID", business_structures(), null, ['class' => 'form-control', 'placeholder' => '']) }}
					</div>
				</div>
				<div class="col-md-6 required">
					<div class="form-group">				
						{{ Form::jLabel("{$current->Name}.RFQ.InsuredName", 'Company or Insured Name:') }}
						{{ Form::jInput("text", "{$current->Name}.RFQ.InsuredName", null, ['class' => 'form-control']) }}
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::jLabel("{$current->Name}.Business.TradeName", 'Trading As:') }}
						{{ Form::jInput("text", "{$current->Name}.Business.TradingName", null, ['class' => 'form-control']) }}
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::jLabel("{$current->Name}.Business.AustralianBusinessNumber", 'ABN No.:', [], false) }}
						{{ Form::jInput("text", "{$current->Name}.Business.AustralianBusinessNumber", null, ['class' => 'form-control']) }}
					</div>	
				</div>
				<div class="col-md-12">
					<div class="form-group">
						{{ Form::jLabel("{$current->Name}.Business.IsRegisteredForGST", 'Registered for GST:') }}
						{{ Form::jBoolean("{$current->Name}.Business.IsRegisteredForGST", ['Y' => 'Yes', 'N' => 'No'], null, ['before' => '<div class=\'disp-inline-block\'>']) }}
					</div>	
				</div>
			</div>			
		</div>			
		<div class="address-fields" id="mail-address" data-address='mail_address'>
			<h5 class="bem-text_left">Mailing Address</h5>
			<div class="grid-100">
				<div class="row">
					@include("Quotes.Form.partials.address-form", ['baseKey' => $current->Name . ".mail_addr"])		
				</div>
			</div>		
		</div>
		@endif
	</div>
</div>



