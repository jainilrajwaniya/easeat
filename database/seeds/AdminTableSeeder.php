<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([//,
            'name' => 'Jainil',
            'email' => 'jainil.udr@gmail.com',
            'password' => '$2y$10$DuM3KGIbomdq54fdvuMxhuNqTzygDb0cOVwroIOyKxhbbNQS9iFyG', //jainiladmin
            'role' => 'SUPER_ADMIN'
        ]);
    }
}
