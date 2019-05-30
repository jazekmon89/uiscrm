@extends('layouts.Backend')
@title('RFQs')
@page_title('RFQs')
@body_class("rfqs layout-box")
@js('plugins/daterangepicker/moment.js', 'moment')

@section('content')
	<style>
		table thead tr th{
			background: #006697;
			color: #fff;
			border: 1px inset #4b8cad;
		}
		table tr {
			border: 0 !important;
		}
		table thead tr#search-current-fields th, table thead tr#search-history-fields th{
			background: #eee;
			border: 1px solid #dfdbdc !important;
		}

		table tr td {
		 	border: 1px solid #dfdbdc !important;
		}
	</style>
	@include("RFQ.Backend.list", ['rfqs' => $rfqs, 'type' => 'current'])

	@if($current)
		<h4>History</h4>

		@include("RFQ.Backend.list", ['rfqs' => [], 'type' => 'history'])	
	@endif
	<script>
		jQuery(document).ready(function(){
			var getData = function(raw, depth, def) 
			{
				var depth = typeof depth === 'string' ? depth.split('.') : depth;
				var ref = raw;
				for(var i in depth) {
					if (ref[depth[i]]) {
						ref = ref[depth[i]];
					}
					else return def;
				}
				return ref;
			},
				NotEmpty = function(value) 
				{
					return !!value;
				},
				getState = function(shortName) 
				{
					var states = {!! json_encode(all_states()) !!};

					return states[shortName] || shortName;
				},
				Address = function(Address) 
				{
					return Address ? [
						//Address.UnitNumber,
						//Address.StreetNumber,
						//Address.StreetName,
						Address.AddressLine1,
						getState(Address.State),
						Address.Postcode,
						Address.Country
					].filter(NotEmpty).join(' ') : "";
				},
				Name = function(Name)
				{
					return Name ? [
						Name.FirstName,
						Name.MiddleNames,
						Name.Surname,
						Name.Name
					].filter(NotEmpty).join(' ') : "";
				},
				DateFormat = function(date)
				{
					return date ? moment(date).format("MM/DD/YYYY") : "";
				},
				RFQLink = function(RFQ, title)
				{
					var route = '{{ route('rfqs.view', "RFQID") }}'.replace('RFQID', RFQ.RFQID);
					
					return '<a href="'+ route +'" >'+title+'</a>'
				}
			$('form').submit(function() {
				var me = $(this),
					btns = me.find('table input'),
					data = me.serializeArray(),
					list = me.find('table tbody');
					
				btns.attr('disabled', true);
				$.ajax({
					type: 'get',
					url: me.attr('action') + '?status='+ me.attr('status'),
					data: data,
					success: function(results) {

						btns.attr('disabled', false);
						list.html("");
						
						if (results && results.length)
						{
							for(var i = 0; i < results.length; i++)
							{
								var row = "<tr>";

								row += "<td>"+ RFQLink(results[i], getData(results[i], "RFQRefNum")) +"</td>";
								row += "<td>"+ getData(results[i], "PolicyType") +"</td>";
								row += "<td>"+ getData(results[i], "RFQStatus") +"</td>";
								row += "<td>"+ getData(results[i], "InsuredName") +"</td>";
								row += "<td>"+ getData(results[i], "Name") +"</td>";
								row += "<td>"+ getData(results[i], "Address") +"</td>";
								row += "<td>"+ DateFormat(getData(results[i], "LodgementDateTime")) +"</td>";
								row += "<td>"+ getData(results[i], "PhoneNumber") + "</td>";
								row += "<td>"+ getData(results[i], "EmailAddress") + "</td>";
								row += "<td>"+ getData(results[i], "BusinessAddress") +"</td>";

								row += "</tr>";

								list.append($(row));
							}
						}
						else {
							list.append($("<tr><td colspan='10' class='text-center'>No match foud.</td></tr>"));

						}
					},
					error: function(xhr) {
						
						btns.attr('disabled', false);
					}
				});
				return false;
			});
		});
	</script>

@endsection
