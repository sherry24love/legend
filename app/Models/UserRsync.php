<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRsync extends Model {
	protected $table='user_rsync';
	
	
    protected $fillable = [
    	'id' , 'type' , 'user_id' , 'token' 
    ];

    public function __construct(array $attributes = []) {
    	parent::__construct( $attributes );
    }
    
    public function user() {
    	return $this->belongsTo( User::calss , 'user_id' , 'id' );
    }
    
}
