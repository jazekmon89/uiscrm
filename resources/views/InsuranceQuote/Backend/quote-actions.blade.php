{{-- see var Quote | action --}}

<div id="actions" class="disp-inline-block">
	<div class="dropdown">
	  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Actions
	  <span class="caret"></span></button>
	  <ul class="dropdown-menu" style="left: auto;right:0;">
	  	@if($action === 'edit')
	    	<li><a href="{{ route('insurancequotes.view', $Quote['InsuranceQuoteID']) }}">Cancel Edit</a></li>
	    @else
	    	<li><a href="{{ route('insurancequotes.view', [$Quote['InsuranceQuoteID'], 'action' => 'edit']) }}">Edit</a></li>
	    @endif
	    @if(array_get($Quote, 'Lead') && strtolower($action) !== 'edit')
	    	<li><a href="#match-contact" data-toggle="modal">Match Contact</a></li>
	    @endif
	    <li><a href="#">Import Quotes</a></li>
	    <li><a href="#non-proceed" onclick="non_proceed()">Non-Proceed</a></li>
	    <li><a href="#">Upload FSG</a></li>
	    <li><a href="#">Upload Quotation Schedule</a></li>
	    <li><a href="#">Upload PDS</a></li>
	    <li><a href="#">Upload Application</a></li>
	    <li><a href="#" data-toggle="modal" data-target="#new_task">Add Task</a></li>
	    <li><a href="#" data-toggle="modal" data-target="#new_note">Add Note</a></li>
	    <li><a href="#" data-toggle="modal" data-target="#new_attachment">Add Attachment</a></li>

	  </ul>
	</div>
</div>