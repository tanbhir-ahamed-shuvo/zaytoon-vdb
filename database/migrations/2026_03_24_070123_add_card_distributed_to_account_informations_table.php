<?php

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
        Schema::table('account_informations', function (Blueprint $table) {
            $table->boolean('card_distributed')->default(false)->after('card_no');
            // card_no is now nullable — only required when card_distributed = true
            $table->string('card_no')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('account_informations', function (Blueprint $table) {
            $table->dropColumn('card_distributed');
            $table->string('card_no')->nullable(false)->change();
        });
    }
};
