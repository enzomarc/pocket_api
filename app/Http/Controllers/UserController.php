<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
	 * @param string $invitation
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 * @throws \Throwable
	 */
	public function store(Request $request, string $invitation)
	{
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required',
		]);
		
		$data = $request->except('_token');
		$data['token'] = hash('md5', uniqid() . Str::random(8));
		$data['password'] = Hash::make($data['password']);
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
			$user = User::with(['shops'])->findOrFail($user);
			
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
	
	/**
	 * Login user with the given credentials.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function login(Request $request)
	{
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required',
		]);
		
		$email = $request->input('email');
		$password = $request->input('password');
		
		try {
			$user = User::with(['shops'])->where('email', $email)->first();
			
			if ($user != null && Hash::check($password, $user->password))
				return response()->json(['message' => "Logged in successfully.", 'user' => $user]);
			else
				return response()->json(['message' => "Unable to login. Invalid credentials."], 401);
		} catch (\Exception $e) {
			return response()->json(['message' => "Unable to login. An error occurred.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Retrieve user with his token.
	 *
	 * @param string $token
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function check(string $token)
	{
		try {
			$user = User::with(['shops'])->where('token', $token)->first();
			
			if ($user != null)
				return response()->json(['user' => $user]);
			else
				return response()->json(['message' => "Unable to find authenticates the given user."], 401);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during user retrieving.", 'exception' => $e->getMessage()], 500);
		}
	}
}
