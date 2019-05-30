@if(count($attachments))
<div class="panel-body">
	@foreach($attachments as $i)
	<div class="file-round-box">
		{{ $i->Filename }} {!! $can_update ? "<i class='remove glyphicon glyphicon-remove-sign glyphicon-white' data-fid='".$i->FileAttachmentID."'></i>" : '' !!}
	</div>
	@endforeach
</div>
@endif