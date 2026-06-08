<?php

namespace Database\Seeders;

use App\Models\OrderReturnStatus;
use Illuminate\Database\Seeder;

class OrderReturnStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->statuses() as $status) {
            OrderReturnStatus::firstOrCreate(['name' => $status]);
        }

        $this->command->info('Order return statuses seeded.');
    }

    private function statuses(): array
    {
        return [
            OrderReturnStatus::REQUESTED,
            OrderReturnStatus::APPROVED,
            OrderReturnStatus::REJECTED,
            OrderReturnStatus::RECEIVED,
            OrderReturnStatus::COMPLETED,
        ];
    }
}
