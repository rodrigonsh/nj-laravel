<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Apocalipse 7:4
        User::factory(144000)->create();

        $count = array_fill(1, 12, 0);

                
        $allusers = User::all();
        foreach ($allusers as $user) {

            $attempts = 0;

            // get one of the 12 tribes if its not yet full with 12000 😁 this will be useful to access the database performance when selecting only the users that are in a position of helping
            // for this we will use the lastDoor field in the users table

            while($count[$user->lastDoor] <= 12000) {
                $user->lastDoor = rand(1, 12);
                $attempts += 1;
            }

            if ($attempts == 12) {
                // All users are assigned, break the loop or handle this case
                break;
            }

            $count[$user->lastDoor] += 1;

            $user->save();
        }
        

    }
}
