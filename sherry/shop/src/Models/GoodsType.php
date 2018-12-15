<?php
namespace Sherry\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model {

	protected $table='shop_goods_type';


	protected $fillable = [
			'name' , 'enable' , 'group_attr'
	];

	public function __construct(array $attributes = []) {
		 
		parent::__construct( $attributes );
	}

	
	public static function selectTypeOptions() {
		$options = self::where('enabled' , 1 )->pluck('name' , 'id' );
		return collect($options)->prepend('请选择分类', 0)->all();
	}
}