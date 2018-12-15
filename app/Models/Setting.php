<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Setting extends Model {

	protected $table='opt';

	protected $fillable = [
			'name' , 'key' , 'val' ,
	];

	public function __construct(array $attributes = []) {
		parent::__construct( $attributes );
	}
	
}
