<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
	/**
	 * Get all customers.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$customers = Customer::all();
		
		return response()->json(['customers' => $customers]);
	}
	
	/**
	 * Get customer.
	 *
	 * @param int $customer
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show(int $customer)
	{
		try {
			$customer = Customer::findOrFail($customer);
			return response()->json(['customer' => $customer]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during customer retrieving.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Store a newly created customer.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 * @throws \Throwable
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'phone' => 'required',
			'created_by' => 'required',
		]);
		
		try {
			$data = $request->input();
			$data['code'] = uniqid('CUS_');
			
			if (isset($data['password']))
				$data['password'] = Hash::make($data['password']);
			else {
				$data['password'] = Hash::make(Str::random(6));
				// Send SMS to customer with password.
			}
			
			$customer = new Customer($data);
			$customer->saveOrFail();
			
			return response()->json(['message' => "Customer created successfully.", 'customer' => $customer], 201);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during customer creation.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Update the given customer.
	 *
	 * @param Request $request
	 * @param int $customer
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, int $customer)
	{
		try {
			$customer = Customer::findOrFail($customer);
			$data = $request->input();
			
			if (isset($data['password']))
				$data['password'] = Hash::make($data['password']);
			
			$customer->update($data);
			
			return response()->json(['message' => "Customer updated successfully.", 'customer' => $customer]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during customer update.", 'exception' => $e->getMessage()], 500);
		}
	}
	
	/**
	 * Delete the given customers.
	 *
	 * @param int $customer
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy(int $customer)
	{
		try {
			$customer = Customer::findOrFail($customer);
			$customer->delete();
			// Delete related
			
			return response()->json(['message' => "Customer deleted successfully."]);
		} catch (\Exception $e) {
			return response()->json(['message' => "An error occurred during customer deletion.", 'exception' => $e->getMessage()]);
		}
	}
}
