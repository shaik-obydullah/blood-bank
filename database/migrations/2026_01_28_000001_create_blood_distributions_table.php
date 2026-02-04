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
        Schema::create('blood_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fk_patient_id');
            $table->unsignedInteger('fk_blood_group_id');
            $table->unsignedInteger('request_unit');
            $table->unsignedInteger('approved_unit')->nullable();
            $table->timestamps();
        });
        
        Schema::table('blood_distributions', function (Blueprint $table) {
            $table->foreign('fk_patient_id')
                  ->references('id')
                  ->on('patients')
                  ->onDelete('cascade');
            
            $table->foreign('fk_blood_group_id')
                  ->references('id')
                  ->on('blood_groups')
                  ->onDelete('restrict');
            
            $table->index('fk_patient_id');
            $table->index('fk_blood_group_id');
            $table->index(['fk_blood_group_id', 'approved_unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_distributions', function (Blueprint $table) {
            $table->dropForeign(['fk_patient_id']);
            $table->dropForeign(['fk_blood_group_id']);
        });
        
        Schema::dropIfExists('blood_distributions');
    }
};