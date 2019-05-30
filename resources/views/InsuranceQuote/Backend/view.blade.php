{{ Form::open(['route' => 'insurancequotes.view', 'status' => $type]) }}
<table class="table table-condensed table-hover table-striped {{ $type }} rfqs">
	<thead>
		<tr>
			<th>RFQ Ref No</th>
			<th>Type</th>
			<th>Insured Name</th>
			<th>Quote Ref No</th>
			<th>Status</th>
			<th>External Source</th>
			<th>Classification</th>
			<th>Underwriter</th>
			<th>Premium</th>
			<th>Start Date</th>
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
					{{ Form::text('InsuredName', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('QuoteRefNum', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('StatusID', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			
			<th>
				<div class="input-group search-area">
					{{ Form::text('ExternalSource', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('Classification', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('UnderWriter', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('Premium', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
			<th>
				<div class="input-group search-area">
					{{ Form::text('StartDate', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
					<span class="input-group-btn">
						<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
					</span>
				</div>
			</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($quotes))
			@foreach($quotes as $quote)
				@php $edit_url = route('insurancequotes.view', $quote['InsuranceQuoteID'])  @endphp
				<tr>
					<td>@iLink($edit_url, array_get($quote, "RFQ.RFQRefNum"))</td>
					<td>{{ array_get($quote, "PolicyType.DisplayText") }}</td>
					<td>{{ array_get($quote, "RFQStatus") }}</td>
					<td>{{ array_get($quote, "Client.InsuredName") }}</td>
					<td>{{ array_get($quote, "QuoteRefNum") }}</td>
					<td>New</td>
					<td>Source</td>
					<td>{{ array_get($quote, "Classification") }}</td>
					<td>{{ array_get($quote, "Underwriter.CompanyName") }}</td>
					<td>{{ array_get($quote, "Premium") }}</td>
					<td>@dateFormat(array_get($quote, "CoverStartDateTime"))</td>
				</tr>
			@endforeach
		@endif
		<tr class="empty {{ $quotes ? 'hidden' : '' }}"><td colspan="10" class="text-center">No entries found.</td></tr>
	</tbody>
</table>
{{ Form::close() }}
