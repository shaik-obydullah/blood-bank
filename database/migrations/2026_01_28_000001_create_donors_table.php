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
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('fk_blood_group_id')->nullable();
            
            $table->string('name', 100);
            $table->string('country', 50);
            $table->string('mobile', 20);
            $table->string('email', 100);
            $table->string('password', 255)->nullable();
            
            $table->date('birthdate');
            
            $table->string('address_line_1', 255)->nullable();
            $table->string('address_line_2', 255)->nullable();
            
            $table->decimal('hemoglobin_level', 5, 2)->nullable();
            $table->integer('systolic')->nullable();
            $table->integer('diastolic')->nullable();
            
            $table->date('last_donation_date')->nullable();
            
            $table->timestamps();
        });
        
        Schema::table('donors', function (Blueprint $table) {
            $table->foreign('fk_blood_group_id')
                  ->references('id')
                  ->on('blood_groups')
                  ->onDelete('set null');
            
            $table->index('fk_blood_group_id');
            $table->index('email');
            $table->index('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropForeign(['fk_blood_group_id']);
        });
        
        Schema::dropIfExists('donors');
    }
};