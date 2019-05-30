<?php

namespace App\Helpers;

use Illuminate\Http\Request;


class FormGroupMapHelper {

    protected $mapped = [];

    protected $form = null;

    protected $request = null;

    protected $data = [];

    public function __construct(Request $request) {
        $this->mapped = config('form_mapping', []);
        $this->request = $request;

        if ($FormID = $request->FormTypeID) {
            $this->form = $FormID;
        }

        $this->data = $request->all();
    }

    public function data($key=null) {
        return $key ? (array)array_get($this->data, $key) : $this->data;
    }

    public function form() {
        return $this->form;
    }

    public function setForm($FormID) {
        $this->form = $FormID;
        return $this;
    }

    public function isLinkedToOthers($QID_OR_GID, $FormID=null) {
        return array_has($this->getFormMap($FormID), $QID_OR_GID);
    }

    public function getQuestionLinkedTo($QuestionID, $FormID=null) {
        return (array)array_get($this->getFormMap($FormID), $QuestionID);
    }

    public function getGroupLinkedTo($GroupName, $FormID=null) {
        return (array)array_get($this->getFormMap($FormID), $GroupName);
    }

    public function getFormMap($FormID=null) {
        return (array)array_get($this->mapped, $FormID ?: $this->form);
    }

    public function checkQuestionLinkToSiblings($dataPath, $QuestionID, $all=false, $FormID=null) {
        $linkeds = $this->getQuestionLinkedTo($QuestionID, $FormID);
        $data    = $this->data($dataPath);

        if ($linkeds && $data) {
            foreach($linkeds as $linkedID => $linked) {
                $_data = (array)array_get($data, $linkedID);
                if (!$_data && $all) 
                    return false;

                $found = false;
                foreach((array)$linked as $index => $answer) {

                    if (in_array($answer, (array)$_data)) {
                        $found = true;
                        break;
                    }
                }
                if (!$found && $all) return false;
                else if ($found && !$all) return true;
            }
            return $found;
        }
        return null;
    }

    public function checkGroupLinkToQuestions($dataPath, $GroupName, $all=false, $FormID=null) {
        $linkeds = $this->getGroupLinkedTo($GroupName, $FormID);
        $data    = $this->data($dataPath);

        if ($linkeds && $data) {
            foreach($linked as $Q => $A) {

                $found = in_array($A, (array)array_get($data, $Q));

                if ($found && !$all || !$found && $all) {
                    return $found;
                }
            }
            return $found;
        }
        return null;
    }
}