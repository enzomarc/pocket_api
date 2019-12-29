<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
	/**
	 * Get all the roles.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$roles = Role::with(['users'])->get();
		return response()->json(['roles' => $roles]);
	}
	
	/**
	 * Get role with the given id.
	 *
	 * @param int $role
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show(int $role)
	{
		try {
			$role = Role::with(['users'])->findOrFail($role);
			return response()->json(['role' => $role]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during role retrieving.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Store newly created role.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 * @throws \Throwable
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'shop' => 'required',
			'name' => 'required',
			'actions' => 'required',
		]);
		
		try {
			$data = $request->except('_token');
			$data['actions'] = json_encode($data['actions']);
			$role = new Role($data);
			$role->saveOrFail();
			
			return response()->json(['message' => "Role created successfully.", 'role' => $role], 201);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during role creation.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Update the given role.
	 *
	 * @param Request $request
	 * @param int $role
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, int $role)
	{
		try {
			$data = $request->except('_token');
			
			if (isset($data['actions']))
				$data['actions'] = json_encode($data['actions']);
			
			$role = Role::with(['users'])->findOrFail($role);
			$role->update($data);
			
			return response()->json(['message' => "Role updated successfully.", 'role' => $role]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during role update.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Delete the given role.
	 *
	 * @param int $role
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy(int $role)
	{
		try {
			$role = Role::findOrFail($role);
			$role->delete();
			// Delete role related.
			
			return response()->json(['message' => "Role deleted successfully."]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during role deletion.", 'exception' => $e->getMessage()], 500);
		}
	}
}
