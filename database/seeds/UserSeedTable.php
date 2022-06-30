<?php

use Illuminate\Database\Seeder;
class UserSeedTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@aiis.co.id',
            'password' => bcrypt('admin'),
            ]);
            DB::table('users')->insert([
            'name' => 'eko',
            'email' => 'eko@aii.co.id',
            'password' => bcrypt('eko'),
            ]);
            DB::table('users')->insert([
            'name' => 'dankur',
            'email' => 'dankur@aii.co.id',
            'password' => bcrypt('dankur'),
            ]);
    }
}
