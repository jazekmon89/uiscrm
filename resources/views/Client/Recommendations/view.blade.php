@inject("ClientHelper", "App\Helpers\ClientHelper")

@extends('layouts.Backend')




@php 
	$quotes = $ClientHelper->getCurrentPolicies($client->ClientID, false);
	$recommended = $ClientHelper->getRecommendedPolicies($client->ClientID, false);
	$recommendations = $ClientHelper->getRecommendations($client->ClientID, false);

	foreach($quotes as $quote) {
		$rec = arr_lkey($recommended, "PolicyTypeID", $quote['PolicyTypeID']);
		if ($rec !== false) {
			$recommended[$rec]['active'] = true;
		}
		if (!arr_lfind($recommendations, "PolicyTypeID", $quote['PolicyTypeID'])) {
			$quote['PolicyType']['active'] = true;
			$recommendations[] = $quote['PolicyType'];
		}
	} 
@endphp


@php
	$name = $subtitle = "";
	if($client->Contact) 
	{

		$name = aname($client->Contact);
		$subtitle .= "<div>";
		
		if($Address = array_get($client->Contact, "HomeAddress")) 
			$subtitle .= "<span class=\"address\">".address($Address)."</span>";

		if($phone = array_get($client->Contact, "MobilePhoneNumber"))
			$subtitle .= "<span class=\"separator\"> - </span><span class=\"mobile\">". $phone ."</span>";
		
		if($email = array_get($client->Contact, "EmailAddress"))
			$subtitle .= "<span class=\"separator\"> - </span><span class=\"email\">". $email ."</span>";

		if($ABN = array_get((array)$client, "Business.AustralianBusinessNumber"))
			$subtitle .= "<span class=\"separator\"> - </span><span class=\"email\">". $ABN ."</span>";
		
		$subtitle .= "</div>";
	}
	if ($subtitle)
		$document->addblock('sub-title', $subtitle, 'title-bottom');			
@endphp
@title($client->InsuredName.'-'. $name ." - RECOMMENDED COVERS")
@page_title($client->InsuredName.'-'. $name ." - RECOMMENDED COVERS")
@groupblock('sidebar-left', 'partials.gi-sidebar', 'gi-sidebar', ['active' => 'client.profiles'])
@groupblock('gi-client-profile-submenu', 'Client.gi-sidebar', 'client-gi-sidebar', compact('ClientID'))
@body_class('has-sidebar')

@js("https://www.gstatic.com/charts/loader.js", "charts")

@jsblock('Client.Recommendations.table-handler', 'client-profile-handler', compact("recommended", "recommendations", "client"))
@jsblock('Client.Recommendations.wheel-script', 'wheel-script', compact("recommended", "recommendations", "client"), [], 10)

@section("content")

<style>
	.d-inline-block{display: inline-block;}
	.d-block{display: block;width: 100%;}
	.options .cover-options-list{
		height: 300px;
		overflow-x: hidden;
		overflow-y: auto;
		background: #efefef;
	}
	.buttons {height: 450px;}
		.buttons button{height: 65px;}
		.buttons > .row > .col-md-12 {
			position: absolute;
			top: 35%;
		}
	.search-option-form .input-group{
		border-radius: 50px;
		-moz-border-radius: 50px;
		-webkit-border-radius: 50px;
		padding: 1px;
		overflow: hidden;
		border: 1px solid #aaa;
		width: 100%;
	}
		.search-option-form .input-group-addon {
			border: 0;
			background: transparent;
			position: absolute;
			left: 0;
			z-index: 10;
    		top: 5px;
		}
		#cover-option-search {
			background: #efefef;
			color: #444;
			border: 0;
			width: 100%;
			padding-left: 30px;
		}
			#cover-option-search:active, #cover-option-search:focus{
				background: #fff;
				color: #000;		
			}
			#cover-option-search:active ~ .input-group-addon, #cover-option-search:focus ~ .input-group-addon{
				background: #fff;
			}

	.cover-selection-form > .row {
		margin: 0;
		background: #fff;
		border-radius: 5px;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		padding-top: 10px;
		padding-bottom: 10px;
	}
		.cover-selection-form .notes{
			display: none;
		}
		.cover-selection-form.expanded .list{
			display: none;
		}
			.cover-selection-form li.active a {color: #fff!important;}
		.cover-selection-form.expanded .notes{
			display: inline;
		}
	
	.btn-expand{float:right;}
	.card .card-header {
		display: inline-block;
		width: 100%;
		background: #3c8dbc;
		font-weight: bold;
		color: #ffffff;
	}

	

	#policy-notes {
		height: 350px;
	}
	.scroll-wrapper{
		min-height: 100%;
		overflow: hidden;
		position: relative;
	}
	.list .scroll{
		margin-top: 35px;
		height: 335px;
		overflow-x: hidden;
		overflow-y: auto;
	}
		.list .scroll th {
			/*position: relative;*/
		}
		.list .scroll th .text {
			position: absolute;
			width: 100%;
			top: -11px;
			padding: 11px 10px;
    		margin-left: -10px;
    		background: #3c8dbc;	
			color: #ffffff;
			font-size: 18px;
		}
		.list .scroll tbody tr {
			cursor: pointer;
		}
		#wheel {
			height: 455px;
			width: 750px;
			position: relative;
			margin: 0 auto;
		}
			#wheel > div {
				position: absolute;
			}
				#wheel > div#secondlayer{
					top: 0;
    				left: 151px;
				}
				#wheel > div#corelayer{
					top: 127px;
					left: 276px;
				}

				/* orly */
				.section-01{
					background-color:#fff;
				}

				.col-md-12 strong{
					color: #006697;
				}

				.card-block .btn{
					background-color: #006697;
					color: #ffffff;
				}

				select {
				   padding: 10px;
				}

				h4 .header-2{
					font-weight: 600;
				}

				.cover-options-list li{
					background-color: #fff;
				}
	.options 

