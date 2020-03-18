<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
	/**
	 * Get all the invitations.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$invitations = Invitation::all();
		return response()->json(['invitations' => $invitations]);
	}
	
	/**
	 * Get invitation with the given id.
	 *
	 * @param int $invitation
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show(int $invitation)
	{
		try {
			$invitation = Invitation::with(['shop', 'role'])->findOrFail($invitation);
			return response()->json(['invitation' => $invitation]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during invitation retrieving.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Store newly created invitation.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 * @throws \Throwable
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'by' => 'required',
			'shop' => 'required',
			'email' => 'required',
			'role' => 'required',
		]);
		
		try {
			$data = $request->except('_token');
			$data['token'] = hash('md5', uniqid() . Str::random(8));
			$invitation = new Invitation($data);
			
			if ($invitation->saveOrFail()) {
				Mail::to($data['email'])->send(new \App\Mail\Invitation($invitation));
			}
			
			return response()->json(['message' => "Invitation delivered successfully.", 'invitation' => $invitation], 201);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during invitation delivering.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Update the given invitation.
	 *
	 * @param Request $request
	 * @param int $invitation
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, int $invitation)
	{
		try {
			$data = $request->except('_token');
			
			if (isset($data['actions']))
				$data['actions'] = json_encode($data['actions']);
			
			$invitation = Invitation::with(['users'])->findOrFail($invitation);
			$invitation->update($data);
			
			return response()->json(['message' => "Invitation updated successfully.", 'invitation' => $invitation]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during invitation update.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Delete the given invitation.
	 *
	 * @param int $invitation
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy(int $invitation)
	{
		try {
			$invitation = Invitation::findOrFail($invitation);
			$invitation->delete();
			// Delete invitation related.
			
			return response()->json(['message' => "Invitation deleted successfully."]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during invitation deletion.", 'exception' => $e->getMessage()], 500);
		}
	}
}
