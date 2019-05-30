@php 
	$baseKey .= ($baseKey ? '.' : '') . $group->Name;
	$baseID = app('FormMacros')->transformKey($baseKey);
	$group_name = snake_case($group->Name);
@endphp
<style type="text/css">
 	.questions label {
        font-size: 13px;
        font-weight: bold;
    }
    table.table td, table.table th {
    	border-color: transparent !important; 
    }
    table thead tr th {
    	background: #F2A314;
    	color: #fff;
    	border: 1px inset #f4b03a !important; 
    	font: 12px/1.5 "Open Sans", Helvetica, sans-serif !important;
    }
	table, table tr, table td, table th {
		white-space: inherit !important;  
		vertical-align: middle !important;
	}
	.table > thead > tr > th, .table > thead > tr > td, 
	.table > tbody > tr > th, .table > tbody > tr > td, 
	.table > tfoot > tr > th, .table > tfoot > tr > td {
		padding: 8px !important;
	}
	/*table td .input-group-addon {
		border: 0!important;
		border-bottom: 1px solid #aaa!important;
		border-radius: 0;
		-moz-border-radius: 0;
		-webkit-border-radius: 0;
		background: transparent!important;
	}*/
	#directors-header {
		/*border-bottom: 2px solid #aaa!important;*/
	}
	#total-row {
		/*border-top: 2px solid #aaa!important;	*/
	}
	@media (max-width: 767px) {
		.input-group .form-control, 
		.form-control {
			width: inherit !important;
		}
	}
