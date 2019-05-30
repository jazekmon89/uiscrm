<div class="grid-100">
	<div id="requester-fields" class="row">
		<h4 class="h-pad-3x">Contact Details</h4>
		<div class="col-md-6">
			<div class="form-group required">
				{{ Form::jLabel(".InsuredName", 'Insured Name:') }}
				{{ Form::jInput('text', "InsuredName", $Client['InsuredName'], ['class' => 'form-control']) }}
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group required">
				{{ Form::jLabel("ContactPerson", 'Contact Person:') }}
				{{ Form::jInput('text', "ContactPerson", $Client['ContactPersion'], ['class' => 'form-control']) }}
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group required">
				{{ Form::jLabel("EmailAddress", 'Email Address:') }}
				{{ Form::jInput("email", "EmailAddress", $Client['EmailAddress'], ['class' => 'form-control']) }}
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group required">
				{{ Form::jLabel("PhoneNumber", 'Phone Number/Mobile:') }}
				{{ Form::jInput('text', "RFQ.PhoneNumber", $Client['PhoneNumber'], ['class' => 'form-control']) }}
			</div>
		</div>	
	</div>
	<div id="business-credentials" class="row">
		<h4 class="h-pad-3x">Business Details</h4>
		{{ Form::jInput("hidden", "InsurableBusinessID", $Client['InsurableBusinessID']) }}
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::jLabel("TradeName", 'Trading As:') }}
				{{ Form::jInput("text", "TradingName", $Client['TradingName'], ['class' => 'form-control']) }}
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group required">
				{{ Form::jLabel("BusinessStructureTypeID", 'Business Structure:') }}
				{{ Form::jInput("select", "BusinessStructureTypeID", $Client['BusinessStructureTypes'], $Client['BusinessStructure'], ['class' => 'form-control', 'placeholder' => '']) }}
			</div>
		</div>	
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::jLabel("AustralianBusinessNumber", 'ABN No.:') }}
				{{ Form::jInput("text", "AustralianBusinessNumber", $Client['ABN'], ['class' => 'form-control']) }}
			</div>
		</div>	
		<div class="form-group required">
			<div class="col-md-2">
				{{ Form::jLabel("IsRegisteredForGST", 'Registered for GST:') }}
			</div>
			<div class="col-md-3">
				{{ Form::jBoolean("IsRegisteredForGST", ['Y' => 'Yes', 'N' => 'No'], 'Y'), $Client['RegisterdForGST'], ['id'=>'IsRegisteredForGST']}}
			</div>
		</div>
	</div>		
	<div class="address-fields row" id="mail-address" data-address='mail_address'>
		
		<h4 class="h-pad-3x">Mailing Address</h4>
		
		<div class="col-md-12">
			{{ Form::jInput("hidden", "AddressID", $Client['AddressID'], ['class' => 'AddressID']) }}
			</div>
			@if(false)
			<div class="col-md-6">
				<div class="form-group UnitNumber">
					{{ Form::jLabel("UnitNumber", 'Unit No.:') }}
					{{ Form::jInput("number", "UnitNumber", $Client['UnitNumber'], ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group required StreetNumber">
					{{ Form::jLabel("StreetNumber", 'Street No.:') }}
					{{ Form::jInput("number", "StreetNumber", $Client['StreetNumber'], ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group required StreetName">
					{{ Form::jLabel("StreetName", 'Street Name:') }}
					{{ Form::jInput("text", "StreetName", $Client['StreetName'], ['class' => 'form-control']) }}
				</div>
			</div>
			@endif
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group AddressLine1">
							{{ Form::jLabel("AddressLine1", 'Address Line 1:') }}
							{{ Form::jInput("text", "AddressLine1", $Client['AddressLine1'], ['class' => 'form-control']) }}
						</div>
					</div>
				</div>		
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group AddressLine2">
							{{ Form::jLabel("AddressLine2", 'Address Line 2:') }}
							{{ Form::jInput("text", "AddressLine2", $Client['AddressLine2'], ['class' => 'form-control']) }}
						</div>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-4">	
					<div class="col-md-6">
						<div class="form-group required City">
							{{ Form::jLabel("City", 'City:') }}
							{{ Form::jInput("text", "City", $Client['City'], ['class' => 'form-control']) }}
						</div>
					</div>
					<div class="col-md-6">	
						<div class="form-group required Postcode">
							{{ Form::jLabel("Postcode", 'Postcode:') }}
							{{ Form::jInput("text", "Postcode", $Client['Postcode'], ['class' => 'form-control']) }}
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">	
					<div class="col-md-12">				
						<div class="form-group required State">
							{{ Form::jLabel("State", 'State:') }}
							{{ Form::jInput("select", "State", all_states(), $Client['State'], ['class' => 'form-control']) }}
						</div>	
					</div>
				</div>
			</div>		
			<div class="row">
				<div class="col-md-4">	
					<div class="col-md-12">					
						<div class="form-group required Country">
							{{ Form::jLabel("Country", 'Country:') }}
							{{ Form::jInput("text", "Country", $Client['County'], ['class' => 'form-control']) }}
						</div>	
					</div>		
				</div>
			</div>			
		</div>
	</div>
	<div class="h-pad-3x text-right grid-100">
		<button class="btn btn-primary" disabled>Edit</button>
	</div>
</div>



