<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerComplaintTest extends TestCase
{
    use RefreshDatabase;
    public function test_guest_cannot_access_complaint_form()
    {
        $response = $this->get(route('customer.complaints.create', 1));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_can_view_complaint_form_for_own_order()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => null,
            'customer_user_id' => $user->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '08123456789',
            'invoice_number' => 'INV-TEST-999',
            'total_amount' => 1500000,
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($user)->get(route('customer.complaints.create', $order->id));
        $response->assertStatus(200);
        $response->assertSee('Formulir Pengajuan Komplain');
        $response->assertSee('INV-TEST-999');
        $response->assertSee('Jenis Kendala / Masalah');
        $response->assertSee('tom-select');
        $response->assertSee('custom-select.js');
    }

    public function test_customer_cannot_view_complaint_form_for_other_customer_order()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = Order::create([
            'user_id' => null,
            'customer_user_id' => $user1->id,
            'customer_name' => 'User One',
            'invoice_number' => 'INV-TEST-111',
            'total_amount' => 500000,
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($user2)->get(route('customer.complaints.create', $order->id));
        $response->assertStatus(404);
    }

    public function test_complaint_submission_validation()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'customer_user_id' => $user->id,
            'customer_name' => 'User Validation',
            'invoice_number' => 'INV-VAL-001',
            'total_amount' => 250000,
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($user)->post(route('customer.complaints.store', $order->id), []);
        $response->assertSessionHasErrors(['complaint_type', 'description', 'nota_bukti', 'product_bukti']);
    }

    public function test_customer_can_submit_complaint_successfully()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $order = Order::create([
            'customer_user_id' => $user->id,
            'customer_name' => 'Ahmad Customer',
            'customer_phone' => '08987654321',
            'invoice_number' => 'INV-SUBMIT-001',
            'total_amount' => 750000,
            'status' => 'Selesai',
        ]);

        $notaFile = UploadedFile::fake()->image('nota.jpg', 600, 800)->size(500);
        $productFile = UploadedFile::fake()->image('rusak.png', 800, 800)->size(800);

        $response = $this->actingAs($user)->post(route('customer.complaints.store', $order->id), [
            'complaint_type' => 'Barang Rusak / Cacat Fisik Saat Tiba',
            'description' => 'Layar retak parah saat kardus dibuka dan unboxing pertama kali.',
            'nota_bukti' => $notaFile,
            'product_bukti' => $productFile,
        ]);

        $response->assertRedirect(route('customer.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'order_id' => $order->id,
            'customer_id' => $user->id,
            'complaint_type' => 'Barang Rusak / Cacat Fisik Saat Tiba',
            'status' => 'Pending',
        ]);

        $complaint = Complaint::where('order_id', $order->id)->first();
        $this->assertNotNull($complaint);
        $this->assertTrue(Storage::disk('public')->exists($complaint->nota_bukti));
        $this->assertTrue(Storage::disk('public')->exists($complaint->product_bukti));
    }

    public function test_admin_can_view_complaints_and_update_status()
    {
        $admin = User::factory()->create();
        $order = Order::create([
            'customer_name' => 'Pak Joko',
            'invoice_number' => 'INV-ADM-001',
            'total_amount' => 1000000,
            'status' => 'Selesai',
        ]);

        $complaint = Complaint::create([
            'order_id' => $order->id,
            'customer_id' => $admin->id,
            'customer_name' => 'Pak Joko',
            'customer_phone' => '08123456789',
            'contact' => '08123456789',
            'complaint_type' => 'Barang Rusak / Cacat Fisik Saat Tiba',
            'description' => 'Port USB longgar',
            'complaint_text' => '[Barang Rusak / Cacat Fisik Saat Tiba] Port USB longgar',
            'status' => 'Pending',
            'date' => now(),
        ]);

        // Admin view complaints page
        $response = $this->actingAs($admin)->get(route('admin.complaints.index'));
        $response->assertStatus(200);
        $response->assertSee('Pak Joko');
        $response->assertSee('Port USB longgar');

        // Admin update status to 'Diproses'
        $responseStatus = $this->actingAs($admin)->post(route('admin.complaints.status', $complaint->id), [
            'status' => 'Diproses',
        ]);
        $responseStatus->assertRedirect(route('admin.complaints.index'));
        $this->assertEquals('Diproses', $complaint->fresh()->status);
    }
}
