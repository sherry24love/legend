<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightPortPrice extends Model {
	
	protected $table='flight_port_to_port_price';
	
    protected $fillable = [
    	'flight_id' , 'from_barge_port_id' , 'to_barge_port_id' , 'from_port_id' , 'to_port_id' , 'price_20gp' , 'price_20hp' , 'price_40gp' , 'price_40hq' ,
    	'is_promotion_20gp' , 'is_promotion_20hp' , 'is_promotion_40gp' , 'is_promotion_40hq' , 'from_port_leave_time' , 'from_barge_port_arrive_time' ,
    	'to_port_leave_time' , 'to_barge_port_arrive_time' ,
    		
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    
    public function fromPort() {
    	return $this->belongsTo( Port::class , 'from_port_id' , 'id' );
    }
    
    public function toPort() {
    	return $this->belongsTo( Port::class , 'to_port_id' , 'id' );
    }
    
    public function flight() {
    	return $this->belongsTo( Flight::class , 'flight_id' , 'id' );
    }
    
    public function setFromPortLeaveTimeAttribute( $v ) {
    	$this->attributes['from_port_leave_time'] = $v ? strtotime( $v ) : 0 ;
    }
    
    public function getFromPortLeaveTimeAttribute( $v ) {
    	return $v ? date('Y-m-d' ,  $v ) : '' ;
    }
    
    public function setFromBargePortArriveTimeAttribute( $v ) {
    	$this->attributes['from_barge_port_arrive_time'] = $v ? strtotime( $v ) : 0 ;
    }
    
    public function getFromBargePortArriveTimeAttribute( $v ) {
    	return $v ? date('Y-m-d' ,  $v ) : '' ;
    }
    
    public function setToPortLeaveTimeAttribute( $v ) {
    	$this->attributes['to_port_leave_time'] = $v ? strtotime( $v ) : 0 ;
    }
    
    public function getToPortLeaveTimeAttribute( $v ) {
    	return $v ? date('Y-m-d' ,  $v ) : '' ;
    }
    
    public function setToBargePortArriveTimeAttribute( $v ) {
    	$this->attributes['to_barge_port_arrive_time'] = $v ? strtotime( $v ) : 0 ;
    }
    
    public function getToBargePortArriveTimeAttribute( $v ) {
    	return $v ? date('Y-m-d' ,  $v ) : '' ;
    }
}
