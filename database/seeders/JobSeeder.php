<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\JobType;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductStore;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Variation;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        JobType::insert(
            [
                ['job_title' => 'Accountant', 'date_of_creation' => Carbon::now(), 'created_by' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]
        );
    }
}
