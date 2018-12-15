<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecevier extends Model {
	
	protected $table='user_recevier';
	
    protected $fillable = [
    		'name' , 'contact_name' , 'mobile' , 'email' , 'address' , 'id_no' , 'user_id' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
}
