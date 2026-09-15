use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->unsignedBigInteger('kelurahan_id')->nullable();
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->text('shareloc_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name', 
                'customer_phone', 
                'bukti_transfer', 
                'payment_method', 
                'total_amount', 
                'kecamatan_id', 
                'kelurahan_id', 
                'shipping_cost', 
                'shareloc_link'
            ]);
        });
    }
};