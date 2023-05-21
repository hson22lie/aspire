<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create user;
        DB::table('users')->insert([
            'name' => "admin",
            'email' => "admin@aspire.com",
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => "tester",
            'email' => "tester",
            'role' => 'user',
        ]);

        DB::table('loans')->insert([
            'user_id' => '1',
            'loan_amount' => 1000,
            'term' => 3,
            'created_by' => '1',
        ]);

        DB::table('loan_details')->insert([
            'loan_id' => '1',
            'installment_amount' => 333.33,
        ]);
        DB::table('loan_details')->insert([
            'loan_id' => '1',
            'installment_amount' => 333.33,
        ]);
        DB::table('loan_details')->insert([
            'loan_id' => '1',
            'installment_amount' => 333.34,
        ]);

        DB::table('loans')->insert([
            'user_id' => '2',
            'loan_amount' => 900,
            'term' => 3,
            'created_by' => '1',
            'status' => 'approved',
            'disbursed_at' => "2023-05-01",
        ]);

        DB::table('loan_details')->insert([
            'loan_id' => '2',
            'installment_amount' => 300,
            'overdue_at' => "2023-05-08",
        ]);
        DB::table('loan_details')->insert([
            'loan_id' => '2',
            'installment_amount' => 300,
            'overdue_at' => "2023-05-15",
        ]);
        DB::table('loan_details')->insert([
            'loan_id' => '2',
            'installment_amount' => 300,
            'overdue_at' => "2023-05-22",
        ]);
    }
}
