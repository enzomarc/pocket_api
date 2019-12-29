<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shop extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'business_name', 'business_description', 'address', 'business_email', 'business_phone', 'support_email', 'business_website', 'currency', 'logo', 'active', 'socials', 'created_by'
	];
	
	/**
	 * @var array
	 */
	protected $casts = [
		'socials' => 'json',
		'active' => 'boolean',
	];
	
	/**
	 * Get shop users.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(User::class, 'users_shops', 'shop', 'user');
	}
}
