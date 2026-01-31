<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'duration_value' => 1,
                'duration_unit' => 'month',
                'price' => 500.00,
                'currency' => 'NPR',
                'published' => true,
                'sort_order' => 1,
                'features' => json_encode([
                    'Access to basic features',
                    'Email support',
                ]),
            ],
            [
                'name' => 'Pro Plan',
                'duration_value' => 3,
                'duration_unit' => 'month',
                'price' => 1200.00,
                'currency' => 'NPR',
                'published' => true,
                'sort_order' => 2,
                'features' => json_encode([
                    'Access to all features',
                    'Priority email support',
                    'Monthly analytics report',
                ]),
            ],
            [
                'name' => 'Enterprise Plan',
                'duration_value' => 1,
                'duration_unit' => 'year',
                'price' => 5000.00,
                'currency' => 'NPR',
                'published' => true,
                'sort_order' => 3,
                'features' => json_encode([
                    'Dedicated account manager',
                    'Full feature access',
                    'Priority support 24/7',
                    'Custom integrations',
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info('Subscription plans seeded successfully!');
    }
}
