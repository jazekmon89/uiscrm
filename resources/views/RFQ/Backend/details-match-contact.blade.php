<div id="match-contact" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content box">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h3 class="modal-title">Match Contact</h3>
				</div>		
				<div class="modal-body">
					<div class="tab-content">
						<div id="search-contact" class="tab-pane fade in active">
							{{ Form::open(['route' => ["client.profiles.search"]]) }}
						    	<table class="table table-condensed table-hover table-striped">
						    		<thead>
									<tr>
										<th>Contact Ref No:</th>
										<th>First Name</th>
										<th>Preferred Name</th>
										<th>Surname</th>
										<th>Address</th>
										<th>Email</th>
										<th></th>
									</tr>	
									<tr id="search-fields">
										<th>
											<div class="input-group search-area">
												{{ Form::text('ContactRefNum', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
												<span class="input-group-btn">
													<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
												</span>
											</div>
										</th>
										<th>
											<div class="input-group search-area">
												{{ Form::text('FirstName', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
												<span class="input-group-btn">
												<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
												</span>
											</div>
										</th>
										<th>
											<div class="input-group search-area">
												{{ Form::text('PreferredName', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
												<span class="input-group-btn">
													<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
												</span>
											</div>
										</th>
										<th>
											<div class="input-group search-area">
												{{ Form::text('Surname', null, ['placeholder' => 'Search', 'class' => 'form-control']) }}
												<span class="input-group-btn">
													<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
												</span>
											</div>
										</th>
										<th class="address">									
											<div class="input-group search-area ">
												{{ Form::text('AddressFull', null, ['id' => 'AddressFull', 'placeholder' => 'Search', 'class' => 'form-control']) }}
												<span class="input-group-btn">
													<button class="btn trigger"><i class="fa fa-search"></i>&nbsp;</button>
												</span>
											</div>
											<div class="dropdown" auto-close="false">
												<a href="#setAddress" class="dropdown-toggle" data-toggle="dropdown">&nbsp;</a>
												<ul class="dropdown-menu">
													@if(false)
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">Unit Number</span>
												    		{{ Form::text('Address[UnitNumber]', null, ['class' => 'form-control']) }}
												    	</div>
												    </li>
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">Street Number</span>
												    			{{ Form::text('Address[StreetNumber]', null, ['class' => 'form-control']) }}
												    	</div>		
												    </li>
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">Street Name</span>
												    			{{ Form::text('Address[StreetName]', null, ['class' => 'form-control']) }}
												    	</div>
												    </li>
												    @endif
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">Address Line 1</span>
												    			{{ Form::text('Address[AddressLine1]', null, ['class' => 'form-control']) }}
												    	</div>
												    </li>
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">Address Line 2</span>
												    			{{ Form::text('Address[AddressLine2]', null, ['class' => 'form-control']) }}
												    	</div>
												    </li>
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">Town/Suburb</span>
												    			{{ Form::text('Address[City]', null, ['class' => 'form-control']) }}
												    	</div>
												    </li>
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">Postcode</span>
												    			{{ Form::text('Address[Postcode]', null, ['class' => 'form-control']) }}
												    	</div>
												    </li>
												    <li>
												    	<div class="input-group">
												    		<span class="input-group-addon">State</span>
												    			{{ Form::select('Address[State]', ["" => ""] + all_states(), null, ['class' => 'form-control']) }}
												    	</div>
												    </li>
												</ul>
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
										<th style="width: 60px;">
											<button class="btn btn-primary"onclick="var f = $('#search-contact form')[0]; f.reset() && f.submit()">Reset</button>
										</th>
									</tr>	
								</thead>
						    		<tbody></tbody>
						    	</table>
					    	{{ Form::close() }}
				    	</div>
				    	<div id="new-contact" class="tab-pane fade">
				    		{{ Form::open(['route' => ['rfqs.create-contact', $RFQID]]) }}
				    			<h4>Contact Details</h4>
				    			<div class="row form-group">
				    				<div class="col-md-4">
				    					<label for="Contact-FirstName">First Name</label>
				    					<input type="text" name="Contact[FirstName]" class="form-control" value="{{ array_get($name, 'FirstName', array_get($name, 'Name')) }}">
				    				</div>
				    				<div class="col-md-4">
				    					<label for="Contact-MiddleNames">Middle Names</label>
				    					<input type="text" name="Contact[MiddleNames]" class="form-control" value="{{ array_get($name, 'MiddleNames') }}">
				    				</div>
				    				<div class="col-md-4">
				    					<label for="Contact-Surname">Surname</label>
				    					<input type="text" name="Contact[Surname]" class="form-control" value="{{ array_get($name, 'Surname') }}">
				    				</div>
				    			</div>
				    			<div class="row form-group">
				    				<div class="col-md-4">
				    					<label for="Contact-MobilePhoneNumber">Preferred Name</label>
				    					<input type="text" name="Contact[PreferredName]" class="form-control" value="{{ array_get($name, 'PreferredName') }}">
				    				</div>
				    				<div class="col-md-4">
				    					<label for="Contact-EmailAddress">Email Address</label>
				    					<input type="text" name="Contact[EmailAddress]" class="form-control" value="{{ $email }}">
				    				</div>
				    				<div class="col-md-4">
				    					<label for="Contact-MobilePhoneNumber">Mobile Phone Number</label>
				    					<input type="number" name="Contact[MobilePhoneNumber]" class="form-control" value="{{ $phone }}">
				    				</div>
				    			</div>
				    			<div class="row form-group">
				    				<div class="col-md-4">
				    					<label for="Contact-BirthDate">Birth Date <code>DD/MM/YYYY</code></label>
				    					<div class="input-group grid-100 datetimepicker" data-date-format="DD/MM/YYYY">
				    						<input type="text" name="Contact[BirthDate]" class="form-control" value="{{ array_get($RFQ, 'Contact.BirthDate', array_get($RFQ, 'Lead.BirthDate')) }}">
				    						<span class="input-group-addon">
				    							<i class="fa fa-calendar">&nbsp;</i>
				    						</span>
				    					</div>
				    				</div>
				    				<div class="col-md-4">
				    					<label for="Contact-BirthCountry">Birth Country</label>
				    					<input type="text" name="Contact[BirthCountry]" class="form-control" value="{{ array_get($addr, 'Country') }}">
				    				</div>
				    				<div class="col-md-4">
				    					<label for="Contact-BirthCity">Birth City</label>
				    					<input type="text" name="Contact[BirthCity]" class="form-control" value="{{ array_get($addr, 'City', array_get($addr, 'State')) }}">	
				    				</div>
				    			</div>

				    			<div class="grid-100">
				    				<h4>Home Address</h4>

				    				<div class="row" id="nc-home-address">
				    					@include('Quotes.Form.partials.address-form', ['baseKey' => 'HomeAddress'])
				    				</div>
				    			</div>

				    			<div class="grid-100">
				    				<h4>Mail Address</h4>

				    			
				    				<div class="input-group" id="use_home_addr">
				    					
			    						<label class="checkbox-inline">Is mailing address same as home address ?</label>
			    						<label class="checkbox-inline"><input class="h-pad" type="radio" name="use_home_addr" value="Y"> Yes</label>
			    						<label class="checkbox-inline"><input class="h-pad" type="radio" name="use_home_addr" value="N"> No</label>	
				    					
				    				</div>
				    				
				    				<div class="row" id="nc-mail-address">
				    					@include('Quotes.Form.partials.address-form', ['baseKey' => 'MailAddress'])
				    				</div>
				    			</div>

				    			<div class="grid-100 text-right h-pad-3x">
				    				<button href="#search-contact" class="btn btn-primary" data-toggle="tab">Cancel</button>
				    				<button class="btn btn-primary" onclick="createContact(this)">Create and Use Contact</button>
				    			</div>
				    		{{ Form::close() }}
				    	</div>
			    	</div>
			  	</div>
			  	<div class="modal-footer">
			  		<div class="ibtn-group">
				  		
				  		<button href="#new-contact" class="btn btn-primary" data-toggle="tab">New Contact</button>
					    <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</a>
				    </div>
				</div>
		  	</div>
		</div>
	</div>