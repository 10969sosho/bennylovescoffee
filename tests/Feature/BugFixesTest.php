<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class BugFixesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    public function test_cart_cumulative_add_cannot_exceed_stock()
    {
        $user = User::where('email', 'john@example.com')->firstOrFail();
        $product = Product::where('slug', 'timemore-c2')->firstOrFail();
        $product->update(['stock' => 120]);

        $this->actingAs($user);

        $this->post('/cart/add/timemore-c2', ['quantity' => 100]);
        $this->post('/cart/add/timemore-c2', ['quantity' => 100]);

        $cart = Cart::where('user_id', $user->id)->where('product_id', $product->id)->first();

        $this->assertNotNull($cart);
        $this->assertEquals(100, $cart->quantity, 'Cumulative quantity must not exceed stock');
    }

    public function test_order_cannot_exceed_stock_and_no_negative_stock()
    {
        $user = User::where('email', 'john@example.com')->firstOrFail();
        $product = Product::where('slug', 'timemore-c2')->firstOrFail();
        $product->update(['stock' => 5]);

        Cart::where('user_id', $user->id)->delete();
        Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 100]);

        $this->actingAs($user);

        $response = $this->post('/orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Test 123',
            'postal_code' => '60293',
            'shipping_cost' => 15000,
            'shipping_service' => 'REG',
            'shipping_etd' => '2-3 days',
            'destination_area_id' => 'test-area',
            'area_name' => 'Surabaya',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Stok', session('error'), 'Redirect should carry a stock error message');

        $this->assertEquals(5, $product->fresh()->stock, 'Stock must not go negative');
        $this->assertEquals(0, Order::count(), 'No order should be created');
    }

    public function test_order_number_is_unique()
    {
        $user = User::where('email', 'john@example.com')->firstOrFail();
        $product = Product::where('slug', 'timemore-c2')->firstOrFail();
        $product->update(['stock' => 100]);

        $numbers = [];

        for ($i = 0; $i < 3; $i++) {
            Cart::where('user_id', $user->id)->delete();
            Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

            $this->actingAs($user);
            $this->post('/orders', [
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_phone' => '081234567890',
                'shipping_address' => 'Jl. Test 123',
                'postal_code' => '60293',
                'shipping_cost' => 15000,
                'shipping_service' => 'REG',
                'shipping_etd' => '2-3 days',
                'destination_area_id' => 'test-area',
                'area_name' => 'Surabaya',
            ]);

            $numbers[] = Order::latest('id')->first()->order_number;
        }

        $this->assertEquals(3, count(array_unique($numbers)), 'order_number must be unique');
    }

    public function test_variant_edit_preserves_order_history()
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();
        $user = User::where('email', 'john@example.com')->firstOrFail();

        $product = Product::create([
            'name' => 'Test Variant Product ' . uniqid(),
            'description' => 'desc',
            'category_id' => 1,
            'slug' => 'test-variant-' . uniqid(),
            'price' => 100000,
            'stock' => 0,
            'weight' => 200,
            'is_active' => true,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Original',
            'quantity' => 10,
            'price' => 100000,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-' . uniqid(),
            'user_id' => $user->id,
            'customer_name' => 'John',
            'customer_email' => 'john@example.com',
            'shipping_address' => 'Jl. Test',
            'total_amount' => 100000,
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'variant_name' => $variant->name,
            'quantity' => 1,
            'price' => 100000,
            'total' => 100000,
        ]);

        $this->actingAs($admin, 'admin');

        $this->put('/admin/products/' . $product->id, [
            'name' => $product->name,
            'description' => 'desc',
            'category_id' => 1,
            'weight' => 200,
            'use_variants' => '1',
            'variants' => [
                ['name' => 'New Variant', 'quantity' => 5, 'price' => 90000],
            ],
        ]);

        $this->assertDatabaseHas('order_items', ['id' => $orderItem->id]);
        $this->assertEquals(1, OrderItem::where('id', $orderItem->id)->count(), 'Order item must not be cascade-deleted');
        $this->assertNull(OrderItem::find($orderItem->id)->variant_id, 'variant_id should be nulled, row kept');
    }

    public function test_destroy_product_blocked_when_has_orders()
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();
        $user = User::where('email', 'john@example.com')->firstOrFail();

        $product = Product::create([
            'name' => 'Delete Protected ' . uniqid(),
            'description' => 'desc',
            'category_id' => 1,
            'slug' => 'delete-protected-' . uniqid(),
            'price' => 100000,
            'stock' => 5,
            'weight' => 200,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-DEL-' . uniqid(),
            'user_id' => $user->id,
            'customer_name' => 'John',
            'customer_email' => 'john@example.com',
            'shipping_address' => 'Jl. Test',
            'total_amount' => 100000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100000,
            'total' => 100000,
        ]);

        $this->actingAs($admin, 'admin');
        $this->delete('/admin/products/' . $product->id);

        $this->assertNotNull(Product::find($product->id), 'Product with orders must not be deleted');
    }

    public function test_api_admin_store_product_and_destroy()
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/admin/products', [
            'name' => 'API Product ' . uniqid(),
            'description' => 'desc',
            'category_id' => 1,
            'price' => 50000,
            'stock' => 10,
            'weight' => 250,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['id' => $response->json('data.id')]);

        $productId = $response->json('data.id');

        $this->actingAs($admin, 'sanctum')->deleteJson('/api/admin/products/' . $productId)
            ->assertStatus(200);
        $this->assertNull(Product::find($productId));
    }

    public function test_api_admin_cannot_manipulate_stock_overflow()
    {
        $admin = User::where('role', 'superadmin')->firstOrFail();

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/products', [
                'name' => 'API Huge Qty ' . uniqid(),
                'description' => 'desc',
                'category_id' => 1,
                'price' => 999999999999999999,
                'stock' => 999999999999999999,
                'weight' => 999999999999999999,
            ])
            ->assertStatus(422);
    }

    public function test_api_cart_add_supports_variant()
    {
        $user = User::where('email', 'john@example.com')->firstOrFail();
        $variant = ProductVariant::where('is_active', true)->first();

        if (!$variant) {
            $this->markTestSkipped('No active variant product in database');
        }

        $product = Product::find($variant->product_id);

        Cart::where('user_id', $user->id)->where('product_id', $product->id)->delete();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/cart/add/' . $product->id, [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variant->id)
            ->first();

        $this->assertNotNull($cart, 'Cart row with variant must be created');
        $this->assertEquals(2, $cart->quantity);
    }
}