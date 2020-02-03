<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'shop', 'type', 'document', 'status', 'verified_by',
	];
	
	/**
	 * Get the shop verification.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function shop()
	{
		return $this->belongsTo(Shop::class, 'shop');
	}
}
