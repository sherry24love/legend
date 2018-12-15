<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSender extends Model {
	
	protected $table='user_sender';
	
    protected $fillable = [
    	'name' , 'contact_name' , 'mobile' , 'email' , 'address' , 'user_id' ,
    ];

    public function __construct(array $attributes = []) {
    	
    	parent::__construct( $attributes );
    }
    
}
