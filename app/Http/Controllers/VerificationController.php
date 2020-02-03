<?php

namespace App\Http\Controllers;

use App\Shop;
use App\Verification;
use Illuminate\Http\Request;
use App\Http\Controllers\Uploader;

class VerificationController extends Controller
{
	/**
	 * Create or update a business verification documents.
	 *
	 * @param Request $request
	 * @param int $shop
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 * @throws \Throwable
	 */
	public function store(Request $request, int $shop)
	{
		$this->validate($request, [
			'type' => 'required',
		]);
		
		try {
			$data = $request->input();
			$approved = Verification::all()->where('shop', $shop)->where('status', 1)->first();
			
			if ($approved) {
				return response()->json(['message' => "Your shop has already been verified and approved. You can start using Pocket."]);
			} else {
				$pending = Verification::all()->where('shop', $shop)->where('status', 0)->first();
				$cancelled = Verification::all()->where('shop', $shop)->where('status', 2)->first();
				
				if ($cancelled != null)
					$cancelled->delete();
				
				if ($pending != null)
					$pending->delete();
				
				if ($request->hasFile('document')) {
					$path = Uploader::file('document')->store();
					
					if ($path != false) {
						$data['document'] = $path;
						$data['shop'] = $shop;
						
						$verification = new Verification($data);
						$verification->saveOrFail();
						
						return response()->json(['message' => "Verification file saved successfully. An administrator will verify your business."], 201);
					} else {
						return response()->json(['message' => "Unable to send your verification file. Retry later."], 500);
					}
				} else {
					return response()->json(['message' => "No verification document specified. Please choose a file and retry."], 500);
				}
			}
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during verification document transfer.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Update shop verification document.
	 *
	 * @param Request $request
	 * @param int $shop
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, int $shop)
	{
		try {
			$verification = Verification::all()->where('shop', $shop)->where('status', 0)->first();
			
			if ($verification != null) {
				$data = $request->input();
				$verification->update($data);
				
				return response()->json(['message' => "Verification updated successfully.", 'verification' => $verification]);
			} else {
				return response()->json(['message' => "There's no verification document for this shop."], 500);
			}
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during verification update.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Get the verification of the given shop.
	 *
	 * @param int $shop
	 * @return bool|\Illuminate\Http\JsonResponse|mixed
	 */
	public function show(int $shop)
	{
		try {
			$verification = Verification::all()->where('shop', $shop)->first();
			
			if ($verification != null)
				return response()->json(['verification' => $verification]);
			else
				return response()->json(['verification' => false]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during verification retrieving.", 'exception' => $e->getMessage()], 500);
		}
	}
}
