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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_order_id');
            $table->string('recommender_email');
            $table->string('approver_email');
            $table->enum('recommender_status', ['Pending','Approved','Disapproved'])->default('Pending');
            $table->enum('approver_status', ['Pending','Approved','Disapproved'])->default('Pending');
            $table->dateTime('recommender_date')->nullable();
            $table->dateTime('approver_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::table('approvals', function (Blueprint $table) {
            $table->foreign('travel_order_id')->references('id')->on('travel_orders')->onDelete('cascade');
            $table->foreign('recommender_email')->references('email')->on('users')->onDelete('cascade');
            $table->foreign('approver_email')->references('email')->on('users')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('approvals');
    }
};
