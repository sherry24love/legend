<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
	use SoftDeletes ;
	
	protected $table='order';
	
    protected $fillable = [
    		'user_id',
    		'flight_id',
    		'barge_port' , 
    		'barge_to_port' ,
    		'waybill',
    		'shipment',
    		'destinationport',
    		'company_id',
    		'ship_id',
    		'voyage',
    		'cabinet',
    		'cabinet_num',
    		'departure',
    		'destination',
    		'trailer_cost',
    		'ship_cost',
    		'other_cost',
    		'costinfo',
    		'state',
    		'barge_time',
    		'barge_plan_time' ,
    		'barge_to_time' ,
    		'barge_to_plan_time' ,
    		'start_time',
    		'end_time',
    		'enable_ensure',
    		'ensure_name',
    		'insure_goods_worth',
    		'transport_protocol',
    		'admin_id',
    		'rebate',
    		'rebate_status',
    		'seal_num',
    		'cabinet_no',
    		'order_sn',
    		'file',
    		'owner' ,
    		'is_finished' ,
    		
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    
    public function goods() {
    	return $this->hasOne( OrderGoods::class , 'order_id' , 'id' );
    }
    
    public function sender() {
    	return $this->hasOne( OrderSender::class , 'order_id' , 'id' );
    }
    
    public function entrust() {
    	return $this->hasOne( OrderEntrust::class , 'order_id' , 'id' );
    }
    
    public function recevier() {
    	return $this->hasOne( OrderRecevier::class , 'order_id' , 'id' );
    }
    
    public function toport() {
    	return $this->belongsTo( Port::class , 'destinationport' , 'id' );
    }
    
    public function bargeport() {
    	return $this->belongsTo( Port::class , 'barge_port' , 'id' );
    }
    
    public function bargetoport() {
    	return $this->belongsTo( Port::class , 'barge_to_port' , 'id' );
    }
    
    public function fromport() {
    	return $this->belongsTo( Port::class , 'shipment' , 'id' );
    }
    
    public function admin() {
    	return $this->belongsTo( \Encore\Admin\Auth\Database\Administrator::class , 'admin_id' , 'id' );
    }
    
    public function company() {
    	return $this->belongsTo( Company::class , 'company_id' , 'id' );
    }
    
    public function ship() {
    	return $this->belongsTo( Ship::class , 'ship_id' , 'id' );
    }
    
    public function flight() {
    	return $this->belongsTo( Flight::class , 'voyage' , 'no') ;
    }
    
    public function user() {
    	return $this->belongsTo( \App\User::class , 'user_id' , 'id' );
    }
}
