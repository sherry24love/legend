<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ship extends Model {
	use SoftDeletes ;
	
	protected $table='ship';
	
    protected $fillable = [
    	'name' , 'company_id' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
    
    public static function selectOption() {
    	$o = self::pluck('name' , 'id');
    	return collect( $o )->prepend('请选择', 0)->all();
    }
    
    public function company() {
    	return $this->belongsTo( Company::class , 'company_id' , 'id' );
    }
    
    public function flight() {
    	return $this->hasMany( Flight::class , 'ship_id' , 'id' );
    }
}
