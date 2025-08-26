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
        Schema::create('employee_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->text('signature_data')->nullable()->comment('Base64 encoded signature data');
            $table->string('signature_path')->nullable()->comment('Path to stored signature file if saved as file');
            $table->string('mime_type')->default('image/png');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure one signature per employee
            $table->unique('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_signatures');
    }
};
