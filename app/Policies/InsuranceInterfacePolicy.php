<?php

namespace App\Policies;

use App\User as User;
use Illuminate\Auth\Access\HandlesAuthorization;
use DB;
use App\StoredProcTrait as StoredProc;

class InsuranceInterfacePolicy
{
    use HandlesAuthorization, StoredProc;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given user can access the page.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function canAdminAccess(User $user)
    {
        $admin_roles = config('user_roles.insurance_interface.admin');
        $return_flag = true;
        foreach($admin_roles as $roles){
            $res = $this->checkUserRole($user->user_id, $roles);
            $return_flag = $return_flag && ($res=='Y'?true:false);
            if($return_flag)
                break;
        }
        return $return_flag;
    }

    /**
     * Determine if the given user can access the page.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function canClientAccess(User $user)
    {
        $admin_roles = config('user_roles.insurance_interface.user');
        $return_flag = true;
        foreach($admin_roles as $roles){
            $res = $this->checkUserRole($user->user_id, $roles);
            $return_flag = $return_flag && ($res=='Y'?true:false);
            if($return_flag)
                break;
        }
        return $return_flag;
    }

    public function checkUserRole($userid, $role){
        $proc   = 'User_IsMemberOfRole';
        $query  = "set nocount on; DECLARE @return_value int, ";
        $query .= "@IsMemberOfRole nvarchar(1); EXEC @return_value = [dbo].[{$proc}] ?, ?, ";
        $query .= "@IsMemberOfRole OUTPUT; SELECT @IsMemberOfRole as isMemberRole, @return_value as return_value";
        return $this->extractSPResult(DB::select(DB::raw($query), [$userid, $role]));
    }
}
