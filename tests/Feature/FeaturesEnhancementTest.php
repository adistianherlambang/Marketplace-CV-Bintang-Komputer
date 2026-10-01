<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class FeaturesEnhancementTest extends TestCase
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
            'name' => 'Admin Store',
            'email' => 'admin@bintangkomputer.com'
        ]);

        $this->customerUser = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
        ]);

        $category = Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        $brand = Brand::create(['name' => 'Asus', 'slug' => 'asus']);
        $supplier = Supplier::create([
            'name' => 'Distributor Jaya',
            'contact_phone' => '0811223344',
            'email' => 'distributor@jaya.com',
            'address' => 'Jakarta'
        ]);

        $this->product = Product::create([
            'name' => 'Asus ZenBook 14 OLED',
            'sku' => 'ASUS-ZB14',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'supplier_id' => $supplier->id,
            'price_modal' => 12000000,
            'price_jual' => 15000000,
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => true,
        ]);

        ProductImage::create([
            'product_id' => $this->product->id,
            'path' => 'products/zenbook.jpg',
            'is_primary' => true,
        ]);

        $this->kecamatan = Kecamatan::create(['nama_kecamatan' => 'Metro Pusat']);
        $this->kelurahan = Kelurahan::create([
            'kecamatan_id' => $this->kecamatan->id,
            'nama_kelurahan' => 'Hadimulyo Barat',
            'tarif_grab' => 15000,
        ]);
    }

    /**
     * TEST 1: Fitur History Pembelian yang Dibatalkan
     */
    public function test_cancelled_order_remains_in_customer_history_with_all_details(): void
    {
        // 1. Buat pesanan online yang kemudian dibatalkan
        $order = Order::create([
            'invoice_number' => 'INV/20261001/0001',
            'customer_user_id' => $this->customerUser->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '08123456789',
            'status' => 'Dibatalkan',
            'payment_method' => 'Transfer BNI',
            'total_amount' => 15015000,
            'shipping_cost' => 15000,
            'kecamatan_id' => $this->kecamatan->id,
            'kelurahan_id' => $this->kelurahan->id,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'item_name' => $this->product->name,
            'price' => 15000000,
            'quantity' => 1,
            'subtotal' => 15000000,
        ]);

        // 2. Pastikan transaksi tetap tersimpan dalam database dan tidak terhapus
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'invoice_number' => 'INV/20261001/0001',
            'status' => 'Dibatalkan',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'item_name' => 'Asus ZenBook 14 OLED',
            'quantity' => 1,
        ]);

        // 3. Akses halaman riwayat pembelian pelanggan
        $response = $this->actingAs($this->customerUser)->get(route('customer.orders.index'));
        $response->assertStatus(200);

        // 4. Pastikan informasi utama transaksi tampil di history:
        // - Nomor pesanan
        $response->assertSee('INV/20261001/0001');
        // - Tanggal pembelian
        $response->assertSee($order->created_at->format('d M Y'));
        // - Nama produk
        $response->assertSee('Asus ZenBook 14 OLED');
        // - Jumlah (1x)
        $response->assertSee('1x');
        // - Harga & Subtotal
        $response->assertSee(number_format(15000000, 0, ',', '.'));
        // - Total Pembayaran
        $response->assertSee(number_format(15015000, 0, ',', '.'));
        // - Status pesanan sebagai "Dibatalkan"
        $response->assertSee('Dibatalkan');
    }

    public function test_customer_can_cancel_pending_order_and_restores_stock(): void
    {
        // 1. Simulasikan pesanan berstatus "Menunggu Konfirmasi"
        $this->product->update(['stock' => 9]);

        $order = Order::create([
            'invoice_number' => 'INV/20261001/0002',
            'customer_user_id' => $this->customerUser->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '08123456789',
            'status' => 'Menunggu Konfirmasi',
            'payment_method' => 'Transfer BNI',
            'total_amount' => 15015000,
            'shipping_cost' => 15000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'item_name' => $this->product->name,
            'price' => 15000000,
            'quantity' => 1,
            'subtotal' => 15000000,
        ]);

        // 2. Pelanggan membatalkan pesanan
        $cancelResponse = $this->actingAs($this->customerUser)->post(route('customer.orders.cancel', $order->id));
        $cancelResponse->assertRedirect();
        $cancelResponse->assertSessionHas('success');

        // 3. Status di DB berubah menjadi Dibatalkan
        $order->refresh();
        $this->assertEquals('Dibatalkan', $order->status);

        // 4. Stok produk otomatis kembali (9 + 1 = 10)
        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);

        // 5. Pesanan tetap ada di history dan tidak terhapus
        $historyResponse = $this->actingAs($this->customerUser)->get(route('customer.orders.index'));
        $historyResponse->assertSee('INV/20261001/0002');
        $historyResponse->assertSee('Dibatalkan');
    }

    /**
     * TEST 2: Laporan Pembelian dalam Format Excel (.xlsx)
     */
    public function test_excel_purchase_report_can_be_downloaded_and_contains_valid_data(): void
    {
        // 1. Buat beberapa pesanan nyata: 1 Lunas, 1 Dibatalkan
        $order1 = Order::create([
            'invoice_number' => 'INV/20261001/0003',
            'customer_user_id' => $this->customerUser->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '08123456789',
            'status' => 'Selesai',
            'payment_method' => 'Transfer BNI',
            'total_amount' => 15015000,
            'shipping_cost' => 15000,
            'created_at' => now(),
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $this->product->id,
            'item_name' => 'Asus ZenBook 14 OLED',
            'price' => 15000000,
            'quantity' => 1,
            'subtotal' => 15000000,
        ]);

        $order2 = Order::create([
            'invoice_number' => 'INV/20261001/0004',
            'customer_user_id' => $this->customerUser->id,
            'customer_name' => 'Andi Wijaya',
            'customer_phone' => '0855667788',
            'status' => 'Dibatalkan',
            'payment_method' => 'Transfer BNI',
            'total_amount' => 15015000,
            'shipping_cost' => 15000,
            'created_at' => now(),
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $this->product->id,
            'item_name' => 'Asus ZenBook 14 OLED',
            'price' => 15000000,
            'quantity' => 1,
            'subtotal' => 15000000,
        ]);

        // 2. Admin mengunduh laporan Excel bulanan
        $response = $this->actingAs($this->adminUser)->get(route('admin.reports.excel', [
            'type' => 'monthly',
            'param' => now()->format('Y-m'),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // 3. Simpan output stream ke temporary file dan verifikasi dengan PhpSpreadsheet
        $tempFile = tempnam(sys_get_temp_dir(), 'test_excel_') . '.xlsx';
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        file_put_contents($tempFile, $content);

        $this->assertFileExists($tempFile);
        $spreadsheet = IOFactory::load($tempFile);
        $sheet = $spreadsheet->getActiveSheet();

        // 4. Verifikasi header kolom pada Baris 5
        $this->assertEquals('Nomor Pesanan', $sheet->getCell('B5')->getValue());
        $this->assertEquals('Tanggal Pembelian', $sheet->getCell('C5')->getValue());
        $this->assertEquals('Nama Produk', $sheet->getCell('E5')->getValue());
        $this->assertEquals('Jumlah', $sheet->getCell('F5')->getValue());
        $this->assertEquals('Harga Produk (Rp)', $sheet->getCell('G5')->getValue());
        $this->assertEquals('Subtotal (Rp)', $sheet->getCell('H5')->getValue());
        $this->assertEquals('Total Pembayaran (Rp)', $sheet->getCell('I5')->getValue());
        $this->assertEquals('Metode Pembayaran', $sheet->getCell('J5')->getValue());
        $this->assertEquals('Status Pesanan', $sheet->getCell('K5')->getValue());

        // 5. Verifikasi bahwa data transaksi sebenarnya ada di baris-baris data
        $foundCompleted = false;
        $foundCancelled = false;

        $highestRow = $sheet->getHighestRow();
        for ($r = 6; $r <= $highestRow; $r++) {
            $inv = $sheet->getCell('B' . $r)->getValue();
            $status = $sheet->getCell('K' . $r)->getValue();
            $prodName = $sheet->getCell('E' . $r)->getValue();

            if ($inv === 'INV/20261001/0003') {
                $foundCompleted = true;
                $this->assertEquals('Asus ZenBook 14 OLED', $prodName);
                $this->assertEquals('Selesai', $status);
            }
            if ($inv === 'INV/20261001/0004') {
                $foundCancelled = true;
                $this->assertEquals('Asus ZenBook 14 OLED', $prodName);
                // Transaksi dibatalkan tetap tercantum dengan status "Dibatalkan"
                $this->assertEquals('Dibatalkan', $status);
            }
        }

        $this->assertTrue($foundCompleted, 'Transaksi selesai harus tercantum dalam file Excel');
        $this->assertTrue($foundCancelled, 'Transaksi yang dibatalkan harus tetap tercantum dengan status Dibatalkan dalam file Excel');

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }

    /**
     * TEST 3: Tampilkan Gambar Produk pada Halaman Checkout
     */
    public function test_checkout_page_displays_product_image_and_all_required_details(): void
    {
        // 1. Kunjungi halaman checkout dengan parameter product_id
        $response = $this->actingAs($this->customerUser)->get(route('checkout.index', [
            'product_id' => $this->product->id
        ]));

        $response->assertStatus(200);

        // 2. Pastikan Gambar Produk tampil (menggunakan path asset gambar storage)
        $expectedImageUrl = asset('storage/' . $this->product->primaryImage->path);
        $response->assertSee($expectedImageUrl);
        $response->assertSee('checkoutProductImage');

        // 3. Pastikan Informasi Produk lengkap tampil:
        // - Nama produk
        $response->assertSee('Asus ZenBook 14 OLED');
        // - Harga produk
        $response->assertSee(number_format(15000000, 0, ',', '.'));
        // - Jumlah (1 Unit)
        $response->assertSee('1 Unit');
        // - Subtotal produk
        $response->assertSee('Subtotal Produk');

        // 4. Pastikan form checkout dapat disubmit secara normal
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti.jpg');

        $storeResponse = $this->actingAs($this->customerUser)->post(route('checkout.store'), [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '08123456789',
            'product_id' => $this->product->id,
            'kecamatan_id' => $this->kecamatan->id,
            'kelurahan_id' => $this->kelurahan->id,
            'shareloc_link' => 'https://maps.app.goo.gl/example123',
            'payment_method' => 'Transfer BNI',
            'bukti_transfer' => $file,
        ]);

        $storeResponse->assertRedirect(route('customer.orders.index'));

        // 5. Pastikan pesanan tersimpan di database
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi Santoso',
            'total_amount' => 15015000,
            'status' => 'Menunggu Konfirmasi',
        ]);
    }

    public function test_admin_can_export_online_orders_to_excel(): void
    {
        Order::create([
            'invoice_number' => 'INV/20261001/0005',
            'customer_user_id' => $this->customerUser->id,
            'customer_name' => 'Budi Santoso',
            'status' => 'Dibatalkan',
            'payment_method' => 'Transfer BNI',
            'total_amount' => 15015000,
            'shipping_cost' => 15000,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.pesanan.excel'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Check index page has Excel button and Dibatalkan status
        $indexResponse = $this->actingAs($this->adminUser)->get(route('admin.pesanan.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Export Excel');
        $indexResponse->assertSee('Dibatalkan');
    }

    public function test_admin_can_export_transactions_to_excel(): void
    {
        Order::create([
            'invoice_number' => 'INV/20261001/0006',
            'customer_user_id' => null,
            'customer_name' => 'Walk-in Customer',
            'status' => 'Lunas',
            'payment_method' => 'Cash',
            'total_amount' => 15000000,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.transactions.excel'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $indexResponse = $this->actingAs($this->adminUser)->get(route('admin.transactions.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Export Excel');
    }

    public function test_admin_reports_page_and_preview_display_excel_download_buttons(): void
    {
        $indexResponse = $this->actingAs($this->adminUser)->get(route('admin.reports.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Download Excel (.xlsx)');

        $previewResponse = $this->actingAs($this->adminUser)->get(route('admin.reports.preview', [
            'type' => 'monthly',
            'month' => now()->format('Y-m'),
        ]));
        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('Download Excel (.xlsx)');
    }
}
