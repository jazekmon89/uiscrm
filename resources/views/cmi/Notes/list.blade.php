<style>
	table thead tr th{
		background: #006697;
		color: #fff;
		border: 1px inset #4b8cad;
	}
	table tr {
		border: 0 !important;
	}		
	table tr td {
	 	border: 1px solid #dfdbdc !important;
	}
</style>
<div class="bem-container__center">
	<div class="container-fluid">
		<div class="row">
			<div class="bem-table__container table-responsive">
				<table class="table table-hover table-striped table-fixed-header">
					<thead>
						<th>Date Created</th>
						<th>Created By</th>
						<th>Description</th>
					</thead>
						<tr><td></td></tr>
						@if(isset($notes))
							@foreach($notes as $note)
								<tr><td>{{ $note->DateCreated }}</td></tr>
								<tr><td>{{ $note->CreatedBy }}</td></tr>
								<tr><td><textarea>{{ $note->Description }}</textarea></td></tr>
							@endforeach
						@endif
					<tbody>
					</tbody>
				</table>
			</div>
		</div>	
	</div>
</div>