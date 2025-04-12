<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('status', 'available')->orderBy('name')->get();
        return view('content.pos.index', compact('customers', 'products'));
    }

    /**
     * Search for products.
     */
    public function searchProducts(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('status', 'available')->orderBy('name');


        if($query){
            $products = $products->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('barcode', 'like', "%{$query}%");
            });
        }
        $products = $products->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get product by barcode.
     */
    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = Product::where('barcode', $barcode)
            ->where('status', 'available')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => __('app.product_not_found'),
                'data' => null
            ]);
        }

        $product->price = $product->getCurrentPrice();
        return response()->json([
            'success' => true,
            'message' => 'Product found',
            'data' => $product
        ]);
    }

    /**
     * Quick add a new product.
     */
    public function quickAddProduct(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'purchase_price' => 'required|numeric|min:0.01',
            'selling_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
        ], [
            'name.required' => __('app.product_name_required'),
            'purchase_price.min' => __('app.purchase_price_positive'),
            'selling_price.min' => __('app.selling_price_positive'),
        ]);

        try {
            // Create the product
            $product = Product::create([
                'name' => $validated['name'],
                'barcode' => $validated['barcode'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'quantity' => $validated['quantity'],
                'status' => 'available',
            ]);

            return response()->json([
                'success' => true,
                'message' => __('app.product_created'),
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 422);
        }
    }

    public function processOrder(Request $request)
    {
        // Check if cart is empty
        if (empty($request->input('items')) || !is_array($request->input('items'))) {
            return response()->json([
                'success' => false,
                'message' => __('app.cart_empty'),
                'data' => null
            ], 422);
        }

        // Check if paid amount is valid
        $paidAmount = $request->input('paid_amount');
        if (!$paidAmount || $paidAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('app.paid_amount_required'),
                'data' => null
            ], 422);
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = $validated['total_amount'];
            $paidAmount = $validated['paid_amount'];
            $remainingAmount = max(0, $totalAmount - $paidAmount);
            $totalProfit = 0;

            // Create order
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'total_amount' => $totalAmount,
                'total_profit' => 0, // Will update after calculating items
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'status' => $remainingAmount > 0 ? 'pending' : 'completed',
            ]);

            // Process items
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['id']);

                // Check if enough stock
                /* if ($product->quantity < $itemData['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                } */

                // Calculate amount and profit
                $price = $product->getCurrentPrice(); // Use discounted price if available
                $amount = $price * $itemData['quantity'];
                $profit = ($price - $product->purchase_price) * $itemData['quantity'];
                $totalProfit += $profit;

                // Create item
                Item::create([
                    'order_id' => $order->id,
                    'name' => $product->name,
                    'barcode' => $product->barcode,
                    'image' => $product->image,
                    'price' => $price,
                    'quantity' => $itemData['quantity'],
                    'amount' => $amount,
                    'profit' => $profit,
                ]);

                // Update product quantity
                $product->quantity -= $itemData['quantity'];
                $product->save();
            }

            // Update order with total profit
            $order->total_profit = $totalProfit;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('app.order_processed'),
                'data' => [
                    'order_id' => $order->id
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 422);
        }
    }
}
