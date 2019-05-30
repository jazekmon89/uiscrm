@inject("TaskCon", "App\Http\Controllers\TasksController")

@php 
	$vars = $TaskCon->createTaskPopupVars($ParentID, $EntityName);

	extract($vars);
@endphp

@cssblock("uis.modal.spinner",'all_styles')

@jsblock("uis.Tasks.js.scripts", "all_scripts", ['TaskTypeID'=>$task_type_id, 'ParentID' => $parent_id, 'EntityName'=>$entity_name, 'list_url'=>route('task-getAll')])

@include('Tasks.main')
