<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Show the form for creating a new discount.
     */
    public function create(Product $product)
    {
        return view('content.discounts.create', compact('product'));
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'new_price' => 'required|numeric|min:0|lt:' . $product->selling_price,
        ]);

        $product->discounts()->create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Discount added successfully.');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount)
    {
        $product = $discount->product;
        return view('content.discounts.edit', compact('discount', 'product'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $product = $discount->product;

        $validated = $request->validate([
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'new_price' => 'required|numeric|min:0|lt:' . $product->selling_price,
        ]);

        $discount->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Discount updated successfully.');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('products.index')
            ->with('success', 'Discount removed successfully.');
    }
}
