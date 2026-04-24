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
            ['ar' => 'مرسى مطروح', 'en' => 'Marsa Matrouh'],
            ['ar' => 'الفيوم', 'en' => 'Fayoum'],
            ['ar' => 'بني سويف', 'en' => 'Beni Suef'],
            ['ar' => 'كفر الشيخ', 'en' => 'Kafr El Sheikh'],
            ['ar' => 'دمياط', 'en' => 'Damietta'],
        ];

        foreach ($cities as $cityData) {
            City::updateOrCreate(
                ['name->en' => $cityData['en']],
                ['name' => $cityData, 'country_id' => $egypt->id]
            );
        }

        // 3. Car Brands & Models (Comprehensive List with Chinese Brands)
        $carBrands = [
            "Alfa Romeo" => ["Giulia", "Stelvio", "4C", "8C", "Spider", "GTV", "Giulia Quadrifoglio"],
            "Aston Martin" => ["DB11", "Vantage", "DBX", "DB9", "Rapide", "Valkyrie", "Vanquish"],
            "Audi" => ["A3", "A4", "A6", "Q3", "Q5", "Q7", "A5", "A8", "Q8", "R8", "TT", "RS6", "RS7"],
            "Bentley" => ["Continental", "Flying Spur", "Bentayga", "Mulsanne", "Arnage", "Azure", "Continental GT"],
            "BMW" => ["3 Series", "5 Series", "7 Series", "X1", "X3", "X5", "X6", "X7", "i3", "i8", "Z4", "M3", "M4", "M5"],
            "Buick" => ["Enclave", "Encore", "Envision", "LaCrosse", "Regal", "Verano", "Grand National"],
            "Cadillac" => ["ATS", "CTS", "XT5", "Escalade", "XT4", "XT6", "XTS", "SRX", "CTS-V"],
            "Chevrolet" => ["Spark", "Malibu", "Impala", "Equinox", "Tahoe", "Silverado", "Camaro", "Corvette", "Traverse", "Suburban", "Trailblazer", "SS", "Optra", "Aveo", "Lanos"],
            "Chrysler" => ["200", "300", "Pacifica", "Aspen", "Voyager", "Sebring", "PT Cruiser", "300 SRT"],
            "Citroen" => ["C3", "C4", "C5 Aircross", "C1", "C5", "C6", "DS4", "C4 VTS", "C-Elysee"],
            "Dodge" => ["Challenger", "Charger", "Durango", "Journey", "Grand Caravan", "Ram", "Viper", "Dart", "Avenger", "Magnum", "Hellcat"],
            "DS Automobiles" => ["DS 3", "DS 7", "DS 9", "DS 4", "DS 5", "DS 3 Performance"],
            "Ferrari" => ["488", "F8", "Portofino", "Roma", "812", "SF90", "GTC4Lusso", "LaFerrari", "Monza"],
            "Fiat" => ["500", "500X", "Panda", "Tipo", "124 Spider", "Bravo", "Punto", "Abarth 595", "128", "Shahin"],
            "Ford" => ["Fiesta", "Focus", "Mustang", "Explorer", "F-150", "Ranger", "Fusion", "Escape", "Edge", "Expedition", "Bronco", "Maverick", "EcoSport", "GT"],
            "Genesis" => ["G70", "G80", "G90", "GV70", "GV80", "Essentia"],
            "GMC" => ["Acadia", "Terrain", "Yukon", "Sierra", "Canyon", "Savanna", "Syclone"],
            "Honda" => ["Civic", "Accord", "Fit", "CR-V", "Pilot", "Ridgeline", "HR-V", "Odyssey", "Passport", "Civic Type R", "NSX"],
            "Hummer" => ["H1", "H2", "H3", "EV"],
            "Hyundai" => ["Elantra", "Sonata", "Tucson", "Santa Fe", "Palisade", "Veloster", "Kona", "Ioniq", "Venue", "Veloster N", "Accent", "Verna", "I10", "Bayon", "Creta"],
            "Infiniti" => ["Q50", "Q60", "Q70", "QX50", "QX60", "QX80", "Q30", "QX30", "Q60 Red Sport 400"],
            "Isuzu" => ["D-Max", "MU-X", "Trooper", "Rodeo"],
            "Jaguar" => ["XE", "XF", "XJ", "F-Pace", "E-Pace", "I-Pace", "F-Type", "XK"],
            "Jeep" => ["Wrangler", "Cherokee", "Grand Cherokee", "Compass", "Renegade", "Gladiator", "Patriot", "Commander", "Liberty", "Grand Cherokee SRT"],
            "Kia" => ["Rio", "Optima", "Sportage", "Sorento", "Telluride", "Stinger", "Forte", "Soul", "Seltos", "K5", "Carnival", "Cerato", "Picanto", "Xceed"],
            "Lamborghini" => ["Huracan", "Aventador", "Urus", "Gallardo", "Murcielago", "Diablo", "Countach"],
            "Lancia" => ["Ypsilon", "Delta", "Thema", "Stratos"],
            "Land Rover" => ["Range Rover", "Range Rover Sport", "Discovery", "Defender", "Range Rover Velar", "Range Rover Evoque", "SVR"],
            "Lexus" => ["IS", "ES", "GS", "RX", "NX", "LX", "UX", "GX", "RC", "LS", "LFA"],
            "Lincoln" => ["MKC", "MKZ", "Navigator", "Corsair", "Aviator", "Continental", "MKX", "MKT"],
            "Maserati" => ["Ghibli", "Levante", "Quattroporte", "GranTurismo", "GranCabrio", "MC20"],
            "Mazda" => ["Mazda3", "Mazda6", "CX-3", "CX-5", "CX-9", "MX-5", "CX-30", "RX-7", "RX-8"],
            "Mercedes" => ["A-Class", "C-Class", "E-Class", "S-Class", "GLA", "GLE", "GLC", "CLS", "G-Class", "SL-Class", "GLS", "AMG GT", "AMG C63"],
            "Mini" => ["Cooper", "Countryman", "Clubman", "Paceman", "John Cooper Works"],
            "Mitsubishi" => ["Mirage", "Lancer", "Outlander", "Pajero", "Eclipse Cross", "ASX", "L200", "Lancer Evolution", "Xpander"],
            "Nissan" => ["Altima", "Sentra", "Maxima", "Rogue", "Murano", "Pathfinder", "370Z", "GT-R", "Juke", "Kicks", "Versa", "Nismo", "Sunny", "Qashqai"],
            "Peugeot" => ["208", "308", "508", "2008", "3008", "5008", "207", "307", "407", "RCZ", "301"],
            "Porsche" => ["911", "Cayenne", "Macan", "Panamera", "Taycan", "718 Boxster", "718 Cayman"],
            "Renault" => ["Clio", "Megane", "Kadjar", "Koleos", "Talisman", "Captur", "Duster", "Megane RS", "Logan", "Sandero", "Stepway"],
            "Rolls-Royce" => ["Ghost", "Wraith", "Dawn", "Phantom", "Cullinan", "Silver Shadow", "Silver Spur"],
            "Saab" => ["9-3", "9-5", "9-4X", "900"],
            "Scion" => ["tC", "FR-S", "iQ", "xB", "xD"],
            "Seat" => ["Ibiza", "Leon", "Ateca", "Arona", "Tarraco", "Cupra"],
            "Skoda" => ["Octavia", "Superb", "Kodiaq", "Kamiq", "Karoq", "Scala", "RS", "Fabia"],
            "Smart" => ["Fortwo", "Forfour", "Roadster"],
            "SsangYong" => ["Tivoli", "Korando", "Rexton", "Musso", "Actyon"],
            "Subaru" => ["Impreza", "Legacy", "Outback", "Forester", "Crosstrek", "Ascent", "BRZ", "WRX", "STI", "XV"],
            "Suzuki" => ["Swift", "Vitara", "Jimny", "Celerio", "Baleno", "Ignis", "S-Cross", "Swift Sport", "Dzire", "Ertiga", "Ciaz"],
            "Tata" => ["Tiago", "Nexon", "Harrier", "Safari", "Hexa", "Altroz"],
            "Tesla" => ["Model S", "Model 3", "Model X", "Model Y", "Roadster", "Cybertruck"],
            "Toyota" => ["Corolla", "Camry", "Prius", "Yaris", "Highlander", "Land Cruiser", "Supra", "4Runner", "RAV4", "Tacoma", "Tundra", "C-HR", "86", "GR Yaris", "Fortuner", "Hilux", "Belta", "Rumion"],
            "Volkswagen" => ["Golf", "Passat", "Jetta", "Tiguan", "Atlas", "Arteon", "Beetle", "Touareg", "Polo", "ID.4", "Golf GTI", "Golf R"],
            "Volvo" => ["S60", "S90", "V60", "XC40", "XC60", "XC90", "V90", "S40", "Polestar 1", "Polestar 2"],
            "BYD" => ["F3", "Song", "Qin", "Han", "Tang", "Atto 3"],
            "Jetour" => ["X70", "X70 Plus", "X90 Plus", "Dashing"],
            "Changan" => ["Alsvin", "Eado", "CS15", "CS35 Plus", "CS55 Plus", "CS75 Plus", "Uni-K", "Uni-T"],
            "Haval" => ["Jolion", "H6", "Dargo"],
            "MG" => ["MG 5", "MG 6", "ZS", "RX5", "HS", "MG 4"],
            "Jac" => ["JS3", "JS4", "J7", "S3", "S4"],
            "GAC" => ["GS3", "GS4", "GA4", "Emkoo", "Empow"],
            "Chery" => ["Tiggo 3", "Tiggo 7", "Tiggo 8", "Arrizo 5", "Envy"],
            "Baic" => ["X3", "X5", "X7"],
            "Exeed" => ["LX", "TXL", "VX"],
            "Hongqi" => ["H5", "HS5", "E-HS9"],
            "Bestune" => ["T33", "T77", "T99"],
            "Forthing" => ["T5 Evo"],
            "Soueast" => ["DX3", "DX5", "DX7"],
            "DFSK" => ["Glory 580", "Eagle Pro", "Glory 330"],
            "Lada" => ["Granta", "Niva"],
            "Brilliance" => ["V3", "V5", "V6", "V7"],
            "Bugatti" => ["Chiron", "Veyron", "Divo"],
            "McLaren" => ["720S", "570S", "P1"],
            "Lotus" => ["Evora", "Elise", "Emira"],
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
            "Harley-Davidson" => ["Sportster", "Softail", "Touring", "Street", "LiveWire"],
            "Honda" => ["CBR", "CB", "CRF", "Gold Wing", "Rebel", "Hornet", "Steed", "Shadow"],
            "Yamaha" => ["YZF-R", "MT", "XT", "Ténéré", "YZ", "R1", "R6", "TMAX", "VMAX"],
            "Kawasaki" => ["Ninja", "Z", "Versys", "KLR", "H2", "Vulcan"],
            "Ducati" => ["Panigale", "Monster", "Multistrada", "Scrambler", "Diavel"],
            "BMW" => ["S1000RR", "R1250GS", "F900R", "K1600", "C400"],
            "Suzuki" => ["GSX-R", "V-Strom", "Hayabusa", "Boulevard", "DR-Z", "Gixxer"],
            "KTM" => ["Duke", "RC", "Adventure", "Super Duke", "EXC"],
            "Triumph" => ["Bonneville", "Tiger", "Speed Triple", "Street Triple", "Rocket"],
            "Aprilia" => ["RSV4", "Tuono", "RS", "Shiver", "Dorsoduro"],
            "Royal Enfield" => ["Classic", "Bullet", "Interceptor", "Himalayan", "Meteor"],
            "Indian" => ["Scout", "Chief", "Chieftain", "Roadmaster", "FTR"],
            "MV Agusta" => ["F4", "Brutale", "Dragster", "Turismo Veloce", "Superveloce"],
            "Moto Guzzi" => ["V7", "V9", "V85", "California", "Griso"],
            "Benelli" => ["TNT", "TRK", "Leoncino", "Imperiale", "BN", "VLM", "VLR", "Zenzero"],
            "Bajaj" => ["Pulsar", "Dominar", "Avenger", "CT", "Platina", "Boxer"],
            "Kymco" => ["AK", "Like", "Agility", "Xciting", "Downtown"],
            "Vespa" => ["Primavera", "GTS", "Sprint", "LX", "946"],
            "Piaggio" => ["Beverly", "Medley", "Liberty", "MP3", "X10"],
            "SYM" => ["Symphony", "Fiddle", "Joyride", "Orbit", "Jet", "Cruisym"],
            "Dayun" => ["Dayun 4", "Dayun 6", "Dayun 10", "Dayun 40", "Dayun 2B"],
            "Haujue" => ["KA 150", "HJ 150", "HJ 150-9"],
            "Keeway" => ["Superlight", "RKS", "RKV"],
            "TVS" => ["Apache RTR", "HLX 150", "King"],
            "Halawa" => ["Tiger", "Express", "Halawa 4"],
            "Zongshen" => ["ZS 150", "ZS 200"],
            "Haojin" => ["Haojin 150", "Haojin 200"],
            "Husqvarna" => ["Vitpilen", "Svartpilen", "701"],
            "Norton" => ["Commando", "V4", "Atlas"],
            "Zontes" => ["310X", "350T", "350X"],
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

        // 5. Car/Bike Features (Expanded)
        $features = [
            ['ar' => 'تكييف', 'en' => 'Air Conditioning'],
            ['ar' => 'نوافذ كهربائية', 'en' => 'Power Windows'],
            ['ar' => 'باور ستيرنج', 'en' => 'Power Steering'],
            ['ar' => 'فتحة سقف', 'en' => 'Sunroof'],
            ['ar' => 'فتحة سقف بانوراما', 'en' => 'Panoramic Sunroof'],
            ['ar' => 'مقاعد جلد', 'en' => 'Leather Seats'],
            ['ar' => 'نظام فرامل ABS', 'en' => 'ABS'],
            ['ar' => 'وسائد هوائية', 'en' => 'Airbags'],
            ['ar' => 'شاشة تعمل باللمس', 'en' => 'Touch Screen'],
            ['ar' => 'كاميرا خلفية', 'en' => 'Rear Camera'],
            ['ar' => 'كاميرا 360 درجة', 'en' => '360 Camera'],
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
            ['ar' => 'كراسي كهرباء', 'en' => 'Electric Seats'],
            ['ar' => 'تسخين كراسي', 'en' => 'Heated Seats'],
            ['ar' => 'تبريد كراسي', 'en' => 'Ventilated Seats'],
            ['ar' => 'كراسي مساج', 'en' => 'Massage Seats'],
            ['ar' => 'زجاج فاميه', 'en' => 'Tinted Glass'],
            ['ar' => 'نظام صوتي ممتاز', 'en' => 'Premium Sound System'],
            ['ar' => 'أندرويد أوتو / أبل كار بلاي', 'en' => 'Android Auto / Apple CarPlay'],
            ['ar' => 'أبل كاربلاي وأندرويد أوتو لاسلكي', 'en' => 'Wireless Apple CarPlay / Android Auto'],
            ['ar' => 'شاحن لاسلكي', 'en' => 'Wireless Charger'],
            ['ar' => 'إضاءة زينون / ليد', 'en' => 'Xenon / LED Lights'],
            ['ar' => 'إضاءة داخلية محيطة', 'en' => 'Ambient Lighting'],
            ['ar' => 'سبويلر خلفي', 'en' => 'Rear Spoiler'],
            ['ar' => 'شنطة كهرباء', 'en' => 'Electric Trunk'],
            ['ar' => 'نظام مراقبة النقاط العمياء', 'en' => 'Blind Spot Monitoring'],
            ['ar' => 'تنبيه مغادرة المسار', 'en' => 'Lane Departure Warning'],
            ['ar' => 'عدادات ديجيتال', 'en' => 'Digital Dashboard'],
            ['ar' => 'عرض المعلومات على الزجاج الأمامي', 'en' => 'Heads-up Display (HUD)'],
            ['ar' => 'بدالات نقل حركة خلف المقود', 'en' => 'Paddle Shifters'],
            ['ar' => 'أنظمة قيادة متعددة', 'en' => 'Driving Modes'],
            ['ar' => 'ركن ذاتي', 'en' => 'Auto Park'],
            ['ar' => 'نظام التحذير من التصادم', 'en' => 'Collision Warning'],
        ];

        foreach ($features as $featureData) {
            CarFeature::updateOrCreate(
                ['name->en' => $featureData['en']],
                ['name' => $featureData]
            );
        }
    }
}
