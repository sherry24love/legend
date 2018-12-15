<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model {
	use SoftDeletes ;
	
	protected $table='company';
	
    protected $fillable = [
    	'name' , 'short_name' , 'cover' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    
    public static function selectOption() {
    	$o = self::pluck('name' , 'id');
    	return collect( $o )->prepend('请选择', 0)->all();
    }
}
