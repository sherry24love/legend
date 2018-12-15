<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model {

	protected $table='withdraw';

	protected $fillable = [
			'user_id' , 'status' , 'remark' , 'cash' , 'card_name' , 'card_no' , 'card_bank_id' ,
	];

	public function __construct(array $attributes = []) {
		parent::__construct( $attributes );
	}
	

	public function user() {
		return $this->belongsTo( \App\User::class , 'user_id' , 'id' );
	}
}
