<?php

namespace Database\Seeders;

use App\Models\EmailCampaignStatus;
use Illuminate\Database\Seeder;

class EmailCampaignStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->statuses() as $status) {
            EmailCampaignStatus::firstOrCreate(['name' => $status]);
        }

        $this->command->info('Email campaign statuses seeded.');
    }

    private function statuses(): array
    {
        return [
            'draft',
            'scheduled',
            'sent',
            'cancelled',
            'failed',
        ];
    }
}
