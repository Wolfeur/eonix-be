<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
	use HasUuids;
	public $timestamps = false;

//	public $primaryKey = "guid";
	protected $keyType = 'string';
	public $incrementing = false;

	protected $guarded = ['id'];
}
