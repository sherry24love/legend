<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model {

	protected $table='reward';

	protected $fillable = [
			'user_id' , 'cash' , 'status' , 'order_id' 
	];

	public function __construct(array $attributes = []) {
		parent::__construct( $attributes );
	}
	
	public function order() {
		return $this->belongsTo( Order::class , 'order_id' , 'id' );
	}
}