</style>
<div class='questions' id='grp-{{$group_name}}'>
	<div class="bem-table__container sm-table-responsive">
		<table class="table table-striped" border="0">
			<thead>
				<tr>
					<th>Category</th>
					<th>Number of staff</th>
					<th></th>
					<th>Estimated wages for 12 months period</th>
				</tr>
			</thead>	

			<tbody>
				<tr>
					<td><label>Direct Employees</label></td>
					<td class="eq-staffs">
						{{ Form::jInput("number", "{$baseKey}.92946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => 'calculateStaffs()']) }}
					</td>
					<td></td>
					<td class="eq-wages">
						<div class="input-group">
						<div class="input-group-addon">$</div>
							{{ Form::jInput("number", "{$baseKey}.93946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => 'calculateWages()']) }}
						</div>
					</td>
				</tr>
				<tr>
					<td><label>Subcontractors</label></td>
					<td class="eq-staffs">{{ Form::jInput("number", "{$baseKey}.94946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => 'calculateStaffs()']) }}</td>
					<td></td>
					<td class="eq-wages">
						<div class="input-group">
						<div class="input-group-addon">$</div>
							{{ Form::jInput("number", "{$baseKey}.95946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => 'calculateWages()']) }}
						</div>
					</td>
				</tr>
				<tr id="directors-header">
					<td><label>Working Directors</label></td>
					<td id="wd-count">{{ Form::jInput("hidden", "{$baseKey}.99946461-C2A1-E611-902E-000C292D0644") }}</td>
					<td><label>Full Names</label></td>
					<td></td>
				</tr>
				@php $cnt = 1 @endphp
				@foreach(Form::getInputValue("{$baseKey}.Director", []) as $i => $val)
				<tr class="directors"
					id="{{ "InsuranceOptions-Director-{$i}" }}"
				>
					<td></td>
					<td class="eq-staffs" >{{ Form::jInput("hidden", "{$baseKey}.Director.{$i}.99946461-C2A1-E611-902E-000C292D0644", 1) }}</td>
					<td class="staff-checker">{{ Form::jInput("text", "{$baseKey}.Director.{$i}.9B946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => "updateStaffsAndWages(this)"]) }}</td>
					<td class="eq-wages">
						<div class="input-group">
						<div class="input-group-addon">$</div>
							{{ Form::jInput("number", "{$baseKey}.Director.{$i}.9C946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => "updateStaffsAndWages(this)"]) }}
						</div>
					</td>
				</tr>
				@php $cnt++ @endphp
				@endforeach
				@php $empty = isset($i) ? $i + 1 : $cnt - 1 @endphp
				<tr class="directors director-repeater"
					id="{{ "InsuranceOptions-Director-{$empty}" }}"
					data-repeat-match="{{ "{$baseKey}.Director" }}"
					data-repeat-index="{{ $empty }}"
				>		
					<td></td>		
					<td class="text-right eq-staffs" >
						{{ Form::jInput("hidden", "{$baseKey}.Director.{$empty}.99946461-C2A1-E611-902E-000C292D0644", 0) }}
						<button class="btn btn-maroon">+ Add</button>
					</td>
					<td class="staff-checker">{{ Form::jInput("text", "{$baseKey}.Director.{$empty}.9B946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => "updateStaffsAndWages(this)"]) }}</td>			
					<td class="eq-wages">
						<div class="input-group">
						<div class="input-group-addon">$</div>
							{{ Form::jInput("number", "{$baseKey}.Director.{$empty}.9C946461-C2A1-E611-902E-000C292D0644", null, ['class' => 'form-control', 'onchange' => "updateStaffsAndWages(this)"]) }}
						</div>
					</td>
				</tr>
				
				<tr id="total-row">
					<td><label>Total Staff</label></td>
					<td id="total-no-staffs">
						<input type="text" class="form-control">
					</td>
					<td><label>Total Wages</label></td>
					<td id="total-wages">
						<div class="input-group">
						<div class="input-group-addon">$</div>
							<input type="text" class="form-control">
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	@php
		foreach($group->questions as $key => $question) 
		{	
			$exclude = [
				'92946461-C2A1-E611-902E-000C292D0644', '93946461-C2A1-E611-902E-000C292D0644',
				'95946461-C2A1-E611-902E-000C292D0644', '94946461-C2A1-E611-902E-000C292D0644', 
				'99946461-C2A1-E611-902E-000C292D0644', '9B946461-C2A1-E611-902E-000C292D0644', '9C946461-C2A1-E611-902E-000C292D0644'
			];
			if (in_array($question->FormQuestionID, $exclude)
			|| preg_match('/(^Direct|Subcontractor|WorkingDirector|GeneralEmployee|Contractors)/', $question->Name)) 
			{
				unset($group->questions[$key]);
			}		
		}
		foreach($group->children as $key => $subgroup)
		{
			if (in_array($subgroup->Name, ['Contractor', 'Director']))
			{
				unset($group->children[$key]);
			}
		}
	@endphp	
	@include('Quotes.Form.DynamicQuestions-Questions', ['current' => $group, 'baseKey' => $baseKey])
	@if(isset($group->children) && $group->children)
		<div class="sub-groups" >
			@if(isset($group->children) && $group->children)
				@include("Quotes.Form.partials.subgroups", ['children' => $group->children, 'baseKey' => $baseKey])	
			@endif
		</div>	
	@endif
</div>
<script type="text/javascript">
	var calculateStaffs = function() {
			var total = 0,
				update = update || true;
			$('.eq-staffs input').each(function() {
				total += (parseInt($(this).val()) || 0)
			});
			if (update) {
				$('#total-no-staffs input').val(total);
			}
			return total;
		},
			calculateWages = function(update) {
				var total = 0,
					update = update || true;
				$('.eq-wages input').each(function() {
					total += (parseInt($(this).val()) || 0)
				});
				if (update) {
					$('#total-wages input').val(total);
				}
				return total;
			},
			updateStaffsAndWages = function(trigger) {
				var me = $(trigger),
					row = me.parents('tr:first'),
					checker = row.find('.staff-checker input');
				if (!checker.val()) {
					row.find('.eq-wages input').val("");
					row.find('.eq-staffs input').val(0);
				}
				else {
					console.log(row.find('.eq-staffs input'));
					row.find('.eq-staffs input').val(1);
				}
				calculateWages();
				calculateStaffs();
				calculateDirectors();
			},
			calculateDirectors = function() {
				var inp = $('#wd-count input'), total = 0;

				$('.staff-checker input').each(function(){
					if ($(this).val())
						total += 1;
				});
				inp.val(total);
			}

	jQuery(document).ready(function() {
		$('.directors.director-repeater .btn').click(function(event) {
			var me = $(this).attr('disabled', true);
			event.preventDefault();
			
			if (typeof incrementInputIndexes !== 'function') {
				return alert("Ops! Something wen't wrong please refresh page.");
			}
			var repeat = me.parents('tr:first'),
				clone = repeat.clone(),
				btn = clone.find('.btn');
			me.insertAfter(btn);
			btn.remove();
			incrementInputIndexes(clone, (repeat.data('repeat-index') || 0) + 1);
			clone.insertAfter(repeat);
			me.attr('disabled', false);
			return false;
		});

		calculateWages();
		calculateStaffs();
		calculateDirectors();

	});

</script>
