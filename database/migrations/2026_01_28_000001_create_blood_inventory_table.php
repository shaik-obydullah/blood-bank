<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('fk_blood_group_id');
            $table->foreignId('fk_donor_id')
                ->nullable()
                ->constrained('donors')
                ->nullOnDelete();
            $table->integer('quantity')->default(0);
            $table->date('collection_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->index(['fk_blood_group_id', 'fk_donor_id', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_inventory');
    }
};