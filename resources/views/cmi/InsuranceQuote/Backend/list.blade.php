{{ Form::open(['route' => 'insurancequotes.index', 'status' => $type]) }}
<div class="bem-container__center">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-right">
				<button class="btn btn-primary " type="button">New Quote</button>
			</div>
		</div>
		<div class="row">
			<div class="bem-table__container table-responsive">
				<table class="table table-hover table-striped {{ $type }} quotes table-fixed-header">
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
							<th>Cover Start Date</th>
							<th>Excess</th>
							<th>ImposedExcess</th>
						</tr>	
						<tr id="search-{{ $type }}-fields">
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('RFQRefNum', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('PolicyTypeID', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('InsuredName', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('QuoteRefNum', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('StatusID', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('ExternalSource', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('Classification', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('UnderWriter', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('Premium', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('StartDate', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('Excess', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
									<span class="input-group-btn">
										<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
									</span>
								</div>
							</th>
							<th>
								<div class="bem-table__search-container input-group search-area">
									{{ Form::text('ImposedExcess', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
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

								<tr>
									@php $edit_url = route('insurancequotes.view', $quote['InsuranceQuoteID']);  
											$edit_rfq_url = route('rfqs.view', $quote['RFQID'])
									@endphp
									
									<td>@iLink($edit_rfq_url, $quote['RFQRefNum'])</td>
									<td>{{ $quote['PolicyType'] }}</td>
									<td>{{ $quote['InsuredName'] }}</td>
									<td>@iLink($edit_url, $quote['QuoteRefNum'])</td>
									<td>{{ $quote['RFQStatus']}}</td>
									<td></td>
									<td>{{ $quote['Classification'] }}</td>
									<td>{{ $quote['UnderwriterID'] }}</td>
									<td>{{ $quote['Premium'] }}</td>
									<td>{{ $quote['CoverStartDateTime'] }}</td>
									<td>{{ $quote['Excess'] }}</td>
									<td>{{ $quote['ImposedExcess'] }}</td>

								</tr>

							@endforeach
						@endif
						<tr class="empty {{ $quotes ? 'hidden' : '' }}"><td colspan="10" class="bem-text_center">No entries found.</td></tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>	
</div>
{{ Form::close() }}
