<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'shop', 'name', 'actions',
	];
	
	/**
	 * @var array
	 */
	protected $casts = [
		'actions' => 'json',
	];
	
	/**
	 * Get role users.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users()
	{
		return $this->hasMany(User::class, 'role');
	}
}
