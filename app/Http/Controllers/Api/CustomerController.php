<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customer::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Customers retrieved successfully',
            'data' => $customers
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => 'required|unique:customers',
            'name' => 'required|string',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);

        $customer = Customer::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    public function show(int $customer): JsonResponse
    {
        $customer = Customer::find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully',
            'data' => $customer
        ]);
    }

    public function update(Request $request, int $customer): JsonResponse
    {
        $customer = Customer::find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $data = $request->validate([
            'customer_id' => 'sometimes|string|unique:customers,customer_id,' . $customer->id,
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);

        $customer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    public function destroy(int $customer): JsonResponse
    {
        $customer = Customer::find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
    }
}