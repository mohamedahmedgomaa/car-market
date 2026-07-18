<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $data = [
        'name' => 'Test',
        'email' => 'test' . rand(1, 1000) . '@test.com',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'phone' => '123456789',
        'store_name' => ['en' => 'test', 'ar' => 'test'],
        'store_description' => ['en' => 'test', 'ar' => 'test'],
        'address' => ['en' => 'test', 'ar' => 'test'],
        'is_verified' => 1,
        'sort_order' => 0,
    ];

    $seller = \App\Http\Modules\Sellers\Models\Seller::create($data);
    echo "Seller created: " . $seller->id . "\n";
    
    // Test the mapper
    $mapper = app(\App\Http\Modules\Sellers\Mappers\SellerMapper::class);
    $dto = $mapper->modelToDto($seller);
    echo "DTO created successfully\n";

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
