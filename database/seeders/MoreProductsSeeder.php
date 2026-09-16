<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MoreProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier = Supplier::first() ?? Supplier::create([
            'name' => 'PT. Bintang Distribusi Nusantara',
            'contact_phone' => '021-5556789',
            'email' => 'sales@bintangdistribusindo.co.id',
            'address' => 'Kawasan Industri Jababeka Blok C-12, Cikarang, Bekasi',
        ]);

        $items = [
            [
                'name' => 'Asus Vivobook 14 A1404ZA Intel Core i3 8GB/512GB SSD',
                'sku' => 'LAP-ASU-V14',
                'barcode' => '4711081992011',
                'category' => 'Laptops',
                'brand' => 'Asus',
                'price_modal' => 6100000.00,
                'price_jual' => 6899000.00,
                'stock' => 8,
                'min_stock' => 2,
                'description' => 'Laptop harian andal Asus Vivobook 14 A1404ZA dengan layar NanoEdge Full HD, prosesor Intel Core i3 generasi ke-12, RAM 8GB DDR4, dan penyimpanan cepat 512GB M.2 NVMe PCIe SSD.',
                'specs' => "Processor: Intel Core i3-1215U (6 Cores, 8 Threads up to 4.4GHz)\nRAM: 8GB DDR4 3200MHz\nStorage: 512GB M.2 NVMe PCIe 3.0 SSD\nDisplay: 14.0 inch FHD (1920 x 1080) Anti-glare\nOS: Windows 11 Home + OHS Pre-installed\nWeight: 1.4 kg",
            ],
            [
                'name' => 'Lenovo IdeaPad Slim 3 14IAU7 Intel Core i5 16GB/512GB SSD',
                'sku' => 'LAP-LNV-IP3',
                'barcode' => '196802318991',
                'category' => 'Laptops',
                'brand' => 'Lenovo',
                'price_modal' => 8000000.00,
                'price_jual' => 8999000.00,
                'stock' => 6,
                'min_stock' => 2,
                'description' => 'Laptop tipis dan ringan Lenovo IdeaPad Slim 3 14IAU7 ditenagai prosesor Intel Core i5 generasi ke-12 dengan RAM besar 16GB, cocok untuk multitasking kantor dan mahasiswa teknik.',
                'specs' => "Processor: Intel Core i5-1235U (10 Cores, 12 Threads up to 4.4GHz)\nRAM: 16GB DDR4 3200MHz Dual Channel\nStorage: 512GB SSD M.2 PCIe 4.0 NVMe\nDisplay: 14 inch FHD (1920x1080) IPS 300nits\nBattery: Up to 7 hours, Rapid Charge Boost\nColor: Arctic Grey",
            ],
            [
                'name' => 'Kingston NV2 SSD M.2 NVMe PCIe 4.0 1TB',
                'sku' => 'SSD-KNG-NV2-1TB',
                'barcode' => '740617329910',
                'category' => 'Hardware',
                'brand' => 'Kingston',
                'price_modal' => 820000.00,
                'price_jual' => 980000.00,
                'stock' => 25,
                'min_stock' => 5,
                'description' => 'Solid State Drive (SSD) Kingston NV2 1TB PCIe 4.0 NVMe berkecepatan tinggi hingga 3.500 MB/s read. Solusi upgrade penyimpanan terbaik untuk PC desktop dan laptop modern.',
                'specs' => "Form Factor: M.2 2280\nInterface: PCIe 4.0 x4 NVMe\nRead Speed: Up to 3.500 MB/s\nWrite Speed: Up to 2.100 MB/s\nCapacity: 1TB (1000GB)\nWarranty: Garansi Resmi 3 Tahun",
            ],
            [
                'name' => 'Logitech MK270 Wireless Keyboard & Mouse Combo',
                'sku' => 'ACC-LOG-MK270',
                'barcode' => '097855088925',
                'category' => 'Accessories',
                'brand' => 'Logitech',
                'price_modal' => 285000.00,
                'price_jual' => 349000.00,
                'stock' => 30,
                'min_stock' => 5,
                'description' => 'Paket keyboard dan mouse wireless Logitech MK270 dengan teknologi koneksi nirkabel 2.4 GHz bebas hambatan jangkauan hingga 10 meter serta baterai tahan lama.',
                'specs' => "Connectivity: 2.4 GHz USB Nano Receiver\nKeyboard Battery: 2x AAA (Up to 36 months)\nMouse Battery: 1x AA (Up to 12 months)\nLayout: Full-size keyboard with numpad & 8 hotkeys\nSpill-resistant design",
            ],
            [
                'name' => 'SanDisk Ultra Flair USB 3.0 Flash Drive 64GB',
                'sku' => 'ACC-SND-FLR-64',
                'barcode' => '619659136727',
                'category' => 'Accessories',
                'brand' => 'SanDisk',
                'price_modal' => 72000.00,
                'price_jual' => 95000.00,
                'stock' => 50,
                'min_stock' => 10,
                'description' => 'Flashdisk USB 3.0 SanDisk Ultra Flair 64GB dengan casing logam elegan berdaya tahan tinggi. Kecepatan transfer data hingga 150 MB/s untuk transfer film dan dokumen cepat.',
                'specs' => "Kapasitas: 64GB\nInterface: USB 3.0 (kompatibel USB 2.0)\nKecepatan Baca: Hingga 150 MB/s\nMaterial: Full Metal Casing\nKeamanan: Sandisk SecureAccess Software 128-bit AES",
            ],
            [
                'name' => 'TP-Link Archer C6 AC1200 Wireless Dual Band Gigabit Router',
                'sku' => 'NET-TPL-AC1200',
                'barcode' => '6935364084325',
                'category' => 'Networking',
                'brand' => 'TP-Link',
                'price_modal' => 380000.00,
                'price_jual' => 465000.00,
                'stock' => 14,
                'min_stock' => 3,
                'description' => 'Router Wi-Fi dual band AC1200 TP-Link Archer C6 dengan 4 antena eksternal berkekuatan tinggi dan 1 antena internal. Dilengkapi 4 port Full Gigabit Ethernet.',
                'specs' => "Wi-Fi Speed: 867 Mbps pada 5GHz + 300 Mbps pada 2.4GHz\nPort: 1x Gigabit WAN, 4x Gigabit LAN\nAntenna: 4x Antena Eksternal High-Gain\nTeknologi: MU-MIMO, Beamforming, WPA3 security\nMode: Router Mode & Access Point Mode",
            ],
            [
                'name' => 'Xiaomi Redmi Desktop Monitor 24" 100Hz IPS Full HD',
                'sku' => 'MON-XMI-R24',
                'barcode' => '6941812745129',
                'category' => 'Accessories',
                'brand' => 'Xiaomi',
                'price_modal' => 1050000.00,
                'price_jual' => 1299000.00,
                'stock' => 9,
                'min_stock' => 2,
                'description' => 'Monitor komputer Xiaomi Redmi 24 inch dengan panel IPS Full HD ber-refresh rate 100Hz mulus. Bezel tipis ultra-slim 3 sisi cocok untuk dual monitor setup.',
                'specs' => "Ukuran: 23.8 inch\nResolusi: 1920 x 1080 (FHD)\nPanel: IPS wide viewing angle 178°\nRefresh Rate: 100Hz\nResponse Time: 6ms (GTG)\nPort: HDMI 1.4, VGA port\nTÜV Low Blue Light certified",
            ],
            [
                'name' => 'Corsair Vengeance LPX 16GB (2x8GB) DDR4 3200MHz',
                'sku' => 'RAM-COR-16GB',
                'barcode' => '843597070189',
                'category' => 'Hardware',
                'brand' => 'Corsair',
                'price_modal' => 550000.00,
                'price_jual' => 685000.00,
                'stock' => 18,
                'min_stock' => 4,
                'description' => 'Kit memori RAM Corsair Vengeance LPX 16GB (2x8GB) DDR4 3200MHz C16 dengan heatspreader aluminium murni untuk pembuangan panas optimal dan overclocking stabil.',
                'specs' => "Kapasitas: 16GB (2x 8GB Kit)\nKecepatan: 3200MHz (PC4-25600)\nLatensi: CL16 (16-20-20-38)\nVoltase: 1.35V\nProfile: Intel XMP 2.0 Support\nForm Factor: 288-pin DIMM Desktop",
            ],
            [
                'name' => 'Epson EcoTank L3210 All-in-One Ink Tank Printer',
                'sku' => 'PRN-EPS-L3210',
                'barcode' => '8885007038101',
                'category' => 'Printers',
                'brand' => 'Epson',
                'price_modal' => 2150000.00,
                'price_jual' => 2450000.00,
                'stock' => 7,
                'min_stock' => 2,
                'description' => 'Printer serbaguna Epson EcoTank L3210 multifungsi (Print, Scan, Copy) dengan sistem tangki tinta hemat biaya. Satu botol tinta mampu mencetak hingga 4.500 halaman hitam dan 7.500 halaman warna.',
                'specs' => "Fungsi: Print, Scan, Copy\nKecepatan Cetak: Hingga 10.0 ipm (Hitam) / 5.0 ipm (Warna)\nResolusi Cetak: 5760 x 1440 dpi\nScanner: Flatbed color image scanner (600 x 1200 dpi)\nTinta: Epson 003 Series (Black, Cyan, Magenta, Yellow)\nKonektivitas: USB 2.0 High Speed",
            ],
            [
                'name' => 'Logitech C922 Pro Stream Webcam Full HD 1080p',
                'sku' => 'ACC-LOG-C922',
                'barcode' => '097855124296',
                'category' => 'Accessories',
                'brand' => 'Logitech',
                'price_modal' => 1200000.00,
                'price_jual' => 1450000.00,
                'stock' => 11,
                'min_stock' => 2,
                'description' => 'Kamera webcam Logitech C922 Pro Streaming resolusi tajam Full HD 1080p 30fps atau 720p 60fps dengan autofokus cepat dan koreksi pencahayaan otomatis.',
                'specs' => "Resolusi Video: 1080p/30fps atau 720p/60fps\nLensa: Kaca Full HD dengan sudut pandang 78° diagonal\nMikrofon: Dual stereo mic omnidirectional terintegrasi\nKoneksi: USB Type-A Plug and Play\nTermasuk: Tripod meja fleksibel & lisensi XSplit 3 bulan",
            ],
        ];

        foreach ($items as $item) {
            $cat = Category::firstOrCreate(
                ['name' => $item['category']],
                ['slug' => Str::slug($item['category'])]
            );

            $brand = Brand::firstOrCreate(
                ['name' => $item['brand']],
                ['slug' => Str::slug($item['brand'])]
            );

            $product = Product::firstOrCreate(
                ['sku' => $item['sku']],
                [
                    'name' => $item['name'],
                    'barcode' => $item['barcode'],
                    'category_id' => $cat->id,
                    'brand_id' => $brand->id,
                    'supplier_id' => $supplier->id,
                    'price_modal' => $item['price_modal'],
                    'price_jual' => $item['price_jual'],
                    'stock' => $item['stock'],
                    'min_stock' => $item['min_stock'],
                    'description' => $item['description'],
                    'specs' => $item['specs'],
                    'is_active' => true,
                ]
            );

            // Record stock history if newly created
            if ($product->wasRecentlyCreated) {
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $product->stock,
                    'reference' => 'Initial Stock Import',
                    'notes' => 'Stok awal produk katalog CV Bintang Jaya Komputer',
                ]);
            }
        }
    }
}