</style>
<div class="section-01">
	
		<div class="wheel row">
			<div class="col-md-12 ">
				<p></p>
				<p class="col-md-12">Provide cover recommendations for: <strong>{{ $client->InsuredName }}</strong></p>
				<!--p class="col-md-12">
					<select>
						<option>Business Insurance</option>
						<option>Just Coffee Insurance</option>
					</select>
				</p-->
				<div class="col-md-12 text-center">
					<div id="wheel">
						<div class="hidden empty">No Recommended covers.</div>
						<!--<div id="thirdlayer"></div>-->
						<div id="secondlayer"></div>
						<div id="corelayer"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="tabs grid-90">
			
			<div class="cover-selection-form r-pad-3x">
				
					<div class="grid-100">
						<p></p>
						<p>To recommend cover options to your client select a cover from the left and click the <b>"Select"</b> button.</p>	
					</div>

					<div class="row">

					<div class="col-md-6 options">
						<div class="cover-list">
							<div class="col-md-10">
								<div class="row">
									<h4 class="header-2">Cover Options</h4>
									<!-- <div class="col-md-12 search-option-form form-group">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-search"></i>
											</div>
												<input id="cover-option-search" class="form-control">
										</div>
									</div> -->

									<div class="card">
										<div class="card-header text-center">
											<h4>Type of Cover</h4>
										</div>
										<ul class="cover-options-list list-group list-group-flush">
											@foreach($recommendations as $key => $recommendation)
												@php 
													$active = (bool)arr_lfind($recommended, "PolicyTypeID", $recommendation['PolicyTypeID']);
												@endphp
												<li class="list-group-item {{ $active ? 'disabled' : '' }}" 
													id="policy-{{ $recommendation['PolicyTypeID'] }}"
													data-policy={{ $key }}
												>
													<a href='#' class="d-block" data-action="setCover" >
													{{ $recommendation['DisplayText'] }}
													</a>
												</li>
											@endforeach
										</ul>

										<div class="card-block row form-group">
											<div class="col-md-10">
												<button class="btn">Create New Cover</button>
											</div>
											<div class="col-md-2">
												<!-- <button class="btn btn-expand"><i class="fa fa-expand"></i></button> -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2 buttons">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group" style = "text-align:center;">
											<button class="btn d-block select disabled" href="#" data-action="select">
												<span class="d-block"></span>
												<i class="fa fa-chevron-right fa-2x"></i>
											</button>
											<span>Select</span>
										</div>
										<div class="form-group" style = "text-align:center;">
											<button class="btn d-block delete disabled" href="#" data-action="delete">
												<i class="fa fa-remove fa-2x"></i>
											</button>
											<span>Delete</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 list">
						<div class="row">
							
							<h4 class="col-md-12 header-2">Recommended Options</h4>
							<div class="col-md-12" style = "">
								<div class="scroll-wrapper">
								<div class="scroll">
								<!-- <div class="card-header text-center">
										<h4>Type of Cover</h4>
									</div> -->
								<table class="table table-striped">
									<thead>
										<tr  class = "table-header">
											<th><h4 class="text">Type of Cover</h4></th>
											<th><h4 class="text">Notes</h4></th>
										</tr>
									</thead>
									<tbody>
										@foreach($recommended as $recommendation)
											<tr data-recommendation="{{ json_encode($recommendation) }}">
												<td class="col-md-5 displayText">{{ array_get($recommendation, "PolicyType.DisplayText", "N/A") }}</td>
												<td class="notes-column col-md-7">
													<span class="summary">{{ str_limit(array_get($recommendation, "Notes", "N/A"), 10) }}</span>
													<button class="btn btn-expand" data-table="recommendations"><i class="fa fa-expand"></i></button>
												</td>
											</tr>
										@endforeach
										<tr class="hidden">
											<td class="displayText col-md-5"></td>
											<td class="notesText col-md-7">
												<span class="summary"></span>
												<button class="btn btn-expand"><i class="fa fa-expand"></i></button>
											</td>
										</tr>
									</tbody>
								</table>
								</div>
								
							</div>
							</div>
						</div>
					</div>

					<div class="col-md-6 notes">
						<div class="row">
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-12">
										<h4 class="col-md-12">Notes</h4>
										<div class="form-group col-md-12">
											<textarea id="policy-notes" class="form-control" placeholder="Recommendation Notes"></textarea>
										</div>
										<div class="form-group col-md-12">
											<button class="btn btn-primary dismiss" onclick="$('.cover-selection-form').toggleClass('expanded');">Close</button>
											<button class="btn btn-primary hidden update" >Update</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					</div>
			</div>
		</div>	
	
</div>
@endsection
