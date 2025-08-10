<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Repository;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::factory()->for(Repository::find(1))->count(5)->create();
    }
}
