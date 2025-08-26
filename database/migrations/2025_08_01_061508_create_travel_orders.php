<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Employee;
use App\Models\TravelOrderStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->string('employee_email'); // FK to employees.email
            $table->decimal('employee_salary', 10, 2);
            $table->string('destination');
            $table->text('purpose');
            $table->date('departure_date');
            $table->date('arrival_date');
            $table->string('recommender');
            $table->string('approver');
            $table->string('appropriation');
            $table->decimal('per_diem', 10, 2);
            $table->decimal('laborer_assistant', 10, 0);
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('status_id'); // FK to travel_status
            $table->timestamps();
        });

        Schema::table('travel_orders', function (Blueprint $table) {
            $table->foreign('employee_email')->references('email')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('travel_order_status')->onDelete('cascade');
            $table->foreign('recommender')->references('email')->on('users')->onDelete('cascade');
            $table->foreign('approver')->references('email')->on('users')->onDelete('cascade');
        });
    }
};
