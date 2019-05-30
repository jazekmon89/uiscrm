	<option value="" data-url="#">Please select</option>
@foreach($insurance_policies as $k=>$i)
	<option value="{{ $k }}" {{ isset($insurance_policy_id) && $k == $insurance_policy_id?'selected':'' }} data-url="{{ route('claims-request-history', [$OrganisationID, $k]) }}">{{ $i }}</option>
@endforeach