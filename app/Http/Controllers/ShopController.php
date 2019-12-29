<?php

namespace App\Http\Controllers;

use App\Shop;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopController extends Controller
{
	/**
	 * Get all shops.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$shops = Shop::with(['users'])->get();
		return response()->json(['shops' => $shops]);
	}
	
	/**
	 * Get the shop with the given ID.
	 *
	 * @param int $shop
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show(int $shop)
	{
		try {
			$shop = Shop::with(['users'])->findOrFail($shop);
			return response()->json(['shop' => $shop]);
		} catch (\Exception $e) {
			return response()->json(['message' => "Unable to find the shop.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Create new shop.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 * @throws \Throwable
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'business_name' => 'required',
			'email' => 'required',
			'password' => 'required',
		]);
		
		try {
			$shop_data = $request->only('business_name');
			$user_data = $request->only(['email', 'password']);
			
			$shop = new Shop($shop_data);
			
			if ($shop->saveOrFail()) {
				$user = User::all()->where('email', $user_data['email'])->first();
				
				if ($user == null) {
					$user_data['token'] = hash('md5', $shop->business_name . Str::random(8));
					$user_data['password'] = Hash::make($user_data['password']);
					$user = new User($user_data);
					
					if ($user->saveOrFail()) {
						DB::table('users_shops')->insert(['user' => $user->id, 'shop' => $shop->id]);
						DB::table('users_roles')->insert(['user' => $user->id, 'shop' => $shop->id, 'role' => 1]);
						$shop->update(['created_by' => $user->id]);
						
						return response()->json(['message' => "Shop created successfully.", 'shop' => $shop], 201);
					} else {
						$shop->delete();
					}
				} else {
					DB::table('users_shops')->insert(['user' => $user->id, 'shop' => $shop->id]);
					DB::table('users_roles')->insert(['user' => $user->id, 'shop' => $shop->id, 'role' => 1]);
					$shop->update(['created_by' => $user->id]);
					
					return response()->json(['message' => "Shop created successfully.", 'shop' => $shop], 201);
				}
			}
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during shop creation.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Update the given shop.
	 *
	 * @param Request $request
	 * @param int $shop
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, int $shop)
	{
		try {
			$data = $request->except('_token');
			$shop = Shop::with(['users'])->findOrFail($shop);
			$shop->update($data);
			
			return response()->json(['message' => "Shop updated successfully.", 'shop' => $shop]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during shop update.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Delete the given shop.
	 *
	 * @param int $shop
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy(int $shop)
	{
		try {
			$shop = Shop::findOrFail($shop);
			$shop->delete();
			// Delete related to the shop.
			
			return response()->json(['message' => "Shop deleted successfully."]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during shop delete.", 'exception' => $e->getMessage()], 500);
		}
	}
}
