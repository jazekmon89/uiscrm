<div class="row">
	<div id="requester-fields">
		<div class="form-group required col-md-6">
			{{ Form::jLabel(".InsuredName", 'Insured Name:') }}
			{{ Form::jInput('text', "InsuredName", $Client['InsuredName'], ['class' => 'form-control']) }}
		</div>
		<div class="form-group required col-md-6">
			{{ Form::jLabel("ContactPerson", 'Contact Person:') }}
			{{ Form::jInput('text', "ContactPerson", $Client['ContactPersion'], ['class' => 'form-control']) }}
		</div>
		<div class="form-group required col-md-6">
			{{ Form::jLabel("EmailAddress", 'Email Address:') }}
			{{ Form::jInput("email", "EmailAddress", $Client['EmailAddress'], ['class' => 'form-control']) }}
		</div>
		<div class="form-group required col-md-6">
			{{ Form::jLabel("PhoneNumber", 'Phone Number/Mobile:') }}
			{{ Form::jInput('text', "RFQ.PhoneNumber", $Client['PhoneNumber'], ['class' => 'form-control']) }}
		</div>
	</div>
	<div id="business-credentials">
		<div class='row'>
			<h4 class="col-md-12 form-section-title">Business Details</h4>
			{{ Form::jInput("hidden", "InsurableBusinessID", $Client['InsurableBusinessID']) }}
			<div class="form-group col-md-6">
				{{ Form::jLabel("TradeName", 'Trading As:') }}
				{{ Form::jInput("text", "TradingName", $Client['TradingName'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group required col-md-6">
				{{ Form::jLabel("BusinessStructureTypeID", 'Business Structure:') }}
				{{ Form::jInput("select", "BusinessStructureTypeID", $Client['BusinessStructureTypes'], $Client['BusinessStructure'], ['class' => 'form-control', 'placeholder' => '']) }}
			</div>
			<div class="form-group col-md-6">
				{{ Form::jLabel("AustralianBusinessNumber", 'ABN No.:') }}
				{{ Form::jInput("text", "AustralianBusinessNumber", $Client['ABN'], ['class' => 'form-control']) }}
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
	</div>		
	<div class="address-fields" id="mail-address" data-address='mail_address'>
		<div class="row">
			<h4 class="col-md-12 form-section-title">Mailing Address</h4>
		</div>
		<div class="form-group col-md-12">
			{{ Form::jInput("hidden", "AddressID", $Client['AddressID'], ['class' => 'AddressID']) }}
			</div>
			@if(false)
			<div class="form-group col-md-6 UnitNumber">
				{{ Form::jLabel("UnitNumber", 'Unit No.:') }}
				{{ Form::jInput("number", "UnitNumber", $Client['UnitNumber'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group required col-md-6  StreetNumber">
				{{ Form::jLabel("StreetNumber", 'Street No.:') }}
				{{ Form::jInput("number", "StreetNumber", $Client['StreetNumber'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group required col-md-6 StreetName">
				{{ Form::jLabel("StreetName", 'Street Name:') }}
				{{ Form::jInput("text", "StreetName", $Client['StreetName'], ['class' => 'form-control']) }}
			</div>
			@endif
			<div class="form-group col-md-6 AddressLine1">
				{{ Form::jLabel("AddressLine1", 'Address Line 1:') }}
				{{ Form::jInput("number", "AddressLine1", $Client['AddressLine1'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group col-md-6 AddressLine2">
				{{ Form::jLabel("AddressLine2", 'Address Line 2:') }}
				{{ Form::jInput("number", "AddressLine2", $Client['AddressLine2'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group required col-md-6 City">
				{{ Form::jLabel("City", 'City:') }}
				{{ Form::jInput("text", "City", $Client['City'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group required col-md-5 Country">
				{{ Form::jLabel("Country", 'Country:') }}
				{{ Form::jInput("text", "Country", $Client['County'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group required col-md-3 Postcode">
				{{ Form::jLabel("Postcode", 'Postcode:') }}
				{{ Form::jInput("text", "Postcode", $Client['Postcode'], ['class' => 'form-control']) }}
			</div>
			<div class="form-group required col-md-4 State">
				{{ Form::jLabel("State", 'State:') }}
				{{ Form::jInput("text", "State", $Client['Postcode'], ['class' => 'form-control']) }}
			</div>
		</div>
	</div>
	<div class="spacer">&nbsp;</div>
	<div class="col-md-11 text-right">
		<button type="button" class="btn btn-primary" id="edit-profile" disabled>Edit</button>
	</div>
	<div class="spacer">&nbsp;</div>
	<div class="spacer">&nbsp;</div>
	<div class="spacer">&nbsp;</div>
	<div class="spacer">&nbsp;</div>
	<div class="spacer">&nbsp;</div>
	<div class="spacer">&nbsp;</div>
</div>



