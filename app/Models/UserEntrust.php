<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEntrust extends Model {

	protected $table= 'user_entrust';

	protected $fillable = [
			'name' , 'contact' , 'mobile', 'user_id' ,
	];

	public function __construct(array $attributes = []) {
		 
		parent::__construct( $attributes );
	}

}
