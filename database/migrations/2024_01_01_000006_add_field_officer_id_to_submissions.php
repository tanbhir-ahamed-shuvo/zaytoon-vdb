<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->foreignId('field_officer_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('field_officers')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\FieldOfficer::class);
            $table->dropColumn('field_officer_id');
        });
    }
};
