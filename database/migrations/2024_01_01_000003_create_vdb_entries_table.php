<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vdb_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->string('vdb_name');
            $table->string('division');
            $table->string('district');
            $table->string('thana');
            $table->string('union');
            $table->string('village');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vdb_entries');
    }
};
