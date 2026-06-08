<?php

namespace Database\Seeders;

use App\Models\OrderRefundStatus;
use Illuminate\Database\Seeder;

class OrderRefundStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->statuses() as $status) {
            OrderRefundStatus::firstOrCreate(['name' => $status]);
        }

        $this->command->info('Order refund statuses seeded.');
    }

    private function statuses(): array
    {
        return [
            OrderRefundStatus::PENDING,
            OrderRefundStatus::PROCESSED,
            OrderRefundStatus::FAILED,
        ];
    }
}
