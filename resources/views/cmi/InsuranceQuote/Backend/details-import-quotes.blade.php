<div id="import_quotes" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content box">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h3 class="modal-title">Import Quotes</h3>
				</div>		
				<div class="modal-body">
					<div id="upload-quotes">
						{{ Form::open(['route' => ['insurancequotes.upload-csv-quotes'], 'files' => true]) }}
							<div class="input-group row">
								<input type="text" class="form-control" disabled> 
								<div class="input-group-addon" style="position:relative;">
									<span>Browse</span>
									{{ Form::file('file', ['class' => 'btn col-md-6', 'style' => 'position:absolute;opacity: 0;left:0;top:0;width:100%;height:100%;']) }}
								</div>
								<div class="input-group-btn">
									<button class="btn btn-default btn-primary btn-action" onclick="return importQuotes(this)">Import</button>
								</div>													
							</div>							
						{{ Form::close() }}
					</div>

					<div id="save-quotes">
						<div id="upload-header-fields">
						<table class="table table-condensed table-hover table-striped">
				    		<thead>
				    			<tr>
				    				<th>Header</th>
				    				<th>Assign field</th>
				    			</tr>
				    		</thead>
				    		<tbody></tbody>
				    	</table>
				    	<button class="btn btn-primary btn-action" onclick="assignFields(this)">Assign fields</button>
				    	</div>
						{{ Form::open(['route' => ['insurancequotes.save-quotes', $Quote['InsuranceQuoteID']], 'class' => 'hidden']) }}
					    	<table class="table table-condensed table-hover table-striped">
					    		<thead></thead>
					    		<tbody></tbody>
					    	</table>

					    	<button class="btn btn-primary btn-action" onclick="return saveQuotes(this)">Upload Quotes</button>
				    	{{ Form::close() }}
			    	</div>
			  	</div>
			  	<div class="modal-footer">
				    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</a>
				</div>
		  	</div>
		</div>
	</div>