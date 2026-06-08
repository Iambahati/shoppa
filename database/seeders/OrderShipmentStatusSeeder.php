<?php

namespace Database\Seeders;

use App\Models\OrderShipmentStatus;
use Illuminate\Database\Seeder;

class OrderShipmentStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->statuses() as $status) {
            OrderShipmentStatus::firstOrCreate(['name' => $status]);
        }

        $this->command->info('Order shipment statuses seeded.');
    }

    private function statuses(): array
    {
        return [
            'pending',
            'shipped',
            'in_transit',
            'delivered',
            'returned',
            'cancelled',
        ];
    }
}
