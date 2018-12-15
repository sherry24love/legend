<?php
namespace Sherry\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Attributes extends Model {

	protected $table='shop_attribute';


	protected $fillable = [
			'sort_order'
	];

	public function __construct(array $attributes = []) {
		 
		parent::__construct( $attributes );
	}

	
	public function type() {
		return $this->belongsTo( GoodsType::class , 'type_id' , 'id' );
	}
}