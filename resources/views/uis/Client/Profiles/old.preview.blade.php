	<div class="row">
		<div class="col-md-12 col-no-padding">
			<div class="row">
				<div class="col-md-7">
					<!-- <div class="form-group text-left row">
						<label class="col-md-5 control-label">Financial Service Managed</label>
						<div class="controls col-md-7 ">
							<select class="form-control">
								<option selected>Insurance</option>
							</select>
						</div>
					</div> -->
					<!-- <div class="form-group text-left row">
						<label class="col-md-5 control-label">Insured Name</label>
						<div class="controls col-md-7"> -->
							{{-- Form::jInput("text", 'InsuredName', null, ['class' => 'form-control', 'disabled' => 'disabled']) --}}
							<!-- <input id="in-q-insuredname" type="text" class="form-control" readonly="readonly"> -->
							
						<!-- </div>
					</div> -->
					<!-- <div class="form-group text-left row">
						<label class="col-md-5 control-label">Company Name</label>
						<div class="controls col-md-7 "> -->
							{{-- Form::jInput("text", 'InsurableBusiness.CompanyName', null, ['class' => 'form-control', 'disabled' => 'disabled']) --}}
							<!-- <input id="in-q-companyname" type="text" class="form-control" readonly="readonly"> -->
						<!-- </div>
					</div> -->
					<!-- <div class="spacer">&nbsp;</div> -->
					<div class="row">
						<h4 class="text-left col-md-12"><strong>Address</strong></h4>
					</div>
					<!-- <div class="spacer">&nbsp;</div> -->
					<form id="address-form">
						<input type="hidden" id="in-q-cid" value="{{ $ClientID }}">
						<div class="row form-group">
							{{ Form::jInput("hidden", 'InsurableBusiness.PostalAddress.AddressID', null, ['id' => 'in-q-aid']) }}
							if(false)
							<div class=" text-left">
								<label class="col-md-2 control-label">Unit Number</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.UnitNumber', null, ['class' => 'form-control', 'id' => 'in-q-unitnumber']) }}
									<!-- <input id="in-q-unitnumber" type="text" class="form-control"> -->
								</div>
							</div>
							<div class="text-left">
								<label class="col-md-2 control-label">Street Number</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.StreetNumber', null, ['class' => 'form-control', 'id' => 'in-q-streetnumber']) }}
									<!-- <input id="in-q-streetnumber" type="text" class="form-control"> -->
								</div>
							</div>
							@endif
							<div class="text-left">
								<label class="col-md-2 control-label">Address Line 1</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.AddressLine1', null, ['class' => 'form-control', 'id' => 'in-q-addressline1']) }}
									<!-- <input id="in-q-streetnumber" type="text" class="form-control"> -->
								</div>
							</div>
							<div class="text-left">
								<label class="col-md-2 control-label">Address Line 2</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.AddressLine2', null, ['class' => 'form-control', 'id' => 'in-q-addressline2']) }}
									<!-- <input id="in-q-streetnumber" type="text" class="form-control"> -->
								</div>
							</div>
						</div>
						<div class="row form-group">
							if(false)
							<div class="text-left">
								<label class="col-md-2 control-label">Street Name</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.StreetName', null, ['class' => 'form-control', 'id' => 'in-q-streetname']) }}
									<!-- <input id="in-q-streetname" type="text" class="form-control"> -->
								</div>
							</div>
							@endif
							<div class="text-left">
								<label class="col-md-2 control-label">City</label>
								<div class="controls col-md-4">
									<!-- <input id="in-q-city" type="text" class="form-control"> -->
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.City', null, ['class' => 'form-control', 'id' => 'in-q-city']) }}
								</div>
							</div>
							<div class="text-left">
								<label class="col-md-2 control-label">State</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.State', null, ['class' => 'form-control', 'id' => 'in-q-state']) }}
									<!-- <input id="in-q-state" type="text" class="form-control" > -->
								</div>
							</div>
						</div>
						<div class="row form-group">
							<div class="text-left">
								<label class="col-md-2 control-label">Country</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.Country', null, ['class' => 'form-control', 'id' => 'in-q-country']) }}
									<!-- <input id="in-q-country" type="text" class="form-control"> -->
								</div>
							</div>
							<div class="text-left">
								<label class="col-md-2 control-label">Postcode</label>
								<div class="controls col-md-4">
									{{ Form::jInput("text", 'InsurableBusiness.PostalAddress.Postcode', null, ['class' => 'form-control', 'id' => 'in-q-postcode']) }}
									<!-- <input id="in-q-postcode" type="text" class="form-control"> -->
								</div>
							</div>
						<div class="row text-left form-group">
							<div class="text-left form-group">
								<div class="controls col-md-6 text-center">
									<input id="update-address" type="submit" class="btn btn-primary col-md-12" value="Update Address">
								</div>
							</div>
						</div>		
					</div>
				</form>
				<div class="col-md-5 policy-details">
	  
					<div class="row">
						<div class="col-md-12">
							<h4 class="text-left"><strong>Policy Details</strong></h4>
								<div class="col-box">
									<div class="row form-group">
										<div class="col-md-6"><label>No. of Existing Policies:</label></div>
										<div class="col-md-6">
											{{ Form::jInput("text", "NumOfPolicies", null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
											<!-- <input type="text" class="form-control" id="no-of-pols" disabled> -->
										</div>
									</div>
									<div class="row form-group">
										<div class="col-md-6"><label>No. of Recommended Policies:</label></div>
										<div class="col-md-6">
											{{ Form::jInput("text", "NumOfRecommends", null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
											<!-- <input type="text" class="form-control" id="no-of-recommends" disabled> -->
										</div>
									</div>
									<div class="row form-group">
										<div class="col-md-12">
											<button class="btn btn-primary rcmds-link col-md-12" href="{{ route("client.recommendations", [$ClientID]) }}">Edit Recommended Policies</button>
										</div>
									</div>	
								</div>	
						</div>
					</div>

					@if (false)
					<div class="row" id="contact-detail-forms">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-8">
									<h4 class="text-left"><strong>Contact Details</strong></h4>
								</div>
								<div class="col-md-4 text-right navigation">
									<div class="btn-group">
										<button class="btn" data-trigger="prev"><i class="fa fa-arrow-left"></i></button>
										<button class="btn" data-trigger="next"><i class="fa fa-arrow-right"></i></button>
									</div>
								</div>
							</div>
							<div class="spacer">&nbsp;</div>
							@php $cnt = 1 @endphp
							@foreach(Form::getInputValue("Contacts", [null]) as $i => $val)
							@php $val['ClientID'] = $ClientID @endphp
							<div class="form" id="contact-form-{{$i}}">
								{{ Form::jInput("hidden", "Contacts.{$i}.ContactID", null, ['class' => 'in-q-ctid']) }}.
								<!-- <input type='hidden' id="in-q-ctid"> -->
								<div class="text-left form-group row">
									<label class="col-md-3 control-label">First Name</label>
									<div class="controls col-md-9">
										<!-- <input type="text" class="form-control" class="in-q-firstname"> -->
										{{ Form::jInput("text", "Contacts.{$i}.FirstName", null, ['class' => 'form-control in-q-firstname']) }}
									</div>
								</div>
								<div class="text-left form-group row">
									<label class="col-md-3 control-label">Preferred Name</label>
									<div class="controls col-md-9">
										{{ Form::jInput("text", "Contacts.{$i}.PreferredName", null, ['class' => 'form-control in-q-preferredname']) }}
										<!-- <input type="text" class="form-control" class="in-q-preferredname"> -->
									</div>
								</div>
								<div class="text-left form-group row">
									<label class="col-md-3 control-label">Last Name</label>
									<div class="controls col-md-9">
										{{ Form::jInput("text", "Contacts.{$i}.Surname", null, ['class' => 'form-control in-q-lastname']) }}
										<!-- <input type="text" class="form-control" id="in-q-lastname"> -->
									</div>
								</div>
								<div class="text-left form-group row">
									<label class="col-md-3 control-label">Email</label>
									<div class="controls col-md-9">
										{{ Form::jInput("text", "Contacts.{$i}.EmailAddress", null, ['class' => 'form-control in-q-email']) }}
										<!-- <input type="text" class="form-control" id="in-q-email"> -->
									</div>
								</div>
								<div class="text-left form-group row">
									<label class="col-md-3 control-label">Mobile No.</label>
									<div class="controls col-md-9">
										{{ Form::jInput("text", "Contacts.{$i}.MobilePhoneNumber", null, ['class' => 'form-control in-q-mobilenum']) }}
										<!-- <input type="text" class="form-control" id="in-q-mobilenum"> -->
									</div>
								</div>
								<!--div class="text-left form-group row">
									<label class="col-md-3 control-label">Driver's License.</label>
									<div class="controls col-md-9">
										<input id="in-q-country" type="text" class="form-control" >
									</div>
								</div-->
								<div class="text-center form-group row">
									
									<div class="col-md-12">
										<button class="btn btn-primary col-md-12" data-form="#contact-form-{{$i}}" data-contact="{{ json_encode($val) }}">Update Contact Details</button>
									</div>
								</div>
							</div>
							@endforeach
						</div>
					</div>
					@endif
				</div>
			</div>
			@if(false)
			<div class="row">
				<div class="col-md-4 policy-details">		  
					<div class="row">
						<div class="col-md-12">
							<h4 class="text-left"><strong>Policy Details</strong></h4>
								<div class="col-box">
									<div class="row form-group">
										<div class="col-md-6"><label>No. of Existing Policies:</label></div>
										<div class="col-md-6">
											{{ Form::jInput("text", "NumOfPolicies", null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
											<!-- <input type="text" class="form-control" id="no-of-pols" disabled> -->
										</div>
									</div>
									<div class="row form-group">
										<div class="col-md-6"><label>No. of Recommended Policies:</label></div>
										<div class="col-md-6">
											{{ Form::jInput("text", "NumOfRecommends", null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
											<!-- <input type="text" class="form-control" id="no-of-recommends" disabled> -->
										</div>
									</div>
									<div class="row form-group">
										<div class="col-md-12">
											<button class="btn btn-primary rcmds-link col-md-12" href="{{ route("client.recommendations", [$ClientID]) }}">Edit Recommended Policies</button>
										</div>
									</div>	
								</div>	
						</div>
					</div>
				</div>	
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-12" style="visibility:hidden;">
							<h4 class="text-left"><strong>Current Tasks</strong></h4>	
							<div class="col-box col-box-heading">							
								<table class="table" id="tasks-list">
									<thead>
										<tr>
											<th colspan="3" class="border-right text-center">Adviser/Consultant</th>
										</tr>
										<tr class="col-headers">
											<th>Task</th>
											<th>Contact</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<tr class="empty">
											<td colspan="3" class="text-center">Open tasks</td>
										</tr>
									</tbody>
								</table>
							</div>								
						</div>
					</div>
				</div>
			</div>	
			@endif
		</div>
	</div>
	<div class="spacer">&nbsp;</div>
