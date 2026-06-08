<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['name' => 'Yellow Apple Technologies', 'description' => 'Premium dealer for upgraded laptops, smartphones, and genuine tech accessories located in Nairobi CBD'],
            ['name' => 'Rondamo Technologies', 'description' => 'Wholesale and retail hub specializing in laptops, Starlink kits, office printers, and computer peripherals'],
            ['name' => 'iPhone Garage', 'description' => 'Popular social media vendor for ex-UK iPhones, flexible lipa polepole payment options, and smartphone trade-ins'],
            ['name' => 'Kenyatronics', 'description' => 'Authorised dealer providing home appliances, smart televisions, and audio entertainment systems'],
            ['name' => 'Digital City Electronics', 'description' => 'Reliable retail store offering the latest consumer gadgets, kitchen appliances, and household tech'],
            ['name' => 'Dixons Electronics', 'description' => 'Top-rated tech store supplying brand new smartphones, wearable devices, and essential accessories'],
            ['name' => 'Slick Gadgets KE', 'description' => 'Nairobi CBD boutique specializing in pristine ex-USA smartphones, tablets, and mobile photography tools'],
            ['name' => 'YES Gadgets Ke', 'description' => 'Trusted provider of high-performance laptops, custom computing accessories, and smartphones'],
            ['name' => 'Zentech Electronics', 'description' => 'Multi-brand shop dealing in both brand-new and certified refurbished laptops and mobile electronics'],
            ['name' => 'GameCity Electronics', 'description' => 'Specialist tech store focusing on high-end custom gaming PCs, displays, and productivity monitors']
        ];

        // Get all vendor user IDs in one query
        $vendorUserIds = User::query()
            ->where('role_id', function ($query) {
                $query->select('id')
                    ->from('roles')
                    ->where('name', 'Vendor')
                    ->limit(1);
            })
            ->pluck('id')
            ->all();

        $vendorsToInsert = array_map(function ($vendor) use ($vendorUserIds) {
            return [
                'user_id'     => $vendorUserIds[array_rand($vendorUserIds)],
                'name'        => $vendor['name'],
                'description' => $vendor['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }, $vendors);

        foreach ($vendorsToInsert as $vendorData) {
            $vendor = Vendor::create($vendorData);
            $this->attachMedia($vendor);
        }
    }

    private function attachMedia(Vendor $vendor): void
    {
        $images = collect(Storage::disk('local')->files('demo-images'));

        if ($images->isEmpty()) {
            return;
        }

        $vendor->addMediaFromDisk($images->random())
            ->preservingOriginal()
            ->toMediaCollection('logo');
    }
}
