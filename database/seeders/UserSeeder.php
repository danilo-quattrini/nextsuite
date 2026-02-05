<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(2)->create(new Sequence(
            [
            'full_name' => 'Danilo Quattrini',
            'email' => 'daniloquattrini.com@gmail.com',
            'password' => 'danilo2003',
            ],
            [
                'full_name' => 'William Properzi',
                'email' => 'william.com@gmail.com',
                'password' => 'william1999',
            ]

        ));

    }
}
