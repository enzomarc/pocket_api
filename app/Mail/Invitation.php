<?php

namespace App\Mail;

use App\Shop;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Invitation as Invit;

class Invitation extends Mailable
{
	use Queueable, SerializesModels;
	
	/**
	 * The invitation instance.
	 *
	 * @var Invit
	 */
	public $invitation;
	
	/**
	 * Create a new message instance.
	 *
	 * @param Invit $invitation
	 */
	public function __construct(Invit $invitation)
	{
		$invitation->by = User::findOrFail($invitation->by);
		$invitation->shop = Shop::findOrFail($invitation->shop);
		
		$this->invitation = $invitation;
	}
	
	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('invitation');
	}
}
