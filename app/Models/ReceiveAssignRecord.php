<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiveAssignRecord extends Model{

	protected $table = 'receive_assign_record';

	protected $fillable = ['type', 'customer_id', 'person_id', 'user_id', 'created_at', 'updated_at'];

}