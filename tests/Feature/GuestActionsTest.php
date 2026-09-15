<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Order;
use App\Models\ProductBooking;
use App\Models\Complaint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestActionsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $product;

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

        $this->product = Product::create([
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
    }

    public function test_guest_can_view_product_detail_page(): void
    {
        $response = $this->get("/products/{$this->product->id}");
        $response->assertStatus(200);
        $response->assertSee('Pesan Sekarang');
    }

    public function test_guest_can_submit_product_booking(): void
    {
        $pickupTime = now()->addDay()->format('Y-m-d H:i:s');
        $response = $this->post("/products/{$this->product->id}/book", [
            'customer_name' => 'John Doe',
            'customer_phone' => '081234567890',
            'pickup_time' => $pickupTime,
            'notes' => 'Tolong disiapkan yang warna silver.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_bookings', [
            'product_id' => $this->product->id,
            'customer_name' => 'John Doe',
            'customer_phone' => '081234567890',
            'notes' => 'Tolong disiapkan yang warna silver.',
            'status' => 'Menunggu',
        ]);
    }

    public function test_guest_can_submit_general_complaint(): void
    {
        $response = $this->postJson('/complaints/send', [
            'customer_name' => 'Jane Doe',
            'contact' => '08987654321',
            'complaint_text' => 'Barang cepat panas.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('complaints', [
            'order_id' => null,
            'customer_name' => 'Jane Doe',
            'contact' => '08987654321',
            'complaint_text' => 'Barang cepat panas.',
            'status' => 'Menunggu',
        ]);
    }

    public function test_guest_can_submit_complaint_with_invoice(): void
    {
        $order = Order::create([
            'invoice_number' => 'INV-2026-0001',
            'user_id' => $this->admin->id,
            'status' => 'Lunas',
            'total_amount' => 20000000,
        ]);

        $response = $this->postJson('/complaints/send', [
            'customer_name' => 'Jane Doe',
            'contact' => '08987654321',
            'invoice_number' => 'INV-2026-0001',
            'complaint_text' => 'Barang cepat panas.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('complaints', [
            'order_id' => $order->id,
            'customer_name' => 'Jane Doe',
            'contact' => '08987654321',
            'complaint_text' => 'Barang cepat panas.',
        ]);
    }

    public function test_admin_can_manage_bookings(): void
    {
        $booking = ProductBooking::create([
            'product_id' => $this->product->id,
            'customer_name' => 'Bobby',
            'customer_phone' => '081234567890',
            'pickup_time' => now()->addDay(),
            'status' => 'Menunggu'
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/bookings');
        $response->assertStatus(200);
        $response->assertSee('Bobby');

        $statusResponse = $this->actingAs($this->admin)->post("/admin/bookings/{$booking->id}/status", [
            'status' => 'Diproses',
            'notes' => 'Sudah dikonfirmasi via WA.'
        ]);

        $statusResponse->assertRedirect('/admin/bookings');
        
        $booking->refresh();
        $this->assertEquals('Diproses', $booking->status);
        $this->assertEquals('Sudah dikonfirmasi via WA.', $booking->notes_internal);
    }
}
