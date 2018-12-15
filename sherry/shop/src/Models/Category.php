<?php

namespace Sherry\Shop\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
	use ModelTree, AdminBuilder;
	
	protected $table='shop_category';
	
	
    protected $fillable = [
    		'name' , 'keyword' , 'description' , 'order' , 'parent_id' , 'cover'
    ];

    public function __construct(array $attributes = []) {
    	
    	$this->setTitleColumn('name') ;
    	parent::__construct( $attributes );
    }
    
    public static function selectCategoryOptions()
    {
    	$options = (new static())->buildSelectOptions();
    
    	return collect($options)->all();
    }
    
}
