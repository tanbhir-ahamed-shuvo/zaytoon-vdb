<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->enum('created_through', ['VDB', 'OWN']);
            $table->string('vdb_name')->nullable();
            $table->string('account_holder_name');
            $table->enum('account_type', [
                'Savings Account (SB)',
                'Current Account (CD)',
                'Personal Retail Account (PRA)',
                'Fixed Deposit Receipt (FDR)',
                'Deposit Pension Scheme (DPS)',
                'Others',
            ]);
            $table->string('account_no');
            $table->string('card_no');
            $table->boolean('app_distribution')->default(false);
            $table->boolean('qr_distribution')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_informations');
    }
};
