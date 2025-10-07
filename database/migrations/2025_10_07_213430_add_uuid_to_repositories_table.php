<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->uuid()->after('id')->unique()->nullable();
        });

        DB::table('repositories')->orderBy('id')->chunk(100, function ($repositories) {
            foreach ($repositories as $repository) {
                DB::table('repositories')->where('id', $repository->id)->update(['uuid' => Str::uuid()]);
            }
        });

        Schema::table('repositories', function (Blueprint $table) {
            $table->uuid()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
