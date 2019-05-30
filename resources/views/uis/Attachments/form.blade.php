@extends('layouts.master-cmi')

@section('body')

<div class="container">

<div class="attachments-form" data-attachment-view="attachments-view-1">
	<div class="row">
		{{Form::open(['route' => ['attachments.upload', "ParentID" => $ParentID, "EntityName" => $EntityName, "DocumentTypeID" => $DocumentTypeID], 'files' => true, 'type' => 'get'])}}
			<div class="btn-group row">
				
					<button data-href="{{route('attachments.upload')}}" class="btn btn-info">Browse</button>
					{{Form::file('file', ['multiple' => false, 'class' => 'btn col-md-6'])}}
				
					{{Form::submit('Upload', ['class' => 'btn btn-success'])}}
				</div>
			</div>
			{{Form::close()}}
		</div>
	</div>

	<div class="row attachments-view-1">

	</div>
</div>
<style>
	.attachments-form .col-md-2{
		position: relative;
		overflow: hidden;
	}
	.attachments-form button {}
	.attachments-form input[type="file"] {
		opacity: 0;
		position: absolute;
		left: 0;right: 0;
		top: 0;bottom: 0;
		z-index: 2;
		margin-left: -100px;
	}

</style>

<script>
	$(document).ready(function(){
		
	});
</script>

@endsection