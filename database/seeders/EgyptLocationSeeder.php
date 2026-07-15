<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Modules\Countries\Models\Country;
use App\Http\Modules\Governorates\Models\Governorate;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Sellers\Models\Seller;
use Illuminate\Support\Facades\DB;

class EgyptLocationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or create Egypt country
        $egypt = Country::updateOrCreate(
            ['id' => 1],
            ['name' => ['ar' => 'مصر', 'en' => 'Egypt']]
        );

        // 2. Save old associations to map back later
        $cars = Car::all();
        $sellers = Seller::all();

        $carOldLocation = [];
        foreach ($cars as $car) {
            $oldCity = City::find($car->city_id);
            $oldGov = Governorate::find($car->governorate_id);
            $carOldLocation[$car->id] = [
                'city_en' => $oldCity ? $oldCity->getTranslation('name', 'en') : null,
                'gov_en' => $oldGov ? $oldGov->getTranslation('name', 'en') : null,
            ];
        }

        $sellerOldLocation = [];
        foreach ($sellers as $seller) {
            $oldCity = City::find($seller->city_id);
            $oldGov = Governorate::find($seller->governorate_id);
            $sellerOldLocation[$seller->id] = [
                'city_en' => $oldCity ? $oldCity->getTranslation('name', 'en') : null,
                'gov_en' => $oldGov ? $oldGov->getTranslation('name', 'en') : null,
            ];
        }

        // 3. Structured location data from the user
        $locations = [
            'Dakahlia' => [
                'name_ar' => 'الدقهلية',
                'cities' => [
                    ['ar' => 'المنصورة', 'en' => 'Mansoura'],
                    ['ar' => 'طلخا', 'en' => 'Talkha'],
                    ['ar' => 'ميت غمر', 'en' => 'Mit Ghamr'],
                    ['ar' => 'دكرنس', 'en' => 'Dekernes'],
                    ['ar' => 'أجا', 'en' => 'Aga'],
                    ['ar' => 'منية النصر', 'en' => 'Minyet El Nasr'],
                    ['ar' => 'السنبلاوين', 'en' => 'El Senbellawein'],
                    ['ar' => 'بني عبيد', 'en' => 'Bani Ubaid'],
                    ['ar' => 'المنزلة', 'en' => 'El Manzala'],
                    ['ar' => 'تمي الأمديد', 'en' => 'Temay El Amdid'],
                    ['ar' => 'الجمالية', 'en' => 'El Gamalia'],
                    ['ar' => 'شربين', 'en' => 'Sherbin'],
                    ['ar' => 'المطرية', 'en' => 'El Matareya'],
                    ['ar' => 'بلقاس', 'en' => 'Belqas'],
                    ['ar' => 'ميت سلسيل', 'en' => 'Meet Salseel'],
                    ['ar' => 'محلة دمنة', 'en' => 'Mahallet Damana'],
                    ['ar' => 'نبروه', 'en' => 'Nabaroh'],
                ]
            ],
            'Damietta' => [
                'name_ar' => 'دمياط',
                'cities' => [
                    ['ar' => 'دمياط', 'en' => 'Damietta'],
                    ['ar' => 'فارسكور', 'en' => 'Faraskur'],
                    ['ar' => 'كفر سعد', 'en' => 'Kafr Saad'],
                    ['ar' => 'الزرقا', 'en' => 'El Zarqa'],
                    ['ar' => 'كفر البطيخ', 'en' => 'Kafr El Bateekh'],
                ]
            ],
            'Cairo' => [
                'name_ar' => 'القاهرة',
                'cities' => [
                    ['ar' => 'القاهرة', 'en' => 'Cairo'],
                    ['ar' => 'مدينة نصر', 'en' => 'Nasr City'],
                    ['ar' => 'مصر الجديدة', 'en' => 'Heliopolis'],
                    ['ar' => 'المعادي', 'en' => 'Maadi'],
                    ['ar' => 'حلوان', 'en' => 'Helwan'],
                    ['ar' => 'الشروق', 'en' => 'El Shorouk'],
                    ['ar' => 'بدر', 'en' => 'Badr'],
                    ['ar' => '15 مايو', 'en' => '15 May'],
                    ['ar' => 'القاهرة الجديدة', 'en' => 'New Cairo'],
                    ['ar' => 'العاصمة الإدارية الجديدة', 'en' => 'New Administrative Capital'],
                ]
            ],
            'Giza' => [
                'name_ar' => 'الجيزة',
                'cities' => [
                    ['ar' => 'الجيزة', 'en' => 'Giza'],
                    ['ar' => '6 أكتوبر', 'en' => '6th of October'],
                    ['ar' => 'الشيخ زايد', 'en' => 'Sheikh Zayed'],
                    ['ar' => 'البدرشين', 'en' => 'El Badrashin'],
                    ['ar' => 'الصف', 'en' => 'El Saf'],
                    ['ar' => 'أطفيح', 'en' => 'Atfih'],
                    ['ar' => 'العياط', 'en' => 'El Ayat'],
                    ['ar' => 'الواحات البحرية', 'en' => 'Bahariya Oasis'],
                    ['ar' => 'أوسيم', 'en' => 'Oseem'],
                    ['ar' => 'كرداسة', 'en' => 'Kerdasa'],
                    ['ar' => 'أبو النمرس', 'en' => 'Abu Nomros'],
                    ['ar' => 'منشأة القناطر', 'en' => 'Manshiyet Al Qanater'],
                ]
            ],
            'Alexandria' => [
                'name_ar' => 'الإسكندرية',
                'cities' => [
                    ['ar' => 'الإسكندرية', 'en' => 'Alexandria'],
                    ['ar' => 'برج العرب', 'en' => 'Borg El Arab'],
                    ['ar' => 'برج العرب الجديدة', 'en' => 'New Borg El Arab'],
                    ['ar' => 'العامرية', 'en' => 'Amreya'],
                ]
            ],
            'Qalyubia' => [
                'name_ar' => 'القليوبية',
                'cities' => [
                    ['ar' => 'بنها', 'en' => 'Banha'],
                    ['ar' => 'شبرا الخيمة', 'en' => 'Shubra El Kheima'],
                    ['ar' => 'قليوب', 'en' => 'Qalyub'],
                    ['ar' => 'القناطر الخيرية', 'en' => 'Al Qanater Al Khayriya'],
                    ['ar' => 'الخانكة', 'en' => 'El Khanka'],
                    ['ar' => 'كفر شكر', 'en' => 'Kafr Shukr'],
                    ['ar' => 'طوخ', 'en' => 'Toukh'],
                    ['ar' => 'شبين القناطر', 'en' => 'Shebin El Qanater'],
                    ['ar' => 'قها', 'en' => 'Qaha'],
                    ['ar' => 'العبور', 'en' => 'Obour'],
                    ['ar' => 'الخصوص', 'en' => 'El Khosous'],
                ]
            ],
            'Sharqia' => [
                'name_ar' => 'الشرقية',
                'cities' => [
                    ['ar' => 'الزقازيق', 'en' => 'Zagazig'],
                    ['ar' => 'العاشر من رمضان', 'en' => '10th of Ramadan'],
                    ['ar' => 'بلبيس', 'en' => 'Belbeis'],
                    ['ar' => 'منيا القمح', 'en' => 'Minya El Qamh'],
                    ['ar' => 'أبو حماد', 'en' => 'Abu Hammad'],
                    ['ar' => 'أبو كبير', 'en' => 'Abu Kabir'],
                    ['ar' => 'فاقوس', 'en' => 'Faqous'],
                    ['ar' => 'الحسينية', 'en' => 'El Husseiniya'],
                    ['ar' => 'ههيا', 'en' => 'Hehia'],
                    ['ar' => 'الإبراهيمية', 'en' => 'Al Ibrahimiyah'],
                    ['ar' => 'ديرب نجم', 'en' => 'Deyarb Negm'],
                    ['ar' => 'كفر صقر', 'en' => 'Kafr Saqr'],
                    ['ar' => 'أولاد صقر', 'en' => 'Awlad Saqr'],
                    ['ar' => 'مشتول السوق', 'en' => 'Mashtoul El Souq'],
                    ['ar' => 'القرين', 'en' => 'El Qurein'],
                    ['ar' => 'صان الحجر', 'en' => 'San El Hagar'],
                ]
            ],
            'Gharbia' => [
                'name_ar' => 'الغربية',
                'cities' => [
                    ['ar' => 'طنطا', 'en' => 'Tanta'],
                    ['ar' => 'المحلة الكبرى', 'en' => 'El Mahalla El Kubra'],
                    ['ar' => 'كفر الزيات', 'en' => 'Kafr El Zayat'],
                    ['ar' => 'زفتى', 'en' => 'Zefta'],
                    ['ar' => 'السنطة', 'en' => 'El Santa'],
                    ['ar' => 'بسيون', 'en' => 'Basion'],
                    ['ar' => 'قطور', 'en' => 'Qutur'],
                    ['ar' => 'سمنود', 'en' => 'Samannoud'],
                ]
            ],
            'Kafr El Sheikh' => [
                'name_ar' => 'كفر الشيخ',
                'cities' => [
                    ['ar' => 'كفر الشيخ', 'en' => 'Kafr El Sheikh'],
                    ['ar' => 'دسوق', 'en' => 'Desouk'],
                    ['ar' => 'فوه', 'en' => 'Fowa'],
                    ['ar' => 'مطوبس', 'en' => 'Motobas'],
                    ['ar' => 'بلطيم', 'en' => 'Baltim'],
                    ['ar' => 'بيلا', 'en' => 'Biyala'],
                    ['ar' => 'الحامول', 'en' => 'El Hamoul'],
                    ['ar' => 'سيدي سالم', 'en' => 'Sidi Salem'],
                    ['ar' => 'الرياض', 'en' => 'Riyadh'],
                    ['ar' => 'البرلس', 'en' => 'Borollos'],
                    ['ar' => 'قلين', 'en' => 'Qallin'],
                ]
            ],
            'Beheira' => [
                'name_ar' => 'البحيرة',
                'cities' => [
                    ['ar' => 'دمنهور', 'en' => 'Damanhur'],
                    ['ar' => 'كفر الدوار', 'en' => 'Kafr El Dawar'],
                    ['ar' => 'رشيد', 'en' => 'Rosetta'],
                    ['ar' => 'إدكو', 'en' => 'Edku'],
                    ['ar' => 'أبو المطامير', 'en' => 'Abu El Matamir'],
                    ['ar' => 'أبو حمص', 'en' => 'Abu Hummus'],
                    ['ar' => 'الدلنجات', 'en' => 'El Delengat'],
                    ['ar' => 'المحمودية', 'en' => 'Mahmoudiyah'],
                    ['ar' => 'الرحمانية', 'en' => 'Rahmaniyah'],
                    ['ar' => 'إيتاي البارود', 'en' => 'Itay El Barud'],
                    ['ar' => 'حوش عيسى', 'en' => 'Hosh Issa'],
                    ['ar' => 'شبراخيت', 'en' => 'Shubrakhit'],
                    ['ar' => 'كوم حمادة', 'en' => 'Kom Hamada'],
                    ['ar' => 'بدر', 'en' => 'Badr'],
                    ['ar' => 'وادي النطرون', 'en' => 'Wadi El Natrun'],
                    ['ar' => 'النوبارية', 'en' => 'Nubaria'],
                ]
            ],
            'Monufia' => [
                'name_ar' => 'المنوفية',
                'cities' => [
                    ['ar' => 'شبين الكوم', 'en' => 'Shebin El Kom'],
                    ['ar' => 'منوف', 'en' => 'Menouf'],
                    ['ar' => 'السادات', 'en' => 'Sadat City'],
                    ['ar' => 'أشمون', 'en' => 'Ashmoun'],
                    ['ar' => 'الباجور', 'en' => 'El Bagour'],
                    ['ar' => 'قويسنا', 'en' => 'Quesna'],
                    ['ar' => 'تلا', 'en' => 'Tala'],
                    ['ar' => 'بركة السبع', 'en' => 'Berket El Sab'],
                    ['ar' => 'الشهداء', 'en' => 'El Shohada'],
                ]
            ],
            'Port Said' => [
                'name_ar' => 'بورسعيد',
                'cities' => [
                    ['ar' => 'بورسعيد', 'en' => 'Port Said'],
                    ['ar' => 'بورفؤاد', 'en' => 'Port Fouad'],
                ]
            ],
            'Ismailia' => [
                'name_ar' => 'الإسماعيلية',
                'cities' => [
                    ['ar' => 'الإسماعيلية', 'en' => 'Ismailia'],
                    ['ar' => 'القنطرة شرق', 'en' => 'Qantara East'],
                    ['ar' => 'القنطرة غرب', 'en' => 'Qantara West'],
                    ['ar' => 'فايد', 'en' => 'Fayed'],
                    ['ar' => 'التل الكبير', 'en' => 'Tel El Kebir'],
                    ['ar' => 'أبو صوير', 'en' => 'Abu Soweir'],
                ]
            ],
            'Suez' => [
                'name_ar' => 'السويس',
                'cities' => [
                    ['ar' => 'السويس', 'en' => 'Suez'],
                    ['ar' => 'عتاقة', 'en' => 'Ataqa'],
                    ['ar' => 'فيصل', 'en' => 'Faisal'],
                ]
            ],
            'North Sinai' => [
                'name_ar' => 'شمال سيناء',
                'cities' => [
                    ['ar' => 'العريش', 'en' => 'Arish'],
                    ['ar' => 'الشيخ زويد', 'en' => 'Sheikh Zuweid'],
                    ['ar' => 'رفح', 'en' => 'Rafah'],
                    ['ar' => 'بئر العبد', 'en' => 'Bir El Abd'],
                    ['ar' => 'الحسنة', 'en' => 'El Hasana'],
                    ['ar' => 'نخل', 'en' => 'Nekhel'],
                ]
            ],
            'South Sinai' => [
                'name_ar' => 'جنوب سيناء',
                'cities' => [
                    ['ar' => 'الطور', 'en' => 'El Tor'],
                    ['ar' => 'شرم الشيخ', 'en' => 'Sharm El Sheikh'],
                    ['ar' => 'دهب', 'en' => 'Dahab'],
                    ['ar' => 'نويبع', 'en' => 'Nuweiba'],
                    ['ar' => 'سانت كاترين', 'en' => 'Saint Catherine'],
                    ['ar' => 'رأس سدر', 'en' => 'Ras Sidr'],
                    ['ar' => 'أبو رديس', 'en' => 'Abu Rudeis'],
                ]
            ],
            'Red Sea' => [
                'name_ar' => 'البحر الأحمر',
                'cities' => [
                    ['ar' => 'الغردقة', 'en' => 'Hurghada'],
                    ['ar' => 'سفاجا', 'en' => 'Safaga'],
                    ['ar' => 'القصير', 'en' => 'Quseir'],
                    ['ar' => 'مرسى علم', 'en' => 'Marsa Alam'],
                    ['ar' => 'رأس غارب', 'en' => 'Ras Ghareb'],
                    ['ar' => 'الشلاتين', 'en' => 'Shalateen'],
                    ['ar' => 'حلايب', 'en' => 'Halayeb'],
                ]
            ],
            'Fayoum' => [
                'name_ar' => 'الفيوم',
                'cities' => [
                    ['ar' => 'الفيوم', 'en' => 'Fayoum'],
                    ['ar' => 'سنورس', 'en' => 'Sinnuris'],
                    ['ar' => 'إطسا', 'en' => 'Itsa'],
                    ['ar' => 'طامية', 'en' => 'Tamia'],
                    ['ar' => 'يوسف الصديق', 'en' => 'Youssef El Seddik'],
                    ['ar' => 'إبشواي', 'en' => 'Ibshaway'],
                ]
            ],
            'Beni Suef' => [
                'name_ar' => 'بني سويف',
                'cities' => [
                    ['ar' => 'بني سويف', 'en' => 'Beni Suef'],
                    ['ar' => 'ناصر', 'en' => 'Nasser'],
                    ['ar' => 'ببا', 'en' => 'Beba'],
                    ['ar' => 'اهناسيا', 'en' => 'Ihnasia'],
                    ['ar' => 'الفشن', 'en' => 'Fashn'],
                    ['ar' => 'الواسطى', 'en' => 'Wasta'],
                    ['ar' => 'سمسطا', 'en' => 'Sumusta'],
                ]
            ],
            'Minya' => [
                'name_ar' => 'المنيا',
                'cities' => [
                    ['ar' => 'المنيا', 'en' => 'Minya'],
                    ['ar' => 'ملوي', 'en' => 'Mallawi'],
                    ['ar' => 'سمالوط', 'en' => 'Samalut'],
                    ['ar' => 'بني مزار', 'en' => 'Beni Mazar'],
                    ['ar' => 'مطاي', 'en' => 'Matay'],
                    ['ar' => 'أبو قرقاص', 'en' => 'Abu Qurqas'],
                    ['ar' => 'دير مواس', 'en' => 'Deir Mawas'],
                    ['ar' => 'العدوة', 'en' => 'El Adwa'],
                    ['ar' => 'المغاغة', 'en' => 'Maghagha'],
                ]
            ],
            'Assiut' => [
                'name_ar' => 'أسيوط',
                'cities' => [
                    ['ar' => 'أسيوط', 'en' => 'Assiut'],
                    ['ar' => 'ديروط', 'en' => 'Dayrout'],
                    ['ar' => 'القوصية', 'en' => 'Qusiya'],
                    ['ar' => 'منفلوط', 'en' => 'Manfalut'],
                    ['ar' => 'أبو تيج', 'en' => 'Abu Tig'],
                    ['ar' => 'صدفا', 'en' => 'Sidfa'],
                    ['ar' => 'ساحل سليم', 'en' => 'Sahel Selim'],
                    ['ar' => 'البداري', 'en' => 'El Badari'],
                    ['ar' => 'الفتح', 'en' => 'El Fateh'],
                    ['ar' => 'الغنايم', 'en' => 'El Ghanaim'],
                    ['ar' => 'أبنوب', 'en' => 'Abnub'],
                ]
            ],
            'Sohag' => [
                'name_ar' => 'سوهاج',
                'cities' => [
                    ['ar' => 'سوهاج', 'en' => 'Sohag'],
                    ['ar' => 'جرجا', 'en' => 'Girga'],
                    ['ar' => 'طهطا', 'en' => 'Tahta'],
                    ['ar' => 'أخميم', 'en' => 'Akhmim'],
                    ['ar' => 'البلينا', 'en' => 'Balyana'],
                    ['ar' => 'المراغة', 'en' => 'Maragha'],
                    ['ar' => 'المنشأة', 'en' => 'Al Monshah'],
                    ['ar' => 'دار السلام', 'en' => 'Dar El Salam'],
                    ['ar' => 'جهينة', 'en' => 'Juhayna'],
                    ['ar' => 'ساقلتة', 'en' => 'Saqilta'],
                    ['ar' => 'العسيرات', 'en' => 'El Usayrat'],
                ]
            ],
            'Qena' => [
                'name_ar' => 'قنا',
                'cities' => [
                    ['ar' => 'قنا', 'en' => 'Qena'],
                    ['ar' => 'نجع حمادي', 'en' => 'Nag Hammadi'],
                    ['ar' => 'دشنا', 'en' => 'Dishna'],
                    ['ar' => 'قفط', 'en' => 'Qift'],
                    ['ar' => 'قوص', 'en' => 'Qus'],
                    ['ar' => 'أبوتشت', 'en' => 'Abu Tesht'],
                    ['ar' => 'فرشوط', 'en' => 'Farshut'],
                    ['ar' => 'نقادة', 'en' => 'Naqada'],
                    ['ar' => 'الوقف', 'en' => 'El Waqf'],
                    ['ar' => 'الأقصر', 'en' => 'Luxor'],
                ]
            ],
            'Luxor' => [
                'name_ar' => 'الأقصر',
                'cities' => [
                    ['ar' => 'الأقصر', 'en' => 'Luxor'],
                    ['ar' => 'أرمنت', 'en' => 'Armant'],
                    ['ar' => 'إسنا', 'en' => 'Esna'],
                    ['ar' => 'الطود', 'en' => 'El Tod'],
                ]
            ],
            'Aswan' => [
                'name_ar' => 'أسوان',
                'cities' => [
                    ['ar' => 'أسوان', 'en' => 'Aswan'],
                    ['ar' => 'كوم أمبو', 'en' => 'Kom Ombo'],
                    ['ar' => 'إدفو', 'en' => 'Edfu'],
                    ['ar' => 'دراو', 'en' => 'Daraw'],
                    ['ar' => 'نصر النوبة', 'en' => 'Nasr Nubia'],
                    ['ar' => 'أبو سمبل', 'en' => 'Abu Simbel'],
                    ['ar' => 'البياضية', 'en' => 'El Biyada'],
                ]
            ],
            'New Valley' => [
                'name_ar' => 'الوادي الجديد',
                'cities' => [
                    ['ar' => 'الخارجة', 'en' => 'Kharga'],
                    ['ar' => 'الداخلة', 'en' => 'Dakhla'],
                    ['ar' => 'الفرافرة', 'en' => 'Farafra'],
                    ['ar' => 'باريس', 'en' => 'Paris'],
                ]
            ],
            'Matrouh' => [
                'name_ar' => 'مطروح',
                'cities' => [
                    ['ar' => 'مرسى مطروح', 'en' => 'Marsa Matrouh'],
                    ['ar' => 'العلمين', 'en' => 'El Alamein'],
                    ['ar' => 'سيوة', 'en' => 'Siwa'],
                    ['ar' => 'الحمام', 'en' => 'El Hamam'],
                    ['ar' => 'الضبعة', 'en' => 'El Dabaa'],
                    ['ar' => 'سيدي براني', 'en' => 'Sidi Barrani'],
                    ['ar' => 'النجيلة', 'en' => 'Negaila'],
                    ['ar' => 'السلوم', 'en' => 'El Salloum'],
                ]
            ],
        ];

        // 4. Truncate locations cleanly
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        }
        
        City::query()->delete();
        Governorate::query()->delete();

        // 5. Insert new locations
        $createdGovs = [];
        $createdCities = [];

        foreach ($locations as $govEn => $govData) {
            $gov = Governorate::create([
                'country_id' => $egypt->id,
                'name' => [
                    'en' => $govEn,
                    'ar' => $govData['name_ar'],
                ]
            ]);

            $createdGovs[$govEn] = $gov;

            foreach ($govData['cities'] as $cityData) {
                $city = City::create([
                    'country_id' => $egypt->id,
                    'governorate_id' => $gov->id,
                    'name' => [
                        'en' => $cityData['en'],
                        'ar' => $cityData['ar'],
                    ]
                ]);

                $createdCities[$cityData['en']] = $city;
            }
        }

        // 6. Map old associations back to new records
        foreach ($cars as $car) {
            $oldLoc = $carOldLocation[$car->id] ?? null;
            if ($oldLoc && $oldLoc['city_en']) {
                $newCity = $createdCities[$oldLoc['city_en']] ?? null;
                if (!$newCity) {
                    // Fuzzy match
                    foreach ($createdCities as $nameEn => $cityModel) {
                        if (stripos($nameEn, $oldLoc['city_en']) !== false || stripos($oldLoc['city_en'], $nameEn) !== false) {
                            $newCity = $cityModel;
                            break;
                        }
                    }
                }
                
                $newGov = null;
                if ($oldLoc['gov_en']) {
                    $newGov = $createdGovs[$oldLoc['gov_en']] ?? null;
                }
                if (!$newGov && $newCity) {
                    $newGov = Governorate::find($newCity->governorate_id);
                }

                $car->update([
                    'city_id' => $newCity ? $newCity->id : ($createdCities['Cairo']->id ?? 1),
                    'governorate_id' => $newGov ? $newGov->id : null,
                ]);
            }
        }

        foreach ($sellers as $seller) {
            $oldLoc = $sellerOldLocation[$seller->id] ?? null;
            if ($oldLoc && $oldLoc['city_en']) {
                $newCity = $createdCities[$oldLoc['city_en']] ?? null;
                if (!$newCity) {
                    foreach ($createdCities as $nameEn => $cityModel) {
                        if (stripos($nameEn, $oldLoc['city_en']) !== false || stripos($oldLoc['city_en'], $nameEn) !== false) {
                            $newCity = $cityModel;
                            break;
                        }
                    }
                }
                
                $newGov = null;
                if ($oldLoc['gov_en']) {
                    $newGov = $createdGovs[$oldLoc['gov_en']] ?? null;
                }
                if (!$newGov && $newCity) {
                    $newGov = Governorate::find($newCity->governorate_id);
                }

                $seller->update([
                    'city_id' => $newCity ? $newCity->id : null,
                    'governorate_id' => $newGov ? $newGov->id : null,
                ]);
            }
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } elseif ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        }
    }
}
