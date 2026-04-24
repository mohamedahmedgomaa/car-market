<?php

namespace Database\Seeders;

use App\Http\Modules\Countries\Models\Country;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Brands\Models\Brand;
use App\Http\Modules\Models\Models\Model;
use App\Http\Modules\CarFeatures\Models\CarFeature;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Countries
        $egypt = Country::updateOrCreate(
            ['id' => 1],
            ['name' => ['ar' => 'مصر', 'en' => 'Egypt']]
        );

        // 2. Cities
        $cities = [
            ['ar' => 'القاهرة', 'en' => 'Cairo'],
            ['ar' => 'الجيزة', 'en' => 'Giza'],
            ['ar' => 'الإسكندرية', 'en' => 'Alexandria'],
            ['ar' => 'المنصورة', 'en' => 'Mansoura'],
            ['ar' => 'طنطا', 'en' => 'Tanta'],
            ['ar' => 'الزقازيق', 'en' => 'Zagazig'],
            ['ar' => 'بنها', 'en' => 'Banha'],
            ['ar' => 'دمنهور', 'en' => 'Damanhour'],
            ['ar' => 'شبين الكوم', 'en' => 'Shibin El Kom'],
            ['ar' => 'أسيوط', 'en' => 'Assiut'],
            ['ar' => 'سوهاج', 'en' => 'Sohag'],
            ['ar' => 'المنيا', 'en' => 'Minya'],
            ['ar' => 'قنا', 'en' => 'Qena'],
            ['ar' => 'الأقصر', 'en' => 'Luxor'],
            ['ar' => 'أسوان', 'en' => 'Aswan'],
            ['ar' => 'السويس', 'en' => 'Suez'],
            ['ar' => 'الإسماعيلية', 'en' => 'Ismailia'],
            ['ar' => 'بور سعيد', 'en' => 'Port Said'],
            ['ar' => 'الغردقة', 'en' => 'Hurghada'],
            ['ar' => 'شرم الشيخ', 'en' => 'Sharm El Sheikh'],
        ];

        foreach ($cities as $cityData) {
            City::updateOrCreate(
                ['name->en' => $cityData['en']],
                ['name' => $cityData, 'country_id' => $egypt->id]
            );
        }

        // 3. Car Brands & Models
        $carBrands = [
            'Toyota' => ['Corolla', 'Camry', 'Yaris', 'Fortuner', 'Hilux', 'Land Cruiser'],
            'Hyundai' => ['Elantra', 'Accent', 'Tucson', 'Verna', 'Creta', 'I10', 'Bayon'],
            'Kia' => ['Cerato', 'Sportage', 'Picanto', 'Rio', 'Sorento', 'K3', 'Xceed'],
            'Fiat' => ['Tipo', '128', 'Shahin', 'Punto', '500', 'Ritmo'],
            'Nissan' => ['Sunny', 'Qashqai', 'Sentra', 'Patrol', 'Juke'],
            'Mitsubishi' => ['Lancer', 'Pajero', 'Eclipse', 'Attrage', 'Mirage'],
            'Chevrolet' => ['Optra', 'Aveo', 'Cruze', 'Captiva', 'T-Series'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'CLA', 'GLC'],
            'BMW' => ['3 Series', '5 Series', '7 Series', 'X1', 'X3', 'X5', 'X6'],
            'Renault' => ['Logan', 'Duster', 'Megane', 'Kadjar', 'Sandero', 'Stepway'],
            'Skoda' => ['Octavia', 'Superb', 'Kodiaq', 'Karoq', 'Fabia'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Jetta', 'Polo'],
            'MG' => ['MG5', 'MG6', 'ZS', 'RX5', 'HS'],
            'Chery' => ['Tiggo', 'Arrizo', 'Envy'],
            'Suzuki' => ['Swift', 'Dzire', 'Ciaz', 'Ertiga', 'Vitara', 'Jimny'],
            'Peugeot' => ['301', '508', '2008', '3008', '5008'],
            'Opel' => ['Astra', 'Insignia', 'Corsa', 'Grandland', 'Crossland'],
            'Mazda' => ['Mazda 3', 'Mazda 6', 'CX-5'],
            'Honda' => ['Civic', 'City', 'CR-V', 'HR-V'],
            'Subaru' => ['Impreza', 'XV', 'Forester'],
            'Jeep' => ['Grand Cherokee', 'Renegade', 'Wrangler', 'Compass'],
            'Lada' => ['Granta', 'Niva'],
        ];

        foreach ($carBrands as $brandName => $models) {
            $brand = Brand::updateOrCreate(
                ['name->en' => $brandName],
                ['name' => ['en' => $brandName, 'ar' => $brandName], 'type' => 'car']
            );

            foreach ($models as $modelName) {
                Model::updateOrCreate(
                    ['name->en' => $modelName, 'brand_id' => $brand->id],
                    ['name' => ['en' => $modelName, 'ar' => $modelName], 'type' => 'car']
                );
            }
        }

        // 4. Motorcycle Brands & Models
        $motorcycleBrands = [
            'Benelli' => ['TNT 150', 'VLM 200', 'Leoncino', 'TRK 502', 'VLR 150', 'Zenzero'],
            'Bajaj' => ['Pulsar 180', 'Pulsar 200', 'Boxer BM 150', 'Discover', 'Avenger'],
            'Honda' => ['CBR 600', 'CBR 1000', 'Hornet', 'Shadow', 'Steed', 'Gold Wing', 'VLR'],
            'Yamaha' => ['R1', 'R6', 'MT-07', 'MT-09', 'TMAX', 'XMAX', 'Cruiser'],
            'Kawasaki' => ['Ninja', 'Z1000', 'Z800', 'Vulcan'],
            'Suzuki' => ['Hayabusa', 'GSX-R', 'Boulevard', 'Gixxer'],
            'Dayun' => ['Dayun 4', 'Dayun 6', 'Dayun 10', 'Dayun 40', 'Dayun 2B'],
            'Haujue' => ['KA 150', 'HJ 150', 'HJ 150-9'],
            'Keeway' => ['Superlight', 'RKS', 'RKV'],
            'TVS' => ['Apache RTR', 'HLX 150', 'King'],
            'Halawa' => ['Tiger', 'Express', 'Halawa 4'],
            'Zongshen' => ['ZS 150', 'ZS 200'],
            'Haojin' => ['Haojin 150', 'Haojin 200'],
        ];

        foreach ($motorcycleBrands as $brandName => $models) {
            $brand = Brand::updateOrCreate(
                ['name->en' => $brandName],
                ['name' => ['en' => $brandName, 'ar' => $brandName], 'type' => 'motorcycle']
            );

            foreach ($models as $modelName) {
                Model::updateOrCreate(
                    ['name->en' => $modelName, 'brand_id' => $brand->id],
                    ['name' => ['en' => $modelName, 'ar' => $modelName], 'type' => 'motorcycle']
                );
            }
        }

        // 5. Car Features
        $features = [
            ['ar' => 'تكييف', 'en' => 'Air Conditioning'],
            ['ar' => 'نوافذ كهربائية', 'en' => 'Power Windows'],
            ['ar' => 'باور ستيرنج', 'en' => 'Power Steering'],
            ['ar' => 'فتحة سقف', 'en' => 'Sunroof'],
            ['ar' => 'مقاعد جلد', 'en' => 'Leather Seats'],
            ['ar' => 'نظام فرامل ABS', 'en' => 'ABS'],
            ['ar' => 'وسائد هوائية', 'en' => 'Airbags'],
            ['ar' => 'شاشة تعمل باللمس', 'en' => 'Touch Screen'],
            ['ar' => 'كاميرا خلفية', 'en' => 'Rear Camera'],
            ['ar' => 'حساسات ركن', 'en' => 'Parking Sensors'],
            ['ar' => 'مثبت سرعة', 'en' => 'Cruise Control'],
            ['ar' => 'جنوط سبور', 'en' => 'Alloy Wheels'],
            ['ar' => 'بلوتوث', 'en' => 'Bluetooth'],
            ['ar' => 'دخول بدون مفتاح', 'en' => 'Keyless Entry'],
            ['ar' => 'فوانيس شبورة', 'en' => 'Fog Lights'],
            ['ar' => 'نظام ملاحة', 'en' => 'Navigation System'],
            ['ar' => 'مرايا كهربائية', 'en' => 'Electric Mirrors'],
            ['ar' => 'سنتر لوك', 'en' => 'Central Lock'],
            ['ar' => 'إنذار', 'en' => 'Alarm'],
        ];

        foreach ($features as $featureData) {
            CarFeature::updateOrCreate(
                ['name->en' => $featureData['en']],
                ['name' => $featureData]
            );
        }
    }
}
