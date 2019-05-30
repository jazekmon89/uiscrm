<?php

namespace App;

use DB;
use App\StoredProcTrait as StoredProc;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
  use StoredProc;

    public $fillable = [
      'ParentID', 'EntityName', 'Description', 'CurentUserID', 'NoteID',
    ];

    public function Note_CreateByTypeName()
    {
      $query = "SET NOCOUNT ON; DECLARE @return_value int, @NoteID uniqueidentifier; ";
      $query .= "EXEC @return_value = [dbo].[FileAttachment_CreateByTypeName] ?,?,?,?,?, ";
      $query .= "@NoteID = @NoteID OUTPUT;";
      $query .= "SELECT @NoteID as NoteID;";
      $query .= "SELECT @return_value as return_value";

      foreach($this->fillable as $field) $data[] = $this->$field;

      try{  
        if ($result = $this->extractSPResult(DB::select($query, $data)))
        {
          $this->id = $result->NoteID;
        }

        return $result;
      }
      catch(Exception $e)
      {
          return false;
      }
    }

    public function getByID($NoteID)
    {
      $query = "SET NOCOUNT ON; DECLARE @return_value int;";
      $query .= "EXEC @return_value = [dbo].[Note_Get] ?; ";
      $query .= "SELECT @return_value as return_value";

      try{  
        if ($result = $this->extractSPResult(DB::select($query, [$NoteID]))) {
          $this->fill((array)$result);
        }
        
        return $this;
      }
      catch(Exception $e) { return false; }
    }

    public function getNotesByParentID($ParentID){
      $query = "SET NOCOUNT ON; DECLARE @return_value int;";
      $query .= "EXEC @return_value = [dbo].[Notes_GetNotesByParentID] ?; ";
      $query .= "SELECT @return_value as return_value";

      try{  
        if ($result = $this->extractSPResult(DB::select($query, [$ParentID]))) {
          $this->fill((array)$result);
        }
        
        return $this;
      }
      catch(Exception $e) { return false; }
    }

    public function getCurrentUserIDAttribute() {
      return $this->attributes['CurrentUserID'] ?: Auth::id();
    }

    public function updateNote($NoteID, $desc, $modified_by){
      $query = "SET NOCOUNT ON; DECLARE @return_value int;";
      $query .= "EXEC @return_value = [dbo].[Note_Update] ? ? ?; ";
      $query .= "SELECT @return_value as return_value";

      try{  
        return $result = $this->extractSPResult(DB::select($query, [$NoteID, $desc, $modified_by]));
      }
      catch(Exception $e) { return false; }
    }
}
