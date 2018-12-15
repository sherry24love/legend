<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRecevier extends Model {
	
	protected $table='order_recevier';
	
    protected $fillable = [
    		'name' , 'contact_name' , 'mobile' , 'email' , 'address' , 'id_no' , 'order_id' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
}
