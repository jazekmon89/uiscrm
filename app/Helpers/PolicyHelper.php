<?php

namespace App\Helpers;

use App\Policy;
use App\Helpers\OrganisationHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class PolicyHelper {

    public function __construct(Policy $policy) {
       $this->policy = $policy;
   }

    public function model() {
        return $this->policy;
    }

	 /**
     * @return Org: policies with form id
     */
	public function getTypes($org=null, $id_keys=false) {
        return OrganisationHelper::getPolicyTypes($org, $id_keys);
    }

    public function getBusinessStructures($list=true) {
    	return business_structures($list);
	}

    /**
     * @return Form with Top level groups 
     */
    public function getForm($PolicyTypeID, $FormTypeID, $org=null) {
    	$Policy = $this->policy;

        if (!$this->isPolicyForm($PolicyTypeID, $FormTypeID, $org)) {
        	return [];
        }

        #$Questions  = (array)$this->getAllQuestions($PolicyTypeID);     
        #$Groups     = $this->extractQuestions($Questions);

        #$this->convertToObjects($Groups);

        $Groups = (array)$this->getFormTopLevelGroups($FormTypeID);

       	return $Groups /*&& $Questions*/ ? compact('Groups', /*'Questions',*/ 'FormTypeID') : [];
    }

    protected function convertToObjects(&$groups) {
        foreach($groups as &$group) {
            $group = (object)$group;
            if ($group->questions) {
                foreach($group->questions as &$question)
                    $question = (object)$question;
            }

            if (isset($group->children) && $group->children)
                $this->convertToObjects($group->children);
        }
    }

    protected function extractQuestions($questions) {
        $result = [];

        foreach($questions as $question) {
            $children = [];
            if ($question->QuestionGroupParentID) {
                /**
                 * @see Form structure subgroups are at index children
                 * @var QuestionGroupParentID @see getFormTopLevelGroups
                 */
                $children = (array)array_get($result, $question->QuestionGroupParentID .'.children');
                
                // make sure we get the right index else we assume children is empty?
                $key = arr_lkey($children, 'FormQuestionGroupID', $question->QuestionGroupID);
                
                /**
                 * @var children are index incrementaly
                 */
                $ref = $question->QuestionGroupParentID . '.children.'. ($key !== false ? $key : count($children));
            }
            // top level group
            else $ref = $question->QuestionGroupID;        
            
            if (!$question->QuestionGroupParentID && !array_has($result, $ref) ||
                /**
                 * @var children make sure no duplication of subgroups
                 */
                $question->QuestionGroupParentID && !arr_lfind($children, 'FormQuestionGroupID', $question->QuestionGroupID)) {

                array_set($result, $ref, [
                    'Name'                  => $question->QuestionGroupName,
                    'DisplayOrder'          => $question->QuestionGroupDisplayOrder,
                    'DisplayText'           => $question->QuestionGroupDisplayText,
                    'IsRepeating'           => $question->IsQuestionGroupRepeating,
                    'FormQuestionGroupID'   => $question->QuestionGroupID,
                    'QuestionGroupParentID' => $question->QuestionGroupParentID,
                    'children'              => [],
                    'questions'             => []
                ]);
            } 
            if ($question->QuestionGroupName === 'ContactDetails' || !$question->QuestionID)
                continue;

            $_questions = (array)array_get($result, $ref . '.questions');

            array_set($result, $ref . '.questions.'. ($_questions ? count($_questions) : 0), [
                'Name'                  => $question->QuestionName,
                'DisplayOrder'          => $question->QuestionDisplayOrder,
                'DisplayText'           => $question->QuestionDisplayText,
                'IsMandatory'           => $question->IsQuestionMandatory,
                'FormQuestionTypeName'  => $question->QuestionTypeName,
                'FormQuestionTypeID'    => $question->QuestionTypeID,
                'FormQuestionID'        => $question->QuestionID,
                'Comments'              => '',
                'choices'               => isset($question->choices) ? $question->choices : [],
                'details'               => $question->details, 
            ]);     
        }
        return $result;
    }

    public function getFormTopLevelGroups($FormTypeID) {
    	$Policy = $this->policy;

    	return Cache::remember("FormTopGroups.{$FormTypeID}", 2800, function() use ($Policy, $FormTypeID) {
            $groups = (array)$Policy->getFormTypeQuestionGroups($FormTypeID);

            foreach($groups as $index => $group) {
                if (($parent = array_get((array)$group, 'ParentFormQuestionGroupID')) &&
                    isset($groups[$parent])) {
                    unset($groups[$index]);
                }
            }

            return $groups;
        });
    }

    public function getGroupChildren($groupID) {
        $Policy = $this->policy;

        // cache per group since groups could have subgroups as subgroups could have to
        // so to avoid file size issue we cache indiviually but prefix by group
        return Cache::remember("FormGroupChildren.{$groupID}", 2800, function() use ($Policy, $groupID) {
            return (array)$Policy->getFormTypeQuestionGroups(null, $groupID, true);
        }); 
    }

    public function getGroupQuestions($groupID) {
        $Policy = $this->policy;
        
        return Cache::remember("FormGroupQuestions.{$groupID}", 2800, function() use ($Policy, $groupID) {
            return (array)$Policy->getFormTypeQuestions($groupID, true);
        });
    }

    public function attachGroupChildrenAndQuestions(&$group, $inc_questions=true) {

        if (!isset($group->children))
    	   $group->children = (array)$this->getGroupChildren($group->FormQuestionGroupID);

        if ($inc_questions && !isset($group->questions))
            $group->questions = (array)$this->getGroupQuestions($group->FormQuestionGroupID);
    }

    public function getAllQuestions($PolicyTypeID) {
    	$Policy = $this->policy;

    	return Cache::remember("FormQuestions.{$PolicyTypeID}", 2800, function() use ($Policy, $PolicyTypeID) {
        	if ($questions = $Policy->GetRFQQuestionsByPolicyTypeID($PolicyTypeID)) {
	    		foreach($questions as $key => &$question) {
                    if (!$question->QuestionID) {

                        if ($question->QuestionGroupName !== 'ContactDetails')
                            unset($questions[$key]);

                        continue;
                    }

	    			$question->details = $Policy->FormQuestion_GetAllStatus_first($question->QuestionID);

	    			if (in_array($question->QuestionTypeName, ['SelectOne', 'SelectMulti'])) {
	    				$question->choices = $Policy->FormQuestion_GetPossChoices($question->QuestionID);
	    			}
	    		}
    		}
    		return $questions;
        });
    }

    /**
     * @see getTypes for matching Polices	
     * @return bool match Policy Form
     */
    public function isPolicyForm($PolicyTypeID, $FormTypeID, $org=null) {
    	if ($Policies = $this->getTypes($org)) {
    		if (arr_lfind($Policies, 'FormTypeID', $FormTypeID)) {
                return true;
            }
    	} 
    	$Form = $this->policy->PolicyType_GetRFQFormTypeID_first($PolicyTypeID, ['FormTypeID' => 'uniqueidentifier']);
    	
    	// helper method for validating policy formID
    	return $Form && array_get((array)$Form, 'FormTypeID') == $FormTypeID;
    }

    /**
     * @param Form @see getForm
     * @param ref needle
     */
    public function getAnswerableFormGroup($Form, &$ref=null) {
    	if ((!$groups = array_get($Form, 'Groups')) /*|| (!$questions = array_get($Form, 'Questions'))*/)
    		return false;

    	$ref 	= $ref ?: key($groups);
    	$return = ['prev' => null, 'current' => null, 'next' => null]; 

    	// we scan through groups since array_keys sort the keys 
        // and so we can still track the display order also we need 
        // to check if group has questions otherwise we'll be redirecting 
        // clients many times and could lead to redirect error
        // first loop locate ref
        while($current = array_shift($groups)) {
            if ($current->Name !== 'ContactDetails') 
                $this->attachGroupChildrenAndQuestions($current);

            if ($current->FormQuestionGroupID === $ref) {
                $return['current'] = $current;
                break;
            }   
            
            if ($current->Name === 'ContactDetails' || !empty($current->questions) || !empty($current->children))
                $return['prev'] = $current;
        }
        if ($groups) {
            // second locate next group that has questions
      		// else we're on the last group
            while($next = array_shift($groups)) {
                $this->attachGroupChildrenAndQuestions($next);
                
                if ($next->questions || $next->children) {
                    $return['next'] =& $next;
                    break;
                }
            }
        }
        return (object)$return;
    }

	/**
	* @return matched to request data structure for Form::facade 
	*/
    public function getStoredData($parent, $key, $default=[]) {
        return session()->has($key) ? [$parent => session($key)] : $default;
    }
    /**
    * Quotes own method for getting Policy Covers
    *
    * @see 2.11. OP006 Request for Quote (UIS/CRM/FRS001 v1.4.1)
    * @param String cache_key
    * @param Reference Form
    * @param PolicyTypeID PolicyTypeID
    * @param Model Policy
    *
    * @return set prev|current|next Groups
    */
    public function getPolicyCovers($PolicyTypeID, $org=null) {
    	$Policy 	= $this->policy;
        $policies 	= $this->getTypes($org);

        if (!$PolicyType = arr_lfind($policies, 'PolicyTypeID', $PolicyTypeID)) 
        	return [];
        return Cache::remember("Covers.{$PolicyTypeID}", 2800, function() use ($Policy, $PolicyTypeID) {
           foreach([true, false] as $set) {
                if ($set && $PolicyTypeID !== 'B4F5EC56-EA20-48D1-8EED-B82A4C7ACCA2') continue;
                if ($Covers = (array)$Policy->getTypeCovers($PolicyTypeID, $set)) {
                    foreach($Covers as &$cover) {
                        $cover->set = $set;
                        $cover->levels = $Policy->getTypeCoverLevels($cover->{$set ? 'CoverLevelSetID' : 'CoverID'} , $set);
                    }  
                    $allCovers[$set ? 'Sets' : 'Covers'] = $Covers;
                }
            }
            return isset($allCovers) ? $allCovers : [];
        });
    }

    public function __call($method, $params) {
    	return call_user_func_array([$this->policy, $method], $params);
    }
}
