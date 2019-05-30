<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\StoredProcTrait as StoredProcTrait;

use DB;

class Task  extends Model
{
	use StoredProcTrait;

	public $fillable = [
      'ParentID', 'EntityName', 'Description', 'CurentUserID', 'NoteID',
    ];
}