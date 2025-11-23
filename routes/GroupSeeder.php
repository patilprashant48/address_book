<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder {
  public function run(): void {
    foreach (['Family','Work','Friends','VIP'] as $n) { Group::firstOrCreate(['name'=>$n]); }
  }
}