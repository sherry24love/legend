<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model {
	
	protected $table='order_goods';
	
    protected $fillable = [
    		'order_id' , 'name' , 'box_type' , 'box_num' , 'total_num' , 'weight' , 'cubage' , 'package'
    ];

    public function __construct(array $attributes = []) {
    	parent::__construct( $attributes );
    }
    
}
