@inject('Policy', 'App\Helpers\PolicyHelper')
@extends('layouts.Backend')
@php
	$name = array_get($RFQ, "Contact", array_get($RFQ, "Lead"));
	$phone = array_get($RFQ, "Contact.MobilePhoneNumber", array_get($RFQ, "Lead.PhoneNumber"));
	$email = array_get($RFQ, "Contact.EmailAddress", array_get($RFQ, "Lead.EmailAddress"));
	$addr = array_get($RFQ, "Contact.PostalAddress", array_get($RFQ, "Lead.Address", array_get($RFQ, "InsurableBusiness.PostalAddress", [])));

	$title = implode(' ', array_filter([
		$RFQ['RFQRefNum'], 
		array_get($RFQ, "PolicyType.DisplayText"), 
		$RFQ['InsuredName'], 
		aname($name),
		"- ".$title
	]));

	$subtitle = implode(' | ', array_filter([
		address($addr),
		$phone,
		$email
	]));
	$Lead = array_has($RFQ, "Lead") ? " - Lead" : "";
@endphp

@title($title)
@page_title($title)
@body_class('rfq layout-full has-sidebar')

@groupblock("sub-title", "<p>{$subtitle}</p>", 'title-bottom')
@groupblock('sidebar-left', 'partials.rfq-gi-sidebar', 'rfq-gi-sidebar', ['active' => 'rfqs'])
@groupblock('gi-rfqs-submenu', 'RFQ.Backend.gi-sidebar', 'rfq-gi-sidebar-submenu', ['RFQID' => $RFQ['RFQID']])

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')

@section('content')
	<style>
	.form-tab-step a{
		color: #636b6f !important;
	}

	.form-tab-step.active a{
		color: #460000 !important;
	}
	.cont-wrapper .white-box.full-box{
		padding: 15px;
	}
	#search-fields .address {
		position: relative;
	}
	#search-fields th{ border: 0!important; }
		#search-fields .address .dropdown{
			position: absolute;
			top: 0;
			z-index: 100;
			width: 80%;
		}
			#search-fields .address .dropdown > a{
				display: block;
				width: 100%;
				text-decoration: none!important;
				height: 40px;
			}
			#search-fields .address .dropdown  > ul {
				width: 300px;
				padding: 5px;
			}
		 #search-fields .address .dropdown .input-group-addon {
		 	border: 0;
			background: none;
		 }
	.modal .inner-overlay{
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0; left: 0;
		z-index: 100;
	}
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
@if(false)
	<!--div id="form-wrapper" class="grid-100">
			<div class="row">
				<div class="col-md-12 client_policy_notes">
					<div class = "form-group">

<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
  Add new note
</button>
</div>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Note</th>
								<th>Created By</th>
								<th>Date Created</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($notes as $key => $value)
								<tr data-id = "{{ $value['NoteID'] }}" class = "notes-list">
									<td class = "notes-description">
										{{ strip_tags($value["Description"]) }}
									</td>
									<td>
										{{ $value["CreatedBy"] }}
									</td>
									<td>
										{{ $value["CreatedDateTime"] }}
									</td>
									<td class = "action">
										<a href = "#" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-edit"></span></a>
										<a href = "{{ route('notes.delete', $value['NoteID']) }}"><span class="glyphicon glyphicon-remove-circle"></span></a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
	</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Note</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(['route' => ['notes.create'], 'class' => 'notes-form']) !!}
		<input type = "hidden" name = "ParentID" class = "ParentID" value = "{{ $RFQID }}">
		<input type = "hidden" name = "NoteID" class = "note-id" value = "">
          <div class="form-group">
			  <label for="sel1">Select Entity:</label>
			  <select class="form-control" id="sel1" name = "EntityName">
			    @foreach($entities as $key => $value)
			    	<option value = "{{$key}}">{{$key}}</option>
			    @endforeach
			  </select>
			</div>
          <div class="form-group">
            {{ Form::label('desc_label', 'Description') }}
            {{ Form::textarea('Description', null, ['rows'=>'10', 'class' => 'Description', 'style' => 'min-width: 100%']) }}
          </div>
        
      </div>
      <div class="modal-footer">
        {{ Form::submit('Submit Request',['class' => 'form-control btn-outline-maroon btn btn-default submit-note', 'style' => 'position: relative !important']) }}    
      </div>
       {!! Form::close() !!}
    </div>
  </div>
</div>
<script>
	$(document).ready(function(e){
		$('body').on('click', '.btn-update', function(){
			var desc =  $.trim($(this).parent().parent('.notes-list').find('.notes-description').html());
			var id =  $.trim($(this).parent().parent('.notes-list').data('id'));
			$('.notes-form').attr("action", "{{ route('notes.update') }}");
			console.log(id);
			$('.note-id').val(id);
			$('.Description').val(desc);
			// console.log();

		});		

		$('body').on('click', '.btn-new', function(){
			$('.notes-form').attr("action", "{{ route('notes.create') }}")
			$('.Description').val('');
		});	
	});
</script>	
-->
	@endif
	<div id="form-wrapper" class="grid-100">
		<div class="row">
			<div class="col-md-12 client_policy_notes">
				{!! $note_buttons !!}
				{!! $note_content !!}
			</div>
		</div>
	</div>

@endsection


