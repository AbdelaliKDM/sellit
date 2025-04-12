<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get paginated results
        $customers = $query->latest()->paginate(10);

        return view('content.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('content.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('customers', 'public');
            $validated['image'] = $imagePath;
        }

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', __('app.customer_created'));
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        return view('content.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('content.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($customer->image) {
                Storage::disk('public')->delete($customer->image);
            }

            $imagePath = $request->file('image')->store('customers', 'public');
            $validated['image'] = $imagePath;
        }

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', __('app.customer_updated'));
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        // Delete image if exists
        if ($customer->image) {
            Storage::disk('public')->delete($customer->image);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', __('app.customer_deleted'));
    }
}
