<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\FileAttachmentsController;
use App\Http\Requests;
use App\Task;
use Flash;
use Validator;
use App\Attachment;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    protected $task = null, $helper = null, $attachment = null, $file_attachment = null;

    public function __construct() {
        $this->task = new Task;
        $this->attachment = new Attachment;
        $this->file_attachment = new FileAttachmentsController($this->attachment);
        $this->middleware('admin');
    }

    /**
     * Creates the task interface
     *
     * @return: Array {
     *              item1: Rendered Task Interface 
     *              item2: CSS array
     *              item3: JS array
     *          }
     */
    public function createTaskInterface($EntityName = null, $ParentID = null, $ClientID = null, $OrganisationID=null, $TaskTypeID = null, $isHideStatusSelection = true, $isHideList = false, $isHideCompleteButton = false, $hideButtons = false, $can_update = false){
        $task_types = $this->getTaskTypes();
        $task_list = [''=>'Please select'];
        foreach($task_types as $k=>$i){
            $task_list[$i->TaskTypeID] = $i->DisplayText;
        }
        $task_status_list = array_merge([''=>'Please select.'], $this->generateDisplayText('TaskStatusID',$this->getTaskStatuses()));
        usort($task_types, array($this, 'compareDisplayOrder'));
        $task_types = array_merge([''=>'Please select'], $this->generateDisplayText('TaskTypeID',$task_types));
        if(empty($OrganisationID) && !empty($ClientID)){
            $OrganisationID = null;
            $contact_ids = $this->task->Client_GetContactIDs($ClientID);
            $contact_id = null;
            foreach($contact_ids as $i){
                $org_ids = $this->task->Contact_GetOrganisations_first($i->ContactID);
                if(!empty($org_ids)){
                    $OrganisationID = $org_ids->OrganisationID;
                    //$contact_id = $i->ContactID;
                    break;
                }
            }
        }
        if(empty($OrganisationID))
            $assignees[] = ['Assignee_UserID'=>'', 'DisplayText'=>'No data.'];
        else{
            $assignees = $this->filterContacts($OrganisationID);
            $assignees = arr_pairs($assignees, 'Assignee_UserID', 'DisplayText');
            $assignees = array_merge([''=>'Please select'], $assignees);
        }
        if(!empty($ParentID))
            $task_list = $this->getAll($ParentID, $can_update);
        $vars_torender = [
            'task_type'=>'',
            'task_type_list'=>$task_types,
            'parent_id'=>$ParentID,
            'entity_name'=>$EntityName,
            'isHideStatusSelection'=>$isHideStatusSelection,
            'isHideList'=>$isHideList,
            'isHideCompleteButton'=>$isHideCompleteButton,
            'hideButtons'=>$hideButtons,
            'client_id'=>$ClientID,
            'organisation_id'=>$OrganisationID,
            'can_update'=>$can_update,
            'assignees'=>$assignees,
            'assignee'=>'',
            'description'=>'',
            'subject'=>'',
            'due_date'=>'',
            'task_status'=>'',
            'task_status_list'=>$task_status_list
        ];
        if($can_update)
            $vars_torender['task_list'] = $task_list;
        $rendered = view('uis.Tasks.main',$vars_torender)->render();
        $rendered_buttons = false;
        if($hideButtons)
            $rendered_buttons = view('uis.Tasks.buttons',['display_texts'=>$task_types,'ParentID'=>$ParentID,'EntityName'=>$EntityName])->render();
        $css = [
            ['uis.modal.spinner','spinner-styles'], // styles for modal with loading
            ['uis.FileAttachments.css.styles', 'file-attachment-widget-style'],
            ['uis.Tasks.css.styles', 'tasks-all-styles']
        ];
        $js = [
            ['', 'momentjs', [], '/plugins/daterangepicker/moment.js'],
            ['', 'bootstrap-datepickerjs', [], '/js/datetimepicker/bootstrap-datetimepicker.js'],
            ['uis.Tasks.js.scripts', 'task_scripts', 
                [
                    'list_url'=>route(($can_update?'task-getAll-update':'task-getAll')),
                    'files_widget_url'=>route('attachments.'.($can_update?'update':'default').'-attachments-widget'),
                    //'create_form_url'=>route('task-create-interface'),
                    //'update_form_url'=>$can_update?route('task-update-interface'):'',
                    'get_task_infos'=>$can_update?route('task-get-infos'):'',
                    'EntityName'=>$EntityName,
                    'ParentID'=>$ParentID,
                    'ClientID'=>$ClientID,
                    'OrganisationID'=>$OrganisationID,
                    'TaskTypeID'=>$TaskTypeID,
                    'can_update'=>$can_update,
                    'isHideStatusSelection'=>$isHideStatusSelection,
                    'isHideList'=>$isHideList,
                    'isHideCompleteButton'=>$isHideCompleteButton,
                    'hideButtons'=>$hideButtons
                ]
            ]
        ];
        return [$rendered,$rendered_buttons,$css,$js];
    }

    private function validator($data){
        $validations = array (
            //'assigned_to' => 'required|min:1',
            'task_request' => 'required|min:1',
        );
        return Validator::make($data, $validations);
    }

    private function updatevalidator($data){
        $validations = array (
            'task_status' => 'required|min:1',
        );
        return Validator::make($data, $validations);
    }

    public function ajaxSave($data, $ajax_flag = true, $immediateRedirect = false){
        if($this->validator($data)->fails()){
            if(!$ajax_flag && $immediateRedirect){
                Flash::error(trans('messages.tasks_incomplete_details'));
                return false;
            }else if(!$ajax_flag && !$immediateRedirect){
                dd($this->validator($data)->errors());
                return false; // has error.
            }
            else
                return trans('messages.tasks_incomplete_details');
        }
        
        $open_status_id = '';
        if(isset($data['status_type']) && !empty($data['status_type']))
            $open_status_id = $data['status_type'];
        else{
            $statuses = $this->getTaskStatuses();
            foreach($statuses as $k=>$i){
                if(strtolower(trim($i->Name)) == 'new')
                    $open_status_id = $i->TaskStatusID;
            }
        }
        
        $organisation_role_id = array_key_exists('OrganisationRoleID', $data)?$data['OrganisationRoleID']:null;
        $due_date = (array_key_exists('due_date', $data)?$data['due_date']:null);
        $CurrentUserID = Auth::user()->user_id;
        $arr = [
            $data['ParentID'],
            $data['EntityName'],
            $data['OrganisationID'],
            $organisation_role_id,
            empty($data['assigned_to'])?null:$data['assigned_to'],
            $data['task_request'],
            $this->getTaskTypeByTaskTypeID($data['task_request'])->DisplayText,
            $data['message_details'],
            $due_date,
            $open_status_id,
            $CurrentUserID,
        ];
        $task_res = $this->task->Task_CreateByTypeName_first($arr, ['TaskID'=>'uniqueidentifier']);
        if($task_res && !property_exists($task_res, 'TaskID')){
            if(!$ajax_flag && $immediateRedirect){
                Flash::error(trans('messages.tasks_failed_error'));
                return false;
            }else if(!$ajax_flag && !$immediateRedirect)
                return false; // has error.
            else
                return trans('messages.tasks_failed_error');
        }

        if(!$ajax_flag && $immediateRedirect)
            return true;
        else if($ajax_flag && !$immediateRedirect)
            return trans('messages.task_success_completed');
        else if(!$ajax_flag && !$immediateRedirect)
            return true;
        else
            return $task_res;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $task_res = $this->ajaxSave($data, false, true);
        if(!$task_res){
            return trans('messages.tasks_failed_error');
            return back();
        }
        /*if(count($request->files)){
            $res = $this->file_attachment->filesSave($request, $task_res->TaskID, 'Task', 'other');
            if(!$res){
                Flash::error(trans('messages.file_attachment_upload_fail'));
                return back();
            }
        }*/
        Flash::success(trans('messages.tasks_success_save'));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($TaskID)
    {
        return $this->task->Task_Get_first($TaskID);
    }

    public function redirectDelete($TaskID)
    {
        $res = $this->delete($TaskID);
        if(!$res){
            Flash::error(trans('messages.task_delete_failed'));
            return back();
        }
        Flash::success(trans('messages.task_delete_completed'));
        return back();
    }

    public function ajaxDelete(Request $request){
        $data = $request->all();
        if(!array_key_exists('TaskID', $data))
            return 'false';
        $res = $this->delete($data['TaskID']);
        if(!$res)
            return 'false';
        return 'true';
    }

    private function delete($TaskID){
        return $this->task->Task_Delete([$TaskID, Auth::id()]);
    }

    public function completeTask(Request $request){
        $data = $request->all();
        $task_statuses = $this->getTaskStatuses();
        $completed_id = null;
        foreach($task_statuses as $i){
            if(strtolower(trim($i->Name)) == 'completed'){
                $completed_id = $i->TaskStatusID;
                break;
            }
        }
        foreach($data['task_ids'] as $i){
            $task_infos = $this->show($i);
            if($task_infos->TaskStatusID != $completed_id){
                $updates = [
                    $i,
                    $task_infos->AssignToOrganisationRoleID,
                    $task_infos->AssignToUserID,
                    $task_infos->Subject,
                    $task_infos->Description,
                    $task_infos->DueDateTime,
                    $completed_id,
                    Auth::id()
                ];
                $res = $this->task->Task_Update_first($updates);
                if(!property_exists($res, "return_value") || (property_exists($res, "return_value") && $res->return_value != "0"))
                    return trans('messages.task_failed_error');
            }
        }
        return trans('messages.task_success_completed');
    }

    public function doUpdate($data){
        $headers = ['ParentID','assigned_to','subject','description','due_date','task_status'];
        foreach($headers as $k=>$i){
            if(!array_key_exists($i, $data) || (array_key_exists($i, $data) && empty($data[$i])))
                $data[$i] = null;
        }
        $arr = [
            $data['ParentID'],
            null, // assigntoorganisationroleid - this is not yet implemented in Stage 1.
            $data['assigned_to'],
            $data['subject'],
            $data['description'],
            $data['due_date'],
            $data['task_status'],
            Auth::id(),
        ];
        return $this->task->Task_Update($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();
        if($this->updateValidator($data)->fails()){
            Flash::error(trans('messages.tasks_incomplete_details'));
            return back();
        }
        $data['ParentID'] = $data['TaskID'];
        $res = $this->doUpdate($data);
        if(!$res){
            Flash::error(trans('messages.tasks_failed_error'));
            return back();
        }
        /*
        if(count($request->files)){
            $res = $this->file_attachment->filesSave($request, $data['ParentID'], 'Task', 'other');
            if(!$res){
                Flash::error(trans('messages.file_attachment_upload_fail'));
                return back();
            }
        }
        */
        Flash::success(trans('messages.tasks_success_update'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getIDsByParentID($ParentID){
        return $this->task->Task_GetIDsByParentID($ParentID);
    }

    public function getAssignedTaskIDs($UserID){
        return $this->task->User_GetAssignedTaskIDs($UserID);
    }

    public function getOpenAssignedTaskIDs($UserID){
        return $this->task->User_GetOpenAssignedTaskIDs($UserID);
    }

    public function getTaskIDsByOrganisation($OrganisationID){
        return $this->task->Organisation_GetTaskIDs($OrganisationID);
    }

    public function getOpenTaskIDsByOrganisationID($OrganisationID){
        return $this->task->Organisation_GetOpenTaskIDs($OrganisationID);
    }

    public function getTaskStatusByTaskStatusID($TaskStatusID){
        return $this->task->TaskStatus_Get_first($TaskStatusID);
    }

    public function getTaskTypeByTaskTypeID($TaskTypeID){
        return $this->task->TaskType_Get_first($TaskTypeID);
    }

    private function getTaskStatuses(){
        return $this->task->TaskStatus_GetTaskStatuses();
    }

    private function getTaskTypes(){
        return $this->task->TaskType_GetTaskTypes();
    }

    private function getContactsByOrganisationID($OrganisationID){
        return $this->task->Oganisation_GetContacts($OrganisationID);
    }

    private function compareDisplayOrder($a, $b){
        return $a->DisplayOrder < $b->DisplayOrder ? 0 : 1;
    }

    private function generateDisplayText($id_name, $data){
        if(count($data) == 0)
            return [];
        $display_texts = [];
        foreach($data as $k=>$i){
            $display_texts[$i->$id_name] = $i->DisplayText;
        }
        return $display_texts;
    }

    private function extractContactSpecifics($ContactID){
        $user_infos = $this->task->Contact_Get_first($ContactID);
        $user_name = ucfirst($user_infos->FirstName).' ' .ucfirst($user_infos->Surname).(!empty(trim($user_infos->PreferredName))?'('.$user_infos->PreferredName.')':'');
        return ['Assignee_UserID'=>$user_infos->UserID, 'DisplayText'=>$user_name];
    }

    private function getContactNameByUserID($UserID){
        if(empty($UserID))
            return '';
        $contact = $this->task->Contact_GetByUserID_first($UserID);
        return $contact->FirstName.' '.$contact->Surname;
    }

    private function filterContacts($OrganisationID){
        $contacts = $this->getContactsByOrganisationID($OrganisationID);
        $assignees = [];
        foreach($contacts as $k=>$i){
            if(strtolower(trim($i->OrganisationRoleName)) != 'client'){
                $assignees[] = $this->extractContactSpecifics($i->ContactID);
            }
        }
        return $assignees;
    }

    public function getAllDefault($ParentID = null){
        return $this->getAll($ParentID = null, false);
    }

    public function getAllWithUpdate($ParentID = null){
        return $this->getAll($ParentID, true);
    }
    private function getAll($ParentID, $can_update){
        $tasks = [];
        if(!empty($ParentID)){
            $task_ids = $this->getIDsByParentID($ParentID);
            if(count($task_ids)){
                foreach($task_ids as $k=>$i){
                    $task_infos = $this->show($i->TaskID);
                    $name = "";
                    if(isset($task_infos->AssignToUserID)){
                        $user = $this->task->Contact_GetByUserID_first($task_infos->AssignToUserID);
                        $name = $user->FirstName." ".$user->Surname;
                    }
                    //$existingAttachmentsWidget = $can_update?'updateExistingAttachmentsWidget':'defaultExistingAttachmentsWidget';
                    $tasks[] =  (object)[
                        'TaskID'=>$task_infos->TaskID,
                        'TaskType'=>$this->getTaskTypeByTaskTypeID($task_infos->TaskTypeID)->DisplayText,
                        'TaskTypeID'=>$task_infos->TaskTypeID,
                        'Subject'=>$task_infos->Subject,
                        'Description'=>$task_infos->Description,
                        'Assigned'=>$this->getContactNameByUserID($task_infos->AssignToUserID),
                        'AssignedID'=>$task_infos->AssignToUserID,
                        'DueDate'=>!empty($task_infos->DueDateTime)?date('d/m/Y', strtotime($task_infos->DueDateTime)):null,
                        'DueDateTime'=>!empty($task_infos->DueDateTime)?date('d/m/Y H:i A', strtotime($task_infos->DueDateTime)):null,
                        'Status'=>$this->getTaskStatusByTaskStatusID($task_infos->TaskStatusID)->DisplayText,
                        'StatusID'=>$task_infos->TaskStatusID,
                        'CreatedDate'=>!empty($task_infos->CreatedDateTime)?date('d/m/Y', strtotime($task_infos->CreatedDateTime)):null,
                        'CreatedDateTime'=>!empty($task_infos->CreatedDateTime)?date('d/m/Y H:i A', strtotime($task_infos->CreatedDateTime)):null,
                        //'ExistingAttachments'=>$this->file_attachment->$existingAttachmentsWidget($task_infos->TaskID, $can_update)
                    ];
                }
            }
        }
        return view('uis.Tasks.list', compact('tasks','ParentID','can_update'))->render();
    }
}