<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightPrice extends Model {
	
	protected $table='flight_price';
	
    protected $fillable = [
    	'from_port' , 'to_port' , 'price' , 'cover' , 'available_from' , 'available_to' ,
    		'link' , 'link_type' , 'display' , 'is_hot' , 'is_promotion' , 'is_recommend' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    
    public function fromPort() {
    	return $this->belongsTo( Port::class , 'from_port' , 'id' );
    }
    
    public function toPort() {
    	return $this->belongsTo( Port::class , 'to_port' , 'id' );
    }
}
