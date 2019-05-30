{{ Form::jInput("hidden", "{$baseKey}.AddressID", null, ['class' => 'AddressID']) }}
@if(false)
<div class="col-md-4">
	<div class="form-group UnitNumber">
		{{ Form::jLabel("{$baseKey}.UnitNumber", 'Unit No.:') }}
		{{ Form::jInput("text", "{$baseKey}.UnitNumber", null, ['class' => 'form-control no-clear']) }}
	</div>	
</div>
<div class="col-md-4">
	<div class="form-group StreetNumber required">
		{{ Form::jLabel("{$baseKey}.StreetNumber", 'Street No.:') }}
		{{ Form::jInput("text", "{$baseKey}.StreetNumber", null, ['class' => 'form-control no-clear']) }}
	</div>
</div>
<div class="col-md-4">
	<div class="form-group StreetName required">
		{{ Form::jLabel("{$baseKey}.StreetName", 'Street Name:') }}
		{{ Form::jInput("text", "{$baseKey}.StreetName", null, ['class' => 'form-control no-clear']) }}
	</div>	
</div>
@endif
<div class="row">
	<div class="col-md-12">
		<div class="col-md-6">
			<div class="form-group AddressLine1 required">
				{{ Form::jLabel("{$baseKey}.AddressLine1", 'Address Line 1:') }}
				{{ Form::jInput("text", "{$baseKey}.AddressLine1", null, ['class' => 'form-control no-clear']) }}
			</div>	
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="col-md-6">
			<div class="form-group AddressLine2">
				{{ Form::jLabel("{$baseKey}.AddressLine2", 'Address Line 2:') }}
				{{ Form::jInput("text", "{$baseKey}.AddressLine2", null, ['class' => 'form-control no-clear']) }}
			</div>	
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="col-md-6">
			<div class="form-group City required">
				{{ Form::jLabel("{$baseKey}.City", 'Town/Suburb:') }}
				{{ Form::jInput("text", "{$baseKey}.City", null, ['class' => 'form-control no-clear']) }}
			</div>	
		</div>
		<div class="col-md-6">
			<div class="form-group Postcode required">
				{{ Form::jLabel("{$baseKey}.Postcode", 'Postcode:') }}
				{{ Form::jInput("text", "{$baseKey}.Postcode", null, ['class' => 'form-control no-clear']) }}
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="col-md-12">
			<div class="form-group State required">
				{{ Form::jLabel("{$baseKey}.State", 'State:') }}
				{{ Form::jInput("select", "{$baseKey}.State", ["" => ""] + all_states(), null, ['class' => 'form-control no-clear']) }}
			</div>
		</div>		
	</div>
</div>
