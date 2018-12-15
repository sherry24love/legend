<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model {
	
	protected $table='flight';
	
    protected $fillable = [
    	'no' , 'ship_id' , 'from' , 'to' , 'from_time' , 'to_time'
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    
    public function ship() {
    	return $this->belongsTo( Ship::class , 'ship_id' , 'id' );
    }
    
   	public function dates() {
   		return $this->hasMany( FlightDate::class , 'flight_id' , 'id' );
   	}
   	
   	public function prices() {
   		return $this->hasMany( FlightPortPrice::class , 'flight_id' , 'id' );
   	}
}
