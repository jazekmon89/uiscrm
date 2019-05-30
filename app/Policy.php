<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
	use StoredProcTrait;

	public function getTypeCovers($PolicyTypeID, $set=false, $ordering=true) {
		// Covers or Sets
		$covers = call_user_func_array([$this, $set ? 'PolicyType_GetCoverLevelSets' : 'PolicyType_GetCovers'], [$PolicyTypeID]);
	
		// since all DisplayOrder for non set Covers are null 
		// we skip sorting			
		if ($covers && $set && $ordering) arr_usort($covers, 'RankWithinPolicyType');	
		return $covers;
	}
	public function getTypeCoverLevels($coverID, $set=false, $ordering=true) {

		// Cover levels or Set levels
		$levels = call_user_func_array([$this, $set ? 'CoverLevelSet_GetCoversAndLevels' : 'Cover_GetCoverLevels'], [$coverID]);

		// sort according to order field
		if ($levels) arr_usort($levels, $set ? 'CoverLevelDisplayOrder' : 'DisplayOrder');
		return $levels;
	}

	//@Note: should we put this on Form Model for ease of use ?
	public function getFormTypeQuestionGroups($formTypeID=null, $parent=null, $recurse=false, &$container=[]) {	

		if ($parent) { 
			$proc = 'FormQuestionGroup_GetSubgroups'; $data = $parent; 
		}
		else { 
			$proc = 'FormType_GetQuestionGroups'; $data = $formTypeID; 
		}

	    if ($groups = call_user_func_array([$this, $proc], [$data])) {
    		// recursive issue must be taken care, hince we're on php and we are bound 
    		// by the timelimit option
    		foreach($groups as &$group) {
    			// most hierarchy for arrays use children index
    			if ($recurse) {

    				// attach questions directly if recursive
    				$group->questions = $this->getFormTypeQuestions($group->FormQuestionGroupID, true);
    				
    				$group->children = [];
    				
    				/**
    				 * use children as container
    				 */
    				$this->getFormTypeQuestionGroups($formTypeID, $group->FormQuestionGroupID, $recurse, $group->children);
    			}
    			$container[$group->FormQuestionGroupID] = $group;
    		}
    	}
	    return $container;
	}

	//@Note: should we put this on Form Model for ease of use ?
	public function getFormTypeQuestions($groupID, $status=false, $filter=true, $choices=true) {			
	    if ($questions = $this->FormQuestionGroup_GetQuestions($groupID)) {
	    	foreach($questions as $key => &$question) {
	    		$question->FormQuestionGroupID = $groupID;
		    	if ($status) {
		    		$question->details = $this->FormQuestion_GetAllStatus_first($question->FormQuestionID);
	
		    		// filtering in active questions
		    		if ($filter && $status && !$question->details->IsActive && $questions[$key] = null)
		    			continue;
		    	}
		    	if ($choices && in_array($question->FormQuestionTypeName, ['SelectOne', 'SelectMulti'])) 
		    		$question->choices = $this->FormQuestion_GetPossChoices($question->FormQuestionID);
	    	}
	    }
	    arr_usort($questions, "DisplayOrder", false);
		return $questions;
	}
}
