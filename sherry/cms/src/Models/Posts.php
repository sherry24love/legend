<?php

namespace Sherry\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model {
	
	protected $table='cms_posts';
	
	
    protected $fillable = [
    		'title' , 'keyword' , 'description' , 'order' , 'category_id' , 'cover' , 'content' ,'is_hot' , 'is_top' , 'is_recom' , 'is_pic'
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    public function category() {
    	return $this->belongsTo( Category::class , 'category_id' , 'id' );
    }
    
}
