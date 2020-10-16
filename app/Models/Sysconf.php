<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sysconf extends Model{

	protected $table = 'sysconf';

	public $timestamps = false;

	protected $fillable = ['name', 'value'];
}