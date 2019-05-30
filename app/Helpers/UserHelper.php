<?php 

namespace App\Helpers;

use App\Providers\Facades\Entity;
use Illuminate\Support\Facades\Auth;

class UserHelper {

    public function getCurrentUser() {
        return Auth::check() ? Auth::user() : null;
    }

    public function getCurrentUserID() {
        return Auth::check() ? Auth::id() : null;
    }

    public static function getRoles() {
        return Cache::remember("OrganisationRoles", 2800, function() {
            return Entity::model()->OrganisationRole_GetOrganisationRoles();  
        }); 
    }

    public function getAssignedTasks($UserID=null) {
        $UserID = $UserID ?: $this->getCurrentUserID();

        if (!$UserID) return [];

        $Tasks = Entity::getMultiple("Task", Entity::model()->User_GetOpenAssignedTaskIDs($UserID));

        foreach($Tasks as $key => $task) {   

            if (!empty($task['AssignToOrganisationRoleID']) && $role = arr_lfind(static::getRoles(), "OrganisationRoleID", $task['AssignToOrganisationRoleID']))
                $task['Role'] = $role;

            if (!empty($task['AssignToUserID'])) 
                $task['Contact'] = Entity::get('UserContact', $task['AssignToUserID']);

            if (!empty($task['TaskStatusID'])) 
                $task['Status'] = Entity::get('TaskStatus', $task['TaskStatusID']);

            $Tasks[$key] = $task;
            
        }

        return $Tasks
    }

}