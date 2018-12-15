<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSender extends Model {
	
	protected $table='order_sender';
	
    protected $fillable = [
    	'name' , 'contact_name' , 'mobile' , 'email' , 'address' , 'load_date' , 'order_id' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
}
