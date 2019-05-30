<div class='search search-insurance'>
	{{ Form::open(['route' => ['search.details', 'FindInsuranceEntitiesByInsuranceDetails'], 'method' => 'get']) }}
	<div class="col-md-6">
		<div class="form-group row">
			<div class="col-md-5">Reference No:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'BrokerRefNum', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Policy No:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'PolicyNum', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Invoice No:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'InvoiceNum', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Motor Vehicle Registration:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'MotorVehicleRegNum', null, ['class' => 'form-control']) }}</div>
		</div>	
	</div>
	<div class="col-md-6">
		<div class="form-group row">
			<div class="col-md-5">Quote No:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'QuoteNum', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Insure Name:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'InsuredName', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="form-group row">
			<div class="col-md-5">Trading Name:</div>
			<div class="col-md-7">{{ Form::jInput('text', 'TradingName', null, ['class' => 'form-control']) }}</div>
		</div>	
		<div class="row">
			<div class="col-md-12 text-right">
				{{ Form::submit('Submit', ['class' => 'form-control btn btn-primary']) }}
			</div>
		</div>
	</div>
	
	{{ Form::close() }}
</div>