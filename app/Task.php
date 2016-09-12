<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	public $casts = ['done' => 'boolean'];

	public function user()
	{
		return $this->belongsTo(User::class);
    }
}
