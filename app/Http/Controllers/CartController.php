<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Display the user's cart
     */
    public function index(): View
    {
        $carts = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $total = $carts->sum(function ($cart) {
            return $cart->quantity * $cart->price;
        });

        return view('cart', compact('carts', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, $productId): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);

        // Check if product is available
        if (!$product->isInStock() || $product->stock < $quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak mencukupi'
                ], 400);
            }
            return back()->with('error', 'Stok produk tidak mencukupi');
        }

        // Check if product already in cart
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            // Update quantity if already exists
            $newQuantity = $cart->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total quantity melebihi stok yang tersedia'
                    ], 400);
                }
                return back()->with('error', 'Total quantity melebihi stok yang tersedia');
            }

            $cart->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => $this->getCartCount()
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $cartId): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($cartId);

        $quantity = $request->input('quantity');

        // Check stock availability
        if ($quantity > $cart->product->stock) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak mencukupi'
                ], 400);
            }
            return back()->with('error', 'Stok produk tidak mencukupi');
        }

        $cart->update(['quantity' => $quantity]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quantity berhasil diupdate',
                'item_total' => $cart->total,
                'cart_total' => $this->getCartTotal()
            ]);
        }

        return back()->with('success', 'Quantity berhasil diupdate');
    }

    /**
     * Remove item from cart
     */
    public function remove($cartId): JsonResponse|RedirectResponse
    {
        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($cartId);

        $cart->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'cart_count' => $this->getCartCount()
            ]);
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    /**
     * Clear all items from cart
     */
    public function clear(): RedirectResponse
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan');
    }

    /**
     * Get cart count for header
     */
    public function count(): JsonResponse
    {
        return response()->json([
            'count' => $this->getCartCount()
        ]);
    }

    /**
     * Private helper methods
     */
    private function getCartCount(): int
    {
        return Cart::where('user_id', Auth::id())->sum('quantity');
    }

    private function getCartTotal(): float
    {
        return Cart::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->sum(function ($cart) {
                return $cart->quantity * $cart->price;
            });
    }
}
