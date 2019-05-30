{{-- see var RFQ | action --}}

<div id="actions" class="disp-inline-block">
	<div class="dropdown">
	  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Actions
	  <span class="caret"></span></button>
	  <ul class="dropdown-menu" style="left: auto;right:0;">
	  	@if($action === 'edit')
	    	<li><a href="{{ route('rfqs.view', $RFQ['RFQID']) }}">Cancel Edit</a></li>
	    @else
	    	<li><a href="{{ route('rfqs.view', [$RFQ['RFQID'], 'action' => 'edit']) }}">Edit</a></li>
	    @endif
	    @if(array_get($RFQ, 'Lead') && strtolower($action) !== 'edit')
	    	<li><a href="#match-contact" data-toggle="modal">Match Contact</a></li>
	    @endif
	    
	    <!--<li><a href="#match-client" data-toggle="modal">Match Client</a></li>-->
	    <li><a href="#non-proceed" onclick="non_proceed()">Non-Proceed</a></li>
	    <li><a href="#" onclick="requote()">Requote</a></li>
	    <!--<li><a href="#change-expiry-date" data-toggle="modal">Change Expiry Date</a></li>
	    <li><a href="#create-quote" data-toggle="modal">Create Quote</a></li>
	    <li><a href="#upload-modal" onclick="uploadQuotesModal()">Upload Quotes</a></li>-->
	    <li><a href="#" data-toggle="modal" data-target="#new_task">Add Task</a></li>
	    <li><a href="#" data-toggle="modal" data-target="#new_note">Add Note</a></li>
	    <li><a href="#" data-toggle="modal" data-target="#new_attachment">Add Attachment</a></li>
	    <li><a href="{{ route('download-csv', $RFQ['RFQID']) }}">Download CSV</a></li>

	  </ul>
	</div>
</div>