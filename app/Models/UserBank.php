<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBank extends Model {
	
	protected $table='user_bank';
	
    protected $fillable = [
    		'name' , 'contact' , 'mobile', 'order_id' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
}
