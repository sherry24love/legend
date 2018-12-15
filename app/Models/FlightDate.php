<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightDate extends Model {
	
	protected $table='flight_port_time';
	
    protected $fillable = [
    	'ship_id' , 'flight_id' , 'port_id' , 'port_name' , 'arrive_plan_date' , 'arrive_actual_date'  , 'leave_plan_date' ,
    		'leave_actual_date'
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    
    public function ship() {
    	return $this->belongsTo( Ship::class , 'ship_id' , 'id' );
    }
}
