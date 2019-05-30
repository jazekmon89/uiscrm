<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
  use StoredProcTrait;

  public $fillable = [
    'ParentID', 'EntityName', 'Description', 'CurentUserID', 'NoteID',
  ];

  public function getCurrentUserIDAttribute() {
    return $this->attributes['CurrentUserID'] ?: Auth::id();
  }
}
