<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderEntrust extends Model {
	
	protected $table='order_entrust';
	
    protected $fillable = [
    		'name' , 'contact' , 'mobile', 'order_id' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
}
