<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\PhoneModel;
use App\Models\Role;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::query()->firstOrCreate(
            ['code' => 'HQ'],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Cabang Utama',
                'phone' => '081234567890',
                'email' => 'cabang@servicehp.test',
                'address' => 'Jl. Contoh No. 1',
                'is_active' => true,
            ]
        );

        $roles = [
            ['name' => 'Owner', 'slug' => 'owner', 'is_system' => true],
            ['name' => 'Admin', 'slug' => 'admin', 'is_system' => true],
            ['name' => 'Kasir', 'slug' => 'kasir', 'is_system' => true],
            ['name' => 'Teknisi', 'slug' => 'teknisi', 'is_system' => true],
        ];

        foreach ($roles as $roleData) {
            Role::query()->firstOrCreate(['slug' => $roleData['slug']], $roleData);
        }

        $paymentMethods = [
            ['code' => 'cash', 'name' => 'Tunai'],
            ['code' => 'transfer', 'name' => 'Transfer'],
            ['code' => 'qris', 'name' => 'QRIS'],
            ['code' => 'other', 'name' => 'Lainnya'],
        ];

        foreach ($paymentMethods as $pm) {
            \App\Models\PaymentMethodModel::query()->firstOrCreate(
                ['code' => $pm['code']],
                ['name' => $pm['name'], 'is_active' => true]
            );
        }

        $roleIds = Role::query()->pluck('id', 'slug');

        $users = [
            ['name' => 'Owner', 'email' => 'owner@servicehp.test', 'role' => 'owner'],
            ['name' => 'Admin', 'email' => 'admin@servicehp.test', 'role' => 'admin'],
            ['name' => 'Kasir', 'email' => 'kasir@servicehp.test', 'role' => 'kasir'],
            ['name' => 'Teknisi Satu', 'email' => 'teknisi@servicehp.test', 'role' => 'teknisi'],
            ['name' => 'Teknisi Dua', 'email' => 'teknisi2@servicehp.test', 'role' => 'teknisi'],
        ];

        foreach ($users as $userData) {
            $user = User::query()->updateOrCreate(
                ['email' => $userData['email']],
                [
                    'uuid' => Str::uuid()->toString(),
                    'branch_id' => $branch->id,
                    'role_id' => $roleIds[$userData['role']],
                    'name' => $userData['name'],
                    'phone' => '0812'.random_int(10000000, 99999999),
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );

            if ($userData['role'] === 'teknisi') {
                Technician::query()->firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'branch_id' => $branch->id,
                        'employee_code' => 'TEC-'.str_pad((string) $user->id, 3, '0', STR_PAD_LEFT),
                        'specialization' => 'Smartphone',
                        'is_active' => true,
                    ]
                );
            }
        }

        $brands = ['Samsung', 'Xiaomi', 'OPPO', 'Vivo', 'Apple', 'Realme'];

        foreach ($brands as $brandName) {
            $brand = Brand::query()->firstOrCreate(
                ['name' => $brandName],
                ['slug' => Str::slug($brandName), 'is_active' => true]
            );

            PhoneModel::query()->firstOrCreate(
                ['brand_id' => $brand->id, 'name' => $brandName.' A Series'],
                ['is_active' => true]
            );
        }

        $customers = [
            ['code' => 'CUST-001', 'name' => 'Budi Santoso', 'phone' => '081211111111'],
            ['code' => 'CUST-002', 'name' => 'Siti Aminah', 'phone' => '081222222222'],
            ['code' => 'CUST-003', 'name' => 'Andi Wijaya', 'phone' => '081233333333'],
        ];

        foreach ($customers as $customerData) {
            Customer::query()->firstOrCreate(
                ['code' => $customerData['code']],
                [
                    'uuid' => Str::uuid()->toString(),
                    'branch_id' => $branch->id,
                    'name' => $customerData['name'],
                    'phone' => $customerData['phone'],
                ]
            );
        }
    }
}
