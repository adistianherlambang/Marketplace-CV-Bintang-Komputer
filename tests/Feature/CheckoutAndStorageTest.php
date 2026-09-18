<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutAndStorageTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
    protected User $adminUser;
    protected Product $product;
    protected Kecamatan $kecamatan;
    protected Kelurahan $kelurahan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'admin@bintangkomputer.com'
        ]);

        $this->customerUser = User::factory()->create([
            'email' => 'customer@example.com'
        ]);

        $category = Category::create(['name' => 'Laptops', 'slug' => 'laptops']);
        $brand = Brand::create(['name' => 'Lenovo', 'slug' => 'lenovo']);
        $supplier = Supplier::create([
            'name' => 'Supplier X',
            'contact_phone' => '0812345678',
            'email' => 'supplierx@example.com',
            'address' => 'Jakarta'
        ]);

        $this->product = Product::create([
            'name' => 'ThinkPad T480',
            'sku' => 'TP-T480',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'supplier_id' => $supplier->id,
            'price_modal' => 4000000,
            'price_jual' => 5500000,
            'stock' => 5,
            'min_stock' => 1,
            'is_active' => true,
        ]);

        $this->kecamatan = Kecamatan::create([
            'nama_kecamatan' => 'Metro Pusat'
        ]);

        $this->kelurahan = Kelurahan::create([
            'kecamatan_id' => $this->kecamatan->id,
            'nama_kelurahan' => 'Metro',
            'tarif_grab' => 15000,
        ]);
    }

    public function test_checkout_decrements_product_stock_and_logs_history(): void
    {
        $this->assertEquals(5, $this->product->stock);

        Storage::fake('public');
        $fakeReceipt = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($this->customerUser)->post('/checkout/store', [
            'customer_name' => 'Customer Budi',
            'customer_phone' => '081298765432',
            'product_id' => $this->product->id,
            'kecamatan_id' => $this->kecamatan->id,
            'kelurahan_id' => $this->kelurahan->id,
            'shareloc_link' => 'https://maps.app.goo.gl/sample',
            'payment_method' => 'Transfer BNI',
            'bukti_transfer' => $fakeReceipt,
        ]);

        $response->assertRedirect('/riwayat-pesanan');
        $response->assertSessionHas('success');

        $this->product->refresh();
        // Stok harus berkurang dari 5 menjadi 4
        $this->assertEquals(4, $this->product->stock);

        // Riwayat penyesuaian stok tercatat
        $this->assertDatabaseHas('stock_histories', [
            'product_id' => $this->product->id,
            'type' => 'out',
            'quantity' => 1,
        ]);

        // Verifikasi di halaman POS Kasir stok juga berkurang
        $posResponse = $this->actingAs($this->adminUser)->get('/admin/transactions/create');
        $posResponse->assertStatus(200);
        $posResponse->assertSee('ThinkPad T480');
    }

    public function test_checkout_fails_if_product_out_of_stock(): void
    {
        $this->product->update(['stock' => 0]);

        $response = $this->actingAs($this->customerUser)->post('/checkout/store', [
            'customer_name' => 'Customer Budi',
            'customer_phone' => '081298765432',
            'product_id' => $this->product->id,
            'kecamatan_id' => $this->kecamatan->id,
            'kelurahan_id' => $this->kelurahan->id,
            'shareloc_link' => 'https://maps.app.goo.gl/sample',
            'payment_method' => 'Transfer BNI',
        ]);

        $response->assertSessionHas('error');
        $this->product->refresh();
        $this->assertEquals(0, $this->product->stock);
    }

    public function test_cancelling_order_in_kelola_pesanan_restores_product_stock(): void
    {
        // 1. Buat pesanan online
        $order = Order::create([
            'invoice_number' => 'INV/TEST/RESTORE',
            'customer_user_id' => $this->customerUser->id,
            'customer_name' => 'Test Customer',
            'status' => 'Menunggu Konfirmasi',
            'total_amount' => 5500000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'item_name' => $this->product->name,
            'price' => 5500000,
            'quantity' => 1,
            'subtotal' => 5500000,
        ]);

        // Simulasikan stok terpotong menjadi 4 saat checkout
        $this->product->update(['stock' => 4]);

        // 2. Admin mengubah status menjadi Dibatalkan
        $response = $this->actingAs($this->adminUser)->put("/admin/pesanan/{$order->id}", [
            'status' => 'Dibatalkan',
        ]);

        $response->assertSessionHas('success');

        // 3. Stok kembali bertambah ke 5
        $this->product->refresh();
        $this->assertEquals(5, $this->product->stock);

        $this->assertDatabaseHas('stock_histories', [
            'product_id' => $this->product->id,
            'type' => 'return',
            'quantity' => 1,
        ]);
    }

    public function test_storage_file_can_be_accessed_via_storage_route(): void
    {
        $testDir = storage_path('app/public/bukti_transfer');
        if (!file_exists($testDir)) {
            mkdir($testDir, 0775, true);
        }

        $testFilePath = $testDir . '/test_unit_receipt.jpg';
        file_put_contents($testFilePath, 'fake-jpeg-content');

        try {
            $response = $this->get('/storage/bukti_transfer/test_unit_receipt.jpg');
            $response->assertStatus(200);
            $response->assertHeader('Cache-Control', 'max-age=86400, public, stale-while-revalidate=604800');
        } finally {
            if (file_exists($testFilePath)) {
                @unlink($testFilePath);
            }
        }
    }

    public function test_storage_file_prevents_directory_traversal(): void
    {
        $response = $this->get('/storage/../.env');
        $this->assertTrue(in_array($response->getStatusCode(), [403, 404]));

        $response404 = $this->get('/storage/bukti_transfer/non_existent_image_123.jpg');
        $response404->assertStatus(404);
    }

    public function test_product_primary_image_fallback_to_first_image(): void
    {
        $image1 = ProductImage::create([
            'product_id' => $this->product->id,
            'path' => 'products/sample1.jpg',
            'is_primary' => false,
        ]);

        $image2 = ProductImage::create([
            'product_id' => $this->product->id,
            'path' => 'products/sample2.jpg',
            'is_primary' => false,
        ]);

        $this->product->refresh();
        $this->assertNotNull($this->product->primaryImage);
        $this->assertEquals($image1->id, $this->product->primaryImage->id);
    }
}
