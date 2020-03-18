<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'by', 'shop', 'email', 'role', 'token',
	];
	
	/**
	 * Get invited user role.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function role()
	{
		return $this->belongsTo(Role::class, 'role');
	}
	
	/**
	 * Get the shop inviting user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function shop()
	{
		return $this->belongsTo(Shop::class, 'shop');
	}
}
