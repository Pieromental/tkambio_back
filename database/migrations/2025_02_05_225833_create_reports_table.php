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
        Schema::create('reports', function (Blueprint $table) {
            $table->string('report_id', 36)->primary();  
            $table->uuid('user_id')->nullable()->index(); 
            $table->string('title'); 
            $table->text('description')->nullable(); 
            $table->string('report_link');
            $table->boolean('active')->default(true); 
            $table->string('created_by', 36)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
