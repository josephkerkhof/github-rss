<?php

use App\Models\Repository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->string('owner')->after('slug')->nullable();
            $table->string('repo')->after('owner')->nullable();

            $table->unique(['owner', 'repo']);
        });

        DB::table('repositories')->orderBy('id')->chunk(100, function (Collection $repositories) {
            $toMassUpdate = $repositories->map(function ($repository) {
                [$owner, $repo] = explode('/', $repository->slug);

                return [
                    'id' => $repository->id,
                    'owner' => $owner,
                    'repo' => $repo,
                ];
            });

            Repository::query()->massUpdate($toMassUpdate);
        });

        Schema::table('repositories', function (Blueprint $table) {
            // remove the old field
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');

            // and set the owner and repo columns to not nullable
            $table->string('owner')->nullable(false)->change();
            $table->string('repo')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();
            $table->unique('slug');
        });

        DB::table('repositories')->orderBy('id')->chunk(100, function (Collection $repositories) {
            $toMassUpdate = $repositories->map(function ($repository) {
                return [
                    'id' => $repository->id,
                    'slug' => $repository->owner . '/' . $repository->repo,
                ];
            });

            Repository::query()->massUpdate($toMassUpdate);
        });

        Schema::table('repositories', function (Blueprint $table) {
            // remove the fields
            $table->dropUnique(['owner', 'repo']);
            $table->dropColumn('owner');
            $table->dropColumn('repo');

            // and set the slug column to not nullable
            $table->string('slug')->nullable(false)->change();
        });
    }
};
