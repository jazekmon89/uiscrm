{{ Form::open(['route' => 'rfqs.index', 'status' => $type]) }}
<table class="table table-condensed table-hover table-striped {{ $type }} rfqs">
	<thead>
		<tr>
			<th>RFQ Ref No</th>
			<th>RFQ Type</th>
			<th>RFQ Status</th>
			<th>Insured Name</th>
			<th>Name</th>
			<th>Address</th>
			<th>Date Submitted</th>
			<th>Mobile</th>
			<th>Email</th>
			<th>Business Address</th>
		</tr>	
		<tr id="search-{{ $type }}-fields">
			<th>
				<div class="input-group search-area">
					{{ Form::text('RFQRefNum', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('PolicyTypeID', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('RFQStatusID', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('InsuredName', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('Name', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('Address', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('LodgementDate', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('PhoneNumber', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('EmailAddress', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('BusinessAddress', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($rfqs))
			@foreach($rfqs as $RFQ)
				@php $edit_url = route('rfqs.view', $RFQ['RFQID'])  @endphp
				<tr>
					<td>@iLink($edit_url, array_get($RFQ, "RFQRefNum"))</td>
					<td>{{ array_get($RFQ, "PolicyType") }}</td>
					<td>{{ array_get($RFQ, "RFQStatus") }}</td>
					<td>{{ array_get($RFQ, "InsuredName") }}</td>
					<td>{{ array_get($RFQ, "Name") }}</td>
					<td>{{ array_get($RFQ, "Address") }}</td>
					<td>@dateFormat(array_get($RFQ, "LodgementDateTime"))</td>
					<td>{{ array_get($RFQ, "PhoneNumber") }}</td>
					<td>{{ array_get($RFQ, "EmailAddress") }}</td>
					<td>{{ array_get($RFQ, "BusinessAddress") }}</td>
				</tr>
			@endforeach
		@endif
		<tr class="empty {{ $rfqs ? 'hidden' : '' }}"><td colspan="10" class="text-center">No entries found.</td></tr>
	</tbody>
</table>
{{ Form::close() }}