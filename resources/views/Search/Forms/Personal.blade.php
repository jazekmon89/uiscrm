<div class='search search-insurance'>
	{{ Form::open(['route' => ['search.details', 'FindContactByPersonalDetails'],  'method' => 'get']) }}
	<div class="col-md-6">
		<div class="form-group row">
			<div class="col-md-5">Contact Reference No:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'ContactRefNum', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">First Name:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'FirstName', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Middle Names:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'MiddleNames', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Surname:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'Surname', null, ['class' => 'form-control']) }}</div>
		</div>	
		
	</div>
	<div class="col-md-6">
		<div class="form-group row">
			<div class="col-md-5">Preferred Name:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'PreferredName', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Email Address:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'EmailAddress', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Phone Number:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'MobilePhoneNumber', null, ['class' => 'form-control']) }}</div>
		</div>	
	</div>

	<div class="col-md-12">
		<h4>Address</h4>
		<div class="row">
			@if(false)
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-5 text-left">Unit Number:</div>
					<div class="col-md-7 text-right">{{ Form::jInput('text', 'Address.UnitNumber', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>	
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-5 text-left">Street Number:</div>
					<div class="col-md-7 text-right">{{ Form::jInput('text', 'Address.StreetNumber', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>	
			@endif
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-5 text-left">Address Line 1:</div>
					<div class="col-md-7 text-right">{{ Form::jInput('text', 'Address.AddressLine1', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-5 text-left">Address Line 2:</div>
					<div class="col-md-7 text-right">{{ Form::jInput('text', 'Address.AddressLine2', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>
		</div>
		<div class="row">
			@if(false)
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-5 text-left">Street Name:</div>
					<div class="col-md-7 text-right">{{ Form::jInput('text', 'Address.StreetName', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>
			@endif
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-5 text-left">City:</div>
					<div class="col-md-7 text-right">{{ Form::jInput('text', 'Address.City', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>	
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-3 text-left">State:</div>
					<div class="col-md-9 text-right">{{ Form::jInput('text', 'Address.State', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-4 text-left">Postcode:</div>
					<div class="col-md-8 text-right">{{ Form::jInput('text', 'Address.Postcode', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-3 text-left">Country:</div>
					<div class="col-md-9 text-right">{{ Form::jInput('text', 'Address.Country', null, ['class' => 'form-control']) }}</div>
				</div>
			</div>	
		</div>
	</div>
	<div class="col-md-12 text-right">
		{{ Form::submit('Submit', ['class' => 'col-md-2 form-control btn btn-primary']) }}
	</div>
	{{ Form::close() }}
</div>