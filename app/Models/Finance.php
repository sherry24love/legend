<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Finance extends Model {

	protected $table='finance';

	protected $fillable = [
			'user_id' , 'act' , 'cash' , 'orgin_cash' , 'result_cash' , 'type' , 'target_id' ,
	];

	public function __construct(array $attributes = []) {
		parent::__construct( $attributes );
	}
	
	public function user() {
		return $this->belongsTo( User::class , 'user_id' , 'id' );
	}
}
