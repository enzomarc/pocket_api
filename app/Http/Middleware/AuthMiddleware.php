<?php

namespace App\Http\Middleware;

use App\Role;
use App\Shop;
use App\User;
use Closure;
use Illuminate\Support\Facades\DB;

class AuthMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param $request
	 * @param Closure $next
	 * @return \Illuminate\Http\JsonResponse|mixed
	 * @throws \Exception
	 */
	public function handle($request, Closure $next)
	{
		if (!$request->hasHeader('Authorization'))
			return response()->json(['message' => 'Authorization Header not found.'], 401);

		$token = $request->header('Authorization');

		if ($token == null)
			return response()->json(['message' => 'No token provided.'], 401);

		try {
			$user = User::all()->where('token', $token)->first();
			
			if ($user != null) {
				$method = $request->method();
				$url_array = explode('/', $request->getRequestUri());
				$shop_position = array_search('shop', $url_array);
				
				if ($shop_position != false) {
					$shop = $url_array[$shop_position + 1];
					
					if (count($url_array) >= $shop_position + 3) {
						$page = $url_array[$shop_position + 2];
						$page = explode('?', $page)[0];
						
						if ($page == 'verifications')
							$page = 'shop';
					} else {
						$page = 'shop';
					}
					
					if (Shop::findOrFail($shop) == null) {
						return response()->json(['message' => "The targetted shop doesn't exists."], 500);
					}
					
					// Check if user can access shop.
					$can_access = DB::table('users_shops')->where('user', $user->id)->where('shop', $shop)->first();
					
					if ($can_access != null) {
						$role_id = DB::table('users_roles')->where('user', $user->id)->where('shop', $shop)->first();
						
						// Check if user have authorization.
						if ($role_id != null) {
							$role_id = $role_id->role;
							$role = Role::find($role_id);
							
							if ($role != null) {
								$actions = $role->actions;
								$can = false;
								
								if ($method == 'GET')
									$to_check = 'view_' . $page;
								else
									$to_check = 'manage_' . $page;
								
								foreach ($actions as $action) {
									if ($action == $to_check)
										$can = true;
								}
								
								if ($can)
									return $next($request);
								else
									return response()->json(['message' => "You don't have permission to access this section.", 'section' => $to_check], 401);
							} else {
								return response()->json(['message' => "You don't have permission to access this section."], 401);
							}
						} else {
							return response()->json(['message' => "You don't have permission to access this section."], 401);
						}
					} else {
						return response()->json(['message' => "You don't have permission to manage this shop."], 401);
					}
				}
			} else {
				return response()->json(['message' => "Invalid token provided."], 401);
			}
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 401);
		}
	}
}
