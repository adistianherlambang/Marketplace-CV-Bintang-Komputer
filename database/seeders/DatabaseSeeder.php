<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ReturnLog;
use App\Models\StockHistory;
use App\Models\Supplier;
use App\Models\User;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::create([
            'name' => 'Krisna Irawan',
            'email' => 'admin@bintangkomputer.com',
            'password' => Hash::make('password'),
        ]);

        // Demo Customer / User
        $user = User::create([
            'name' => 'Pelanggan Demo',
            'email' => 'user@bintangkomputer.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Categories
        $categories = [
            'Laptops' => 'laptops',
            'Smartphones' => 'smartphones',
            'Headphones' => 'headphones',
            'Accessories' => 'accessories',
            'Smart Home' => 'smart-home',
        ];
        $catModels = [];
        foreach ($categories as $name => $slug) {
            $catModels[$name] = Category::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        // 3. Brands
        $brands = [
            'Apple' => 'apple',
            'Asus' => 'asus',
            'Samsung' => 'samsung',
            'Sony' => 'sony',
            'Logitech' => 'logitech',
            'Google' => 'google',
        ];
        $brandModels = [];
        foreach ($brands as $name => $slug) {
            $brandModels[$name] = Brand::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        // 4. Suppliers
        $suppliers = [
            [
                'name' => 'PT. Bintang Distribusi Nusantara',
                'contact_phone' => '021-5556789',
                'email' => 'sales@bintangdistribusindo.co.id',
                'address' => 'Kawasan Industri Jababeka Blok C-12, Cikarang, Bekasi',
            ],
            [
                'name' => 'CV. Global Gadget Lampung',
                'contact_phone' => '0721-789012',
                'email' => 'globalgadgetlampung@gmail.com',
                'address' => 'Jl. Raden Intan No. 45, Bandar Lampung',
            ],
        ];
        $supplierModels = [];
        foreach ($suppliers as $sup) {
            $supplierModels[] = Supplier::create($sup);
        }

        // 5. Customers
        $customers = [
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'budi.santoso@gmail.com',
                'address' => 'Jl. Ki Hajar Dewantara No. 12, Iringmulyo, Metro Timur',
            ],
            [
                'name' => 'Siti Rahmawati',
                'phone' => '085712345678',
                'email' => 'siti.rahma@yahoo.com',
                'address' => 'Jl. Ahmad Yani No. 56, Yosodadi, Metro Timur',
            ],
            [
                'name' => 'Joko Susilo',
                'phone' => '08991234567',
                'email' => 'joko.susilo@hotmail.com',
                'address' => 'Jl. Jendral Sudirman No. 89, Metro Pusat',
            ],
        ];
        $customerModels = [];
        foreach ($customers as $cust) {
            $customerModels[] = Customer::create($cust);
        }

        // 6. Products (from Figma catalog image references)
        $productsData = [
            [
                'name' => 'Samsung Odyssey G7 48" 4K Gaming Monitor',
                'sku' => 'MON-SAM-ODG7-48',
                'barcode' => '8806094765341',
                'category' => 'Accessories',
                'brand' => 'Samsung',
                'price_modal' => 14500000.00,
                'price_jual' => 16500000.00,
                'stock' => 8,
                'min_stock' => 2,
                'description' => 'Monitor gaming Samsung Odyssey G7 48 inch resolusi 4K dengan refresh rate 144Hz. Layar OLED premium, HDR10+, response time 0.1ms ideal untuk professional gaming dan multimedia workstation.',
                'specs' => 'Ukuran Layar: 48 inch\nResolusi: 4K UHD (3840 x 2160)\nPanel: OLED\nRefresh Rate: 144Hz\nResponse Time: 0.1ms\nHDR: HDR10+\nPort: HDMI 2.1, DisplayPort 1.4',
            ],
            [
                'name' => 'Apple AirPods 3rd Generation Wireless Earbuds',
                'sku' => 'AUD-APL-AP3',
                'barcode' => '194252818512',
                'category' => 'Headphones',
                'brand' => 'Apple',
                'price_modal' => 2100000.00,
                'price_jual' => 2490000.00,
                'stock' => 15,
                'min_stock' => 4,
                'description' => 'Earbuds Apple AirPods generasi ke-3 dengan Spatial Audio dinamis, tahan air (IPX4), dan daya tahan baterai hingga 30 jam dengan casing pengisian daya MagSafe.',
                'specs' => 'Audio: Spatial Audio, Adaptive EQ\nSensors: Dual beamforming mics, Force sensor\nBattery Life: Up to 6 hours (30 hours total with case)\nWater Resistance: IPX4\nCharging: Lightning or MagSafe Wireless',
            ],
            [
                'name' => 'Sony WH-1000XM4 Noise Cancelling Headphones',
                'sku' => 'AUD-SNY-XM4',
                'barcode' => '4548736112100',
                'category' => 'Headphones',
                'brand' => 'Sony',
                'price_modal' => 3700000.00,
                'price_jual' => 4290000.00,
                'stock' => 12,
                'min_stock' => 3,
                'description' => 'Headphones wireless Sony WH-1000XM4 dengan Active Noise Cancelling (ANC) terbaik kelas industri, daya tahan baterai 30 jam, dan sensor pintar Speak-to-Chat.',
                'specs' => 'Driver: 40mm Dome Type\nNoise Cancelling: HD Noise Cancelling Processor QN1\nBluetooth: v5.0, LDAC codec support\nBattery Life: Up to 30 hours\nCharging: USB Type-C Quick Charge',
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra 5G (12GB/256GB)',
                'sku' => 'MBL-SAM-S23U-256',
                'barcode' => '8806094729112',
                'category' => 'Smartphones',
                'brand' => 'Samsung',
                'price_modal' => 15200000.00,
                'price_jual' => 17490000.00,
                'stock' => 6,
                'min_stock' => 2,
                'description' => 'Flagship smartphone Samsung Galaxy S23 Ultra 5G dengan kamera utama 200MP, optical zoom 100x, prosesor Snapdragon 8 Gen 2, dan S-Pen bawaan terintegrasi.',
                'specs' => 'Screen: 6.8 inch Dynamic AMOLED 2X 120Hz\nCPU: Snapdragon 8 Gen 2 for Galaxy\nRAM: 12GB\nStorage: 256GB\nRear Camera: 200MP + 10MP + 10MP + 12MP\nBattery: 5000mAh (45W fast charge)',
            ],
            [
                'name' => 'Asus ROG Strix G16 Gaming Laptop',
                'sku' => 'LAP-ASU-ROG16',
                'barcode' => '4711081926889',
                'category' => 'Laptops',
                'brand' => 'Asus',
                'price_modal' => 22000000.00,
                'price_jual' => 24999000.00,
                'stock' => 4,
                'min_stock' => 1,
                'description' => 'Laptop gaming bertenaga Asus ROG Strix G16 dengan Intel Core i7 generasi ke-13, kartu grafis NVIDIA GeForce RTX 4060 8GB, RAM 16GB, dan penyimpanan 1TB SSD.',
                'specs' => 'Processor: Intel Core i7-13650HX\nGraphics: NVIDIA GeForce RTX 4060 8GB GDDR6\nMemory: 16GB DDR5\nStorage: 1TB PCIe 4.0 NVMe SSD\nDisplay: 16 inch FHD+ 165Hz IPS-Level',
            ],
            [
                'name' => 'Logitech G Pro Wireless Gaming Mouse',
                'sku' => 'ACC-LOG-GPW',
                'barcode' => '097855139443',
                'category' => 'Accessories',
                'brand' => 'Logitech',
                'price_modal' => 1350000.00,
                'price_jual' => 1589000.00,
                'stock' => 20,
                'min_stock' => 5,
                'description' => 'Mouse gaming wireless Logitech G Pro super ringan (80 gram) dengan sensor HERO 25K yang sangat presisi hingga 25.600 DPI, konektivitas Lightspeed tanpa delay.',
                'specs' => 'Sensor: HERO 25K\nDPI: 100 - 25.600 DPI\nWeight: 80g\nConnectivity: LIGHTSPEED Wireless\nButtons: 4-8 programmable buttons',
            ],
            [
                'name' => 'Google Nest Hub Smart Display 2nd Gen',
                'sku' => 'SMT-GGL-NEST2',
                'barcode' => '193575007421',
                'category' => 'Smart Home',
                'brand' => 'Google',
                'price_modal' => 1050000.00,
                'price_jual' => 1250000.00,
                'stock' => 10,
                'min_stock' => 3,
                'description' => 'Asisten rumah pintar Google Nest Hub generasi ke-2 dengan layar sentuh 7 inch, speaker terintegrasi, kontrol suara Google Assistant, dan sensor pemantau tidur.',
                'specs' => 'Display: 7 inch touchscreen (1024x600)\nSpeaker: Full-range speaker with 3 mics\nConnectivity: Wi-Fi 5, Bluetooth v5.0\nVoice Assistant: Google Assistant built-in\nFeatures: Sleep Sensing, Gesture Control',
            ],
        ];

        $stockService = new StockService();

        foreach ($productsData as $pData) {
            $product = Product::create([
                'name' => $pData['name'],
                'sku' => $pData['sku'],
                'barcode' => $pData['barcode'],
                'category_id' => $catModels[$pData['category']]->id,
                'brand_id' => $brandModels[$pData['brand']]->id,
                'supplier_id' => $supplierModels[0]->id,
                'price_modal' => $pData['price_modal'],
                'price_jual' => $pData['price_jual'],
                'stock' => $pData['stock'],
                'min_stock' => $pData['min_stock'],
                'description' => $pData['description'],
                'specs' => $pData['specs'],
                'is_active' => true,
            ]);

            // Add stock history entries
            StockHistory::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $product->stock,
                'user_id' => $admin->id,
                'date' => now()->subDays(60),
                'description' => 'Initial inventory load from supplier ' . $supplierModels[0]->name,
            ]);
        }

        // 7. Additional Products and Metro Districts
        $this->call([
            MoreProductsSeeder::class,
            WilayahMetroSeeder::class,
        ]);
    }
}
