<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->string('employee_email'); // FK to employees.email
            $table->string('destination');
            $table->text('purpose');
            $table->date('departure_date');
            $table->date('arrival_date');
            $table->string('appropriation')->nullable();
            $table->decimal('per_diem', 10, 2)->nullable();
            $table->string('laborer_assistant')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('status_id'); // FK to travel_status
            $table->timestamps();
        });

        Schema::table('travel_orders', function (Blueprint $table) {
            $table->foreign('employee_email')->references('email')->on('employees')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('travel_order_status')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('travel_orders');
    }
};
