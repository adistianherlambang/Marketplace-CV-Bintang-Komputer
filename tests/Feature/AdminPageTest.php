<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPageTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@bintangkomputer.com'
        ]);

        $category = Category::create(['name' => 'Laptops', 'slug' => 'laptops']);
        $brand = Brand::create(['name' => 'Apple', 'slug' => 'apple']);
        $supplier = Supplier::create([
            'name' => 'Supplier A',
            'contact_phone' => '12345',
            'email' => 'supplier@example.com',
            'address' => 'Test Address'
        ]);

        Product::create([
            'name' => 'MacBook Pro',
            'sku' => 'MBP123',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'supplier_id' => $supplier->id,
            'price_modal' => 15000000,
            'price_jual' => 20000000,
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => true
        ]);

        Customer::create([
            'name' => 'Budi',
            'phone' => '08123',
            'email' => 'budi@example.com',
            'address' => 'Test Address'
        ]);
    }

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_pos_create_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/transactions/create');
        $response->assertStatus(200);
    }

    public function test_returns_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/returns');
        $response->assertStatus(200);
    }

    public function test_invoice_and_nota_pdf_can_be_downloaded_without_header_utils_exception(): void
    {
        // 1. Create a dummy order with typical slashes in the invoice number
        $order = Order::create([
            'invoice_number' => 'INV/20260628/0001',
            'user_id' => $this->admin->id,
            'status' => 'Lunas',
            'total_amount' => 20000000,
        ]);

        $product = Product::first();
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'item_name' => $product->name,
            'price' => 20000000,
            'quantity' => 1,
            'subtotal' => 20000000
        ]);

        // 2. Request the invoice PDF download
        $invoiceResponse = $this->actingAs($this->admin)
            ->get("/admin/transactions/{$order->id}/invoice");

        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertHeader('content-disposition', 'attachment; filename=invoice-INV-20260628-0001.pdf');

        // 3. Request the nota PDF download
        $notaResponse = $this->actingAs($this->admin)
            ->get("/admin/transactions/{$order->id}/nota");

        $notaResponse->assertStatus(200);
        $notaResponse->assertHeader('content-disposition', 'attachment; filename=nota-INV-20260628-0001.pdf');
    }

    public function test_product_stock_can_be_adjusted_on_update(): void
    {
        $product = Product::first();
        $this->assertEquals(10, $product->stock);

        $response = $this->actingAs($this->admin)->put("/admin/products/{$product->id}", [
            'name' => 'MacBook Pro Updated',
            'sku' => $product->sku,
            'category_id' => $product->category_id,
            'brand_id' => $product->brand_id,
            'supplier_id' => $product->supplier_id,
            'price_modal' => $product->price_modal,
            'price_jual' => $product->price_jual,
            'stock' => 15,
            'stock_reason' => 'Restocked',
            'min_stock' => $product->min_stock,
            'is_active' => true
        ]);

        $response->assertRedirect('/admin/products');
        
        $product->refresh();
        $this->assertEquals(15, $product->stock);

        $this->assertDatabaseHas('stock_histories', [
            'product_id' => $product->id,
            'quantity' => 5,
            'type' => 'in',
            'description' => 'Restocked'
        ]);
    }
}
