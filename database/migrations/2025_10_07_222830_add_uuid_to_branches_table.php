<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->uuid()->after('id')->unique()->nullable();
        });

        DB::table('branches')->orderBy('id')->chunk(100, function ($branches) {
            foreach ($branches as $branch) {
                DB::table('branches')->where('id', $branch->id)->update(['uuid' => Str::uuid()]);
            }
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->uuid()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
