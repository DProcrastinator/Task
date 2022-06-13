<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeauticianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        \App\Models\Beautician::factory(10)->create();

        // foreach (range(1, 10) as $i) {
        //     $beautician = factory('App\Models\Beautician')->create();
        //     foreach (range(1, 7) as $j) {
        //         factory('App\Models\WorkingHour')->create([
        //             'beautician_id' => $beautician->id,
        //             'day' => $j,
        //         ]);
            }

       // $this->call(BeauticianFactory::class);
    // }
// }
}
