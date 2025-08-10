<?php

use App\Models\Author;
use App\Models\Branch;
use App\Models\Repository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pull_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Repository::class);
            $table->foreignIdFor(Branch::class);
            $table->foreignIdFor(Author::class);
            $table->string('number');
            $table->string('title');
            $table->text('body');
            $table->string('url');
            $table->timestamp('merged_at');
            $table->timestamps();

            $table->unique(['repository_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pull_requests');
    }
};
