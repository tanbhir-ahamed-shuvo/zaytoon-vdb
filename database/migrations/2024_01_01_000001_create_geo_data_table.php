<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geo_data', function (Blueprint $table) {
            $table->id();
            $table->string('division');
            $table->string('district');
            $table->string('thana');
            $table->string('union');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geo_data');
    }
};
