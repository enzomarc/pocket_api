<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	/**
	 * Get all users.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$users = User::with(['shop'])->get();
		return response()->json(['users' => $users]);
	}
	
	/**
	 * Get user with the given id.
	 *
	 * @param int $user
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show(int $user)
	{
		try {
			$user = User::with(['shop'])->findOrFail($user);
			return response()->json(['user' => $user]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during user retrieving.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Store a newly created user.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 * @throws \Throwable
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'email' => 'required',
			'phone' => 'required',
			'password' => 'required',
		]);
		
		$data = $request->except('_token');
		$user = new User($data);
		
		try {
			$user->saveOrFail();
			return response()->json(['message' => "User created successfully.", 'user' => $user], 201);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during account creation.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Update user with the given id.
	 *
	 * @param Request $request
	 * @param int $user
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, int $user)
	{
		try {
			$data = $request->except('_token');
			$user = User::with(['shop'])->findOrFail($user);
			
			$user->update($data);
			return response()->json(['message' => "User updated successfully.", 'user' => $user]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during user update.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Delete user with the given id.
	 *
	 * @param int $user
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy(int $user)
	{
		try {
			$user = User::with(['shop'])->findOrFail($user);
			$user->delete();
			// Delete user related.
			
			return response()->json(['message' => "User deleted successfully."]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during user deletion.", 'exception' => $e->getMessage()], 500);
		}
	}
}
