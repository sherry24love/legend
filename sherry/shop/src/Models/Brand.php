<?php

namespace Sherry\Shop\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model {
	
	protected $table='shop_brand';
	
	
    protected $fillable = [
    		'name' , 'keyword' , 'description' , 'order' , 'parent_id' , 'cover'
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
}
