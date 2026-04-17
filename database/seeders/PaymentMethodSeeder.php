<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            [
                'name' => 'Cash On Delivery (COD)',
                'code' => 'cod',
                'is_active' => 1,
            ],
            [
                'name' => 'BCA Virtual Account',
                'code' => 'bca_va',
                'is_active' => 1,
            ],
            [
                'name' => 'BNI Virtual Account',
                'code' => 'bni_va',
                'is_active' => 1,
            ],
            [
                'name' => 'BRI Virtual Account',
                'code' => 'bri_va',
                'is_active' => 1,
            ],
            [
                'name' => 'Mandiri Virtual Account',
                'code' => 'mandiri_va',
                'is_active' => 1,
            ],
        ]);
    }
}
