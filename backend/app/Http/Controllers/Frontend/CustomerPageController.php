<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerPageController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|unique:customers,customer_id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:customers,email',
            'address'     => 'required|string',
            'status'      => 'required|boolean',
        ]);

        Customer::create($validated);

        return redirect('/customers')->with('success', 'Customer added successfully!');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $validated = $request->validate([
            'customer_id' => 'required|string|unique:customers,customer_id,' . $id,
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:customers,email,' . $id,
            'address'     => 'required|string',
            'status'      => 'required|boolean',
        ]);

        $customer->update($validated);

        return redirect('/customers')->with('success', 'Customer updated successfully!');
    }

    public function activate($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['status' => 1]);

        return redirect('/customers')->with('success', 'Customer activated successfully!');
    }

    public function deactivate($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['status' => 0]);

        return redirect('/customers')->with('success', 'Customer deactivated successfully!');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect('/customers')->with('success', 'Customer deleted successfully!');
    }
}