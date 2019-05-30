<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Flash;
use App\Attachment;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileAttachmentsController extends Controller
{
	protected $attachment = null, $policy = null;

    public function __construct(Attachment $attachment) {
        $this->attachment = $attachment;
    }
    public function index(Request $request)
    {
    	$ParentID = $request->ParentID ?: null;
    	$EntityName = $request->EntityName ?: null;
    	$DocumentTypeID = $request->DocumentTypeID ?: null;

    	$validator = Validator::make($request->all(), [
			'ParentID'	=> 'required|regex:/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/',
			'EntityName' => 'required',
			'DocumentTypeID' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json($validator->getMessageBag()->toArray());
		}

    	return view("Attachments.form", compact("ParentID", "EntityName", "DocumentTypeID"));
    }
	public function get()
	{

	}

    public function defaultExistingAttachmentsWidget($ParentID){
        return $this->existingAttachmentsWidget($ParentID, false);
    }

    public function updateExistingAttachmentsWidget($ParentID){
        return $this->existingAttachmentsWidget($ParentID, true);
    }

	private function existingAttachmentsWidget($ParentID, $can_update = false){
		if(empty($ParentID))
			return '';
		$fids = $this->attachment->FileAttachment_GetFileAttachmentsByParentID($ParentID);
		$attachments = [];
		foreach($fids as $i){
			$file = $this->attachment->FileAttachment_Get_first($i->FileAttachmentID);
			$attachments[] = (object)['FileAttachmentID'=>$i->FileAttachmentID, 'Filename'=>$file->Title];
		}
		return view('uis.FileAttachments.attachments-widget', compact('attachments','can_update'))->render();
	}

	public function filesSave(Request $request, $ParentID, $EntityName, $TypeName = null, $DocumentTypeID = null){
		$document_types = $this->attachment->DocumentType_GetDocumentTypes();
        if(empty($DocumentTypeID)){
	        foreach($document_types as $i){
	            $type_name = $i->Name;
	            $type_name = trim($type_name);
	            $type_name = strtolower($type_name);
	            if($type_name == $TypeName){
	                $DocumentTypeID = $i->DocumentTypeID;
	                break;
	            }
	        }
	    }
        $files = $this->attachment->capture($request, $ParentID, $EntityName, $DocumentTypeID, Auth::id());
        foreach($files as $k=>$i){
        	$data = [
        		/*$i->ParentID,
        		$i->EntityName,
        		$i->Title,
        		$i->FileName,
        		$i->FileData,
        		$i->Comments,
        		$DocumentTypeID,
        		$i->CurrentUserID*/
                $i['attributes']['ParentID'],
                $i['attributes']['EntityName'],
                $i['attributes']['Title'],
                $i['attributes']['FileName'],
                $i['attributes']['FileData'],
                $i['attributes']['Comments'],
                $DocumentTypeID,
                $i['attributes']['CurrentUserID']
        	];
        	$res = $this->attachment->FileAttachment_CreateByTypeName($data, ['FileAttachmentID' => 'uniqueidentifier']);
        	if(!$res)
        		return false;
        }
        return true;
	}

    private function validateFiles($data){
        $validator = Validator::make($data, [
            'ParentID'  => 'required|regex:/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/',
            'EntityName' => 'required',
            'DocumentTypeID' => 'required'
        ]);
        return $validator->fails();
    }

	public function directUpload(Request $request){
		$data = $request->all();
		if($this->validateFiles($data)) {
			Flash::error(trans('messages.file_validation_error'));
            return back();
		}
		$res = $this->filesSave($request, $data['ParentID'], $data['EntityName'], null, $data['DocumentTypeID']);
		if(!$res){
			Flash::error(trans('messages.file_failed_error'));
            return back();
		}
		Flash::success(trans('messages.file_success_save'));
        return back();
	}

	public function upload(Attachment $Attachment, Request $request)
	{	
		$file = $request->file('file');

		if($this->validateFiles($request->all())) {
			return response()->json($validator->getMessageBag()->toArray());
		}

		$Document = Attachment::makeFromFile($file, $request->ParentID, $request->EntityName, $request->DocumentTypeID);
		
		return response()->json($Document);

		if ($Document = $Document->FileAttachment_CreateByTypeName(null, ['FileAttachmentID' => 'uniqueidentifier'])) {
			return response()->json($Document);
		}
		else {
			return response()->json($Document->getSpErrors());
		}

		$folder 	= [config('filesystems.uploads_folder')];

		if (isset($request->folder) && $request->folder)
			$folder[] = $request->folder;

		$folder 		= isset($request->folder) ? $request->folder : "";
		$rules			= isset($request->rules) ? $request->rules : "image|max:1000";
		$validator 		= Validator::make($rules);

		$results 		= [];

		foreach($request->allFiles() as $key => $file)
		{
			
			if (!$validator->fails($file))
			{
				$results[$key] = ['error' => $validator->errors()];
			}
			else
			{	
				#$file->store($public_path . '/' . $folder . );
			}
		}

	}

    public function createList($ParentID){
        $attachment_ids = $this->attachment->FileAttachment_GetFileAttachmentsByParentID($ParentID);
        $attachments = [];
        foreach($attachment_ids as $i){
            $attachment_infos = $this->attachment->FileAttachment_Get_first($i->FileAttachmentID);
            $contact = empty($attachment_infos->CreatedBy)?(object)[]:$this->attachment->Contact_GetByUserID_first($attachment_infos->CreatedBy);
            $name = property_exists($contact, 'FirstName')?$contact->FirstName.' '.$contact->Surname:'';
            $CreatedDate=!empty($attachment_infos->CreatedDateTime)?date('d/m/Y', strtotime($attachment_infos->CreatedDateTime)):null;
            $document_type_infos = $this->attachment->DocumentType_Get_first($attachment_infos->DocumentTypeID);
            $attachments[] = (object)['FileAttachmentID'=>$i->FileAttachmentID, 'CreatedDateTime'=>$CreatedDate, 'CreatedDateTimeFull'=>$attachment_infos->CreatedDateTime, 'CreatedBy'=>$name, 'FileName'=>$attachment_infos->FileName, 'Title'=>$attachment_infos->Title, 'Comments'=>$attachment_infos->Comments,'DocumentType'=>$document_type_infos->DisplayText,'DocumentTypeID'=>$attachment_infos->DocumentTypeID];
        }
        return $attachments;
    }

    public function getattachmentsList($ParentID){
        $to_return_if_null = '<tr><td colspan=2 style="text-align:center;">No data.</td></tr>';
        if(empty($ParentID))
            return $to_return_if_null;
        $note_list = $this->createList($ParentID);
        $notes = [];
        foreach($note_list as $i){
            $notes[] = '<tr data-iseditable="'.($i->isEditable?'true':'false').'"><td>'.$i->CreatedDateTime.'</td><td>'.$i->CreatedBy.'</td></tr>';
        }
        return implode($notes);
    }

    public function defaultList($ParentID){
        return $this->attachmentsList($ParentID);
    }

    public function updateList($ParentID){
        return $this->attachmentsList($ParentID, true);
    }

    public function attachmentsList($ParentID, $can_update = false){
        $attachments = $this->createList($ParentID);
        return view('uis.FileAttachments.list', compact('attachments','can_update'))->render();
    }

    public function attachmentsInterface($EntityName, $ParentID, $hideAddAttachmentsButton, $hideList = false, $can_update = false){
    	$document_types_raw = $this->attachment->DocumentType_GetDocumentTypes();
    	$document_types = [''=>'Please Select'];
    	foreach($document_types_raw as $k=>$i){
    		$document_types[$i->DocumentTypeID] = $i->DisplayText;
    	}
        $rendered = view('uis.FileAttachments.main',[
            'parent_id'=>$ParentID,
            'entity_name'=>$EntityName,
            'hideAddAttachmentsButton'=>$hideAddAttachmentsButton,
            'hideList'=>$hideList,
            'attachments_list'=>empty($ParentID)?'':$this->attachmentsList($ParentID, $can_update),
            'document_types'=>$document_types,
            'can_update'=>$can_update
        ])->render();
        $rendered_buttons = $hideAddAttachmentsButton?view('uis.FileAttachments.buttons')->render():'';
        $css = [
            ['uis.FileAttachments.css.styles','attachments_styles']
        ];
        $js = [
            ['uis.FileAttachments.js.scripts', 'attachments_scripts', 
                [
                    'list_url'=>route('attachments.'.($can_update?'update':'default').'-list')
                ]
            ]
        ];
        return [$rendered,$rendered_buttons,$css,$js];
    }

    public function redirectDelete($FileAttachmentID)
    {
        $res = $this->delete($FileAttachmentID);
        if(!$res){
            Flash::error(trans('messages.note_delete_failed'));
            return back();
        }
        Flash::success(trans('messages.note_delete_completed'));
        return back();
    }

    public function ajaxDelete(Request $request){
        $data = $request->all();
        if(!array_key_exists('FileAttachmentID', $data))
            return 'false';
        $res = $this->delete($data['FileAttachmentID']);
        if(!$res)
            return 'false';
        return 'true';
    }

    private function delete($FileID){
        return $this->attachment->FileAttachment_Delete([$FileID, Auth::id()]);
    }

    public function download($FileAttachmentID){
        $attachment_infos = $this->attachment->FileAttachment_Get_first($FileAttachmentID);
        if(!property_exists($attachment_infos, 'Title'))
            abort(404);
        $filename = $attachment_infos->Title;
        $data = base64_decode($attachment_infos->FileData);
        header("Pragma: public");
        header("Expires: 0");
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        $browser = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/MSIE 5.5/', $browser) || preg_match('/MSIE 6.0/', $browser)){
            header('Pragma: private');
            // the c in control is lowercase, didnt work for me with uppercase
            header('Cache-control: private, must-revalidate');
            // MUST be a number for IE
            header("Content-Length: ".strlen($data)); 
            header('Content-Type: application/x-download');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
        }
        else{
            header("Content-Length: ".strlen($data));
            header('Content-Type: application/x-download');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
        }
        echo $data;
    }

    public function update(Request $request = null) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'FileAttachmentID'  => 'required|regex:/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/',
            'DocumentTypeID' => 'required|min:1',
            'Title' => empty($data['Comments'])?'required|min:1':'',
            'Comments' => empty($data['Title'])?'required|min:1':'',
        ]);
        if($validator->fails()){
            Flash::error(trans('messages.file_validation_error'));
            return back();
        }
        $user_id = Auth::id();
        $file_data = [
            $data['FileAttachmentID'],
            $data['Title'],
            $data['Comments'],
            $data['DocumentTypeID'],
            $user_id
        ];
        $res = $this->attachment->FileAttachment_Update($file_data);
        if(!$res){
            Flash::error(trans('messages.note_failed_error'));
            return back();
        }
        Flash::success(trans('messages.note_update_completed'));
        return back();
    }
}
