<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderChangeLog extends Model {
	
	protected $table='order_change_log';
	
    protected $fillable = [
    		'order_id' , 'user_id' , 'admin_id' , 'content' , 'mark' , 'status'
    ];

    public function __construct(array $attributes = []) {
    	parent::__construct( $attributes );
    }
    
    public function user() {
    	return $this->belongsTo( \App\User::class , 'user_id' , 'id' );
    }
    
    public function admin() {
    	return $this->belongsTo( \Encore\Admin\Auth\Database\Administrator::class , 'admin_id' , 'id' );
    }
}
