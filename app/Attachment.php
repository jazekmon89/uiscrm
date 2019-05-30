<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile as UploadedFile;

class Attachment extends Model
{
  use StoredProcTrait;

    public $fillable = [
      'ParentID', 'EntityName', 'Title', 'FileName', 'FileData', 'Comments', 'CurrentUserID'
    ];

    public $casts = ['id' => 'string'];
    public static function makeFromFile($file, $ParentID, $EntityName, $DocumentTypeID, $CurrentUserID=null)
    {
      $attrs = [  
        'ParentID'      => $ParentID,
        'EntityName'    => $EntityName,
        'Title'         => $file->getClientOriginalName(),
        //'FileName'      => $file->getFileName(),
        'FileName'      => $file->getClientOriginalName(),
        'FileData'      => base64_encode(file_get_contents($file->getPathname())),
        'Comments'      => null,
        'DocumentTypeID' => $DocumentTypeID,
        'CurrentUserID' => $CurrentUserID ?: Auth::id()
      ];

      #Storage::delete($rel_path);
      $var = new static($attrs);

      return $var->setProcedureData(array_values($attrs));
    }

    public static function capture(Request $request, $ParentID, $EntityName, $DocumentTypeID, $CurrentUserID=null)
    {
      $results = [];
      foreach($request->allFiles() as $files) {
        foreach($files as $file){
          $results[] = static::makeFromFile($file, $ParentID, $EntityName, $DocumentTypeID, $CurrentUserID);
        }
      }
      return $results;
    }

    public function linkTo($EntityName, $ParentID)
    {
      $this->EntityName = $EntityName;
      $this->ParentID = $ParentID;

      return $this;
    }

    public function getCurrentUserIDAttribute() {
      return $this->attributes['CurrentUserID'] ?: Auth::id();
    }

    public function setAttributeFileData($value) {
      $this->attributes['FileData'] = base64_encode($value);
    }

    public function getFileDataAttribute($value) {
      return base64_decode($value);
    }
}
