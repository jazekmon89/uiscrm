<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\ClientHelper;

use App\Note;
use Flash;

use Illuminate\Support\Facades\Auth;

use DateTime;


class NotesController extends Controller
{
    protected $note = null;

    public function __construct(Note $Note) {
    	$this->note = $Note;
    }

    /**
     * @param GUID NoteID 
     * @return Object Note | Array SP Errors
     */
    public function get($NoteID) {
    	$Note = $this->note->Note_Get_first($NoteID);

    	return response()->json($Note ?: $this->note->getSpErrors());
    }

    /**
     * @param GUID ID of parent 
     * @return Array Objects Note | Array SP Errors
     */
    public function getByParent($ParentID) {
    	$Notes = (array)$this->note->Note_GetNotesByParentID($ParentID);

    	foreach($Notes as &$Note) {
    		$Note = $this->note->Note_Get_first($Note->NoteID);
    	}

    	return response()->json($Notes ?: $this->note->getSpErrors());	
    }

    public function update(Request $request = null) {
        $data = $request->all();
        $user_id = Auth::id();
        $note_data = [
            $data['NoteID'],
            $data['Description'],
            $user_id
        ];
        // dd($note_data);

        $res = $this->note->Note_Update($note_data);

        if(!$res){
            Flash::error(trans('messages.note_failed_error'));
            return back();
        }

        Flash::success(trans('messages.note_update_completed'));
        return back();
    }

    public function redirectDelete($NoteID)
    {
        $res = $this->delete($NoteID);
        if(!$res){
            Flash::error(trans('messages.note_delete_failed'));
            return back();
        }
        Flash::success(trans('messages.note_delete_completed'));
        return back();
    }

    public function ajaxDelete(Request $request){
        $data = $request->all();
        if(!array_key_exists('NoteID', $data))
            return 'false';
        $res = $this->delete($data['NoteID']);
        if(!$res)
            return 'false';
        return 'true';
    }

    private function delete($NoteID){
        return $this->note->Note_Delete([$NoteID, Auth::id()]);
    }

    protected function validator(array $data){
        $validations = array (
            'ParentID' => 'required|min:1',
            'EntityName' => 'required|min:1',
        );
        return Validator::make($data, $validations);
    }

    public function create(Request $request = null){
        $data = $request->all();
        $user_id = Auth::id();
        $note_data = [
            $data['ParentID'],
            $data['EntityName'],
            $data['Description'],
            $user_id,
        ];
        $res = $this->note->Note_CreateByTypeName($note_data, ['NoteID'=>'uniqueidentifier']);
     
        if(!$res){
            Flash::error(trans('messages.note_incomplete_details'));
            return back();
        }

        Flash::success(trans('messages.note_success_completed'));
        return back();
    }

    public function createList($ParentID){
        $note_ids = $this->note->Note_GetNotesByParentID($ParentID);
        $notes = [];
        foreach($note_ids as $i){
            $note_infos = $this->note->Note_Get_first($i->NoteID);
            $contact = $this->note->Contact_GetByUserID_first($note_infos->CreatedBy);
            $date1 = new DateTime(date('Y-m-d H:i:s', strtotime($note_infos->CreatedDateTime)));
            $date2 = new DateTime(date('Y-m-d H:i:s'));
            $isEditable = date_diff($date1, $date2)->d<1?true:false;
            $name = property_exists($contact, 'FirstName')?$contact->FirstName.' '.$contact->Surname:'';
            $CreatedDate=!empty($note_infos->CreatedDateTime)?date('d/m/Y', strtotime($note_infos->CreatedDateTime)):null;
            $notes[] = (object)['NoteID'=>$i->NoteID, 'CreatedDateTime'=>$CreatedDate, 'CreatedDateTimeFull'=>$note_infos->CreatedDateTime, 'CreatedBy'=>$name, 'Description'=>$note_infos->Description,'IsEditable'=>$isEditable];
        }
        return $notes;
    }

    public function getNotesList($ParentID){
        $to_return_if_null = '<tr><td colspan=3 style="text-align:center;">No data.</td></tr>';
        if(empty($ParentID))
            return $to_return_if_null;
        $note_list = $this->createList($ParentID);
        $notes = [];
        foreach($note_list as $i){
            $notes[] = '<tr data-iseditable="'.($i->isEditable?'true':'false').'"><td>'.$i->CreatedDateTime.'</td><td>'.$i->CreatedBy.'</td><td>'.$i->Description.'</td></tr>';
        }
        return implode($notes);
    }

    public function defaultList($ParentID){
        return $this->notesList($ParentID);
    }

    public function updateList($ParentID){
        return $this->notesList($ParentID, true);
    }

    private function notesList($ParentID, $can_update=false){
        $notes = $this->createList($ParentID);
        return view('uis.Notes.list', compact('notes','can_update'))->render();
    }

    public function notesInterface($EntityName, $ParentID, $hideAddNoteButton = false, $can_update = false, $hideList = false){
        $rendered = view('uis.Notes.main',[
            'parent_id'=>$ParentID,
            'entity_name'=>$EntityName,
            'hideAddNoteButton'=>$hideAddNoteButton,
            'can_update'=>$can_update,
            'notes_list'=>empty($ParentID)?'':$this->notesList($ParentID, $can_update),
            'hideList'=>$hideList
        ])->render();
        $rendered_buttons = $hideAddNoteButton?view('uis.Notes.buttons')->render():'';
        $css = [
            ['uis.Notes.css.styles','notes_styles']
        ];
        $js = [
            ['', 'tinymce-min', [], '//cloud.tinymce.com/stable/tinymce.min.js'],
            ['uis.Notes.js.scripts', 'note_scripts', 
                [
                    'list_url'=>route(($can_update?'notes.update-list':'notes.default-list'))
                ]
            ]
        ];
        return [$rendered,$rendered_buttons,$css,$js];
    }
}
